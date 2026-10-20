# GymHub - Entity Relationship Diagram (draft)

```mermaid
erDiagram
    USERS ||--o{ USER_MEMBERSHIPS : buys
    MEMBERSHIPS ||--o{ USER_MEMBERSHIPS : "is bought as"
    TRAINERS ||--o{ CLASSES : teaches
    USERS ||--o{ BOOKINGS : makes
    CLASSES ||--o{ BOOKINGS : has

    USERS {
        int id PK
        string full_name
        string email UK
        string password_hash
        string role "admin or member"
        datetime created_at
    }

    MEMBERSHIPS {
        int id PK
        string name
        decimal price
        int duration_days
        string description
    }

    USER_MEMBERSHIPS {
        int id PK
        int user_id FK
        int membership_id FK
        date start_date
        date end_date
        string status "active, expired"
    }

    TRAINERS {
        int id PK
        string full_name
        string specialty
        string bio
        int experience_years
    }

    CLASSES {
        int id PK
        int trainer_id FK
        string name
        datetime start_time
        int capacity
        string description
    }

    BOOKINGS {
        int id PK
        int user_id FK
        int class_id FK
        string status "booked, cancelled, completed"
        datetime created_at
    }
```

## Relationships

| Relationship | Type | Description |
|---|---|---|
| users - user_memberships | 1:N | A user can have many purchased memberships |
| memberships - user_memberships | 1:N | A membership plan is bought by many users |
| trainers - classes | 1:N | A trainer teaches many classes |
| users - bookings | 1:N | A user makes many bookings |
| classes - bookings | 1:N | A class has many bookings |
| users - classes | N:M | Resolved through bookings |
| users - memberships | N:M | Resolved through user_memberships |

## Notes
- The Exercises page uses an external API, so exercises are not stored in the database.
- A user cannot book the same class twice (unique pair user_id + class_id in bookings).
- Passwords are stored as hashes, never as plain text.