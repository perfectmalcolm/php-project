<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullName = trim($_POST['full_name']);
    $age = $_POST['age'];
    $email = trim($_POST['email']);

    // Initialize profile picture
    $profilePicture = null;

    // Input validation
    $errors = [];

    // Validate Full Name
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    }

    // Validate Age
    if (empty($age) || !is_numeric($age) || $age <= 0) {
        $errors[] = "Age must be a positive number.";
    }

    // Validate Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
        }

        // File validation
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Invalid file type. Only JPG, JPEG, PNG, and GIF images are allowed.";
        } else {
            // Generate a unique file name to avoid conflicts
            $uniqueFileName = uniqid() . '.' . $fileExtension;
            $profilePicture = $targetDir . $uniqueFileName;

            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicture)) {
                $errors[] = "Error uploading the file. Please try again.";
            }
        }
    }

    // Check for validation errors
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // Prepare and execute the SQL query
        $sql = "INSERT INTO Students (Full_Name, Age, Email, Profile_Picture) VALUES (?, ?, ?, ?)";
        $params = [$fullName, $age, $email, $profilePicture];

        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            echo "<p style='color:red;'>Error: " . print_r(sqlsrv_errors(), true) . "</p>";
        } else {
            echo "<p style='color:green;'>Student registered successfully!</p>";
            sqlsrv_free_stmt($stmt);
        }
    }

    // Close the database connection
    sqlsrv_close($conn);
}
?>
