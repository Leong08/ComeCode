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
    <title>Teacher Panel</title>
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


            <div class="box-container">

                <div class="box box1" onclick="window.location.href='teacherPanel.php?action=courses';">
                    <div class="text">
                        <?php
                        $q = "SELECT COUNT(*) AS Total FROM courses";
                        $r = mysqli_query($condb, $q);
                        $m = mysqli_fetch_array($r);
                        if ($m) {
                            $totalCount = $m['Total'];
                        } else {
                            $totalCount = 0;
                        }
                        ?>
                        <h2 class="topic-heading"><?php echo $totalCount; ?></h2>
                        <h2 class="topic">Courses</h2>
                    </div>

                    <img src="/comeCode/img/course.png" alt="course">
                </div>

                <div class="box box2" onclick="window.location.href='teacherPanel.php?action=task';">
                    <div class="text">
                        <?php

                        $q = "SELECT COUNT(*) AS Total FROM tasks";
                        $r = mysqli_query($condb, $q);
                        $m = mysqli_fetch_array($r);
                        if ($m) {
                            $totalCount = $m['Total'];
                        } else {
                            $totalCount = 0;
                        }
                        ?>
                        <h2 class="topic-heading"><?php echo $totalCount ?></h2>
                        <h2 class="topic">Task</h2>
                    </div>

                    <img src="/comeCode/img/task.png"
                        alt="task">

                </div>

                <div class="box box3" onclick="window.location.href='teacherPanel.php?action=module';">
                    <div class="text">

                        <?php

                        $q = "SELECT COUNT(*) AS Total FROM modules";
                        $r = mysqli_query($condb, $q);
                        $m = mysqli_fetch_array($r);
                        if ($m) {
                            $totalCount = $m['Total'];
                        } else {
                            $totalCount = 0;
                        }

                        ?>
                        <h2 class="topic-heading"><?php echo $totalCount ?></h2>
                        <h2 class="topic">Modules</h2>
                    </div>

                    <img src="/comeCode/img/module.png"
                        alt="module">
                </div>

                <div class="box box4" onclick="window.location.href='teacherPanel.php?action=student';">
                    <div class="text">
                        <?php

                        $q = "SELECT COUNT(*) AS Total FROM students";
                        $r = mysqli_query($condb, $q);
                        $m = mysqli_fetch_array($r);
                        if ($m) {
                            $totalCount = $m['Total'];
                        } else {
                            $totalCount = 0;
                        }
                        ?>
                        <h2 class="topic-heading"><?php echo $totalCount ?></h2>
                        <h2 class="topic">Students</h2>
                    </div>

                    <img src="/comeCode/img/student.png" alt="students">
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