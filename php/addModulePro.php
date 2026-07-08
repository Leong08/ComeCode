<?php
include_once("connection.php");
session_start();


if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $module_id = mysqli_real_escape_string($condb, $_POST['module_id']);
    $module_name = mysqli_real_escape_string($condb, $_POST['module_name']);
    $course_code = mysqli_real_escape_string($condb, $_POST['courseSelect']);
    $moduleDescription = mysqli_real_escape_string($condb, $_POST['moduleDescription']);
    $url = mysqli_real_escape_string($condb, $_POST['url']);


    // Insert query for adding module
    $sql = "INSERT INTO modules (module_id, module_name, course_code, description, url_link)
            VALUES ('$module_id', '$module_name', '$course_code', '$moduleDescription', '$url')";

    // Execute the query
    if (mysqli_query($condb, $sql)) {
        echo "<script>alert('Module added successfully'); window.location.href = 'moduleList.php';</script>";
    } else {
        echo "<script>alert('Error adding module: " . mysqli_error($condb) . "'); window.location.href = 'moduleList.php';</script>";
    }
}
