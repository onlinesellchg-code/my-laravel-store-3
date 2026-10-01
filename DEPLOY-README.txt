READY-TO-UPLOAD LARAVEL STORE
=============================

1) Create or use an empty GitHub repository.
2) Upload ALL files in this ZIP to the repository root.
3) Render Web Service:
   - Runtime: Docker
   - Dockerfile: ./Dockerfile
   - Docker Build Context: .
   - Root Directory: blank
   - Branch: main
   - Auto Deploy: On Commit
4) Render Environment Variables:
   ADMIN_USERNAME = your admin username
   ADMIN_PASSWORD = your new strong admin password
   DB_CONNECTION = pgsql
   DB_URL = the Internal Database URL from your Render PostgreSQL database

Do not paste DB_URL or ADMIN_PASSWORD into public GitHub files.
The app will automatically run migrations and seed sample data at startup.
Admin login: /admin/login
Dashboard: /admin
