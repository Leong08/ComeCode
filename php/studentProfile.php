<!DOCTYPE html>
<html lang="en">

<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "student")) {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

$userId = $_SESSION['userId'];

$studentData = [];
$userRow = [];

if ($userId) {
    // Fetch student data based on userId from session
    $sql = "SELECT * FROM students WHERE userId = '$userId'";
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $studentData = mysqli_fetch_assoc($result);
    }

    $userSql = "SELECT * FROM user WHERE userId = '$userId'";
    $userResult = mysqli_query($condb, $userSql);
    
    if ($userResult && mysqli_num_rows($userResult) > 0) { 
        $userRow = mysqli_fetch_assoc($userResult); 
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="/comeCode/css/stdTemp.css">
</head>

<body>
    <h2>Student Profile</h2>
    <h2>Personal Details</h2>
    <form id="student" action="" method="post">

        <div class="form-group">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" value="<?php echo $studentData['student_id'] ?? ''; ?>" placeholder="Will be auto generate" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" maxlength="50" value="<?php echo $studentData['first_name'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" maxlength="50" value="<?php echo $studentData['last_name'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo $studentData['date_of_birth'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" value="<?php echo $studentData['gender'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" maxlength="15" value="<?php echo $studentData['phone_number'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" maxlength="100" value="<?php echo $studentData['email'] ?? ''; ?>" readonly><br><br>
        </div>

        <h3>Login Information</h3>

        <div class="form-group">
            <label for="userId">User ID:</label>
            <input type="number" id="userId" name="userId" value="<?php echo $userRow['userId'] ?? ''; ?>" placeholder="Will be auto generate" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" maxlength="50" value="<?php echo $userRow['username'] ?? ''; ?>" readonly><br><br>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $userRow['password'] ?? ''; ?>" readonly><br><br>
        </div>
        
        <br>
        <a href="javascript:history.back();" class="return-btn">Return</a>
    </form>
</body>

</html>
