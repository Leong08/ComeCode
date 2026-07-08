<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "admin") {
    echo "<script>alert('Please Login With admin Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
} else {
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
    <link rel="stylesheet" href="/comeCode/css/adminPanel.css">
    <?php include_once("connection.php"); ?>
</head>



<body>
    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>
    </header>

    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        Account
                    </h1>
                    <button class="view" onclick="window.location.href='dltStudent.php'">Delete</button>

                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>UserID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT userid, username ,userType FROM user WHERE userType != 'admin'";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['userType']) . "</td>";

                                    // Initialize teacherID and studentID
                                    $teacherID = null;
                                    $studentID = null;

                                    // If it's a teacher, fetch teacherID
                                    if ($row['userType'] == 'teacher') {
                                        $queryTeacher = "SELECT teacher_id FROM teachers WHERE userId = " . intval($row['userid']);
                                        $resultTeacher = mysqli_query($condb, $queryTeacher);
                                        if ($resultTeacher && mysqli_num_rows($resultTeacher) > 0) {
                                            $teacher = mysqli_fetch_assoc($resultTeacher);
                                            $teacherID = $teacher['teacher_id'];
                                        }
                                    }
                                    // If it's a student, fetch studentID
                                    elseif ($row['userType'] == 'student') {
                                        $queryStudent = "SELECT student_id FROM students WHERE userId = " . intval($row['userid']);
                                        $resultStudent = mysqli_query($condb, $queryStudent);
                                        if ($resultStudent && mysqli_num_rows($resultStudent) > 0) {
                                            $student = mysqli_fetch_assoc($resultStudent);
                                            $studentID = $student['student_id'];
                                        }
                                    }

                                    // Generate the correct link based on the userType
                                    echo "<td>
                                    <button class='bIcon view' onclick=\"window.location.href='" .
                                        ($row['userType'] == 'teacher' ?
                                            'teacherTemp.php?action=view&tchID=' . htmlspecialchars($teacherID) :
                                            'stdTemp.php?action=view&stdID=' . htmlspecialchars($studentID)
                                        ) . "'\">
                                            <ion-icon name='eye-outline'></ion-icon>
                                        </button>
                                        <button class='bIcon dlt' onclick='showConfirmDialog(\"" . htmlspecialchars($row['userid']) . "\", \"" . htmlspecialchars($row['userType']) . "\")'>
                                            <ion-icon name='trash-outline'></ion-icon>
                                        </button>
                                        </td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No data found.</td></tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script>
    function showConfirmDialog(userid, userType) {
        if (confirm('Are you sure you want to delete this user?')) {
            window.location.href = 'adminDltPro.php?userID=' + encodeURIComponent(userid) + '&role=' + encodeURIComponent(userType) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>