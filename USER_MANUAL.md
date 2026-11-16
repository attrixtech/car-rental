# CAR RENTAL ERP — OPERATOR USER MANUAL

## 1. Creating a Vehicle Reservation
1. Navigate to **Reservations & Operations -> New Reservation** (`/bookings/create`).
2. Choose an active customer or register a new customer with Driving License details.
3. Select Rental Mode:
   - **Self-Drive:** Requires Driving License verification and refundable escrow security deposit.
   - **Chauffeur-Driven:** Professional driver assigned with automated trip duty sheet.
4. Set Pickup & Return schedule. The live system immediately checks fleet availability and calculates estimated tariffs including GST.
5. Click **Confirm & Create Reservation**.

---

## 2. Dispatching a Vehicle (Handover Inspection)
1. Go to the Booking Details page (`/bookings/{id}`).
2. Ensure a physical vehicle is allocated.
3. Click **Initiate Handover →** (`/operations/handover/{id}`).
4. Record starting odometer and fuel gauge (in eighths of tank).
5. Verify checklist items: spare wheel, jack toolkit, registration & insurance folder.
6. Submit handover. The booking transitions to **ACTIVE** and vehicle status to **RENTED**.

---

## 3. Vehicle Return & Deposit Settlement
1. When the car returns, click **Vehicle Return & Inspection →** (`/operations/return/{id}`).
2. Record ending odometer and return fuel level.
3. If new scratches or dents are discovered, click **+ Add Damage Incident** and input the estimated repair cost.
4. The system automatically computes:
   - Excess kilometer charges (if distance exceeded allowance)
   - Fuel deficit surcharges (if tank level is below dispatch level)
   - Deep cleaning fee (if flagged)
   - New damage deductions
5. Click **Finalize Return & Generate Tax Invoice**.
6. The escrow security deposit is adjusted automatically, the refund balance is computed, the vehicle is released back to **AVAILABLE**, and a GST Tax Invoice is generated.

