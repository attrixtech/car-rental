# 🚗 thrumble.car-rental
### Enterprise Car Rental, Self-Drive Fleet & Chauffeur Mobility Management System

<p align="center">
  <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80" alt="thrumble.car-rental Banner" width="100%" style="border-radius: 12px; max-height: 400px; object-fit: cover;" />
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.4+"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="https://alpinejs.dev"><img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.0+"></a>
  <img src="https://img.shields.io/badge/Tests-14%20Passed%20%7C%2077%20Assertions-brightgreen?style=for-the-badge&logo=checkmarx&logoColor=white" alt="Tests Passed">
  <img src="https://img.shields.io/badge/GST-Compliant%20(India)-blue?style=for-the-badge" alt="GST Compliant">
</p>

---

## 📖 Executive Summary

**thrumble.car-rental** is an automotive-grade, enterprise-ready Fleet & Car Rental Management ERP engineered with **Laravel 11, PHP 8.4, MySQL, Tailwind CSS, Alpine.js, and Chart.js**.

Designed specifically for automotive rental operations, self-drive rental companies, luxury chauffeur services, and multi-branch commercial fleet operators, **thrumble.car-rental** handles the complete vehicle lifecycle — from public customer booking to KYC digital onboarding, deposit escrow tracking, return settlement with automated damage deduction, and general ledger accounting.

---

## ⚡ Key Highlights & Core Capabilities

### 1. 🌐 Public Dark-Luxury Booking Portal (`/`)
* **Live Fleet Catalog:** High-resolution vehicle photography with category filters (Hatchback, Compact SUV, Full-Size SUV, Executive Sedan, Premium Luxury).
* **Interactive Search Console:** Toggle seamlessly between **Self-Drive**, **Chauffeur Driven**, and **Airport Transfer** with location hubs and date-time pickers.
* **Transparent Pricing:** Clear daily rates, refundable security deposit transparency, and included amenities (GPS, Bluetooth, Fastag, Airbags).
* **Automated Vehicle Detail Pages:** In-depth technical specifications matrix, tariff breakdown, insurance terms, and instant reservation trigger.

### 2. 🔐 5 Role-Segregated Portals & Dashboards
Every stakeholder operates within a tailored workflow with dedicated navigation and permissions:

| Role | Target Portal | Key Operational Duties |
|---|---|---|
| 👑 **Super Admin** | Executive Command Center | Real-time fleet utilization, P&L analytics, escrow deposits held, company ledger, and user RBAC. |
| 🚙 **Fleet Manager** | Fleet Maintenance Command | Live GPS fleet status, preventive service intervals, workshop work orders, and 30-day compliance / fitness expiration alerts. |
| 📋 **Counter Staff** | Dispatch Counter Desk | Today's check-outs, returns due today, unallocated bookings queue, and instant customer DL verification. |
| 🚖 **Chauffeur / Driver** | Chauffeur Duty Portal | Assigned duty slips, passenger contact & pickup navigation, daily attendance, and night bata / allowance tracking. |
| 👤 **Customer** | Customer Self-Service Portal | Active rentals, booking history, rental agreement downloads, GST tax invoices, and live refund status. |

---

### 3. 🛡️ Self-Drive & Chauffeur Workflows

```
┌───────────────────────────────────────────────────────────────────────────┐
│                       SELF-DRIVE RENTAL WORKFLOW                         │
└───────────────────────────────────────────────────────────────────────────┘
   Customer Books ──► Aadhaar/DL Verification ──► Escrow Deposit Collected
          │
          ▼
   360° Handover Inspection ──► Active Rental ──► Return Check-in
          │
          ▼
   Automated Settlement (Fuel Deficit + Excess KM + Damage) ──► Escrow Refund
```

* **Self-Drive Workflow:**
  * Mandatory driving license (DL) & government identity (Aadhaar/Passport) verification.
  * Refundable Escrow Security Deposit held in liability accounts (Account 2001) segregated from operating income.
  * 360° digital inspection mapping odometer, fuel level gauge (eighths), and scratch/dent notations.
  * SAC 997311 @ 18% GST (CGST + SGST or IGST).

* **Chauffeur-Driven Workflow:**
  * Automated duty slip allocation to verified uniformed chauffeurs.
  * Telemetry logging (garage start/end km, garage time, passenger pickup/drop times).
  * Outstation allowances, driver night batas, and toll/parking expense logging.
  * SAC 996601 @ 5% GST (CGST + SGST or IGST).

---

### 4. 🧮 Intelligent Pricing & Booking Engines
* **Atomic Temporal Availability Engine:** Guarantees zero double bookings through continuous temporal overlap exclusion query with an automated **2-hour cleaning and turnaround buffer**.
* **Indian GST Tax Engine:** Computes Intra-state (CGST 9% + SGST 9%) vs Inter-state (IGST 18%) for Self-Drive, and (CGST 2.5% + SGST 2.5%) vs Inter-state (IGST 5%) for Chauffeur trips.
* **Automated Return Settlement:** Auto-computes excess km charges, fuel deficiency fees, cleaning penalties, and damage charges deducted directly from escrow with instant refund calculations.

---

## 🛠️ Tech Stack & Architecture

