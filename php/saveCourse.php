<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


$course = $_GET['course'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseCode = mysqli_real_escape_string($condb, $_POST['courseCode']);
    $courseTitle = mysqli_real_escape_string($condb, $_POST['CourseName']);
    $description = mysqli_real_escape_string($condb, $_POST['CourseDescription']);

    $sql = "SELECT course_code FROM courses WHERE course_code = '$courseCode'";

    if ($result = mysqli_query($condb, $sql)) {
        $numRow = mysqli_num_rows($result);

        if ($numRow = 0 || $courseCode === $course) {
            $sql = "UPDATE courses 
            SET  course_code = '$courseCode',
                course_title = '$courseTitle', 
                course_description = '$description'
            WHERE course_code = '$course'";

            if (mysqli_query($condb, $sql)) {
                echo "<script>alert('Course updated successfully!'); window.location.href = 'courseList.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($condb) . "');</script>";
            }
        }
        
    } else {
        echo "<script>alert('Error: " . mysqli_error($condb) . "');</script>";
    }
}
