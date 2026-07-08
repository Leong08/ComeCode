<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="/comeCode/css/studentPanel.css">
    <?php include_once("connection.php"); ?>
</head>

<html>

<body>

    <header>

        <div class="logosec">
            <div class="logo"><a class="logo" href="teacherPanel.php">TEACHER PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>

        <style>
            .leaderboard {
                margin: auto;
                padding: auto;
            }
        </style>


    </header>

    <div class="main-container">
        <?php include_once("teacherNav.html") ?>
        <div class="main">
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
                            <a class="studentScore"><?php echo (isset($points[$i]) ? $points[$i] : "&nbsp"); ?></a>
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</body>
<script src="/comeCode/js/teacherNav.js"></script>

</html>