- **Backend Framework:** [Laravel 11.x](https://laravel.com)
- **Language:** [PHP 8.4+](https://php.net) (Strict Types, Enums, Modern Accessors)
- **Database:** [MySQL 8.0+](https://mysql.com) / MariaDB
- **Frontend Engine:** Blade Templates, [Tailwind CSS 3.x](https://tailwindcss.com), [Vite 5.x](https://vitejs.dev)
- **Reactivity & Interactivity:** [Alpine.js 3.x](https://alpinejs.dev)
- **Data Visualizations:** [Chart.js 4.x](https://www.chartjs.org/)
- **Icons:** [Lucide Icons](https://lucide.dev)
- **Testing:** PHPUnit with Feature & Unit Suites (14 Tests / 77 Assertions)

---

## 🚀 Quickstart & Installation

### Prerequisites
* PHP >= 8.2 (PHP 8.4 recommended)
* Composer 2.x
* Node.js 18+ and NPM
* MySQL 8.0+ / MariaDB 10.4+

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone https://github.com/thrumble/car-rental.git
cd car-rental

# 2. Install PHP backend dependencies
composer install

# 3. Install frontend assets and build
npm install
npm run build

# 4. Environment configuration
cp .env.example .env
php artisan key:generate

# Configure your database credentials in .env:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=carrental_erp
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run database migrations with comprehensive dummy seeds
php artisan migrate:fresh --seed

# 6. Start the development server
php artisan serve
```

Access the application in your browser:
- **Public Homepage & Fleet Catalog:** `http://127.0.0.1:8000/`
- **ERP Login Portal:** `http://127.0.0.1:8000/login`

---

## 🔑 Demo Login Credentials & 1-Click Role Switcher

The login page (`/login`) includes **1-Click Instant Demo Login Buttons** for rapid testing across all roles:

| Role | Email Address | Password | Dedicated Portal |
|---|---|---|---|
| 👑 **Super Admin** | `admin@agracarrental.in` | `password123` | Executive Owner Dashboard |
| 🚙 **Fleet Manager** | `fleet@agracarrental.in` | `password123` | Fleet & Maintenance Command |
| 📋 **Counter Staff** | `staff@agracarrental.in` | `password123` | Counter Check-out & Return Operations |
| 🚖 **Chauffeur** | `driver@agracarrental.in` | `password123` | Driver Duty & Trip Portal |
| 👤 **Customer** | `customer@agracarrental.in` | `password123` | Customer Self-Service Portal |

---

## 🧪 Automated Testing Suite

**thrumble.car-rental** comes with a battle-tested test suite verifying availability buffers, GST computations, role authorization, and complete rental lifecycles.

```bash
php artisan test
```

```
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Unit\PricingEngineTest
  ✓ pricing calculation for daily self drive with gst
  ✓ interstate gst assigns igst

   PASS  Tests\Unit\VehicleAvailabilityTest
  ✓ vehicle is available when no conflicts
  ✓ vehicle is not available when overlapping booking exists
  ✓ turnaround buffer time is enforced

   PASS  Tests\Feature\ChauffeurDrivenWorkflowTest
  ✓ complete chauffeur driven outstation lifecycle

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

   PASS  Tests\Feature\RentalWorkflowTest
  ✓ complete self drive lifecycle workflow

   PASS  Tests\Feature\RoleDashboardAccessTest
  ✓ super admin sees executive owner dashboard
  ✓ fleet manager sees fleet maintenance dashboard
  ✓ counter staff sees dispatch counter dashboard
  ✓ driver sees chauffeur duty portal
  ✓ customer sees customer self service portal

  Tests:    14 passed (77 assertions)
  Duration: 0.56s
```

---

## 🏛️ Project Directory Structure

```
thrumble.car-rental/
├── app/
│   ├── Enums/                 # RentalStatus, VehicleStatus, BookingType, PaymentMethod
│   ├── Http/
│   │   ├── Controllers/       # Admin, Fleet, Counter, Driver, Customer, Public controllers
│   │   └── Requests/          # Form validation requests
│   ├── Models/                # Vehicle, Booking, Inspection, Invoice, MaintenanceLog, DutySlip
│   └── Services/              # PricingEngine, AvailabilityService, EscrowLedgerService
├── database/
│   ├── migrations/            # 12 relational database migrations
│   └── seeders/               # Indian fleet, users, hubs, reservations, maintenance logs
├── resources/
│   ├── css/                   # Tailwind CSS entry points
│   ├── js/                    # Alpine.js, Lucide, Chart.js bundles
│   └── views/
│       ├── layouts/           # Master application layouts
│       ├── public/            # Public fleet catalog & vehicle details
│       ├── admin/             # Executive Owner views
│       ├── fleet/             # Fleet manager views
│       ├── counter/           # Counter staff check-in/out views
│       ├── driver/            # Chauffeur duty slips views
│       └── customer/          # Customer self-service views
└── tests/                     # Unit and Feature automated tests
```

---

## 📄 Credits & Ownership

- **Demo Company:** Agra Car Rental Service — 307, Prateek Centre, Sanjay Place, Agra 282002
- **Developed by:** [Attrix Technologies](https://www.attrixtech.com)
- **Marketing by:** [infusionn Pvt. Ltd.](https://www.infusionn.in)
