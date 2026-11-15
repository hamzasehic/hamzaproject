# hamzaproject

GymHub is a single-page web application for managing a gym.

Members browse membership plans, trainers and group classes and book spots.
Administrators manage users, memberships, trainers and classes.

## Roles
- Admin: manages users, memberships, trainers and classes.
- Member: views plans, trainers and classes, books and cancels classes, manages own profile.

## Tech stack
- Frontend: HTML, CSS, JavaScript, Bootstrap 5, jQuery, spapp (SPA routing)
- Backend (planned): PHP, FlightPHP (REST API), PDO
- Database (planned): MySQL

## Pages
Login, Register, Dashboard, Memberships, Trainers, Classes, My Bookings,
Exercises, Users (admin), Profile, 404.

## External API (planned)
The Exercises page will use the API Ninjas Exercises API to search exercises by
muscle group and difficulty. The API key will be stored on the backend
(config.php, not committed), and the frontend will call our own backend.

## Database
Draft ERD with 6 entities (users, memberships, user_memberships, trainers,
classes, bookings): see [docs/ERD.md](docs/ERD.md).

## Project structure
- frontend: index.html, views/, css/, js/, assets/
- backend: rest/ (routes, services, dao), middleware/, config/, logs/
- docs: ERD, AI log

## Running the frontend locally

## Database setup
1. Start MySQL (e.g. via XAMPP).
2. Import `database/gymhub.sql` (phpMyAdmin -> SQL tab, paste and run). It creates
   the `gymhub` database, all tables and sample data.
3. Copy `backend/config/config.example.php` to `backend/config/config.php` and
   adjust the values if needed (XAMPP defaults: host 127.0.0.1, user root, empty password).
4. Run the DAO test: `php backend/tests/dao_test.php`

## Test accounts
| Role | Email | Password |
|---|---|---|
| Admin | admin@gymhub.com | Admin123! |
| Member | emma@example.com | Member123! |

## DAO layer
`BaseDao` provides generic CRUD (prepared statements, PDO). Each entity has its own DAO
extending it: UserDao, MembershipDao, TrainerDao, UserMembershipDao, ClassDao, BookingDao.

## Running the backend
1. `cd backend` and run `composer install`.
2. Copy `backend/config/config.example.php` to `backend/config/config.php` and set the values
   (the database settings work with XAMPP defaults; put your API Ninjas key into `EXTERNAL_API_KEY`).
3. Import `database/gymhub.sql` (see "Database setup" above).
4. Start the API: `php -S localhost:8001 index.php` (run inside the `backend` folder).
5. Swagger documentation: http://localhost:8001/public/v1/docs/index.html

## API overview
Responses are JSON. Errors have the form `{"error": "..."}`.

| Resource | Endpoints |
|---|---|
| Trainers | `GET/POST /trainers`, `GET/PUT/DELETE /trainers/{id}` |
| Membership plans | `GET/POST /memberships`, `GET/PUT/DELETE /memberships/{id}` |
| Classes | `GET/POST /classes`, `GET /classes/upcoming`, `GET/PUT/DELETE /classes/{id}` |
| Users | `GET/POST /users`, `GET/PUT/DELETE /users/{id}` |
| Bookings | `GET/POST /bookings`, `GET/PUT/DELETE /bookings/{id}`, `POST /bookings/{id}/cancel`, `GET /users/{id}/bookings` |
| User memberships | `GET/POST /user-memberships`, `GET/PUT/DELETE /user-memberships/{id}`, `GET /users/{id}/memberships`, `GET /users/{id}/membership` |
| Exercises (external API) | `GET /exercises/options`, `GET /exercises?muscle=biceps&difficulty=beginner` |

Status codes: 200 OK, 201 Created, 400 invalid data or a broken business rule, 404 not found,
500 unexpected error (generic message), 503 external service unavailable.

## Business rules (services)
- Trainers with classes, plans that were bought, classes with active bookings and the last admin cannot be deleted.
- A new class must start in the future; its capacity cannot drop below the number of bookings.
- A booking needs an active membership and a class that has not started and is not full; the same class cannot be booked twice.
- A user can have only one active membership; its end date is calculated from the plan.
- Passwords are stored as bcrypt hashes and are never returned by the API.

## External API
The Exercises page will use the API Ninjas Exercises API. The backend calls it (`ExerciseService`)
with a timeout, validates the input first, hides the API key and, if the service fails, logs the error
to `backend/logs/error.log` and answers with 503 and a friendly message while the rest of the app keeps working.

## Architecture
routes -> services -> DAOs -> MySQL. Only services call DAOs; routes only handle HTTP.