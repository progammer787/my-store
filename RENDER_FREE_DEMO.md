# Free Render + Neon demo deployment

This configuration publishes the boutique demo as a preview. It is not production hosting for a shop taking real orders.

## What this setup uses

- Render Free Docker web service for the Laravel app.
- Neon Free Postgres for persistent database records.
- Product illustrations and compiled front-end assets included in the repository.

The Render filesystem is temporary on the free plan. Do not upload product images or enter real customer/order data; local uploads, sessions, and SQLite data can disappear after a restart or deploy. The service sleeps after inactivity. Keep this deployment as a showcase only.

## One-time account setup

1. Push the current project files to the GitHub repository, including `Dockerfile`, `docker/`, and `render.yaml`. Do not commit `.env` or credentials.
2. Create a free Neon project and copy its pooled or direct PostgreSQL connection URL. Neon currently offers 1 GB and 100 compute-hours per project on Free.
3. In Render, choose **New > Blueprint**, connect the repository, and select the branch containing `render.yaml`.
4. Set the Blueprint's prompted secret values:
   - `APP_KEY`: generate locally with `php artisan key:generate --show` and paste the result into Render.
   - `DB_URL`: the Neon connection URL; keep `sslmode=require` in its query string or set `DB_SSLMODE=require`.
   - `INITIAL_ADMIN_EMAIL`: an email address you control.
   - `INITIAL_ADMIN_PASSWORD`: a unique password of at least 16 characters. Never use the local demo password.
5. Deploy. The container runs database migrations and seeds the demo on first start. Later restarts don't reset edited demo products or the admin password.
6. Open the generated `*.onrender.com` URL and check the shop and `/admin/login`.

Laravel migrations and seed data run against Neon. The local SQLite database is intentionally excluded from the Docker image. The production seeder creates only the admin account supplied through Render's private environment settings; development-only accounts are not created in production.

## Free-plan limitations

Render Free services sleep after 15 minutes without traffic, can take about a minute to wake, and have an ephemeral filesystem. Neon Free has monthly compute and storage limits. Do not use this preview for real sales, customer records, uploaded media, or as an uptime promise to a shop. Before a real store launch, move to persistent hosting/storage and configure and verify the live order, payment, delivery, backup, and admin-password recovery flows.
