# TuniVert - AI agent notes

Purpose: equip AI coding agents with just enough repo context to be productive.

## Architecture
- **Laravel app (PHP)** drives web/UI and APIs.
  - Routes: `routes/web.php` (+ includes `routes/qr-verify.php`, `routes/test-upload.php`).
  - Models: `app/Models` (key: `Donation.php`, `Event.php`, `User.php`, `PaymentMethod.php`, `Badge.php`).
  - Controllers: `app/Http/Controllers/*` (core: `DonationController`, `EventController`, `AdminController`).
  - Services: `app/Services/*` (DonationAI bridge, QR code, chatbot, gamification, moderation).
  - Views: `resources/views/**` (notably `pages/donation.blade.php`, `admin/evenements/index.blade.php`).
- **Python microservice** for AI-powered donation suggestions (optional).
  - FastAPI: `python/donation_ai_service.py` (default http://127.0.0.1:8085).
  - Laravel calls it via `App\Services\DonationAI` if `services.donation_ai.url` is set; else uses local heuristic.
- **Multi-role system**: `admin`, `association`, `user` with different permissions and workflows.
- **Comprehensive monitoring**: Prometheus metrics, Grafana dashboards, Docker deployment ready.

## Database & migrations
- **Custom timestamp columns**: `Donation` uses `date_creation`/`date_mise_a_jour` instead of Laravel's `created_at`/`updated_at`; business date is `date_don`.
- **Role-based models**: Users have `role` field (`admin`/`association`/`user`) and `matricule` for associations.
- **Migration safety**: All migrations use `Schema::hasColumn()` checks to prevent conflicts during development.
- **Seeding workflow**: `DatabaseSeeder` calls ordered seeders (`EssentialDataSeeder` → `UserSeeder` → `GamificationSeeder` → etc.).
- **Testing**: Use `RefreshDatabase` trait; tests create factories for `User`, `Event`, `Donation` models.

## Donations and payments
- Event AI suggestions: `EventController@show` builds context from user history, event stats, time/device; calls `DonationAI::suggest($eventId, $userId, $ctx)`; logs `donation_ai_suggestion_exposed`.
- Providers:
  - Stripe: `POST /payments/stripe/intent` → confirm in browser (Stripe.js) → `POST /payments/stripe/confirm` persists `Donation` (never before confirmation).
  - PayPal: `create-order` then `capture`; persist on success.
  - Paymee (e-DINAR): `create` returns `payment_url`; webhook `/webhooks/paymee` validates `check_sum` and creates `Donation`. `order_id` encodes `userId[_eventId]_timestamp` and is parsed in the webhook.
  - Bank transfer: `store` creates `Donation` immediately with a generated reference; user gets instructions.
- Gamification and emails: `GamificationService::onDonation($donation)` awards points/badges; badge sync/backfill handled in `DonationController@history`. In `local` env, receipts usually go to the `log` mailer; otherwise queued via default mailer.
- Event AI suggestions: `EventController@show` builds context from user history, event stats, time/device; calls `DonationAI::suggest($eventId, $userId, $ctx)`; logs `donation_ai_suggestion_exposed`.
- Providers:
  - Stripe: `POST /payments/stripe/intent` → confirm in browser (Stripe.js) → `POST /payments/stripe/confirm` persists `Donation` (never before confirmation).
  - PayPal: `create-order` then `capture`; persist on success.
  - Paymee (e-DINAR): `create` returns `payment_url`; webhook `/webhooks/paymee` validates `check_sum` and creates `Donation`. `order_id` encodes `userId[_eventId]_timestamp` and is parsed in the webhook.
  - Bank transfer: `store` creates `Donation` immediately with a generated reference; user gets instructions.
- Gamification and emails: `GamificationService::onDonation($donation)` awards points/badges; badge sync/backfill handled in `DonationController@history`. In `local` env, receipts usually go to the `log` mailer; otherwise queued via default mailer.

## QR codes and verification
- `App\Services\QrCodeService` builds code `TV-<hash>`, embeds an SVG in receipt emails, and can attach the SVG.
- Public verify: `GET /donation/verify/{code}` recomputes code server-side (see `routes/qr-verify.php`).
- Note: two code generators exist (PHP xxh128 vs SQL SHA2). If you change one, update both to keep verification valid.

## Configuration actually used
- `config/services.php` keys referenced by code:
  - **Stripe**: `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_CURRENCY`.
  - **PayPal**: `PAYPAL_CLIENT_ID`, `PAYPAL_SECRET`, `PAYPAL_MODE`, `PAYPAL_CURRENCY`.
  - **Paymee**: `PAYMEE_API_KEY`, `PAYMEE_MODE`, `PAYMEE_CURRENCY`.
  - **Donation AI**: `DONATION_AI_URL`.
  - **OpenAI** (chatbot): `OPENAI_API_KEY` (model is `gpt-3.5-turbo` in `ChatbotService`).
- **Database**: Multiple services (MySQL, Redis for caching); Docker networking via `tunivert-network`.
- **File uploads**: Test endpoints in `routes/test-upload.php` for debugging.

## Developer workflows
- **Run Laravel**: `php artisan serve` (or task: Run Laravel Server).
- **Start Donation AI** (optional): create venv, `pip install -r python/requirements.txt`, run uvicorn for `donation_ai_service.py` on 127.0.0.1:8085 (task: Start Donation AI Service). When toggling AI URL: set `DONATION_AI_URL` then `php artisan config:clear` and `php artisan cache:clear`.
- **Database setup**: Use `seed-database.ps1` (Windows) or `seed-database.sh` (Unix) for full migration + seeding. Includes verification step.
- **Testing**: `php artisan test` or `vendor/bin/phpunit` (see `tests/Feature/**`).
- **Docker**: `docker-compose up` for full stack; includes monitoring services (Prometheus, Grafana) and database.
- **VS Code tasks**: Available tasks for Laravel Server and Donation AI Service.

## Conventions to follow
- Payment methods: keys are normalized lowercase slugs; managed via `PaymentMethod` and admin endpoints (`/admin/donations/methodes`). Blade reads active methods from DB.
- Default method stored in session (`last_method`) and fed back into AI context as `default_method`.
- Analytics: server logs `donation_ai_suggestion_exposed`; UI reports clicks via `POST /donations/suggestion-click`.
- Receipts: Blade views under `resources/views/emails/donations/**` with QR from `QrCodeService`.

## Extending safely
- New gateway: mirror Stripe/PayPal/Paymee patterns in `DonationController`; only persist after definitive success; add/enable a `PaymentMethod` so the UI renders it.
- AI changes: keep `DonationAI` payload/fields consistent with FastAPI `/suggest`; Laravel auto-falls back to heuristic if the service is down.
- QR/receipt changes: keep the code format in sync between `QrCodeService` and `routes/qr-verify.php`.

---
Questions for maintainers: Should we unify the QR hashing and document the canonical format? Any required mailer config for local beyond the `log` mailer?
