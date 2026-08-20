# 🏋️ Fitness Tracker — Indoor Exercise, Health Biometrics & Progression App

A modern, high-performance, dark-themed, fully responsive **Indoor Fitness, Biometric Health & Body Weight Progression Tracker** built with **Laravel 12**, **Alpine.js**, **Tailwind CSS**, **Chart.js**, and **Flatpickr**.

---

## ✨ Features Overview

### 🚴 Workout Logging & Dynamic Modals
- **Supported Activities**: Indoor Cycling, Treadmill, Heavyweight Training, Jump Rope, Yoga, Calisthenics, HIIT, and Custom Indoor Exercises.
- **Activity-Specific Inputs**:
  - *Heavyweight Training*: Weight lifted (`kg`), sets, reps per set, and total volume calculation.
  - *Jump Rope*: Total jumps counter.
  - *Indoor Cycling & Treadmill*: Distance (`km`) and speed (`km/h`) tracking.
- **Smart Calorie Auto-Estimation**: Real-time calorie burn predictions based on intensity, duration, and exercise type.
- **Date & Time Picker**: Integrated Flatpickr date-time picker with universal formatting (`DD/MM/YYYY HH:MM`).
- **Recent Workouts Scroll Container**: Dashboard recent workouts table features an internal custom scrollbar (`max-h-[500px]`) and sticky table headers (`sticky top-0`) to match the log workout modal in height.

---

### 📜 Dedicated Workout History (`/workouts`)
- **Full History View**: Browse your complete training history with date, activity type, duration, distance, calories, and full session notes.
- **Activity & Keyword Filters**: Filter by activity type and search through session notes in real-time.
- **Interactive Dashboard Link**: Clicking the **"Total Logged"** badge on the dashboard instantly navigates to the complete Workout History page.
- **Summary Metrics**: Live statistics displaying Total Workouts, Active Hours, Total Distance, and Total Calories Burned.
- **Pagination**: Smooth pagination with page-size controls.

---

### ⚕️ Health Biometrics & Target Weight Engine
- **Body Mass Index (BMI)**: Numeric BMI calculation with status badges (*Underweight*, *Normal Weight*, *Overweight*, *Obese*) and a 4-zone visual gauge bar.
- **Resting BMR (Basal Metabolic Rate)**: Accurate resting energy expenditure using the **Mifflin-St Jeor formula**.
- **Daily Maintenance TDEE**: Daily caloric needs computed for 5 activity levels (*Sedentary*, *Lightly Active*, *Moderately Active*, *Very Active*, *Extra Active*).
- **Ideal Target Weight**: Computes the healthy weight range span ($18.5 - 24.9$ BMI) and recommended target weight based on height.
- **Interactive Biometrics Modal**: Glassmorphic Alpine.js popup modal with smooth scale-fade transitions to update biometrics directly from the dashboard.

---

### 📊 Performance Analytics & Progression (`/analytics`)
- **Interactive Time Range Filter**: View performance metrics over **7, 14, 30, 60, or 90 days**.
- **Visual Charting (Chart.js)**:
  - Daily Calories Burned trend line.
  - Daily Distance Covered trend line.
  - Daily Active Minutes trend line.
  - Activity Type Distribution doughnut chart.
- **⚖️ Body Weight Progression**: Dedicated weight timeline chart (`kg` vs date) with trend badges (*Loss*, *Gain*, *Maintained*).
- **📅 Month-over-Month Comparisons**: Compares current calendar month totals against the previous month with growth percentage badges (e.g. `▲ +18% vs last month`).

---

### 🏆 Gamification & Fitness Goals
- **🔥 Active Daily Streak Counter**: Real-time streak tracking of consecutive active days displayed in the navigation bar.
- **🎯 Weekly Fitness Goals**: Set and monitor weekly progress for active minutes, calories burned, and total workout sessions.
- **🏆 Trophy Room (Personal Records)**: Automatic awards for All-Time Max Weight Lifted, Max Distance, Max Calories Burned, and Longest Duration.

---

### 💾 Database Backup & Cloud-to-Local Sync
- **One-Click Backup**: Download your live SQLite database directly from `/profile` (`/export-database`) to easily back up data or sync cloud workouts to your local development environment.

---

### 🔒 Account Management & Deletion
- **Isolated Deletion Modal**: Teleported directly to `document.body` to ensure crystal-clear visual styling without blur or click interception.
- **Password Verification**: Secure confirmation requiring current password to permanently wipe data.

---

## 🛠️ Technology Stack

| Layer | Technology |
|---|---|
| **Backend Framework** | [Laravel 12](https://laravel.com/) (PHP 8.2+) |
| **Testing Suite** | [Pest PHP 3](https://pestphp.com/) (42 Feature Tests, 121 Assertions) |
| **Code Formatter** | [Laravel Pint](https://laravel.com/docs/pint) |
| **Database** | SQLite (with automatic persistent volume detection) |
| **Frontend Reactive Logic** | [Alpine.js](https://alpinejs.dev/) |
| **Styling** | [Tailwind CSS](https://tailwindcss.com/) |
| **Charts** | [Chart.js](https://www.chartjs.org/) |
| **Date-Time Picker** | [Flatpickr](https://flatpickr.js.org/) |

---

## 🚀 Local Development Setup

### Prerequisites
- **PHP** >= 8.2 with `pdo_sqlite`, `zip`, and `curl` extensions
- **Composer**
- **Node.js** >= 18.x & **npm**

### Installation Steps

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/JrUchsas/Fitness-Tracker.git
   cd Fitness-Tracker
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies & Build**:
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

6. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   Access the app at `http://127.0.0.1:8000`.

---

## 🚢 Deployment & Docker Persistence

This application is container-ready for deployment on **Render**, **Railway**, **Fly.io**, or **Laravel Cloud**.

- **Persistent Disk**: Mount your cloud persistent volume at `/var/data`.
- **Automatic Fallback**: [`config/database.php`](file:///d:/All%20Codes/PHP%20Projects/Fitness%20Tracker/config/database.php) automatically detects `/var/data` and uses `/var/data/database.sqlite`.
- **Safe Boot Strategy ([`Dockerfile`](file:///d:/All%20Codes/PHP%20Projects/Fitness%20Tracker/Dockerfile))**:
  ```sh
  mkdir -p /var/data && (test -f /var/data/database.sqlite || (test -f database/database.sqlite && cp database/database.sqlite /var/data/database.sqlite) || touch /var/data/database.sqlite) && php artisan migrate --force && php -S 0.0.0.0:${PORT:-8000} -t public/
  ```
  On first boot, it initializes `/var/data/database.sqlite`. On subsequent restarts or redeployments, it **never overwrites** your saved workouts.

---

## 🧪 Testing & Quality Assurance

### Run Pest Test Suite
```bash
php artisan test --compact
```
*(42 Feature Tests passing with 121 assertions across Auth, Workouts, Biometrics, Goals, Streaks, History, Analytics, and Profile).*

### Run Code Formatter
```bash
vendor/bin/pint --format agent
```

---

## 📄 License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
