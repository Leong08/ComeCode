<?php
include_once("connection.php");
session_start();

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Check if studentId is provided
if (isset($_GET['stdID'])) {
    $studentId = mysqli_real_escape_string($condb, $_GET['stdID']);

    // Fetch the associated userId of the student to delete the user
    $sql = "SELECT userId FROM students WHERE student_id = '$studentId'";
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['userId'];

        // Delete student record
        $deleteStudentSql = "DELETE FROM students WHERE student_id = '$studentId'";
        $deleteUserSql = "DELETE FROM user WHERE userId = '$userId'"; // Delete user login info

        // Execute the delete queries
        if (mysqli_query($condb, $deleteStudentSql) && mysqli_query($condb, $deleteUserSql)) {
            echo "<script>alert('Student and associated user deleted successfully!'); window.location.href = 'studentList.php';</script>";
        } else {
            echo "<script>alert('Error deleting student or user data.'); window.location.href = 'studentList.php';</script>";
        }
    } else {
        echo "<script>alert('Student not found.'); window.location.href = 'studentList.php';</script>";
    }
} else {
    echo "<script>alert('No student ID provided.'); window.location.href = 'studentList.php';</script>";
}
