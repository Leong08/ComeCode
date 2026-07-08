<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "admin") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $firstName = mysqli_real_escape_string($condb, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($condb, $_POST['last_name']);
    $phoneNumber = mysqli_real_escape_string($condb, $_POST['phone_number']);
    $email = mysqli_real_escape_string($condb, $_POST['email']);
    $username = mysqli_real_escape_string($condb, $_POST['username']);
    $password = mysqli_real_escape_string($condb, $_POST['password']);

    // Insert into users table to create the login credentials (userId will be auto-generated)
    $userSql = "INSERT INTO user (username, password, userType) 
                VALUES ('$username', '$password', 'teacher')";

    if (mysqli_query($condb, $userSql)) {
        // Get the last inserted userId (auto-incremented)
        $userId = mysqli_insert_id($condb);

        // Now insert the student's information into the teacher table
        $sql = "INSERT INTO teachers (first_name, last_name, phone_number, email, userId) 
                VALUES ('$firstName', '$lastName', '$phoneNumber', '$email', '$userId')";

        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Teacher added successfully!'); window.location.href = 'adminCreate.php';</script>";
        } else {
            echo "<script>alert('Error adding teacher: " . mysqli_error($condb) . "'); window.location.href = 'adminCreate.php';</script>";
        }
    } else {
        echo "<script>alert('Error creating user: " . mysqli_error($condb) . "'); window.location.href = 'adminCreate.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'adminCreare.php';</script>";
}
