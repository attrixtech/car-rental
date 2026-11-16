# CAR RENTAL MANAGEMENT ERP — DEVELOPMENT ROADMAP & TESTING STRATEGY

---

## 1. 12-Phase Development Roadmap

```
+---------------------------------------------------------------------------------------------------------+
|                                    12-PHASE IMPLEMENTATION LIFECYCLE                                    |
+---------------------------------------------------------------------------------------------------------+
| Phase 1: Foundation (Laravel, DB, Auth, Multi-Branch, RBAC, Layout Shell)                               |
|   |                                                                                                     |
| Phase 2: Master Data (Customers, Drivers, Fleet Categories, Brands, Models, Locations)                  |
|   |                                                                                                     |
| Phase 3: Fleet Management (Vehicle Registry, Profiles, Documents, Status Engine, Calendar)               |
|   |                                                                                                     |
| Phase 4: Dynamic Pricing & Packages (Hourly/Daily/Weekly/Monthly, Kilometers, GST, Surcharges)           |
|   |                                                                                                     |
| Phase 5: Booking & Reservation System (Self-Drive & Chauffeur Booking, Availability Validator, Assign)  |
|   |                                                                                                     |
| Phase 6: Rental Operations (Rental Agreements, Digital Handover, Return, Damage & Duty Slips)           |
|   |                                                                                                     |
| Phase 7: Deposits, Invoicing & Billing (Escrow Deposits, GST Invoices, Payments, Refunds)              |
|   |                                                                                                     |
| Phase 8: Maintenance & Fleet Lifecycle (Work Orders, Service Alerts, Downtime Blocking, Insurance)     |
|   |                                                                                                     |
| Phase 9: Chauffeur & Driver Management (Duty Rosters, Bata Allowances, Driver Portal, Payroll Logs)    |
|   |                                                                                                     |
| Phase 10: Financial Accounting & Fleet Costing (Double-Entry Ledger, P&L by Vehicle, Cost Centers)     |
|   |                                                                                                     |
| Phase 11: Customer Portal, Public Website & Telemetry (Self-Service, Online Booking, GPS Alerts)       |
|   |                                                                                                     |
| Phase 12: End-to-End Verification, Performance Hardening & Production Handover                         |
+---------------------------------------------------------------------------------------------------------+
```

### Detailed Phase Breakdown

#### Phase 1: Foundation & Application Core
- Initialize clean Laravel 11 application with robust directory structure.
- Configure MySQL/MariaDB database connection (`carrental_erp`).
- Implement Company, Branch, User, Role, and Permission schema and seeders.
- Build responsive Tailwind CSS Admin Layout with high-density sidebar navigation, breadcrumbs, search, user dropdown, and active state indicators.
- Setup authentication and role-based route middleware.

#### Phase 2: Master Data Infrastructure
- Seed and manage Vehicle Categories (Hatchback, Sedan, SUV, Luxury, Van).
- Seed Brands (Maruti Suzuki, Mahindra, Hyundai, Tata, Toyota, BMW).
- Models & Variants with seating capacity, transmission, and fuel type.
- Operational Locations (Offices, Airport Terminals, Railway stations, Hubs).
- Customer master with KYC document tracking (Aadhaar, Passport, DL).
- Driver roster with commercial badge, license expiry, and status.

#### Phase 3: Fleet Management Engine
- Vehicle registration with VIN, plate number, color, year, initial odometer.
- Document compliance tracking with expiry alerts (RC, Insurance, Fitness, PUC, Permit).
- Real-time vehicle status transitions (`active`, `reserved`, `rented`, `maintenance`, `decommissioned`).
- Interactive fleet calendar displaying vehicle allocation and availability.

#### Phase 4: Dynamic Pricing & Rate Engine
- Rental types: Self-Drive, Chauffeur-Driven, Airport Transfer, Outstation.
- Rate plans per category: Hourly, Daily (24h), Weekly, Monthly slabs.
- Packaged deals (e.g. 8h/80km local package).
- Excess kilometer and excess hour surcharge calculation.
- GST rules (18% for Self-Drive, 5%/12% for Chauffeur).

#### Phase 5: Booking & Reservation System
- Multi-step booking wizard with real-time vehicle category and model selection.
- Atomic vehicle availability checking with buffer time (prevent double bookings).
- Driver allocation for chauffeur bookings.
- Booking status workflow: Draft -> Confirmed -> Allocated -> Active -> Completed -> Cancelled.
- Cancellation engine with policy-based fee calculation.

