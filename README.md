# 🏋️ Fitness Tracker — Indoor Exercise & Weight Progression Application

A modern, high-performance, dark-themed **Indoor Fitness & Body Weight Progression Tracker** built with **Laravel 12**, **Alpine.js**, **Tailwind CSS**, **Chart.js**, and **Flatpickr**.

---

## ✨ Features

### 🚴 Indoor Workout Logging & Auto Predictions
- **Supported Activities**: Indoor Cycling, Treadmill, Heavyweight Training, Jump Rope, Yoga, and Custom Indoor Exercises.
- **Smart Auto Predictions**: Automatic real-time calorie estimation and speed calculations for Indoor Cycling & Treadmill sessions.
- **Activity-Specific Inputs**:
  - *Heavyweight Training*: Weight lifted (kg), sets, reps per set, and total volume calculations.
  - *Jump Rope*: Total jumps counter.
  - *Indoor Cycling & Treadmill*: Fixed manual distance (km) and speed (km/h) inputs.
- **Date & Time Picker**: Flatpickr integration for guaranteed international `DD/MM/YYYY` (Day/Month/Year) date and time selection.

---

### 📊 Analytics & Performance Insights (`/analytics`)
- **Interactive Time Range Selector**: Filter analytics by **7, 14, 30, 60, or 90 days**.
- **Visual Charts (Chart.js)**:
  - Daily Calories Burned trend.
  - Daily Distance Covered trend.
  - Daily Active Duration trend.
  - Activity Type Distribution pie/doughnut chart.
- **⚖️ Body Weight Progression Chart**: Dedicated weight tracking line chart (`kg` vs date) located above monthly comparisons.
- **📅 Monthly Performance Comparison Grid**: Compares current calendar month totals against the previous calendar month with growth percentage badges (e.g. `▲ +18% vs last month`).

---

### 🏆 Gamification & Goals
- **🔥 Active Daily Streak Counter**: Tracks consecutive active days right in the top navigation header.
- **🎯 Weekly Fitness Goals**: Set and monitor progress for total active minutes, calories burned, and workout sessions with progress bars and interactive modal.
- **🏆 Personal Records (Trophy Room)**: Automatic record badges for All-Time Max Weight Lifted, Max Distance Covered, Max Calories Burned, and Longest Session.

---

### 👤 Profile & Body Weight Management (`/profile`)
- **Glassmorphic Dark UI**: High-contrast, sleek interface with customized inputs and dark backdrop overlays.
- **Personal Metrics**: Manage age, gender, and current body weight (`kg`).
- **Seamless Weight Logs Sync**: Updating weight in profile or via the Dashboard modal automatically appends a new entry to your `weight_logs` progression timeline.

---

## 🛠️ Technology Stack

- **Framework**: [Laravel 12](https://laravel.com/) (PHP 8.2+)
- **Testing**: [Pest PHP 3](https://pestphp.com/)
- **Frontend Interactivity**: [Alpine.js](https://alpinejs.dev/)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/)
- **Charting**: [Chart.js](https://www.chartjs.org/)
- **Date Picker**: [Flatpickr](https://flatpickr.js.org/)

---

## 🚀 Quick Start Guide

### Prerequisites
- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18.x & **npm**

### Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/fitness-tracker.git
   cd fitness-tracker
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Install JavaScript Dependencies**:
   ```bash
   npm install
   ```

4. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

6. **Build Production Assets**:
   ```bash
   npm run build
   ```

7. **Serve the Application**:
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.

---

## 🧪 Testing & Code Quality

### Run Test Suite (Pest PHP)
```bash
php artisan test --compact
```

### Format Code (Laravel Pint)
```bash
vendor/bin/pint --format agent
```

---

## 📄 License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
