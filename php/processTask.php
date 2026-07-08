<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
</head>
<body>
    <div class="container">
        <h1 class="result">Quiz Result</h1>
        <?php
        include_once("connection.php");
        session_start();

        if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
            http_response_code(403);
            include("403.html");
            exit();
        } else if (!isset($_GET['taskID'])) {
            echo "<script>alert('Please select a task!!!'); window.location.href = 'studentTask.php';</script>";
            exit();
        }

        $totalPoint = 0;
        // Check if the data is available in the query string
        if (!empty($_GET)) {
            // Loop through each key-value pair in the $_GET array
            foreach ($_GET as $questionId => $answer) {
                if ($questionId == 'taskID') {
                    continue;
                }
                $sql = "SELECT is_correct FROM options WHERE option_id = '$answer'";
                $result = mysqli_query($condb, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                } else {
                    echo "This option id not found!";
                }

                if ($row['is_correct'] == 1) {
                    $totalPoint += 20;
                } else {
                    echo 'wrong';
                }

                echo '<br><br>';
            }

            $getStdID = "SELECT * FROM students WHERE userId = '" . $_SESSION['userId'] . "'";
            $result = mysqli_query($condb, $getStdID);

            $row = mysqli_fetch_assoc($result);

            $student_id = mysqli_real_escape_string($condb, $row['student_id']);
            $task_id = mysqli_real_escape_string($condb, $_GET["taskID"]);

            // Construct the SQL query
            $sql = "INSERT INTO studentTask (student_id, task_id, point) VALUES ('$student_id', '$task_id', '$totalPoint')";

            // Execute the query
            if (mysqli_query($condb, $sql)) {
                $getData = "SELECT points , tasks_done FROM students WHERE student_id = '$student_id'";
                $resultF = mysqli_query($condb, $getData);
                if ($data = mysqli_fetch_assoc($resultF)) {
                    $points = $data["points"];
                    $taskDone = $data["tasks_done"];
                }

                $points += $totalPoint;
                $taskDone += 1;

                $updateData = "UPDATE students SET points = $points , tasks_done = $taskDone WHERE student_id = $student_id";
                $update = mysqli_query($condb, $updateData);

                if ($update) {
                    echo "<script> alert('Congratulations! You have earned " . $totalPoint . " for this task.'); </script>";
                    echo "<script>window.location.href='studentTask.php'</script>";
                }
            } else {
                echo "Error executing query: " . mysqli_error($condb);
            }
        } else {
            echo "No data submitted!";
        }
        ?>
    </div>
</body>
</html>
