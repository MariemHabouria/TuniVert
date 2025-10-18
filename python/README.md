# Donation AI Service

FastAPI microservice providing personalized donation suggestions for TuniVert.

## Features
- **Propensity scoring**: Estimates likelihood a user will donate to an event
- **Amount suggestions**: Returns low/mid/high donation amounts tailored to user history
- **Method recommendation**: Suggests the payment method most likely to convert
- **Rich context**: Uses user history, event details, time, and device signals

## Setup

### 1. Install dependencies
```powershell
cd python
pip install -r requirements.txt
```

### 2. Start the service
```powershell
uvicorn donation_ai_service:app --host 127.0.0.1 --port 8085 --reload
```

Or use the virtual environment (recommended):
```powershell
# From project root
.\.venv\Scripts\Activate.ps1
cd python
uvicorn donation_ai_service:app --host 127.0.0.1 --port 8085 --reload
```

### 3. Configure Laravel
Add to your `.env`:
```
DONATION_AI_URL=http://127.0.0.1:8085
```

Then clear config:
```powershell
php artisan config:clear
php artisan cache:clear
```

## API Endpoints

### GET /health
Health check endpoint.

**Response:**
```json
{
  "status": "ok",
  "time": "2025-10-17T12:00:00.000000"
}
```

### POST /suggest
Generate donation suggestions for a user×event pair.

**Request:**
```json
{
  "event_id": 2,
  "user_id": 5,
  "ctx": {
    "user_total_tnd": 340.0,
    "user_count": 12,
    "user_avg_tnd": 28.33,
    "user_last_days": 15,
    "user_badge_count": 3,
    "default_method": "paymee",
    "event_popularity": 25,
    "event_days_left": 6,
    "event_category": "Ecosystem",
    "event_goal_progress": 0.45,
    "hour": 19,
    "weekday": 5,
    "is_mobile": 0
  }
}
```

**Response:**
```json
{
  "propensity": 0.487,
  "amounts": {
    "low": 20,
    "mid": 34,
    "high": 54
  },
  "method": "paymee"
}
```

## Context Features

### User Features
- `user_total_tnd`: Total donated by user (all time)
- `user_count`: Number of donations made
- `user_avg_tnd`: Average donation amount
- `user_last_days`: Days since last donation (null if never)
- `user_badge_count`: Number of badges earned
- `default_method`: Last used payment method

### Event Features
- `event_popularity`: Number of donations to this event
- `event_days_left`: Days until event date
- `event_category`: Event category (e.g., "Ecosystem", "Recycling")
- `event_goal_progress`: Progress toward goal (0.0–1.0)

### Context Features
- `hour`: Hour of day (0–23)
- `weekday`: Day of week (0=Sunday, 6=Saturday)
- `is_mobile`: 1 if mobile device, 0 otherwise

## Development

### Current Model
The service uses a **heuristic model** that combines:
- User loyalty signals (total donated, frequency, recency, badges)
- Event signals (popularity, urgency, goal progress, category)
- Time context (evening/weekend boosts)
- Device context (mobile adjustments)

### Future: ML Model
Replace the heuristic with a trained model:
1. Collect labeled data (user×event → donated? amount?)
2. Train XGBoost or CatBoost with the same features
3. Serialize model (e.g., `model.pkl`)
4. Load in `/suggest` endpoint:
   ```python
   import joblib
   model = joblib.load('model.pkl')
   prediction = model.predict(features)
   ```

### Testing
```powershell
# Test health
curl http://127.0.0.1:8085/health

# Test suggest
curl -X POST http://127.0.0.1:8085/suggest -H "Content-Type: application/json" -d '{"event_id":2,"user_id":1,"ctx":{"user_total_tnd":340,"event_popularity":25,"event_days_left":6}}'
```

## Metrics
The service logs are minimal; Laravel tracks:
- **Exposure**: `donation_ai_suggestion_exposed` when suggestions are shown
- **Selection**: `donation_ai_suggestion_clicked` when users click a suggested amount

Use these logs to measure:
- Conversion uplift (% donated after seeing suggestions)
- AOV uplift (average amount vs. control)
- Method adoption (% using recommended method)

## VS Code Task
A task is available in `.vscode/tasks.json` to start the service with one click (Run Task → "Start Donation AI Service").
