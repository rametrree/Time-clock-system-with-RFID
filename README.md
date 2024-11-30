# Time-clock-system-with-RFID
## Connect RFID-RC522 to ESP8266:
SDA (SS_PIN) -> D4
SCK -> D5
MOSI -> D7
MISO -> D6
RST -> D3
GND -> GND
3.3V -> 3.3V
Use 3.3V power supply for ESP8266 and RFID-RC522.

Here are the SQL commands you can use to create the `employees` and `attendance` tables in your `employee_system` database:

### Step 1: Create `employees` Table
This table stores employee information, including their unique ID, name, and RFID UID.

```sql
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Employee ID (auto-incremented)
    name VARCHAR(255) NOT NULL,                   -- Employee Name (must not be NULL)
    rfid_uid VARCHAR(50) NOT NULL UNIQUE          -- RFID UID (must be unique and not NULL)
);
```

### Step 2: Create `attendance` Table
This table stores attendance records for employees, including their check-in and check-out times and dates.

```sql
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Attendance record ID (auto-incremented)
    employee_name VARCHAR(255) NOT NULL,          -- Employee's name (must not be NULL)
    check_in_time TIME NOT NULL,                  -- Time the employee checked in (must not be NULL)
    check_in_date DATE NOT NULL,                  -- Date the employee checked in (must not be NULL)
    check_out_time TIME DEFAULT NULL,             -- Time the employee checked out (optional, defaults to NULL)
    check_out_date DATE DEFAULT NULL              -- Date the employee checked out (optional, defaults to NULL)
);
```

### Instructions:
1. Open your database management tool (like phpMyAdmin or any SQL client).
2. Select the `employee_system` database.
3. Run the SQL commands one by one in the SQL query section to create the tables.

- **`employees` Table** will store:
    - `id`: Unique identifier for each employee (auto-incremented).
    - `name`: Name of the employee.
    - `rfid_uid`: Unique identifier for the RFID card associated with the employee.

- **`attendance` Table** will store:
    - `id`: Unique identifier for each attendance record (auto-incremented).
    - `employee_name`: Name of the employee (could be referenced from the `employees` table).
    - `check_in_time`: Time when the employee checked in.
    - `check_in_date`: Date when the employee checked in.
    - `check_out_time`: Time when the employee checked out (optional).
    - `check_out_date`: Date when the employee checked out (optional).

