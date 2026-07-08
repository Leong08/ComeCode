<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "admin") {
    echo "<script>alert('Please Login With Admin Account!!!'); window.location.href = 'adminLoginPanel.php';</script>";
    exit();
}
include_once("connection.php");
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/comeCode/css/adminReport.css">
</head>

<body>

    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="/comeCode/adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>

        <style>
            .download_button {
                display: block;
                width: 200px;
                margin: 20px auto;
                padding: 10px 20px;
                text-align: center;
                background-color: skyblue;
                color: white;
                border: none;
                border-radius: 5px;
                border: 2px solid white;
                cursor: pointer;
                text-decoration: none;
            }
        </style>
        
    </header>

    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">
            <h1> Student Information</h1>

            <div class="report-container">
                <div class="report-body">
                    <table class="report-table">
                        <tbody>
                            <?php
                            $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
                            if ($student_id) {
                                $query = "SELECT s.student_id, s.first_name, s.last_name, s.date_of_birth, s.gender, s.phone_number, s.email, s.tasks_done, s.points, u.userId, u.username, u.password 
                                          FROM students s 
                                          JOIN user u ON s.userId = u.userId 
                                          WHERE s.student_id = '$student_id'";

                                $result = mysqli_query($condb, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    echo "<tr><td>StudentID:</td><td>" . htmlspecialchars($row['student_id']) . "</td></tr>";
                                    echo "<tr><td>Full Name:</td><td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) . "</td></tr>";
                                    echo "<tr><td>Date of Birth:</td><td>" . htmlspecialchars($row['date_of_birth']) . "</td></tr>";
                                    echo "<tr><td>Gender:</td><td>" . htmlspecialchars($row['gender']) . "</td></tr>";
                                    echo "<tr><td>Phone Number:</td><td>" . htmlspecialchars($row['phone_number']) . "</td></tr>";
                                    echo "<tr><td>Email:</td><td>" . htmlspecialchars($row['email']) . "</td></tr>";
                                    echo "<tr><td>UserID:</td><td>" . htmlspecialchars($row['userId']) . "</td></tr>";
                                    echo "<tr><td>Username:</td><td>" . htmlspecialchars($row['username']) . "</td></tr>";
                                    echo "<tr><td>Password:</td><td>" . htmlspecialchars($row['password']) . "</td></tr>";
                                    echo "<tr><td>Task Done:</td><td>" . htmlspecialchars($row['tasks_done']) . "</td></tr>";
                                    echo "<tr><td>Points:</td><td>" . htmlspecialchars($row['points']) . "</td></tr>";
                                } else {
                                    echo "<tr><td colspan='2'>No data found.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No student ID provided.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($student_id) { ?> 
                <a href="generateStudentPDF.php?student_id=<?php echo $student_id; ?>" class="download_button">Download PDF</a> 
            <?php } ?>
        </div>
    </div>

</body>
<script>
    function showConfirmDialog(student_id) {
        if (confirm('Are you sure you want to generate this user?')) {
            window.location.href = '#' + encodeURIComponent(student_id) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>
