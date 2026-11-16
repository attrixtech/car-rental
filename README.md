# WebotApp DriveSphere ERP
### Enterprise Car Rental, Self-Drive, Chauffeur & Fleet Management Software
**GitHub Repository:** [https://github.com/webotapp-academy/WebotApp-DriveSphere-ERP.git](https://github.com/webotapp-academy/WebotApp-DriveSphere-ERP.git)

---

## 1. Overview & Description

**WebotApp DriveSphere ERP** is a full-featured, commercially usable Car Rental Management ERP engineered with **Laravel 11, PHP 8.4+, MySQL/MariaDB, Tailwind CSS, Alpine.js, and Chart.js**.

Designed specifically for car rental companies, self-drive agencies, chauffeur travel services, and fleet operators in India and global markets, WebotApp DriveSphere ERP manages the entire vehicle and customer lifecycle from reservation to digital inspection, billing, and accounting.

### Key Capabilities:
- **Role-Segregated Dashboards & Portals:**
  - 👑 **Super Admin / Executive Owner:** Full financial KPIs, fleet utilization, escrow deposits held, revenue analytics, and general ledger.
  - 🚗 **Fleet Manager:** Real-time fleet status, preventive maintenance schedules, workshop work orders, and 30-day compliance/insurance expiration alerts.
  - 📋 **Counter Staff:** Today's check-outs (handovers), returns due today, unallocated bookings queue, and instant customer DL verification.
  - 🚖 **Chauffeur / Driver Portal:** Active assigned trip duty slips, passenger contact, route info, daily attendance, and earned day/night bata allowances.
  - 👤 **Customer Self-Service Portal:** Active on-rent vehicle tracking, rental agreement PDF downloads, GST tax invoices, loyalty points, and online car bookings.
- **Self-Drive & Chauffeur Workflows:**
  - **Self-Drive:** Mandatory Driving License (DL) & Aadhaar verification, refundable Escrow Security Deposit, digital 360° handover and return damage mapping, SAC 997311 @ 18% GST.
  - **Chauffeur-Driven:** Uniformed driver dispatch, duty slip telemetry (garage start/end km), outstation night batas, toll & parking reimbursement, SAC 996601 @ 5% GST.
- **Atomic Availability Engine:** Prevents double bookings using temporal overlap exclusion with 2-hour cleaning and turnaround buffers.
- **Escrow Security Deposit Accounting:** Security deposits are tracked under liability escrow accounts (Account 2001) and strictly segregated from rental sales revenue until settlement or forfeiture.
- **Automated Return Settlement:** Auto-computes excess kilometer fees, fuel deficit charges, deep cleaning fees, and damage repair deductions directly against the customer deposit with instant refund calculation.

---

## 2. GitHub Repository Information

- **Software Name:** `WebotApp DriveSphere ERP` (`WebotApp CarRental ERP`)
- **GitHub Repository URL:** [https://github.com/webotapp-academy/WebotApp-DriveSphere-ERP.git](https://github.com/webotapp-academy/WebotApp-DriveSphere-ERP.git)
- **License:** Proprietary / Commercial License (WebotApp Mobility Systems)
- **Primary Market:** India (GST compliant, CGST/SGST/IGST, Aadhaar & DL verification, Indian fleet models)

---

## 3. Demo Login Credentials & Instant Role Switcher

The login page at `http://localhost:8000/login` features **1-click instant login buttons** for every role:

| Role | Email | Password | Dedicated View |
|---|---|---|---|
| **Super Admin** | `admin@drivesphere.com` | `password123` | Executive Owner Dashboard |
| **Fleet Manager** | `fleet@drivesphere.com` | `password123` | Fleet & Maintenance Command |
| **Counter Staff** | `staff@drivesphere.com` | `password123` | Desk Check-out & Return Operations |
| **Chauffeur** | `driver@drivesphere.com` | `password123` | Driver Duty & Trip Portal |
| **Customer** | `customer@drivesphere.com` | `password123` | Customer Self-Service Portal |

---

## 4. Running the Application & Automated Tests

```bash
# 1. Run migrations and demo seeds
php artisan migrate --seed

# 2. Build assets
npm run build

# 3. Start local development server
php artisan serve

# 4. Run automated test suite (14 test suites, 77 assertions)
php artisan test
```
