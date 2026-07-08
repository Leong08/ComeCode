<?php
include_once("connection.php");
session_start();

function handleFatalError($buffer) {
    if (strpos($buffer, 'Fatal error') !== false) {
        echo "<script>alert('Please make sure your email address is correct'); window.location.href = 'stdTemp.php?action=add';</script>";
        return ' ';
    }
    return $buffer;
}

ob_start("handleFatalError");

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "teacher" && $_SESSION['userType'] != "admin")) {
    http_response_code(403);
    include("403.html");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $firstName = mysqli_real_escape_string($condb, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($condb, $_POST['last_name']);
    $dob = mysqli_real_escape_string($condb, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($condb, $_POST['gender']);
    $phoneNumber = mysqli_real_escape_string($condb, $_POST['phone_number']);
    $email = mysqli_real_escape_string($condb, $_POST['email']);
    $username = mysqli_real_escape_string($condb, $_POST['username']);
    $password = mysqli_real_escape_string($condb, $_POST['password']);

    

    try {
        $checkEmailQuery = "SELECT * FROM students WHERE email = '$email'";
        $checkEmailResult = mysqli_query($condb, $checkEmailQuery);

        if (mysqli_num_rows($checkEmailResult) > 0) {
            echo "<script>alert('Email address already be registered. Please use different email address.'); window.location.href = 'stdTemp.php?action=add';</script>";
        } else {

            // Insert into users table to create the login credentials (userId will be auto-generated)
            $userSql = "INSERT INTO user (username, password, userType) 
            VALUES ('$username', '$password', 'student')";
            
            if (mysqli_query($condb, $userSql)) {
                // Get the last inserted userId (auto-incremented)
                $userId = mysqli_insert_id($condb);
        
                // Now insert the student's information into the students table
                $sql = "INSERT INTO students (first_name, last_name, date_of_birth, gender, phone_number, email, userId) 
                        VALUES ('$firstName', '$lastName', '$dob', '$gender', '$phoneNumber', '$email', '$userId')";
        
                if (mysqli_query($condb, $sql)) {
                    if ($_SESSION['userType'] === "teacher") {
                        echo "<script>alert('Student added successfully!'); window.location.href = 'studentList.php';</script>";
                    } else {
                        echo "<script>alert('Student added successfully!'); window.location.href = 'adminCreate.php';</script>";
                    }
                } else {
                    if ($_SESSION['userType'] === "teacher") {
                        echo "<script>alert('Error adding student: " . mysqli_error($condb) . "'); window.location.href = 'studentList.php';</script>";
                    } else {
                        echo "<script>alert('Error adding student: " . mysqli_error($condb) . "'); window.location.href = 'adminCreate.php';</script>";
                    }
                }
            } else {
                if ($_SESSION['userType'] === "teacher") {
                    echo "<script>alert('Error creating user: " . mysqli_error($condb) . "'); window.location.href = 'studentList.php';</script>";
                } else {
                    echo "<script>alert('Error creating user: " . mysqli_error($condb) . "'); window.location.href = 'adminCreate.php';</script>";
                }
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('123'); window.location.href = 'stdTemp.php?action=add';</script>";
    }    

} else {
    if ($_SESSION['userType'] === "teacher") {
        echo "<script>alert('Invalid request.'); window.location.href = 'studentList.php';</script>";
    } else {
        echo "<script>alert('Invalid request.'); window.location.href = 'adminCreare.php';</script>";
    }
}