#### Phase 6: Rental Operations (Handover & Return)
- Digital rental agreement generation with terms and customer signature capture.
- Digital Handover Checklist: Starting odometer, starting fuel level, accessories check, existing damage mapping.
- Digital Return Checklist: Ending odometer, ending fuel level, actual return time.
- Automated damage assessment: Scratch/dent logging with repair estimate.
- Automated late return penalty and excess kilometer charge computation.
- Chauffeur duty slip closing with toll and parking disbursements.

#### Phase 7: Deposits, Invoicing & Billing
- Escrow Security Deposit collection (Cash, Card, UPI, Bank).
- GST Tax Invoice generation with SAC code mapping, CGST/SGST/IGST breakdown.
- Multi-mode payment recording with gateway transaction references.
- Deposit settlement: Automated deduction of damages, fuel deficit, and excess km with instant balance refund.

#### Phase 8: Maintenance & Compliance
- Preventive maintenance schedules based on odometer intervals (e.g. every 10,000 km) and date intervals.
- Maintenance work orders with mechanic/workshop assignment and spare parts costing.
- Automatic vehicle status locking: Vehicle marked `maintenance` automatically blocked in booking calendar.
- Insurance renewal and accident claim tracking.

#### Phase 9: Chauffeur Operations & Driver Portal
- Driver assignment queue and daily duty slips.
- Outstation trip allowance calculation: Day bata, night bata, overtime per hour.
- Driver trip logs and fuel expense reimbursement.
- Driver performance rating and trip completion records.

#### Phase 10: Financial Accounting & Fleet Costing
- Standard Chart of Accounts (Assets, Liabilities, Equity, Revenue, Expense).
- Double-entry automated journal postings on invoice creation, payment, deposit receipt, deposit refund, and maintenance expense.
- Vehicle Costing Ledger: Real-time P&L per vehicle (Revenue minus EMI, Insurance, Maintenance, Fuel, Depreciation).
- Financial statements: Trial Balance, Income Statement, Customer Accounts Receivable Ledger.

#### Phase 11: Public Booking Website & Customer Portal
- Responsive customer-facing car catalog with filters (Category, Transmission, Fuel, Seats).
- Online booking form with date/time pickers and instantaneous price estimation.
- Customer self-service portal: Active bookings, rental agreements, invoices, trip history.
- Telemetry & GPS integration simulation (speed alerts, geofence status).

#### Phase 12: End-to-End Verification & Production Readiness
- Complete automated test suite:
  - Unit tests for Availability Engine and Pricing Calculator.
  - Feature tests for Booking lifecycle, Handover, and Return.
  - Full end-to-end integration tests for Self-Drive and Chauffeur-driven complete cycles.
- Database seeder with realistic Indian market fleet, bookings, customers, and drivers.
- Complete documentation and clean deployment script.

---

## 2. Comprehensive Testing Strategy

### 2.1 Automated Test Suites (PHPUnit / Pest)
1. **VehicleAvailabilityTest:**
   - Validates that overlapping confirmed bookings for the same vehicle fail validation.
   - Validates that buffer turnaround time (2 hours) is respected.
   - Validates that vehicles in maintenance cannot be allocated.
2. **RentalPricingEngineTest:**
   - Tests hourly calculation with minimum hours.
   - Tests 24-hour daily cycle with grace period.
   - Tests excess km and excess hour penalties.
   - Tests GST computation (CGST+SGST vs IGST).
3. **SelfDriveWorkflowTest (Critical E2E):**
   - Step 1: Customer registration & DL verification.
   - Step 2: Create booking for 3 days Self-Drive.
   - Step 3: Security deposit ₹10,000 collected in escrow.
   - Step 4: Digital handover (Start Km: 25,000, Fuel: 8/8).
   - Step 5: Vehicle Return with +100 excess km, 6/8 fuel, and ₹1,500 bumper scratch.
   - Step 6: Verify automated deduction from deposit (₹10,000 - deductions = refund).
   - Step 7: Verify final invoice and balanced double-entry journal entries.
4. **ChauffeurDrivenWorkflowTest (Critical E2E):**
   - Step 1: Outstation booking for 2 days with chauffeur.
   - Step 2: Driver assigned with badge check.
   - Step 3: Trip start duty slip created.
   - Step 4: Return with 650 km, night halt, and ₹400 toll expense.
   - Step 5: Verify driver night bata and toll reimbursement added.
   - Step 6: Verify final billing and driver settlement.

