<!DOCTYPE html>
<html lang="en">

<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "teacher")) {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}


$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$userId = $_SESSION['userId'];



$moduleData = [];
$userRow = [];

if ($action === 'view') {
    // Fetch module data if action is 'view'
    $sql = "SELECT * FROM teachers WHERE userId = '$userId'";;
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $moduleData = mysqli_fetch_assoc($result);
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
    <title>Teacher</title>
    <link rel="stylesheet" href="/comeCode/css/stdTemp.css">
</head>

<body>
    <h2>Teacher</h2>
    <form id="teacher" action="<?php echo $action === 'add' ? 'addTchPro.php' : ''; ?>" method="post">

        <h3>Personal Details</h3>

        <div class=form-group>
            <label for="teacher_id">Teacher ID:</label>
            <input type="text" id="teacher_id" name="teacher_id" value="<?php echo $moduleData['teacher_id'] ?? ''; ?>" placeholder="Will be auto generate" readonly><br><br>
        </div>

        <div class=form-group>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" maxlength="50" value=" <?php echo $moduleData['first_name'] ?? ''; ?> " <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>

        <div class=form-group>
            <label for=" last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" maxlength="50" value="<?php echo $moduleData['last_name'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>


        <div class=form-group>
            <label for="phone_number">Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" maxlength="15" value="<?php echo $moduleData['phone_number'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>

        <div class=form-group>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" maxlength="100" value="<?php echo $moduleData['email'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>



        <h3>Account Details</h3>

        <div class=form-group>
            <label for="userId">User ID:</label>
            <input type="number" id="userId" name="userId" value="<?php echo $userRow['userId'] ?? ''; ?>" placeholder="Will be auto generate" readonly><br><br>
        </div>

        <div class=form-group>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" maxlength="50" value="<?php echo $userRow['username'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>

        <div class=form-group>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo $userRow['password'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>><br><br>
        </div>
        
        <br>
            <a href="javascript:history.back();" class="return-btn">Return</a>
    </form>
</body>

</html>