<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if (isset($_GET['course']) && !empty($_GET['course'])) {
    $courseCode = mysqli_real_escape_string($condb, $_GET['course']);

 
    $checkSql = "SELECT * FROM courses WHERE course_code = '$courseCode'";
    $checkResult = mysqli_query($condb, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
      
        $sql = "DELETE FROM courses WHERE course_code = '$courseCode'";

        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Course deleted successfully!'); window.location.href = 'courseList.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($condb) . "'); window.location.href = 'courseList.php';</script>";
        }
    } else {
       
        echo "<script>alert('Course not found!'); window.location.href = 'courseList.php';</script>";
    }
} else {
    
    echo "<script>alert('No Course selected for deletion!'); window.location.href = 'courseList.php';</script>";
}
