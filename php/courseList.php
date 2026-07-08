<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
} else {
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
    <link rel="stylesheet" href="/comeCode/css/teacherPanel.css">
    <?php include_once("connection.php"); ?>
</head>



<body>
    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="teacherPanel.php">TEACHER PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>
    </header>

    <div class="main-container">
        <?php include_once("teacherNav.html") ?>
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        Course
                    </h1>
                    <button class="view" onclick="window.location.href='courseTemp.php?action=add'">Add</button>

                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT course_code, course_title FROM courses ";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['course_title']) . "</td>";
                                    echo "<td>
                                            <button class='bIcon view' onclick=\"window.location.href='courseTemp.php?action=view&course=" . htmlspecialchars($row['course_code']) . "'\">
                                                <ion-icon name='eye-outline'></ion-icon>
                                            </button>
                                            <button class='bIcon edit' onclick=\"window.location.href='courseTemp.php?action=edit&course=" . htmlspecialchars($row['course_code']) . "'\">
                                                <ion-icon name='create-outline'></ion-icon>
                                            </button>
                                            <button class='bIcon dlt' onclick=\"showConfirmDialog('" . htmlspecialchars($row['course_code']) . "')\">
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
    function showConfirmDialog(moduleId) {
        if (confirm('Are you sure you want to delete this course?')) {
            window.location.href = 'dltCourse.php?course=' + encodeURIComponent(moduleId) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>