<?php
// Include database connection
require_once 'db_connection.php';

// Function to generate unique booking ID
function generateBookingId() {
    return 'HC-' . mt_rand(100000, 999999);
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate unique booking ID
    $booking_id = generateBookingId();
    
    // Sanitize form data
    $patient_name = sanitizeInput($_POST['patient-name']); // Changed from first-name
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $dob = sanitizeInput($_POST['dob']);
    $gender = sanitizeInput($_POST['gender']);
    $address = sanitizeInput($_POST['address']);
    $appointment_date = sanitizeInput($_POST['appointment-date']);
    $appointment_time = sanitizeInput($_POST['appointment-time']);
    $department = sanitizeInput($_POST['department']);
    $doctor_code = isset($_POST['doctor']) ? sanitizeInput($_POST['doctor']) : null;
    $reason = sanitizeInput($_POST['reason']);
    $insurance = isset($_POST['insurance']) ? sanitizeInput($_POST['insurance']) : null;
    
    // Split patient name into first and last name
    $name_parts = explode(' ', $patient_name, 2);
    $first_name = $name_parts[0];
    $last_name = isset($name_parts[1]) ? $name_parts[1] : '';
    
    // First, check if patient exists
    $stmt = $conn->prepare("SELECT patient_id FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Patient exists, get their ID
        $row = $result->fetch_assoc();
        $patient_id = $row['patient_id'];
    } else {
        // Patient doesn't exist, create new patient
        $stmt = $conn->prepare("INSERT INTO patients (first_name, last_name, email, password, phone, address, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Generate a random password (in a real app, you'd send this to the user)
        $password = password_hash(mt_rand(100000, 999999), PASSWORD_DEFAULT);
        $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $password, $phone, $address, $dob, $gender);
        
        if ($stmt->execute()) {
            $patient_id = $conn->insert_id;
        } else {
            // Return error response as JSON
            $response = [
                'status' => 'error',
                'message' => 'Error creating patient record. Please try again.'
            ];
            echo json_encode($response);
            exit;
        }
    }
    
    // Map the doctor code to a numeric ID
    // This is a temporary solution - in a real system, you would have a proper mapping table
    $doctor_id = null;
    
    if ($doctor_code) {
        // Extract the numeric part from the doctor code (e.g., 'c1' -> 1)
        $numeric_part = intval(substr($doctor_code, 1));
        
        // Map department codes to numeric ranges
        $department_prefix = substr($doctor_code, 0, 1);
        $department_ranges = [
            'c' => [1, 10],    // Cardiology: 1-10
            'n' => [11, 20],   // Neurology: 11-20
            'o' => [21, 30],   // Orthopedics: 21-30
            'p' => [31, 40],   // Pediatrics: 31-40
            'g' => [41, 50],   // Gynecology: 41-50
            'd' => [51, 60],   // Dermatology: 51-60
            'op' => [61, 70],  // Ophthalmology: 61-70
            'e' => [71, 80],   // ENT: 71-80
            'de' => [81, 90],  // Dental: 81-90
            'gm' => [91, 100]    // General Medicine: 91-100
        ];
        
        // Find the correct range for this department
        foreach ($department_ranges as $prefix => $range) {
            if (strpos($doctor_code, $prefix) === 0) {
                // Calculate the doctor_id based on the range and position
                $position = $numeric_part - 1; // Convert to 0-based index
                $doctor_id = $range[0] + $position;
                break;
            }
        }
    }
    
    // Check if doctor_id is valid before creating appointment
    if ($doctor_id) {
        $stmt = $conn->prepare("SELECT doctor_id FROM doctors WHERE doctor_id = ?");
        $stmt->bind_param("i", $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            // Doctor not found, return error
            $response = [
                'status' => 'error',
                'message' => 'Selected doctor is not available. Please select another doctor.'
            ];
            echo json_encode($response);
            exit;
        }
    } else {
        // No valid doctor ID was generated, return error
        $response = [
            'status' => 'error',
            'message' => 'Invalid doctor selection. Please try again.'
        ];
        echo json_encode($response);
        exit;
    }
    
    // Now create the appointment
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status, patient_fname, patient_lname) VALUES (?, ?, ?, ?, ?, 'Scheduled', ?, ?)");
    $stmt->bind_param("iisssss", $patient_id, $doctor_id, $appointment_date, $appointment_time, $reason, $first_name, $last_name);
    
    if($stmt->execute()) {
        // Return success response as JSON
        $response = [
            'status' => 'success',
            'message' => 'Appointment booked successfully!',
            'booking_id' => $booking_id
        ];
        
        echo json_encode($response);
    } else {
        // Return error response as JSON
        $response = [
            'status' => 'error',
            'message' => 'Error booking appointment. Please try again.'
        ];
        
        echo json_encode($response);
    }
    
    $stmt->close();
} else {
    // If not a POST request, redirect to the form page
    header("Location: appointment.html");
    exit();
}
?>