<?php
// Include database connection
require_once 'db_connection.php';

// Function to generate unique application ID
function generateApplicationId() {
    return 'FC-' . mt_rand(100000, 999999);
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
    // Debug $_POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Generate unique application ID
    $application_id = generateApplicationId();

    // Sanitize form data
    $full_name = sanitizeInput($_POST['full-name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $pincode = sanitizeInput($_POST['pincode']);
    $dob = sanitizeInput($_POST['dob']);
    $gender = sanitizeInput($_POST['gender']);
    $card_type = sanitizeInput($_POST['card-type']);
    $family_members = (int)sanitizeInput($_POST['family-members']);

    // File handling
    $upload_dir = "uploads/family_card/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Process ID proof file
    $id_proof_file = "";
    if (isset($_FILES['id-proof']) && $_FILES['id-proof']['error'] == 0) {
        $id_proof_name = $application_id . "_id_" . basename($_FILES['id-proof']['name']);
        $id_proof_path = $upload_dir . $id_proof_name;
        if (!move_uploaded_file($_FILES['id-proof']['tmp_name'], $id_proof_path)) {
            die("Error uploading ID proof.");
        }
        $id_proof_file = $id_proof_path;
    }

    // Prepare and execute SQL statement for main application
    $stmt = $conn->prepare("INSERT INTO family_card_applications (application_id, full_name, email, phone, address, city, state, pincode, dob, gender, card_type, family_members, id_proof_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssi", $application_id, $full_name, $email, $phone, $address, $city, $state, $pincode, $dob, $gender, $card_type, $family_members, $id_proof_file);

    if (!$stmt->execute()) {
        die("Error executing query: " . $stmt->error);
    }
    $stmt->close();

    echo "Application submitted successfully!";
} else {
    echo "Invalid request method.";
}