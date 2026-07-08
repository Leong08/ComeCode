<!DOCTYPE html>
<html lang="en">

<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "teacher" && $_SESSION['userType'] != "admin")) {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

// Get the action from the URL (default to 'view' if not provided)
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$teacher_id = isset($_GET['tchID']) ? $_GET['tchID'] : '';
$userId = '';


$moduleData = [];
if ($action === 'view' || $action === 'edit') {
    // Fetch module data if action is 'view' or 'edit'
    $sql = "SELECT * FROM teachers WHERE teacher_id = '$teacher_id'";;
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $moduleData = mysqli_fetch_assoc($result);
        $userId = $moduleData['userId'];
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



        <h3>Login Information</h3>
        <p>Please use the fields below for login purposes.</p>

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

        <?php if ($action !== 'view') { ?>
            <button class="submit-button" type="submit"><?php echo $action === 'edit' ? 'Save Changes' : 'Submit'; ?></button>
        <?php } ?>
        <br>
        <?php if ($_SESSION['userType'] === "teacher") { ?>
            <a href="studentList.php" class="return-btn">Return</a>
        <?php } else { ?>
            <a href="javascript:history.back();" class="return-btn">Return</a>
        <?php } ?>
    </form>
    <script>
        document.getElementById("teacher").addEventListener("submit", function(e) {
            e.preventDefault();

            let firstName = document.getElementById('first_name').value.trim();
            let lastName = document.getElementById('last_name').value.trim();
            let phoneNumber = document.getElementById('phone_number').value.trim();
            let email = document.getElementById('email').value.trim();
            let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            let phoneRegex = /^[\+0-9]{1,4}[ -]?[0-9]{1,4}[ -]?[0-9]{1,4}[ -]?[0-9]{1,4}$/;
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();


            if (firstName.length === 0) {
                alert('First Name is required');
                return;
            }
            if (lastName.length === 0) {
                alert('Last Name is required');
                return;
            }
            if (phoneNumber.length === 0) {
                alert('Phone Number is required');
                return;
            } else if (!phoneRegex.test(phoneNumber)) {
                alert('Please enter a valid phone number.\nValid formats: \n+123 456 7890\n123-456-7890\n123 456 7890\n1234567890');
                return;
            }
            if (email.length === 0) {
                alert('Email is required');
                return;
            } else if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            if (username.length === 0) {
                alert('Username is required');
                return;
            }
            if (password.length === 0) {
                alert('Password is required');
                return;
            }

            this.submit();
        })
    </script>
</body>

</html>