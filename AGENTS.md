# AGENTS.md

## Project
GymHub: a single-page web application for managing a gym.
Roles: admin and member.

## Tech stack
- Frontend: HTML, CSS, JavaScript, Bootstrap 5, jQuery, spapp (SPA routing)
- Backend: PHP, FlightPHP (REST API), PDO
- Database: MySQL

## Structure
- frontend/index.html: page shell (sidebar, top bar, <main id="spapp">)
- frontend/views/: one HTML fragment per page
- frontend/js/app.js: spapp setup, active menu link, auth layout
- frontend/css/: style.css (theme), spapp.css (page switching)
- backend/rest/routes, services, dao: backend layers
- backend/middleware, config, logs
- docs/: ERD and AI log

## Architecture
routes -> services -> dao. Routes handle HTTP only, services contain business
logic, DAOs are the only layer that talks to the database.

## Conventions
- All UI text and documentation are in English.
- View files are HTML fragments without <html>, <head> or <body>.
- Every <section> id in index.html matches its hash link (#trainers -> id="trainers").
- The dashboard section must stay the last <section> (default view).
- Theme colors are CSS variables in style.css (--navy, --accent).

## Rules for AI assistants
- Do not change the folder structure without asking.
- Never put secrets (passwords, API keys) in code; use backend/config/config.php (not committed).
- Explain every change; keep code simple enough for the student to explain.
- Do not call the external API from the frontend; go through the backend.

## Database and DAO rules
- All SQL lives in DAO classes (backend/rest/dao), nowhere else.
- Always use prepared statements; never concatenate user input into SQL.
- Column names in insert/update come from code, never from raw user input.
- Passwords are stored only as bcrypt hashes (password_hash / password_verify).
- Database credentials come from Config (config.php, not committed).

## Services, routes and API rules
- Routes (backend/rest/routes) only read the request, call a service and return JSON. No business logic.
- Services (backend/rest/services) hold validation and business rules and are the only layer that calls DAOs.
- Services signal problems with exceptions that carry an HTTP status (utils/exceptions.php);
  one error handler in index.php turns them into JSON. Unexpected errors return 500 with a generic message.
- Services keep only whitelisted fields (allowed_fields) before they reach a DAO.
- The password hash is never returned. The external API key stays on the backend (config.php, not committed).
- Every new endpoint must be added to docs/api.http and documented in backend/public/v1/docs/paths.
- Never log secrets.