# 🧠 User Activity Tracking and Inactivity Monitoring System

A **Laravel 12**-based application for monitoring user activity, detecting inactivity, and automatically applying penalties after specific idle time intervals.

---

## 🚀 Overview

This project provides a full-featured **real-time inactivity tracking system** with multi-level warnings, penalty management, and an admin dashboard to monitor user performance and sessions.

It uses Laravel's clean architecture and well-structured **Repository & Observer Patterns** for maintainable and scalable code.

---

## ⚙️ Installation & Setup

### 1️⃣ Prerequisites

Make sure you have installed:

-   PHP >= 8.2
-   Composer
-   MySQL >= 8.0
-   Node.js & NPM
-   Git

### 2️⃣ Clone Repository

```bash
git clone https://github.com/amr-94/User-Activity-Tracking.git
cd user-activity
```

### 3️⃣ Install Dependencies

```bash
composer install
npm install
```

### 4️⃣ Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Then update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_activity
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ Run Migrations & Seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6️⃣ Build Frontend

```bash
npm install
npm run dev
```

### 7️⃣ Serve Application

```bash
php artisan serve
```

---

## 🧩 Technologies Used

| Layer           | Technology                           |
| --------------- | ------------------------------------ |
| Backend         | Laravel 12 (PHP 8.2)                 |
| Frontend        | Blade, jQuery, Bootstrap 5           |
| Database        | MySQL                                |
| Design Patterns | Repository Pattern, Observer Pattern |
| Others          | AJAX, Font Awesome                   |

---

## 🧱 System Architecture

### 🔁 Repository Pattern

-   **Purpose:** Decouple data layer from logic layer.
-   **Structure:**
    -   `BaseRepositoryInterface` for CRUD contracts.
    -   `UserRepository`, `ActivityRepository`, `IdleSessionRepository`, and `PenaltyRepository` for model-specific logic.

### 👀 Observer Pattern

-   **Usage:** Automatically log activities & idle sessions.
-   **Examples:**
    -   `UserObserver` → Tracks user login/logout events.
    -   `ActivityObserver` → Logs model actions.

---

## 📊 Core Features

### 🔐 Authentication & Roles

-   Multi-level Authentication (Admin / User)
-   Role-based Dashboard Access
-   Profile Management (Name, Email, Password)

### 🕒 Inactivity Monitoring

-   Real-time idle tracking via `idle-monitor.js`
-   Configurable timeout stored in `settings` table
-   Progressive warning system:
    1. Level 1 – after idle_timeout minutes
    2. Level 2 – after ×2 previous duration
    3. Level 3 – after ×2 previous duration again (auto penalty)

### ⚡ Penalty System

-   Auto-penalties after final warning
-   Stored in `penalties` table
-   Admin can view, remove, or adjust penalties

### 📈 Admin Dashboard

-   View total users, penalties, and active idle sessions
-   Monitor real-time user activity logs
-   Display of warning levels and penalties count

---

## 🗂️ Database Structure

| Table           | Description                                       |
| --------------- | ------------------------------------------------- |
| `users`         | Stores user info                                  |
| `activity_logs` | Logs all user actions                             |
| `idle_sessions` | Tracks idle times per user                        |
| `penalties`     | Stores penalty records                            |
| `settings`      | Global system settings (timeout, monitoring flag) |

---

## 📡 Main Routes

| Route              | Method | Description         |
| ------------------ | ------ | ------------------- |
| `/`                | GET    | User Dashboard      |
| `/admin/dashboard` | GET    | Admin Dashboard     |
| `/api/idle/start`  | POST   | Start idle tracking |
| `/api/idle/end`    | POST   | End idle session    |
| `/api/penalties`   | GET    | List penalties      |

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── User/
│   └── Middleware/
├── Interfaces/
│   └── BaseRepositoryInterface.php
├── Models/
├── Observers/
├── Repositories/
│   ├── ActivityRepository.php
│   ├── UserRepository.php
│   ├── IdleSessionRepository.php
│   └── PenaltyRepository.php
database/
├── migrations/
└── seeders/
resources/
├── views/
│   ├── admin/
│   ├── user/
│   └── layouts/
public/
└── js/
    └── idle-monitor.js
routes/
└── web.php
```

---

## 🧠 Key Files

| File                                                 | Description             |
| ---------------------------------------------------- | ----------------------- |
| `app/Repositories/BaseRepository.php`                | Common CRUD logic       |
| `app/Observers/UserObserver.php`                     | User events tracking    |
| `app/Http/Controllers/Admin/DashboardController.php` | Admin dashboard logic   |
| `public/js/idle-monitor.js`                          | Frontend idle detection |

---

## 🔐 Security Measures

-   CSRF & XSS Protection
-   Role-based Middleware
-   Input Validation & Sanitization
-   Session & Token Security

---

## 🧑‍💻 Contributing

1. Fork repository
2. Create new branch
3. Commit and push changes
4. Create pull request

---
## 🧑‍💻 Author

**Amr — Laravel Backend Developer**  
Building maintainable, real-time backend systems ⚡
