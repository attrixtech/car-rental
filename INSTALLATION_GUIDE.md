# CAR RENTAL ERP — INSTALLATION & SETUP GUIDE

## 1. Prerequisites
- **PHP:** 8.2+ (PHP 8.4 recommended) with extensions: `pdo_mysql`, `mbstring`, `curl`, `openssl`, `gd`, `zip`, `bcmath`.
- **Database:** MariaDB 10.4+ or MySQL 8.0+.
- **Node.js:** v20+ with npm.
- **Composer:** v2+.

---

## 2. Quick Setup

```bash
# 1. Clone or navigate to directory
cd /Applications/XAMPP/xamppfiles/htdocs/carrental

# 2. Install PHP & Node dependencies
composer install
npm install

# 3. Compile frontend assets
npm run build

# 4. Create database and run migrations
mysql -u root -e "CREATE DATABASE IF NOT EXISTS carrental_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed

# 5. Start the local server
php artisan serve
```

Visit: `http://localhost:8000`

---

## 3. Demo Login Credentials

| Role | Email | Password |
|---|---|---|
| **Super Admin** | `admin@agracarrental.in` | `password123` |
| **Fleet Manager** | `fleet@agracarrental.in` | `password123` |
| **Counter Staff** | `staff@agracarrental.in` | `password123` |
| **Chauffeur** | `driver@agracarrental.in` | `password123` |
| **Customer** | `customer@agracarrental.in` | `password123` |

*(Note: The login screen also features 1-click instant login buttons for all roles).*

