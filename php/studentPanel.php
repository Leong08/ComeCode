<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

include_once("connection.php");

// Retrieve studentId from the database
$userId = $_SESSION['userId'];
$query = "SELECT student_id FROM students WHERE userId = '$userId'";
$result = mysqli_query($condb, $query);
$studentData = mysqli_fetch_assoc($result);
$studentId = $studentData['student_id'];
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Panel</title>
    <link rel="stylesheet" href="/comeCode/css/studentPanel.css">

    <style>
        .leaderboard {
            margin: 50px auto;
            padding: auto;
        }
        .username {
            margin: auto;
        }
    </style>
</head>

<body>

    <header>

        <div class="logosec">
            <div class="logo"><a class="logo" href="studentPanel.php">STUDENT PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
            <div class="username"> <?php echo "Welcome back, " . $_SESSION['username'] ?> </div>

        </div>

    </header>

    <div class="main-container">
        <?php include_once("studentNav.html") ?>
        <div class="main">

            <div class="box-container">

                <div class="box box1" onclick="window.location.href='studentCourses.php';">
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

                <div class="box box2" onclick="window.location.href='studentTask.php';">
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

            </div>

            <div class="leaderboard">
                <h1>LEADERBOARD</h1>
                <p>Top ranking of your peers</p>

                <div class="podium">

                    <?php
                    $q = "SELECT * FROM students ORDER BY points DESC";
                    $r = mysqli_query($condb, $q);


                    while ($m = mysqli_fetch_assoc($r)) {
                        $students[] = $m['first_name'] . " " . $m['last_name']; // Add each row of data to the array
                        $points[] = $m['points'];
                    }

                    ?>


                    <div class="podium-position silver">
                        <img src="/comeCode/img/second_place.png" width="50px" height="50px" alt="">
                        <p><?php echo $students[1] ?></p>
                        <p><?php echo $points[1] ?></p>
                    </div>

                    <div class="podium-position gold">
                        <img src="/comeCode/img/first_place.png" width="50px" height="50px" alt="">
                        <p><?php echo $students[0] ?></p>
                        <p><?php echo $points[0] ?></p>

                    </div>

                    <div class="podium-position bronze">
                        <img src="/comeCode/img/third_place.png" width="50px" height="50px" alt="">
                        <p><?php echo $students[2] ?></p>
                        <p><?php echo $points[2] ?></p>
                    </div>
                </div>

                <div class="other-positions">
                    <?php for ($i = 3; $i < 10; $i++) { ?>
                        <p>#<?php echo $i + 1 ?>
                            <a class="studentName"><?php echo (isset($students[$i]) ? $students[$i] : "&nbsp"); ?></a>
                            <a class="studentScore"><?php echo (isset($points[$i]) ? $points[$i] : "&nbsp "); ?></a>
                        </p>
                    <?php } ?>
                </div>
            </div>

        </div>


    </div>
    </div>

    <script src="/comeCode/js/studentNav.js"></script>
</body>

</html>