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
            <div class="logo"><a class="logo" href="/comeCode/php/adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>
    </header>

    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">
            <h1> Student Information</h1>

            <div class="box-container">
                <form method="GET" action="adminGenerateStudentReport.php">
                    <input class="uname" type="text" name="uname" placeholder="Student Name" value="<?php echo (isset($_GET['uname']) ? $_GET['uname'] : ''); ?>" required>
                    <button class="search-button">Search</button>
                </form>
            </div>

            <div class="report-container">
                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>StudentID</th>
                                <th>Full Name</th>
                                <th>Task done</th>
                                <th>Points</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $uname = isset($_GET['uname']) ? $_GET['uname'] : '';
                            $query = "SELECT student_id, first_name, last_name, tasks_done, points 
                                        FROM students 
                                        WHERE first_name LIKE '%$uname%' OR 
                                        last_name LIKE '%$uname%' OR 
                                        CONCAT(first_name, ' ', last_name) LIKE '%$uname%'";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) .  "</td>";
                                    echo "<td>" . htmlspecialchars($row['tasks_done']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['points']) . "</td>";
                                    echo "<td>
                                           <button class='view' onclick=\"window.location.href='generateStudentReport.php?student_id=" . htmlspecialchars($row['student_id']) . "'\">Generate</button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
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