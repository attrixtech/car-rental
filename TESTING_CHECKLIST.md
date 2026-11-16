# CAR RENTAL ERP — TESTING CHECKLIST & VERIFICATION MATRIX

## 1. Automated Test Execution
Run all tests via:
```bash
php artisan test
```

## 2. Verification Checklist

| Test Item | Verification Criteria | Status |
|---|---|---|
| **Turnaround Buffer** | 2-hour buffer between return and subsequent booking blocks double-allocation | **PASS** |
| **Overlapping Bookings** | Attempting to allocate an already committed vehicle fails validation | **PASS** |
| **Maintenance Lockout** | Vehicle placed in scheduled maintenance is hidden from booking calendar | **PASS** |
| **GST Calculation** | Intra-state splits into CGST 9% + SGST 9%; Inter-state applies IGST 18% | **PASS** |
| **Self-Drive Complete Workflow** | Booking -> Escrow Deposit -> Handover -> Return -> Deposit deduction -> Refund -> Tax invoice | **PASS** |
| **Chauffeur-Driven Workflow** | Booking -> Badge check -> Handover -> Trip record -> Bata allowance -> Settlement | **PASS** |
| **Security Deposit Accounting** | Deposits tracked under liability account 2001, never recognized as revenue | **PASS** |
| **Damage Recovery** | New damage discovered during return inspection adjusts against deposit | **PASS** |

