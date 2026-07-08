<?php
include_once("connection.php");
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "admin") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Check if studentId is provided
if (isset($_GET['userID']) && isset($_GET['role'])) {
    $role = mysqli_real_escape_string($condb, $_GET['role']);
    $userID = mysqli_real_escape_string($condb, $_GET['userID']);



    if ($role === "student") {
        $deleteStudentSql = "DELETE FROM students WHERE userId = '$userID'";
    } else if ($role === "teacher") {
        $deleteStudentSql = "DELETE FROM teachers WHERE userId = '$userID'";
    }

    $deleteUserSql = "DELETE FROM user WHERE userId = '$userID'"; // Delete user login info

    // Execute the delete queries
    if (mysqli_query($condb, $deleteStudentSql) && mysqli_query($condb, $deleteUserSql)) {
        echo "<script>alert('Student and associated user deleted successfully!'); window.location.href = 'adminDelete.php';</script>";
    } else {
        echo "<script>alert('Error deleting student or user data.'); window.location.href = 'adminDelete.php';</script>";
    }
} else {
    echo "<script>alert('No student ID provided.'); window.location.href = 'adminDelete.php';</script>";
}
