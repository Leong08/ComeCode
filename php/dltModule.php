<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if (isset($_GET['moduleID']) && !empty($_GET['moduleID'])) {
    $moduleID = mysqli_real_escape_string($condb, $_GET['moduleID']);

 
    $checkSql = "SELECT * FROM modules WHERE module_id = '$moduleID'";
    $checkResult = mysqli_query($condb, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
      
        $sql = "DELETE FROM modules WHERE module_id = '$moduleID'";

        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Module deleted successfully!'); window.location.href = 'moduleList.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($condb) . "'); window.location.href = 'moduleList.php';</script>";
        }
    } else {
       
        echo "<script>alert('Module not found!'); window.location.href = 'moduleList.php';</script>";
    }
} else {
    
    echo "<script>alert('No module selected for deletion!'); window.location.href = 'moduleList.php';</script>";
}
