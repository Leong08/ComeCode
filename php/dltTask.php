<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}


if (isset($_GET['taskID']) && !empty($_GET['taskID'])) {
    $taskID = mysqli_real_escape_string($condb, $_GET['taskID']);


    $checkSql = "SELECT * FROM tasks WHERE task_id = '$taskID'";
    $checkResult = mysqli_query($condb, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {

        $sql = "DELETE FROM tasks WHERE task_id = '$taskID'";

        if (mysqli_query($condb, $sql)) {
            echo "<script>alert('Task deleted successfully!'); window.location.href = 'taskList.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($condb) . "'); window.location.href = 'taskList.php';</script>";
        }
    } else {

        echo "<script>alert('Task not found!'); window.location.href = 'taskList.php';</script>";
    }
} else {

    echo "<script>alert('No Task selected for deletion!'); window.location.href = 'taskList.php';</script>";
}
