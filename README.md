# Time & Productivity Analyzer
Track tasks, focus sessions, and personal productivity metrics from one clean, developer-first workspace.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?logo=vite&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?logo=tailwindcss&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## Overview
Time & Productivity Analyzer is a web platform for people who want a practical way to plan work and measure focus quality.

It solves the common gap between task management and time tracking by connecting both in one workflow: create tasks, run focus sessions, and view analytics that show what is actually getting done.

Target users include students, freelancers, developers, and small teams who need personal productivity visibility without heavyweight project-management overhead.

The platform's core purpose is to help users convert planned work into measurable output through daily tracking, reminders, and performance insights.

## Features
- Secure authentication with email verification workflow
- Task lifecycle management with completion toggles
- Category-based task organization with color labels
- Focus timer with active session state and task linking
- Time log editing, deletion, and CSV export
- Analytics dashboard with productivity score, streaks, and weekly trend data
- Reminder management with upcoming, missed, and completed states
- User settings for profile, password, and timezone preferences

## Tech Stack
| Layer | Technologies |
| --- | --- |
| Frontend | Blade templates, Tailwind CSS 4, Vite 7, Axios |
| Backend | Laravel 12, PHP 8.2, Eloquent ORM, Form Request validation |
| Database | Relational DB via Laravel migrations (SQLite/MySQL compatible) |
| Authentication | Session auth, hashed passwords, email verification, auth middleware |
| APIs / Interfaces | Web routes + JSON endpoints for timer start/stop and session actions |
| Tooling | Composer, NPM, Laravel Pint, PHPUnit, Faker, Concurrency script via `concurrently` |
| Deployment | Production-ready for VPS/Forge/Docker or cloud PHP runtimes |

<!-- ## UI Preview
Add screenshots or GIFs below.

![Dashboard Preview](public/Images/dashboard-preview.png)
![Time Tracking Preview](public/Images/time-tracking-preview.gif)
![Analytics Preview](public/Images/analytics-preview.png)

> Replace these placeholders with actual captures from your app UI. -->

## System Architecture / Workflow
1. A user registers and verifies email before accessing protected pages.
2. Authenticated users create tasks, optionally group them by category, and set priorities in their workflow.
3. The focus timer starts a session tied to a task (or uncategorized), storing active state on the user profile.
4. Stopping the timer writes a time log (minimum duration rule applied) and clears active state.
5. Time logs feed the analytics layer for weekly focus time, completion trends, productivity score, and streak calculations.
6. Reminders help users act on planned work and track read/unread status over time.
7. Settings manage profile identity, password security, and timezone-aware reporting behavior.

## Installation & Setup
### 1) Clone the repository
```bash
git clone https://github.com/your-username/time-and-productivity-analyzer.git
cd time-and-productivity-analyzer
```

### 2) Install dependencies
```bash
composer install
npm install
```

### 3) Environment setup
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 4) Run backend + frontend
```bash
composer run dev
```

### 5) Production build
```bash
npm run build
```

### Optional helper (one-shot project bootstrap)
```bash
composer run setup
```

## Environment Variables
Use this as a baseline `.env` configuration.

| Variable | Example | Purpose |
| --- | --- | --- |
| `APP_NAME` | `"Time & Productivity Analyzer"` | Application display name |
| `APP_ENV` | `local` | Runtime environment |
| `APP_KEY` | `base64:...` | Laravel encryption key |
| `APP_DEBUG` | `true` | Debug mode |
| `APP_URL` | `http://127.0.0.1:8000` | Base URL |
| `DB_CONNECTION` | `sqlite` | Database driver |
| `DB_DATABASE` | `database/database.sqlite` | Database path/name |
| `SESSION_DRIVER` | `database` | Session persistence driver |
| `QUEUE_CONNECTION` | `database` | Queue backend |
| `MAIL_MAILER` | `smtp` | Mail transport |
| `MAIL_HOST` | `smtp.mailtrap.io` | SMTP host |
| `MAIL_PORT` | `2525` | SMTP port |
| `MAIL_USERNAME` | `your_mail_user` | SMTP username |
| `MAIL_PASSWORD` | `your_mail_password` | SMTP password |
| `MAIL_FROM_ADDRESS` | `noreply@example.com` | Sender address |

