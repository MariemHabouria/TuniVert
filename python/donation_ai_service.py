from fastapi import FastAPI
from pydantic import BaseModel, Field
from typing import Optional, Dict, Any
from datetime import datetime

app = FastAPI(title="Donation AI Service", version="1.0.0")


class Ctx(BaseModel):
    # User features
    user_total_tnd: float = 0.0
    user_count: int = 0
    user_avg_tnd: float = 0.0
    user_last_days: Optional[int] = None
    user_badge_count: int = 0
    default_method: Optional[str] = None
    
    # Event features
    event_popularity: int = 0
    event_days_left: int = 30
    event_category: str = "general"
    event_goal_progress: float = 0.0
    
    # Context features
    hour: int = 12
    weekday: int = 3
    is_mobile: int = 0


class SuggestRequest(BaseModel):
    event_id: int = Field(..., ge=1)
    user_id: Optional[int] = None
    ctx: Ctx = Field(default_factory=Ctx)


class SuggestResponse(BaseModel):
    propensity: float
    amounts: Dict[str, int]
    method: Optional[str]


@app.get("/")
def root():
    """Root endpoint with service info"""
    return {
        "service": "Donation AI",
        "version": "1.0.0",
        "status": "running",
        "endpoints": {
            "health": "/health",
            "suggest": "POST /suggest",
            "docs": "/docs"
        }
    }


@app.get("/health")
def health() -> Dict[str, str]:
    return {"status": "ok", "time": datetime.utcnow().isoformat()}


@app.post("/suggest", response_model=SuggestResponse)
def suggest(payload: SuggestRequest):
    """
    Enhanced heuristic model using all available features.
    Replace this with trained XGBoost/CatBoost for production.
    """
    # User features
    user_total = float(payload.ctx.user_total_tnd or 0)
    user_count = int(payload.ctx.user_count or 0)
    user_avg = float(payload.ctx.user_avg_tnd or 0)
    user_last_days = payload.ctx.user_last_days
    badge_count = int(payload.ctx.user_badge_count or 0)
    
    # Event features
    pop = int(payload.ctx.event_popularity or 0)
    days_left = int(payload.ctx.event_days_left or 30)
    category = payload.ctx.event_category or "general"
    goal_progress = float(payload.ctx.event_goal_progress or 0)
    
    # Context features
    hour = int(payload.ctx.hour or 12)
    weekday = int(payload.ctx.weekday or 3)
    is_mobile = int(payload.ctx.is_mobile or 0)

    # Propensity calculation with expanded features
    base = 0.15
    
    # User history signals
    base += min(user_total / 1000.0, 0.25)  # Loyal donors more likely
    if user_count > 0:
        base += min(user_count / 20.0, 0.1)  # Frequent donors boost
    if user_last_days is not None:
        # Recent donors more likely; decay after 30 days
        recency_boost = max(0, 0.1 * (1 - user_last_days / 30.0))
        base += recency_boost
    base += min(badge_count / 10.0, 0.05)  # Gamified users engage more
    
    # Event signals
    base += min(pop / 100.0, 0.2)  # Popular events convert better
    base += 0.05 if days_left < 7 else 0.0  # Urgency boost
    base += min(goal_progress, 0.1)  # Near-goal events get a lift
    
    # Category boost (example: Ecosystem events slightly higher)
    if category.lower() in ['ecosystem', 'écosystème']:
        base += 0.03
    
    # Time context (evenings and weekends slightly higher)
    if 18 <= hour <= 22:
        base += 0.02
    if weekday in [0, 6]:  # Sunday=0, Saturday=6
        base += 0.02
    
    # Mobile users may give smaller amounts but convert well
    if is_mobile:
        base += 0.01
    
    base = min(base, 0.9)

    # Amount tiers: scale with user giving history and event context
    # Higher avg donors see higher suggestions
    if user_avg > 0:
        mid = round(max(15, min(250, user_avg * 1.2)))
    elif user_total > 0:
        mid = round(max(10, min(250, user_total * 0.05)))
    else:
        # Cold start: use event popularity and goal progress
        mid = round(25 + (pop * 0.5) + (goal_progress * 20))
        mid = max(15, min(mid, 50))
    
    # Mobile users: suggest slightly lower amounts
    if is_mobile:
        mid = round(mid * 0.9)
    
    low = max(5, round(mid * 0.6))
    high = min(1000, round(mid * 1.6))

    method = payload.ctx.default_method or "paymee"

    return SuggestResponse(
        propensity=round(base, 3),
        amounts={"low": int(low), "mid": int(mid), "high": int(high)},
        method=method,
    )
