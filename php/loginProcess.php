<?php

include_once("connection.php");
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $pass = $_POST['password'];


    $q = "SELECT * FROM user 
    WHERE username = '$username' 
    AND 
    password = '$pass'";
    $result = mysqli_query($condb, $q);

    if (mysqli_num_rows($result) == 1) {
        $m = mysqli_fetch_array($result);

        $_SESSION['userId'] = $m['userId'];
        $userType = $m['userType'];
        $_SESSION['userType'] = $userType;
        $_SESSION['username'] = $username;

        if ($userType == "teacher") {
            echo "<script>alert('Welcome,{$username}!\\nYou will be redirected to the teacher page.');window.location.href='teacherPanel.php'</script>";
        } else if ($userType == "admin") {
            echo "<script>alert('Welcome,{$username}!\\nYou will be redirected to the admin page.');window.location.href='adminPanel.php'</script>";
        } else {
            echo "<script>alert('Welcome,{$username}!\\nYou will be redirected to the student page.');window.location.href='studentPanel.php'</script>";
        }
    } else {
        echo "<script>alert('Please enter your credentials correctly.')</script>";
        echo "<script>window.location.href='loginPanel.php'</script>";
    }
} else {
    echo "<script>alert('Please enter both username and password.')</script>";
    echo "<script>window.location.href='loginPanel.php'</script>";
}
