# CAR RENTAL MANAGEMENT ERP — SYSTEM ARCHITECTURE SPECIFICATION

## 1. System Overview
The Car Rental Management ERP is an enterprise-grade, multi-branch, multi-company platform designed for automobile rental operators in India and global markets. It supports both **Self-Drive** and **Chauffeur-Driven** operations across hourly, daily, weekly, monthly, corporate, subscription, airport transfer, and outstation trip models.

---

## 2. Technology Stack & Runtime Architecture

| Layer | Component | Specification |
|---|---|---|
| **Runtime Environment** | PHP 8.4+ & Node.js 22+ | PHP with JIT, BCMath, PDO_MySQL, GD, Zip, OpenSSL |
| **Backend Framework** | Laravel 11.x (or 10.x LTS) | MVC, Service-Repository Pattern, Events/Listeners, Action Classes |
| **Database Engine** | MariaDB 10.4+ / MySQL 8.0+ | InnoDB, ACID Transactions, Strict SQL Mode, JSON fields for telemetry |
| **Dynamic Frontend** | Laravel Blade + Livewire 3 + Alpine.js | Component-driven reactivity, real-time validations, stateful modals |
| **Styling & Design System** | Tailwind CSS 3+ | High-density ERP layout, dark/light contrast, responsive tables |
| **Document Generation** | Barryvdh DomPDF + Maatwebsite Excel | GST tax invoices, rental agreements, trip sheets, inspection receipts |
| **Charts & BI Visuals** | Chart.js 4+ | Fleet utilization, revenue breakdown, expense distribution |
| **Cache & Session** | File / Redis / Database | Fast session management and rate limiting |
| **Queue & Scheduling** | Database / Redis Queue | Background document generation, GPS alert processing, reminder dispatch |

---

## 3. High-Level Modular Architecture

```
+-------------------------------------------------------------------------------------------------------+
|                                           CAR RENTAL ERP                                              |
+-------------------------------------------------------------------------------------------------------+
|  +---------------------------+  +---------------------------+  +-----------------------------------+  |
|  |     ADMIN & HQ PORTAL     |  |       BRANCH PORTAL       |  |     PUBLIC & CUSTOMER PORTAL      |  |
|  |  * Global Fleet & Rules   |  |  * Local Check-in/Return  |  |  * Vehicle Search & Booking       |  |
|  |  * Finance & Ledger       |  |  * Driver Dispatch        |  |  * Document Upload & KYC       |  |
|  |  * Master Pricing & Audit |  |  * Maintenance Orders     |  |  * Trip Tracking & Invoices       |  |
|  +---------------------------+  +---------------------------+  +-----------------------------------+  |
+-------------------------------------------------------------------------------------------------------+
                                                   |
+-------------------------------------------------------------------------------------------------------+
|                                        APPLICATION CORE DOMAINS                                       |
+-------------------------------------------------------------------------------------------------------+
|  [1. Company & Branch]      [2. RBAC & Identity]      [3. Fleet & Vehicle Lifecycle]                  |
|  [4. Customer CRM & KYC]    [5. Driver & Dispatch]    [6. Dynamic Pricing Engine]                     |
|  [7. Reservation System]    [8. Handover & Return]    [9. Maintenance & Compliance]                   |
|  [10. Security Deposit]     [11. Invoicing & GST]     [12. Double-Entry Accounting & P&L]             |
+-------------------------------------------------------------------------------------------------------+
                                                   |
+-------------------------------------------------------------------------------------------------------+
|                                       SHARED INFRASTRUCTURE LAYER                                     |
+-------------------------------------------------------------------------------------------------------+
|  * Availability Validator Engine     * GPS & Telemetry Ingestion      * Notification Dispatcher       |
|  * Audit Trail & Activity Logger     * PDF & QR Code Service          * File & Document Storage Vault |
+-------------------------------------------------------------------------------------------------------+
                                                   |
+-------------------------------------------------------------------------------------------------------+
|                                   DATA PERSISTENCE LAYER (MySQL / MariaDB)                             |
|         80+ Normalized Tables, Foreign Keys, Referential Integrity, Company-Level Data Isolation      |
+-------------------------------------------------------------------------------------------------------+
```

---

## 4. Workflows Separation: Self-Drive vs Chauffeur-Driven

### 4.1 Self-Drive Rental Workflow
1. **Customer Verification:** Mandatory Driving License (DL) verification, Aadhaar/Passport proof, minimum age policy (e.g. 21+), driving experience checks.
2. **Security Deposit:** Compulsory pre-authorization or cash/UPI/card deposit held in escrow liability account before vehicle release.
3. **Rental Agreement:** Signed legal agreement with liability waiver, interstate permit acknowledgement, speed limit (e.g. 80-100 km/h) compliance clause.
4. **Handover (Check-Out):** Digital inspection with fuel level recording (eighths or litres), start odometer, exterior/interior 360 damage checklist, customer signature.
5. **During Rental:** Geofencing, speed limit alerts, extension requests, breakdown assistance.
6. **Return (Check-In):** Detailed damage comparison against handover checklist, fuel differential computation, excess kilometer calculation, late return penalty.
7. **Deposit Settlement:** Immediate or staggered refund after deducting excess km, fuel shortfall, new damages, and pending challans/tolls.

### 4.2 Chauffeur-Driven Trip Workflow
1. **Driver Assignment:** Matching driver based on license category (commercial/transport badge), language, city familiarity, and shift availability.
2. **Duty Slip / Trip Sheet Generation:** Assigned start garage time, garage km, customer pickup location, package selection (e.g., 8h/80km local, outstation per km with night halt).
3. **Trip Execution:** Start trip recording (time & odometer), toll/parking expense logging by driver, intermediate stops, outstation night allowances (bata).
4. **Trip Close & Handover:** Customer signs digital duty slip confirming end km, end time, toll/parking disbursements.
5. **Chauffeur Settlement & Payroll:** Automated calculation of driver trip allowance, overtime, per diem, and vehicle fuel reimbursement.
6. **Billing:** Invoice generated directly based on contracted package rate, extra km, extra hours, night charges, driver allowance, tolls, and GST.

---

## 5. Shared Modules Between Workflows
Both workflows seamlessly leverage unified core business services:
- **Vehicle Availability Engine:** Single source of truth preventing double bookings across all rental types.
- **Fleet Inventory & Lifecycle:** Registration, insurance, fitness, pollution, tax, maintenance schedules, GPS tracking.
- **Customer CRM:** Unified profile with complete rental history, document repository, credit standing, loyalty points.
- **Invoicing & GST Engine:** HSN/SAC code mappings (SAC 996601 for rental of passenger vehicles with operator, SAC 997311 for rental without operator), CGST+SGST or IGST logic, B2B e-invoicing.
- **Payment & Accounting Gateway:** Double-entry journal entries, multi-mode payment collection, bank reconciliations, audit trail.
- **Maintenance & Work Orders:** Automated downtime blocking in vehicle calendar for scheduled servicing, preventive maintenance, or accident repairs.

---

## 6. Multi-Branch & Data Isolation Security
- Every operational record contains `company_id` and `branch_id`.
- Global Scope (`BelongsToCompanyBranch`) enforces tenant and branch isolation at the Eloquent query layer.
- Super Admins and HQ Executives have multi-branch aggregated visibility, while branch operators only access local fleet, handovers, returns, and daily cash drawers.

