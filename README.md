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