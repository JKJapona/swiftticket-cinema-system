<p align="center">
  <img src="public/images/swiftticket_abreeza_pill.svg" alt="SwiftTicket Abreeza Logo" width="1000">
</p>

# SwiftTicket: Abreeza Cinema Reservation System

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)
[![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg?style=for-the-badge)](https://github.com/JKJapona/swiftticket-cinema-system/graphs/commit-activity)

**SwiftTicket** is a production-ready cinema management and ticketing platform. Engineered with **Laravel 12**, it delivers an end-to-end solution for modern theaters. Balancing a frictionless customer booking flow with a robust administrative engine for real-time operations.

---

## 🚀 Live Deployment
**Production URL:** [https://swiftticket-cinema-system.onrender.com/](https://swiftticket-cinema-system.onrender.com/)  
> **Note:** Hosted on Render's Free Tier. If the site is idle, please allow **30–60 seconds** for the instance to spin up.

---

## 📊 Repository Metrics
<p align="left">
<img src="https://github-readme-stats.vercel.app/api?username=JKJapona&show_icons=true&theme=tokyonight&hide_border=true&count_private=true" alt="GitHub Stats" height="150">
<img src="https://github-readme-stats.vercel.app/api/top-langs/?username=JKJapona&layout=compact&theme=tokyonight&hide_border=true" alt="Top Languages" height="150">
</p>

---

## 🌟 Core Functionalities

### 👤 Customer Experience
* **Interactive Seat Mapping:** JS-based, real-time seat selection interface ensuring zero concurrency conflicts.
* **Smart Scheduling:** Automated filtering for "Now Showing" vs. "Coming Soon" based on system timestamps.
* **Digital Ticketing:** Automated generation of unique alphanumeric booking references (e.g., `OD8KKF0N`).
* **Flexible Checkout:** Secure selection between GCash, Credit Card, or "Pay at Cinema" workflows.

### 🛡️ Administrative Engine
* **Operational Control:** CRUD management for Movie libraries, Cinema Halls (Standard, IMAX, 4DX), and Showtimes.
* **Security & Governance:** Robust Role-Based Access Control (RBAC) with account suspension capabilities.
* **Data Integrity:** Fully normalized MySQL schema with Foreign Key constraints to ensure relational consistency and booking accuracy.

---

## 🛠️ Technical Architecture

| Layer | Technology |
| :--- | :--- |
| **Framework** | Laravel 12 (PHP 8.2+) |
| **Database** | MySQL via Clever Cloud (Relational Schema) |
| **Frontend** | Blade Templates, Vanilla JS, Bootstrap 5, Bootstrap Icons |
| **Security** | Session-based authentication / CSRF Protection |
| **Deployment** | Render PaaS / GitHub Actions (CI/CD) |

---

## 🔐 Sandbox Access (For Testing)

| Role | Email | Password |
| :--- | :--- | :--- |
| **System Admin** | `admin@swiftticket.com` | `password` |
| **Alt Admin** | `admin.alt@swiftticket.com` | `password` |
| **Standard User** | `test@customer.com` | `password` |
| **Suspended User** | `banned@customer.com` | `password` |

---

## 🎨 Design Philosophy
SwiftTicket adopts a **"Usability First"** aesthetic. The UI is optimized for high-contrast readability, adhering to professional accessibility standards. The layout utilizes a structured hierarchy to guide users through the 3-step booking process: **Select Movie → Pick Seats → Confirm Payment.**

---

## 👨‍💻 Development Team
Developed for the **College of Computing Education**.

* **Lead System Architect:** [Jheric Kent Japona](https://github.com/JKJapona)
* **Lead Supervisor:** Prof. Glenn Angelo Oliva

---
<p align="center">
  Built with ❤️ by Jheric Kent Japona | 2026
</p>