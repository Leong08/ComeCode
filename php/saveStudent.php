<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $studentId = mysqli_real_escape_string($condb, $_POST['student_id']);
    $firstName = mysqli_real_escape_string($condb, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($condb, $_POST['last_name']);
    $dob = mysqli_real_escape_string($condb, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($condb, $_POST['gender']);
    $phoneNumber = mysqli_real_escape_string($condb, $_POST['phone_number']);
    $email = mysqli_real_escape_string($condb, $_POST['email']);
    $userId = mysqli_real_escape_string($condb, $_POST['userId']);
    $username = mysqli_real_escape_string($condb, $_POST['username']);
    $password = mysqli_real_escape_string($condb, $_POST['password']);

    // Update user credentials in the user table (no password hashing)
    if (!empty($password)) {
        $userSql = "UPDATE user 
                    SET username = '$username', 
                        password = '$password' 
                    WHERE userId = '$userId'";
    } else {
        $userSql = "UPDATE user 
                    SET username = '$username' 
                    WHERE userId = '$userId'";
    }

    // Execute the user update query
    if (mysqli_query($condb, $userSql)) {
        // Now update student profile in the students table
        $sql = "UPDATE students 
                SET first_name = '$firstName', 
                    last_name = '$lastName', 
                    date_of_birth = '$dob', 
                    gender = '$gender', 
                    phone_number = '$phoneNumber', 
                    email = '$email' 
                WHERE student_id = '$studentId'";

        // Execute the student update query
        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Student updated successfully!'); window.location.href = 'studentList.php';</script>";
        } else {
            echo "<script>alert('Error saving student information: " . mysqli_error($condb) . "'); window.location.href = 'studentList.php';</script>";
        }
    } else {
        echo "<script>alert('Error updating user information: " . mysqli_error($condb) . "'); window.location.href = 'studentList.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'studentList.php';</script>";
}
