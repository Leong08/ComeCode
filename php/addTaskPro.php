<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Get form data
$taskId = $_POST['taskID']; // Task ID from hidden input

// Get teacher ID from URL (e.g., yourpage.php?tchID=123)
$tchID = isset($_GET['tchID']) ? $_GET['tchID'] : null;

$dueDate = $_POST['dueDate'];

// Ensure the teacher ID is provided
if (!$tchID) {
    echo "<script>alert('Teacher ID is missing!'); window.location.href = 'taskList.php';</script>";
    exit();
}

// Handle form submission for adding a new task
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $search = "SELECT * FROM tasks WHERE task_id = '$taskId'";
    $result = mysqli_query($condb, $search);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This task id already exist.')</script>";
        echo "<script>history.back()</script>";
    }

    // Insert task into the tasks table (task_id is provided from the form)
    $insertQuery = "INSERT INTO tasks (task_id, teacher_id,due_date) VALUES (?, ?,?)";
    if ($stmt = $condb->prepare($insertQuery)) {
        $stmt->bind_param("sis", $taskId, $tchID, $dueDate); // Bind task_id and teacher_id
        if ($stmt->execute()) {

            // Loop through questions and options
            for ($i = 1; $i <= 5; $i++) {
                $question = $_POST["question" . $i];
                $answer = $_POST["answer" . $i];

                // Insert question into the questions table
                $query = "INSERT INTO questions (task_id, question_text) VALUES (?, ?)";
                if ($stmt = $condb->prepare($query)) {
                    $stmt->bind_param("ss", $taskId, $question);
                    $stmt->execute();
                    $questionId = $stmt->insert_id;  // Get the question ID for this inserted question
                    $stmt->close();

                    // Insert options for this question
                    foreach (['A', 'B', 'C', 'D'] as $option) {
                        $choice = $_POST["q" . $i . strtoupper($option)];
                        $isCorrect = ($answer === $option) ? 1 : 0;

                        $choiceQuery = "INSERT INTO options (question_id, option_label, option_text, is_correct) VALUES (?, ?, ?, ?)";
                        if ($choiceStmt = $condb->prepare($choiceQuery)) {
                            $choiceStmt->bind_param("isss", $questionId, $option, $choice, $isCorrect);
                            $choiceStmt->execute();
                            $choiceStmt->close();
                        }
                    }
                }
            }

            // After successfully inserting, redirect or display a success message
            echo "<script>alert('Task added successfully!'); window.location.href = 'taskList.php';</script>";
        } else {
            echo "<script>alert('Error adding task.'); window.location.href = 'taskList.php';</script>";
        }
    }
}
