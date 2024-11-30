<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "employee_system";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Check if RFID UID is provided
    if (isset($_POST['rfid_uid'])) {
        $rfid_uid = $conn->real_escape_string($_POST['rfid_uid']);

        // Check if the RFID UID exists in the employees table
        $sql_check = "SELECT id, name, rfid_uid FROM employees WHERE rfid_uid='$rfid_uid'";
        $result = $conn->query($sql_check);

        if ($result && $result->num_rows > 0) {
            // Employee matched with the RFID UID
            $row = $result->fetch_assoc();
            $employee_name = $row['name'];
            $employee_id = $row['id'];

            // Check if the employee has already checked in today
            $sql_check_attendance = "SELECT * FROM attendance WHERE employee_id='$employee_id' AND check_out_time IS NULL AND check_in_date = CURDATE()";
            $attendance_result = $conn->query($sql_check_attendance);

            // Get the current time and check-in time limit (8:00 AM)
            $current_time = date("H:i:s");
            $check_in_time_limit = "08:00:00";

            if ($attendance_result && $attendance_result->num_rows > 0) {
                // Employee has already checked in, so we need to check them out
                $attendance = $attendance_result->fetch_assoc();
                $check_in_time = $attendance['check_in_time'];
                $check_in_hour = date("H:i:s", strtotime($check_in_time));
                $remarks = ""; // Remarks for check-in time

                // If check-in is after 8:00 AM, mark as "Late"
                if (strtotime($check_in_hour) > strtotime($check_in_time_limit)) {
                    $remarks = "Late";
                }

                // Perform check-out
                $sql_update = "UPDATE attendance SET check_out_time = NOW(), check_out_date = CURDATE(), remarks = '$remarks' WHERE employee_id='$employee_id' AND check_out_time IS NULL";

                if ($conn->query($sql_update) === TRUE) {
                    echo json_encode(["status" => "success", "message" => "Attendance Recorded: Check-Out"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error updating attendance: " . $conn->error]);
                }
            } else {
                // Employee has not checked in yet, so we need to check them in
                $remarks = "Absent"; // Absent if no check-in record exists
                $sql_insert = "INSERT INTO attendance (employee_id, employee_name, check_in_time, check_in_date, remarks) 
                               VALUES ('$employee_id', '$employee_name', NOW(), CURDATE(), '$remarks')";

                if ($conn->query($sql_insert) === TRUE) {
                    echo json_encode(["status" => "success", "message" => "Attendance Recorded: Check-In"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error inserting attendance: " . $conn->error]);
                }
            }

            // Automatic check-out after 4:00 PM
            $auto_checkout_time = "16:00:00";  // Define automatic check-out time
            if (strtotime($current_time) >= strtotime($auto_checkout_time)) {
                $sql_auto_checkout = "UPDATE attendance SET check_out_time = NOW(), check_out_date = CURDATE(), remarks = 'Automatic Check-Out' WHERE employee_id='$employee_id' AND check_out_time IS NULL AND check_in_date = CURDATE()";

                if ($conn->query($sql_auto_checkout) === TRUE) {
                    echo json_encode(["status" => "success", "message" => "Automatic Check-Out Recorded"]);
                }
            }

        } else {
            echo json_encode(["status" => "error", "message" => "RFID not found in employee database."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing RFID UID parameter."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request Method."]);
}

$conn->close();
?>
