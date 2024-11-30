# Time Clock System with RFID

This project allows you to track employee attendance using RFID cards. The system consists of an ESP8266 microcontroller connected to an RFID-RC522 module and a MySQL database for storing employee and attendance data.

## Components
1. **ESP8266** (Microcontroller)
2. **RFID-RC522** (RFID Reader Module)

## Hardware Connections

Connect the RFID-RC522 to the ESP8266 as follows:
- **SDA (SS_PIN)** -> D4
- **SCK** -> D5
- **MOSI** -> D7
- **MISO** -> D6
- **RST** -> D3
- **GND** -> GND
- **3.3V** -> 3.3V

Make sure to use a **3.3V power supply** for both the ESP8266 and RFID-RC522 module.

## Setting Up the Database

### 1. Install XAMPP
Download and install [XAMPP](https://www.apachefriends.org/) to run a local server on your PC.

### 2. Start XAMPP
- Open **XAMPP Control Panel**.
- Start **Apache** and **MySQL**.

### 3. Create Database
1. Open [phpMyAdmin](http://localhost/phpmyadmin) in your browser.
2. Click on **New** (top left).
3. Enter `employee_system` as the database name.
4. Choose **utf8_general_ci** as the collation format.
5. Click **Create**.

### 4. Create Tables

After creating the database, you need to create two tables: `employees` and `attendance`.

#### **1. Create `employees` Table**
This table stores employee details such as name and RFID UID.

```sql
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Employee ID (auto-incremented)
    name VARCHAR(255) NOT NULL,                   -- Employee Name (required)
    rfid_uid VARCHAR(50) NOT NULL UNIQUE          -- RFID UID (must be unique)
);
```

#### **2. Create `attendance` Table**
This table stores the attendance records for employees, including check-in and check-out times.

```sql
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,           -- Attendance record ID (auto-incremented)
    employee_name VARCHAR(255) NOT NULL,          -- Employee's name
    check_in_time TIME NOT NULL,                  -- Check-in time (required)
    check_in_date DATE NOT NULL,                  -- Check-in date (required)
    check_out_time TIME DEFAULT NULL,             -- Check-out time (optional)
    check_out_date DATE DEFAULT NULL              -- Check-out date (optional)
);
```

### 5. Running SQL Queries
1. Open **phpMyAdmin** and select your `employee_system` database.
2. Go to the **SQL** tab.
3. Paste the SQL code for creating the tables and click **Go**.

### What These Tables Do:
- **`employees` Table**: Stores information about each employee, including:
  - `id`: Unique identifier (auto-incremented).
  - `name`: The employee’s name.
  - `rfid_uid`: The unique ID from the RFID card.
  
- **`attendance` Table**: Records attendance data for employees:
  - `id`: Unique record identifier (auto-incremented).
  - `employee_name`: Employee’s name (linked to the `employees` table).
  - `check_in_time`: The time the employee checked in.
  - `check_in_date`: The date of check-in.
  - `check_out_time`: The time the employee checked out (optional).
  - `check_out_date`: The date the employee checked out (optional).

---

Now you can use your RFID system to track employee attendance and store data in the database!
