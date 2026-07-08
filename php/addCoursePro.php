<?php
include_once("connection.php");
session_start();


if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $courseCode = mysqli_real_escape_string($condb, $_POST['courseCode']);
    $courseTitle = mysqli_real_escape_string($condb, $_POST['CourseName']);
    $description = mysqli_real_escape_string($condb, $_POST['CourseDescription']);

    $search = "SELECT * FROM courses WHERE course_code = '$courseCode'";
    $result = mysqli_query($condb, $search);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This course code already exist.')</script>";
       echo "<script>history.back()</script>";
    }


    // Insert query for adding module
    $sql = "INSERT INTO courses 
            VALUES ('$courseCode', '$courseTitle', '$description')";

    // Execute the query
    if (mysqli_query($condb, $sql)) {
        echo "<script>alert('Course added successfully'); window.location.href = 'courseList.php';</script>";
    } else {
        echo "<script>alert('Error adding module: " . mysqli_error($condb) . "'); window.location.href = 'courseList.php';</script>";
    }
}
