<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

if (isset($_GET['course_code'])) {
    $course_code = htmlspecialchars($_GET['course_code']);

    include_once("connection.php");


    $query = "SELECT * FROM modules WHERE course_code = '$course_code'";
    $result = mysqli_query($condb, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $module = mysqli_fetch_assoc($result);
    } else {
    }
} else {
    echo "<p>No course code provided.</p>";
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module</title>
    <link rel="stylesheet" href="/comeCode/css/studentSelectModule.css">
    <?php include_once("connection.php"); ?>
</head>



<body>
    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="studentPanel.php">MODULE</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>
    </header>

    <script>
        function handleClick(module_id) {
            window.location.href = 'studentModule.php?moduleID=' + encodeURIComponent(module_id);
        }
    </script>

    <div class="main-container">
        <?php include_once("studentNav.html") ?>
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        <?php
                        $desc = "SELECT * FROM courses WHERE course_code ='$course_code' ";
                        $resultDesc = mysqli_query($condb, $desc);
                        $descR = mysqli_fetch_assoc($resultDesc);
                        echo $descR['course_description'];
                        ?>
                    </h1>
                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <!-- <th>Module ID</th> -->
                                <th>Module Name</th>
                                <th>Module Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $query = "SELECT module_id, module_name, description FROM modules WHERE course_code = '" . mysqli_real_escape_string($condb, $course_code) . "' ORDER BY module_id ASC";

                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr onclick = \"handleClick('" . htmlspecialchars($row['module_id']) . "')\">";
                                    // echo "<td>" . htmlspecialchars($row['module_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
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