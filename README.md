# 🏋️ Fitness Tracker — Indoor Exercise & Weight Progression Application

A modern, high-performance, dark-themed, fully responsive **Indoor Fitness, Biometric Health & Body Weight Progression Tracker** built with **Laravel 12**, **Alpine.js**, **Tailwind CSS**, **Chart.js**, and **Flatpickr**.

---

## ✨ Features

### ⚕️ Automatic Health & Biometric Metrics (BMI, BMR, TDEE & Target Weight)
- **Body Mass Index (BMI)**: Real-time numeric BMI calculation with category badges (*Underweight*, *Normal Weight*, *Overweight*, *Obese*) and a 4-zone visual gauge bar.
- **Resting BMR (Basal Metabolic Rate)**: Accurately calculates baseline resting calories burned daily using the **Mifflin-St Jeor formula**.
- **Daily Maintenance TDEE (Total Daily Energy Expenditure)**: Calculates calorie maintenance targets tailored to 5 daily activity levels (*Sedentary*, *Lightly Active*, *Moderately Active*, *Very Active*, *Extra Active*).
- **Ideal Target Weight Indicator**: Computes healthy weight range span ($18.5 - 24.9$ BMI range) and optimal target weight for the user's height.
- **Decimal & Integer Precision**: Full support for decimal height inputs (e.g. `177.8` cm) and weight inputs (e.g. `74.5` kg).
- **Interactive Biometrics Modal**: Glassmorphic Alpine.js popup modal with smooth scale-fade transitions to update biometrics directly from the dashboard.

---

### 🚴 Indoor Workout Logging & Auto Predictions
- **Supported Activities**: Indoor Cycling, Treadmill, Heavyweight Training, Jump Rope, Yoga, and Custom Indoor Exercises.
- **Smart Auto Predictions**: Real-time calorie estimation and speed calculations for Indoor Cycling & Treadmill sessions based on duration and intensity.
- **Activity-Specific Inputs**:
  - *Heavyweight Training*: Weight lifted (`kg`), sets, reps per set, and total volume calculations.
  - *Jump Rope*: Total jumps counter.
  - *Indoor Cycling & Treadmill*: Manual distance (`km`) and speed (`km/h`) inputs.
- **Date & Time Picker**: Flatpickr integration for guaranteed international `DD/MM/YYYY` (Day/Month/Year) date and time selection with automatic popover closing.

---

### 📊 Analytics & Performance Insights (`/analytics`)
- **Interactive Time Range Selector**: Filter analytics by **7, 14, 30, 60, or 90 days**.
- **Visual Charts (Chart.js)**:
  - Daily Calories Burned trend line chart.
  - Daily Distance Covered trend line chart.
  - Daily Active Duration trend line chart.
  - Activity Type Distribution pie/doughnut chart.
- **⚖️ Body Weight Progression Chart**: Dedicated weight tracking line chart (`kg` vs date) with chronological progression timeline.
- **📅 Monthly Performance Comparison Grid**: Compares current calendar month totals against the previous calendar month with growth percentage badges (e.g. `▲ +18% vs last month`).

---

### 📱 Multi-Device & Mobile Scalability
- **Responsive Drawer Menu**: Mobile navigation drawer menu (`sm:hidden`) with backdrop blur, touch-friendly padding, icons, user avatar, and active link highlights.
- **Adaptive Grid Layouts**: Responsive grid layouts (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5`) tailored for mobile, tablet, and desktop viewports.
- **Overflow Table Wrappers**: Smooth horizontal scrolling for workout history tables on mobile devices.

---

### 🏆 Gamification & Fitness Goals
- **🔥 Active Daily Streak Counter**: Real-time streak tracking of consecutive active days in the top navigation header.
- **🎯 Weekly Fitness Goals**: Set and monitor weekly progress for total active minutes, calories burned, and workout sessions with progress bars and an interactive edit modal.
- **🏆 Personal Records (Trophy Room)**: Automatic trophy badges for All-Time Max Weight Lifted, Max Distance Covered, Max Calories Burned, and Longest Session Duration.

---

### 👤 Profile & Body Weight Management (`/profile`)
- **Glassmorphic Dark UI**: High-contrast, sleek interface with Tailwind CSS styling and backdrop overlays.
- **Personal Metrics**: Manage name, email, age, gender, height (`cm`), body weight (`kg`), and activity level.
- **Seamless Weight Logs Sync**: Updating weight in profile or via dashboard biometrics automatically appends a new entry to your `weight_logs` progression timeline.

---

## 🛠️ Technology Stack

- **Framework**: [Laravel 12](https://laravel.com/) (PHP 8.2+)
- **Testing**: [Pest PHP 3](https://pestphp.com/) (34 Feature Tests, 95 Assertions)
- **Code Formatter**: [Laravel Pint](https://laravel.com/docs/pint)
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
