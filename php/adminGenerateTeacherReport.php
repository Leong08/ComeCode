<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "admin") {
    echo "<script>alert('Please Login With Admin Account!!!'); window.location.href = 'adminLoginPanel.php';</script>";
    exit();
} else {
}
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/comeCode/css/adminReport.css">
    <?php include_once("connection.php"); ?>
</head>

<body>

    <header>

        <div class="logosec">
            <div class="logo"><a class="logo" href="/comeCode/php/adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>


    </header>


    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">
            <h1>Teacher Information</h1>

            <div class="box-container">
                <form method="GET" action="adminGenerateTeacherReport.php">
                    <input class="uname" type="text" name="uname" placeholder="Teacher Name" value="<?php echo (isset($_GET['uname']) ? $_GET['uname'] : ''); ?>" required>
                    <button class="search-button">Search</button>
                </form>
            </div>

            <!-- For this part have to edit somethings, may refer back to the copilot -->
            <div class="report-container">
                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>TeacherID</th>
                                <th>Full Name</th>
                                <th>Courses</th>
                                <th>Task</th>
                                <th>Module</th>
                                <th>Student</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $uname = isset($_GET['uname']) ? $_GET['uname'] : '';
                            $query = "SELECT t.teacher_id, t.first_name, t.last_name,
                                      (SELECT COUNT(DISTINCT c.course_code) FROM courses c 
                                       JOIN modules m ON c.course_code = m.course_code 
                                       JOIN tasks ta ON ta.teacher_id = t.teacher_id) AS totalCourses,
                                      (SELECT COUNT(*) FROM tasks ta WHERE ta.teacher_id = t.teacher_id) AS totalTasks,
                                      (SELECT COUNT(*) FROM modules m 
                                       JOIN courses c ON m.course_code = c.course_code
                                       JOIN tasks ta ON ta.teacher_id = t.teacher_id) AS totalModules,
                                      (SELECT COUNT(DISTINCT s.student_id) FROM students s
                                       JOIN studenttask st ON st.student_id = s.student_id
                                       JOIN tasks ta ON ta.task_id = st.task_id
                                       WHERE ta.teacher_id = t.teacher_id) AS totalStudents
                                      FROM teachers t
                                      WHERE t.first_name LIKE '%$uname%' OR 
                                            t.last_name LIKE '%$uname%' OR 
                                            CONCAT(t.first_name, ' ', t.last_name) LIKE '%$uname%'";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['teacher_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']) .  "</td>";
                                    echo "<td>" . htmlspecialchars($row['totalCourses']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['totalTasks']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['totalModules']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['totalStudents']) . "</td>";
                                    echo "<td><button class='view' onclick='window.location.href=\"generateTeacherReport.php?teacher_id=" . htmlspecialchars($row['teacher_id']) . "\"'>Generate</button></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- For this part have to edit somethings -->


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
