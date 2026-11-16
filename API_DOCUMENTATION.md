# CAR RENTAL ERP — REST & INTERNAL API SPECIFICATION

## 1. Authentication
Internal routes utilize Laravel Session Authentication. External or AJAX calls require the `X-CSRF-TOKEN` header.

---

## 2. Endpoints Catalog

### 2.1 Check Vehicle Availability
- **Method:** `POST`
- **URL:** `/api/check-availability`
- **Payload:**
  ```json
  {
    "category_id": 1,
    "pickup_datetime": "2026-11-20 10:00:00",
    "return_datetime": "2026-11-22 10:00:00",
    "branch_id": 1
  }
  ```
- **Response:**
  ```json
  {
    "count": 3,
    "vehicles": [
      {
        "id": 1,
        "registration_number": "MH 02 FB 4521",
        "fuel_type": "petrol",
        "transmission": "manual",
        "model": { "name": "Swift ZXi", "brand": { "name": "Maruti Suzuki" } }
      }
    ]
  }
  ```

### 2.2 Calculate Dynamic Pricing Estimate
- **Method:** `POST`
- **URL:** `/api/calculate-pricing`
- **Payload:**
  ```json
  {
    "category_id": 1,
    "rental_type_id": 1,
    "pickup_datetime": "2026-11-20 10:00:00",
    "return_datetime": "2026-11-22 10:00:00"
  }
  ```
- **Response:**
  ```json
  {
    "total_hours": 48,
    "total_days": 2,
    "free_km": 500,
    "base_rental_amount": 3600.00,
    "driver_allowance_amount": 0.00,
    "subtotal": 3600.00,
    "gst_rate_percent": 18.00,
    "cgst_amount": 324.00,
    "sgst_amount": 324.00,
    "total_tax": 648.00,
    "total_estimated_amount": 4248.00,
    "security_deposit_amount": 4000.00,
    "sac_code": "997311"
  }
  ```

