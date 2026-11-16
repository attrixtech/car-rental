# CAR RENTAL ERP — DATABASE ARCHITECTURE SPECIFICATION

This database design adheres to 3NF normalization, full ACID compliance, foreign key constraints, decimal precision for currency (`DECIMAL(12,2)`), and tenant/branch data partitioning.

---

## Complete Table Catalog by Business Domain

### 1. Core & Tenant Infrastructure
1. `companies` — Tenant profile, GSTIN, CIN, currency, logo, contact, registered address, tax regimes.
2. `branches` — Operational branches, airport hubs, city offices, addresses, contact details, status.
3. `users` — Authentication credentials, user type (`admin`, `staff`, `customer`, `driver`), company_id, branch_id.
4. `roles` — Spatie/Custom RBAC roles (`Super Admin`, `Fleet Manager`, `Operations Executive`, `Accountant`, `Driver`, `Customer`).
5. `permissions` — Granular action permissions (`fleet.view`, `fleet.create`, `booking.approve`, `deposit.refund`, etc.).
6. `role_has_permissions` — Role-permission junction.
7. `model_has_roles` — User-role polymorphic junction.
8. `activity_logs` — Operational audit trails recording user, action, IP, payload changes.
9. `audit_logs` — Financial and compliance audit snapshots.
10. `system_settings` — Key-value configuration for rental policies, tax rates, notification rules.

### 2. Customer Management (CRM & KYC)
11. `customers` — Customer identity, type (`individual`, `corporate`), credit limit, loyalty tier, blacklisted flag.
12. `customer_addresses` — Residential, billing, and permanent addresses.
13. `customer_documents` — Driving license, Aadhaar card, passport, verification status, expiry dates.
14. `customer_notes` — Internal staff notes, behavioral flags, special requests.
15. `customer_communications` — SMS, email, WhatsApp communication log.

### 3. Fleet & Vehicle Management
16. `vehicle_categories` — Hatchback, Sedan, Premium Sedan, Compact SUV, Full SUV, Luxury, Van, EV.
17. `vehicle_brands` — Maruti Suzuki, Hyundai, Mahindra, Tata, Toyota, BMW, Mercedes, etc.
18. `vehicle_models` — Swift, Dzire, Scorpio-N, Innova Crysta, Fortuner, Nexon EV, etc.
19. `vehicle_variants` — Specific trims, transmission (`manual`, `automatic`), fuel (`petrol`, `diesel`, `electric`, `cng`), seating capacity.
20. `vehicles` — Physical fleet records: Registration plate, VIN, engine number, current status (`active`, `reserved`, `rented`, `maintenance`, `decommissioned`), current branch, current odometer, fuel capacity.
21. `vehicle_documents` — RC, Fitness Certificate, State/All-India Permits, PUC (Pollution), Insurance Policy attachments and expiry.
22. `vehicle_photos` — Fleet gallery photos and initial inspection baseline photos.
23. `vehicle_status_history` — State change audit trail with timestamps and user references.
24. `vehicle_odometer_readings` — Time-series odometer logs linked to handovers, returns, and maintenance.
25. `pickup_locations` — Designated pickup points (Branch office, Airport Terminal 1/2, Railway station, Hotel).
26. `return_locations` — Designated return depots and drop-off hubs.

### 4. Driver Management
27. `drivers` — Driver roster, employee/contractor, license number, badge number, license expiry, status (`available`, `on_trip`, `leave`, `inactive`).
28. `driver_documents` — Commercial driving license, police verification certificate, medical fitness, Aadhaar.
29. `driver_shifts` — Shift rosters, working hours, weekly off schedule.
30. `driver_attendance` — Daily check-in/out, biometric or manual attendance.
31. `driver_assignments` — Assignment history mapping driver to bookings/vehicles.
32. `driver_trip_logs` — Chauffeur duty slips, start/end garage km and times, route logs.
33. `driver_allowances` — Day bata, night bata, outstation per diem, food/stay allowances.

### 5. Pricing & Rate Engine
34. `rental_types` — `self_drive`, `chauffeur_driven`, `airport_transfer`, `outstation`, `subscription`.
35. `rental_rate_plans` — Base pricing rules per vehicle category or model.
36. `rental_pricing_rules` — Hourly rate, daily 24h rate, weekly rate, monthly rate, excess km rate, excess hour rate.
37. `rental_packages` — Packaged deals: e.g. "8 Hours / 80 Km Local", "12 Hours / 120 Km", "Weekend Escape 300 Km".
38. `seasonal_rates` — Peak season / festival surcharges (percentage or flat adder).
39. `promotional_coupons` — Discount codes, validity, max discount, minimum booking spend.

