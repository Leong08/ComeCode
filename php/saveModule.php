<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $moduleID = mysqli_real_escape_string($condb, $_POST['module_id']);
    $moduleName = mysqli_real_escape_string($condb, $_POST['module_name']);
    $courseCode = mysqli_real_escape_string($condb, $_POST['courseSelect']);
    $moduleDescription = mysqli_real_escape_string($condb, $_POST['moduleDescription']);
    $urlLink = mysqli_real_escape_string($condb, $_POST['url']);



    $sql = "UPDATE modules 
            SET module_name = '$moduleName', 
                course_code = '$courseCode', 
                description = '$moduleDescription', 
                url_link = '$urlLink'
            WHERE module_id = '$moduleID'";

    if (mysqli_query($condb, $sql)) {
        echo "<script>alert('Module updated successfully!'); window.location.href = 'moduleList.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($condb) . "');</script>";
    }
}
