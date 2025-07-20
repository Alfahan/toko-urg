# 🛒 Point of Sale (POS) Application

A modern web-based Point of Sale (POS) application built using **Laravel**, **Vue 3**, and **Inertia.js**. This application helps manage daily retail transactions, products, inventory, users, and access control efficiently through a clean and responsive interface.

---

## 📦 Tech Stack

| Layer     | Technology                      |
|-----------|----------------------------------|
| Backend   | Laravel 10.x                     |
| Frontend  | Vue 3                            |
| Bridge    | Inertia.js                       |
| Auth      | Laravel Breeze / Sanctum         |
| Styling   | Bootstrap 5, Tailwind (optional) |
| Database  | MySQL / MariaDB                  |
| Alerts    | SweetAlert2                      |

---

## 🚀 Features

- ✅ User authentication with role-based access
- ✅ Permissions management
- ✅ CRUD for:
  - Products
  - Categories
  - Units
  - Users & Roles
- ✅ Sales transaction system
- ✅ Dashboard with quick access
- ✅ Interactive alerts (SweetAlert2)
- ✅ Responsive layout for desktop & mobile

---

## 🧩 Folder Structure

```
├── app/
│   └── Http/
│       ├── Controllers/
│       └── Middleware/
├── resources/
│   └── js/
│       ├── Pages/
│       ├── Components/
│       └── Layouts/
├── routes/
│   └── web.php
├── database/
│   └── migrations/
│   └── seeders/
```

---

## ⚙️ Installation Guide

### 1. Clone Repository

```bash
git clone https://github.com/Alfahan/toko-urg.git
cd toko-urg
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` to configure your database and other environment settings.

### 4. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

> Includes default admin user and base permissions.

### 5. Serve Application

```bash
php artisan serve
```

Access your app via [http://localhost:8000](http://localhost:8000)

---

## 👤 Default Admin Credentials

| Email              | Password  |
|--------------------|-----------|
| admin@gmail.com    | password  |

> Make sure to change this password after first login.

---

## 🛠️ Useful Artisan Commands

```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan route:list
```

---

## ⚒️ Build for Production

```bash
npm run build
```

---

## 📖 Conventions

- All pages are located in `resources/js/Pages`
- All Vue components are located in `resources/js/Components`
- Layouts use Inertia `<slot />` to wrap content dynamically
- Roles and Permissions are dynamically assigned through checkbox-based UI

---

## 🌐 Live Demo

You can access the live version of this application at:

🔗 [https://toko-urg.alfahaaan.my.id](https://toko-urg.alfahaaan.my.id)

Credential: 

| Email              | Password  |
|--------------------|-----------|
| admin@gmail.com    | password  |
| cashier@gmail.com  | password  |
---
## 📄 License

This project is open-source and licensed under the [MIT License](LICENSE).

---

## 🙏 Credits

- [Laravel](https://laravel.com)
- [Vue.js](https://vuejs.org)
- [Inertia.js](https://inertiajs.com)
- [SweetAlert2](https://sweetalert2.github.io)
