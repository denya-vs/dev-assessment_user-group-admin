# User Roles Management Application

This application allows you to manage user roles using a React frontend and a Symfony backend. The setup uses Docker to ensure consistent environment dependencies.

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

### 1. Clone the Repository 

### 2. Create Environment Files
Backend (.env.local)
Copy the env.example file to .env.local in the Symfony backend directory:

```bash
cp backend/.env.example backend/.env.local
```

Frontend (.env)
Copy the env.example file to .env in the React frontend directory:

```bash
cp frontend/.env.example frontend/.env
```
### 3. Build and Start Docker Containers

```bash
docker compose up --build -d
```

### 4. Run Migrations
Run migrations to set up the database schema:
```bash
docker compose exec php bin/console doctrine:migrations:migrate
```

### 5. Load Fixtures
Load fixtures to populate the database with initial data:
```bash
docker compose exec php bin/console doctrine:fixtures:load
```

### 6. Access the Application
   Frontend: http://localhost:8080

   Backend (API): http://localhost:9001/api/