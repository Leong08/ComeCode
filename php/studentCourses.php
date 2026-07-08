<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
} else {
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="/comeCode/css/studentPanel.css">
    <?php include_once("connection.php"); ?>
</head>



<body>
    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="studentPanel.php">STUDENT PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>
    </header>

    <script>
        function handleClick(courseCode) {
            window.location.href = 'studentSelectModule.php?course_code=' + encodeURIComponent(courseCode);
        }
    </script>

    <div class="main-container">
        <?php include_once("studentNav.html") ?>
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        Course
                    </h1>
                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT course_code, course_title FROM courses ORDER BY course_code ASC";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr onclick = \"handleClick('" . htmlspecialchars($row['course_code']) . "')\">";
                                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['course_title']) . "</td>";
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


<script src="/comeCode/js/studentNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>