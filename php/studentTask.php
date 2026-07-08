<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include_once("connection.php");



if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
} else {
}

$getStdID = "SELECT * FROM students WHERE userId = '" . $_SESSION['userId'] . "'";
$result = mysqli_query($condb, $getStdID);

$row = mysqli_fetch_assoc($result);




$student_id = mysqli_real_escape_string($condb, $row['student_id']);
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <link rel="stylesheet" href="/comeCode/css/studentPanel.css">

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


    <div class="main-container">
        <?php include_once("studentNav.html") ?>
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        Task
                    </h1>
                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Task Code</th>
                                <th>Create By </th>
                                <th>Task Create</th>
                                <th>Due Date </th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT  task_id, teacher_id ,created_at ,due_date FROM tasks ORDER BY task_id DESC";


                            $result = mysqli_query($condb, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $tchQuery = "SELECT * FROM teachers WHERE teacher_id = '" . $row['teacher_id'] . "'";
                                    $tchResult = mysqli_query($condb, $tchQuery);
                                    $tch = mysqli_fetch_assoc($tchResult);
                                    echo "<tr onclick = \"handleClick('" . htmlspecialchars($row['task_id']) . "')\">";
                                    echo "<td>" . htmlspecialchars($row['task_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($tch['first_name']) . ' ' . htmlspecialchars($tch['last_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";

                                    $currentDate = date('Y-m-d');
                                    $dueDate = date('Y-m-d', strtotime($row['due_date']));

                                    $checkSql = "SELECT * FROM studenttask WHERE student_id ='$student_id' AND task_id = '" . $row['task_id'] . "'";
                                    $checkRst = mysqli_query($condb, $checkSql);

                                    if (mysqli_num_rows($checkRst) == 1) {
                                        echo "<td>
                                        <button class='bIcon done'>
                                            &nbsp&nbsp&nbsp<img src='/comeCode/img/done_icon.png' alt='Done Icon'/>
                                        </button>
                                        </td>";
                                    }
                                    
                                    else if($dueDate < $currentDate){
                                        echo "<td>
                                        <button class='bIcon overdue'>
                                            &nbsp&nbsp&nbsp<img src='/comeCode/img/dueDate_icon.png' alt='Done Icon'/>
                                        </button>
                                        </td>";
                                    }else {
                                        echo "<td>
                                            <button class='bIcon start' onclick=\"window.location.href='taskJava.php?taskID=" . htmlspecialchars($row['task_id']) . "'\">
                                                &nbsp&nbsp&nbsp<img src='/comeCode/img/submit_icon.png' alt='Done Icon'/>
                                            </button>
                                        </td>";
                                    }
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