## Folder Structure
| Directory | Description |
| --- | --- |
| `app/Http/Controllers` | Request handling for auth, tasks, reminders, settings, analytics, and time tracking |
| `app/Models` | Core domain entities (`User`, `Task`, `TimeLog`, `Reminder`, `Category`) |
| `app/Support` | Shared utility classes for durations and timezone/user-time logic |
| `database/migrations` | Database schema evolution and feature-specific table changes |
| `resources/views` | Blade views for dashboard, analytics, auth pages, reminders, and settings |
| `resources/js` | Frontend JavaScript entry points and app scripts |
| `resources/css` | Global styles and Tailwind integration |
| `routes` | Route definitions for web app flows |
| `tests` | Feature and unit test coverage |

## API Endpoints
Primary routes are server-rendered web endpoints, with selected JSON interactions for timer controls.

| Method | Endpoint | Auth | Description |
| --- | --- | --- | --- |
| `POST` | `/time/start` | Yes (`auth`,`verified`) | Starts focus timer for optional task |
| `POST` | `/time/stop` | Yes (`auth`,`verified`) | Stops timer and stores session if valid |
| `PATCH` | `/time-logs/{time_log}` | Yes (`auth`,`verified`) | Updates duration/task mapping for a time log |
| `DELETE` | `/time-logs/{time_log}` | Yes (`auth`,`verified`) | Deletes a time log |
| `GET` | `/time-tracking/export` | Yes (`auth`,`verified`) | Exports logs as CSV within date range |
| `PATCH` | `/tasks/{id}/complete` | Yes (`auth`,`verified`) | Toggles task completion state |
| `PATCH` | `/reminders/{id}/read` | Yes (`auth`,`verified`) | Marks reminder as read |

## Challenges Faced
- Maintaining timezone-correct analytics for daily/weekly calculations
- Keeping timer state consistent across refreshes and concurrent requests
- Preventing unauthorized access to user-owned tasks, logs, and reminders
- Designing fast exports for large time-log datasets
- Balancing clean UX with strict validation and security middleware

## Future Enhancements
- Team workspaces with shared projects and collaborative dashboards
- Calendar integrations (Google/Outlook) for reminder synchronization
- Advanced analytics (focus quality score, burnup velocity, anomaly detection)
- REST/GraphQL API for mobile clients
- Background job-based report generation and scheduled insights

## Performance / Security Features
- Auth and verified-email middleware gates for protected routes
- Authorization policies for time-log update/delete/export actions
- Input validation on all critical write operations
- Database transactions for atomic timer start/stop logic
- Query filtering/pagination for scalable time-log browsing
- Password hashing and secure session regeneration on login/logout

## Deployment
Typical production flow:

1. Provision server/runtime (Ubuntu + Nginx + PHP-FPM + MySQL/SQLite, or Laravel Forge equivalent).
2. Configure environment variables and secrets in `.env`.
3. Run migrations and optimized build commands.
4. Serve `public/` as web root and enable HTTPS.
5. Configure queue worker/process monitor for background jobs.

Example release commands:
```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
npm ci
npm run build
```
<!-- 
## Contributing
1. Fork the repository.
2. Create a feature branch: `git checkout -b feat/your-feature`.
3. Commit with clear messages and focused scope.
4. Run tests and formatting checks before opening a PR.
5. Submit a pull request with context, screenshots, and testing notes.

## License
This project is licensed under the MIT License.

## Author
**Your Name**

- GitHub: https://github.com/your-username
- LinkedIn: https://linkedin.com/in/your-profile
- Portfolio: https://your-portfolio.dev -->
