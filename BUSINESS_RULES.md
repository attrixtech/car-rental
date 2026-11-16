# CAR RENTAL ERP — BUSINESS RULES & LOGICAL SPECIFICATIONS

This document codifies the mandatory business logic, mathematical algorithms, and accounting treatments enforced across the platform.

---

## 1. Booking Lifecycle State Machine

The booking entity operates through a strictly enforced state machine:

```
[Inquiry / Draft] ──> [Pending Approval] ──> [Confirmed / Reserved] ──> [Allocated / Assigned]
                                                      │                            │
                                                      v                            v
                                            [Cancelled / Expired]        [Ready for Handover]
                                                                                   │
                                                                                   v
[Completed / Closed] <── [Settled] <── [Returned / In-Inspection] <── [Active / On-Trip]
```

### Transition Guards:
1. **Draft -> Confirmed:** Requires customer KYC status approved or waiver, vehicle availability verified for duration + turnover buffer, pricing snapshot generated.
2. **Confirmed -> Allocated:** Vehicle assigned. If chauffeur-driven, verified driver assigned.
3. **Allocated -> Active (On-Trip):** For Self-Drive: Security deposit collected AND signed rental agreement AND completed Handover Checklist with odometer/fuel/photos. For Chauffeur: Duty slip generated and trip started.
4. **Active -> Returned:** Vehicle physical return recorded with ending odometer, ending fuel level, and check-in damage inspection.
5. **Returned -> Settled:** All extra km, extra hours, fuel penalty, damage repairs, and toll/parking charges calculated; invoice finalized and balanced against payments/deposit.
6. **Settled -> Closed:** Security deposit balance refunded or zeroed, double-entry journal balanced, booking marked closed.

---

## 2. Vehicle Availability & Conflict Prevention Logic

### 2.1 The Availability Formula
A vehicle `V` is available for requested window `[ReqStart, ReqEnd]` if and only if:
1. `V.status` is `'active'` (NOT in `'maintenance'`, `'in_repair'`, `'impounded'`, or `'decommissioned'`).
2. There exists **NO** overlapping booking `B` where:
   - `B.vehicle_id = V.id`
   - `B.status IN ('confirmed', 'allocated', 'active', 'on_trip')`
   - Overlap Condition:
     $$\max(ReqStart, B.start\_datetime) < \min(ReqEnd + BufferTime, B.end\_datetime + BufferTime)$$
   *(where `BufferTime` defaults to 2 hours for inspection, cleaning, and turnaround).*
3. There exists **NO** scheduled maintenance `M` where:
   - `M.vehicle_id = V.id`
   - `M.status IN ('scheduled', 'in_progress')`
   - Overlap Condition:
     $$\max(ReqStart, M.start\_datetime) < \min(ReqEnd, M.end\_datetime)$$

### 2.2 Concurrency & Race Condition Guard
All allocation queries execute inside a database transaction with `SELECT ... FOR UPDATE` row-level locks on the vehicle record to prevent double allocation under concurrent requests.

---

## 3. Rental Pricing Engine Logic

### 3.1 Rental Models & Unit Calculation
- **Hourly Rental:** Billed per hour with minimum billable hours (e.g. 4 hrs). Overstay billed at `extra_hour_rate`.
- **Daily Rental (24h Window):** Calculated on 24-hour cycles from start time. Grace period (e.g. 60 minutes) allowed before triggering extra hour or full day charge.
- **Weekly / Monthly / Subscription:** Discounted slab rate with fixed monthly billing cycle.
- **Chauffeur Packages:**
  - *Local:* 4h/40km, 8h/80km, 12h/120km base rate + extra km rate + extra hour rate.
  - *Outstation:* Minimum 250 km/day (or 300 km/day) charging rule. Driver Night Bata applicable if trip spans 22:00 to 06:00.

### 3.2 Dynamic Pricing & Surcharges
$$\text{Base Total} = (\text{Duration Units} \times \text{Unit Rate}) \times (1 + \text{Seasonal Surcharge \%}) + \text{Weekend Surcharge}$$

### 3.3 Indian GST Rules
- **Self-Drive (Without Operator - SAC 997311):** 18% GST (9% CGST + 9% SGST for intra-state; 18% IGST for inter-state).
- **Chauffeur-Driven (With Operator - SAC 996601):** 5% GST (without ITC) or 12% GST (with ITC) as configured in company tax profile.

---

## 4. Vehicle Handover & Return Inspection Logic

