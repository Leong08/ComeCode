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
    <link rel="stylesheet" href="/comeCode/css/adminPanel.css">
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


            <div class="box-container">

                <div class="box box1" onclick="window.location.href='adminGenerateStudentReport.php'">
                    <div class="text">

                        <h2 class="topic">Student Report</h2>
                    </div>

                    <img src="/comeCode/img/student.png" alt="student report">
                </div>

                <div class="box box2" onclick="window.location.href='adminGenerateTeacherReport.php';">
                    <div class="text">

                        <h2 class="topic"> Teacher Report</h2>
                    </div>

                    <img src="/comeCode/img/teacher.png"
                        alt="teacher report">

                </div>

                <div class="box box3" onclick="window.location.href='adminCreate.php';">
                    <div class="text">


                        <h2 class="topic">Create Account </h2>
                    </div>

                    <img src="/comeCode/img/create account.png"
                        alt="create account">
                </div>

                <div class="box box4" onclick="window.location.href='adminDelete.php';">
                    <div class="text">

                        <h2 class="topic">Delete Account</h2>
                    </div>

                    <img src="/comeCode/img/delete account.png" alt="delete account">
                </div>
            </div>

            <div class="report-container">
                <?php
                if (isset($_GET['action'])) {
                    $action = $_GET['action'];

                    if ($action == "courses") {
                        $word = "Courses";
                    } else if ($action == "task") {
                        $word = "Tasks";
                    } else if ($action == "module") {
                        $word = "Modules";
                    } else {
                        $word = "Students";
                    }
                } else {
                    $word = "Welcome to Come Code";
                }
                ?>

                <div class="report-header">
                    <h1 class="recent-Articles">
                        <?php echo $word; ?>
                    </h1>

                    <?php if (isset($action)) {
                        $link = "";

                        if ($action == "courses") {
                            $link = "courseList.php";
                        } else if ($action == "task") {
                            $link = "taskList.php";
                        } else if ($action == "module") {
                            $link = "moduleList.php";
                        } else {
                            $link = "studentList.php";
                        }

                    ?>
                        <button class="view" onclick="window.location.href='<?php echo $link; ?>'">View All</button>
                    <?php
                    } else {
                    } ?>


                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <?php
                                if (isset($action)) {
                                    if ($action == "courses") {
                                        echo "<th>Course Code</th><th>Course Title</th>";
                                    } elseif ($action == "task") {
                                        echo "<th>Task ID</th><th>Teacher ID</th><th>Due Date</th>";
                                    } elseif ($action == "student") {
                                        echo "<th>Student ID</th><th>First Name</th><th>Last Name</th><th>Points</th>";
                                    } elseif ($action == "module") {
                                        echo "<th>Module ID</th><th>Module Name</th><th>Course Code</th>";
                                    } else {
                                        echo "<th>Invalid action specified.</th>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "";
                            if (isset($action)) {
                                if ($action == "courses") {
                                    $query = "SELECT course_code, course_title FROM courses LIMIT 5";
                                } elseif ($action == "task") {
                                    $query = "SELECT task_id, teacher_id,due_date FROM tasks LIMIT 5";
                                } elseif ($action == "student") {
                                    $query = "SELECT student_id, first_name, last_name, points FROM students LIMIT 5";
                                } else {
                                    $query = "SELECT module_id, module_name, course_code FROM modules LIMIT 5";
                                }

                                if ($query) {
                                    $result = mysqli_query($condb, $query);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            if ($action == "courses") {
                                                echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['course_title']) . "</td>";
                                            } elseif ($action == "task") {
                                                echo "<td>" . htmlspecialchars($row['task_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['teacher_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                                            } elseif ($action == "student") {
                                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['points']) . "</td>";
                                            } else {
                                                echo "<td>" . htmlspecialchars($row['module_id']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                                            }
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No data found.</td></tr>";
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>

    <script src="/comeCode/js/teacherNav.js"></script>
</body>

</html>