### 6. Booking & Reservations
40. `bookings` — Master reservation: booking number, customer_id, rental_type_id, status (`draft`, `confirmed`, `allocated`, `active`, `completed`, `cancelled`), start datetime, scheduled return datetime, pickup/return location.
41. `booking_vehicles` — Assigned vehicle, assigned category, vehicle rate snapshot.
42. `booking_services` — Chauffeur service, child seat, GPS unit, additional driver add-ons.
43. `booking_status_history` — Comprehensive audit trail of status transitions.
44. `booking_pricing_snapshots` — Immutable JSON/column breakdown of base rental, taxes, discounts, estimated total.
45. `booking_cancellations` — Cancellation reason, cancelled by, cancellation fees applied, refund eligibility.
46. `booking_extensions` — Extension request, extra duration, extra amount, approval status.

### 7. Rental Operations & Handover / Return
47. `rental_agreements` — Contract number, terms version, digital signature customer, signature date, legal clauses.
48. `vehicle_handovers` — Handover dispatch: handover datetime, officer_id, customer_id, start odometer, start fuel level (1-8 scale), clean interior flag, clean exterior flag.
49. `vehicle_handover_checklists` — Itemized condition: spare wheel, jack, tools, documents in glovebox, scratch/dent markers.
50. `vehicle_returns` — Check-in intake: return datetime, receiving officer_id, return odometer, return fuel level, late hours, excess km, cleaning required flag.
51. `vehicle_return_checklists` — Inspection comparison against handover state.
52. `vehicle_damage_reports` — Reported damages, severity (`minor`, `moderate`, `major`), location on body, estimated repair cost.
53. `vehicle_damage_photos` — Photographic evidence tagged to damage report.
54. `damage_charges` — Final billed damage deductions charged to customer/deposit.
55. `trip_records` — Chauffeur trip telemetry: actual pickup time, destination, garage return time, customer approval signature.
56. `trip_expenses` — Toll receipts, parking fees, interstate border tax incurred during trip.
57. `rental_settlements` — Final settlement balancing total charges, deposits applied, and net balance due/refund.

### 8. Security Deposit & Financial Billing
58. `security_deposits` — Deposit record: deposit number, booking_id, customer_id, amount, status (`held`, `refunded`, `forfeited_partially`), mode (`card`, `upi`, `cash`, `bank`).
59. `deposit_transactions` — Collection, release, deduction adjustment logs.
60. `invoices` — GST compliant tax invoice: invoice number, invoice date, due date, customer GSTIN, place of supply, taxable amount, CGST, SGST, IGST, total.
61. `invoice_items` — Itemized rental charges, extra km, extra hours, night allowance, damages, fuel charges.
62. `payments` — Transaction receipt: payment number, invoice_id, amount, payment method, gateway transaction ID, reference number.
63. `payment_allocations` — Mapping payment to one or more invoices.
64. `refunds` — Refund voucher: original payment reference, refund amount, reason, approval, bank txn reference.

### 9. Maintenance, Accidents & Insurance
65. `vehicle_maintenance` — Preventive schedules (every 10,000 km or 6 months), oil change, brake inspection.
66. `maintenance_work_orders` — Service order, workshop/vendor, scheduled start, scheduled end, actual completion, total cost.
67. `maintenance_tasks` — Specific repair items per work order.
68. `vehicle_accidents` — Accident log, date/time, driver/customer involved, third-party details, police FIR number.
69. `insurance_policies` — Insurer name, policy number, IDV, premium, start date, expiry date, coverage type (Comprehensive, Zero Dep).
70. `insurance_claims` — Claim number, accident_id, surveyor name, estimated claim amount, approved amount, settlement date.

### 10. Corporate Management
71. `corporate_clients` — Company name, GSTIN, billing cycle (`monthly`, `bi-weekly`), credit limit, payment terms (Net 30).
72. `corporate_contracts` — Master Service Agreement, validity period, negotiated discount/tariff schedules.
73. `corporate_bookings` — Junction linking corporate client, employee booker, purchase order number.
74. `corporate_invoices` — Consolidated monthly statements aggregating multiple trips.

### 11. Accounting & Costing Ledgers
75. `chart_of_accounts` — Assets, Liabilities, Equity, Revenue, Expense standard accounts.
76. `journal_entries` — Balanced double-entry voucher header (Entry #, Date, Reference, Description).
77. `journal_entry_lines` — Individual debit/credit lines mapped to account_id and vehicle_id for cost tracing.
78. `vehicle_cost_ledgers` — Direct P&L by vehicle (Rental Revenue vs Fuel, Maintenance, EMI, Insurance, Depreciation).
79. `bank_accounts` — Company cash drawers, current accounts, gateway settlement accounts.

### 12. GPS & Telemetry
80. `vehicle_gps_devices` — IMEI, device model, SIM number, active status.
81. `vehicle_location_logs` — Latitude, longitude, speed, heading, timestamp, ignition status.
82. `gps_alerts` — Speed violation, geofence exit, panic button, device disconnect events.