### 4.1 Handover (Check-Out) Rules
- Mandatory recording of:
  - Starting Odometer ($Km_{start}$)
  - Starting Fuel Level ($Fuel_{start}$ in 1/8 increments or % of tank)
  - Vehicle Condition Checklist (Spare tyre, jack, toolkit, RC copy, Insurance copy)
  - Existing damage markers (Front, Rear, Left, Right, Roof, Glass) with uploaded photos
  - Digital signature of customer and handover officer

### 4.2 Return (Check-In) Rules
- Recorded upon arrival:
  - Ending Odometer ($Km_{end}$)
  - Ending Fuel Level ($Fuel_{end}$)
  - Return Datetime ($T_{actual\_return}$)
- **Automated Charge Calculations:**
  1. **Kilometer Excess:**
     $$\Delta Km = Km_{end} - Km_{start}$$
     $$Km_{excess} = \max(0, \Delta Km - Km_{allowed})$$
     $$\text{Charge}_{km} = Km_{excess} \times \text{Rate}_{excess\_km}$$
  2. **Fuel Deficit:**
     $$\text{Fuel Shortfall \%} = \max(0, Fuel_{start} - Fuel_{end})$$
     $$\text{Charge}_{fuel} = (\text{Fuel Shortfall Litres} \times \text{Fuel Price per Litre}) + \text{Refueling Service Fee}$$
  3. **Late Return Penalty:**
     $$\text{Grace Period} = 60 \text{ minutes}$$
     $$\text{Late Hours} = \max\left(0, \left\lceil \frac{T_{actual\_return} - (T_{scheduled\_return} + \text{Grace})}{3600} \right\rceil\right)$$
     $$\text{Charge}_{late} = \text{Late Hours} \times \text{Rate}_{extra\_hour}$$
  4. **New Damage Assessment:**
     Checked against initial checklist. Any new scratch/dent logged with repair estimate for deposit deduction.

---

## 5. Security Deposit Accounting & Settlement

Security deposits represent escrow liabilities held on behalf of the customer, strictly segregated from rental sales revenue.

### 5.1 Deposit Lifecycle
1. **Collection:** Upon booking confirmation / handover:
   - Customer deposits ₹5,000 to ₹25,000 (cash, card, bank transfer, UPI).
   - Deposit status: `held`.
2. **Post-Return Settlement:**
   - Total Deductions = New Damage Charges + Excess Km Charges + Fuel Shortfall + Unpaid Tolls/Fines.
   - If Deductions $\le$ Deposit:
     $$\text{Refund Amount} = \text{Deposit} - \text{Total Deductions}$$
     $$\text{Deposit Status} = \text{'refunded'}$$
   - If Deductions $>$ Deposit:
     $$\text{Shortfall Payable} = \text{Total Deductions} - \text{Deposit}$$
     $$\text{Deposit Status} = \text{'forfeited_partially_and_billed'}$$
3. **Refund Processing:** Refund transaction recorded with original payment reference ID, mode of refund, and bank transaction ID.

---

## 6. Financial Transaction Flow & Double-Entry Ledger

Every economic event triggers self-balancing double-entry journal entries:

### Scenario A: Security Deposit Collected (₹10,000)
- **Debit:** Cash / Bank Account (Asset) : ₹10,000
- **Credit:** Customer Security Deposit Payable (Liability - Escrow) : ₹10,000

### Scenario B: Rental Invoice Generated (₹6,000 + 18% GST = ₹7,080)
- **Debit:** Accounts Receivable / Customer Ledger (Asset) : ₹7,080
- **Credit:** Vehicle Rental Income (Revenue) : ₹6,000
- **Credit:** CGST Output Payable (Liability) : ₹540
- **Credit:** SGST Output Payable (Liability) : ₹540

### Scenario C: Rental Payment Received (₹7,080)
- **Debit:** Bank / Cash / Razorpay (Asset) : ₹7,080
- **Credit:** Accounts Receivable (Asset) : ₹7,080

### Scenario D: Return Settlement with Deductions from Deposit
*(Damage ₹2,000 + Fuel ₹1,000 = ₹3,000 deducted; ₹7,000 refunded)*
- **Debit:** Customer Security Deposit Payable (Liability) : ₹10,000
- **Credit:** Damage Recovery Income (Revenue) : ₹2,000
- **Credit:** Fuel Reimbursement Income (Revenue) : ₹1,000
- **Credit:** Bank / Cash Account (Asset - Refund Issued) : ₹7,000

