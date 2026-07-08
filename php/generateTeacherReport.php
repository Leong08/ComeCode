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
    <style>
        .modules-column {
            max-width: 300px; /* Adjust the width as needed */
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        .report-table td {
            padding: 10px;
        }
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
</head>

<body>

    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="/comeCode/adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>
    </header>

    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">
            <h1> Teacher Information</h1>

            <div class="report-container">
                <div class="report-body">
                    <table class="report-table">
                        <tbody>
                            <?php
                            $teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';
                            if ($teacher_id) {
                                $query = "SELECT t.teacher_id, 
                                                 CONCAT(t.first_name, ' ', t.last_name) AS full_name, 
                                                 t.phone_number,
                                                 t.email,
                                                 u.userId,
                                                 u.username,
                                                 u.password,
                                                 GROUP_CONCAT(DISTINCT s.first_name SEPARATOR ', ') AS students,
                                                 GROUP_CONCAT(DISTINCT tk.task_id SEPARATOR ', ') AS tasks,
                                                 GROUP_CONCAT(DISTINCT m.module_name SEPARATOR ', ') AS modules
                                          FROM teachers t
                                          LEFT JOIN tasks tk ON t.teacher_id = tk.teacher_id
                                          LEFT JOIN courses c ON c.course_code IN (SELECT course_code FROM modules WHERE module_id IN (SELECT module_id FROM tasks WHERE teacher_id = t.teacher_id))
                                          LEFT JOIN modules m ON m.course_code = c.course_code
                                          LEFT JOIN students s ON s.student_id IN (SELECT student_id FROM studenttask WHERE task_id IN (SELECT task_id FROM tasks WHERE teacher_id = t.teacher_id))
                                          LEFT JOIN user u ON t.userId = u.userId
                                          WHERE t.teacher_id = '$teacher_id'
                                          GROUP BY t.teacher_id";

                                $result = mysqli_query($condb, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        // Limit modules to 10
                                        $modules = explode(', ', $row['modules']);
                                        $limitedModules = implode(', ', array_slice($modules, 0, 10));

                                        echo "<tr><td>TeacherID:</td><td>" . htmlspecialchars($row['teacher_id']) . "</td></tr>";
                                        echo "<tr><td>Full Name:</td><td>" . htmlspecialchars($row['full_name']) . "</td></tr>";
                                        echo "<tr><td>Phone Number:</td><td>" . htmlspecialchars($row['phone_number']) . "</td></tr>";
                                        echo "<tr><td>Email:</td><td>" . htmlspecialchars($row['email']) . "</td></tr>";
                                        echo "<tr><td>UserID:</td><td>" . htmlspecialchars($row['userId']) . "</td></tr>";
                                        echo "<tr><td>Username:</td><td>" . htmlspecialchars($row['username']) . "</td></tr>";
                                        echo "<tr><td>Password:</td><td>" . htmlspecialchars($row['password']) . "</td></tr>";
                                        echo "<tr><td>Students:</td><td>" . htmlspecialchars($row['students']) . "</td></tr>";
                                        echo "<tr><td>Tasks:</td><td>" . htmlspecialchars($row['tasks']) . "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No data found.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>No teacher ID provided.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($teacher_id) { ?> 
                <a href="generateTeacherPDF.php?teacher_id=<?php echo $teacher_id; ?>" class="download_button">Download PDF</a> 
            <?php } ?>
        </div>
    </div>

</body>
<script>
    function showConfirmDialog(teacher_id) {
        if (confirm('Are you sure you want to generate this report?')) {
            window.location.href = '#' + encodeURIComponent(teacher_id) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>