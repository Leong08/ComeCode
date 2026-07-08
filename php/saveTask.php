<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    http_response_code(403);
    include("403.html");
    exit();
}

// Get form data
$taskId = $_POST['taskID'];

$taskData = [];

// Fetch data for the given task_id using a prepared SQL query
$query = "
        SELECT
            t.task_id,
            t.due_date,
            q.question_id,
            q.question_text,
            c.option_label,
            c.option_text,
            c.is_correct
        FROM tasks t
        JOIN questions q ON t.task_id = q.task_id
        JOIN options c ON q.question_id = c.question_id
        WHERE t.task_id = ?
        ORDER BY q.question_id, c.option_label
    ";


if ($stmt = $condb->prepare($query)) {
    $stmt->bind_param("s", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $taskData[$row['question_id']][] = $row;
    }
    $stmt->close();
} else {
    echo "Error in query execution: " . $condb->error;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $dueUpdate = "UPDATE tasks SET due_date = ? WHERE task_id = ?";
    if ($stmt = $condb->prepare($dueUpdate)) {
        $stmt->bind_param("ss", $_POST['dueDate'], $taskId);
        $stmt->execute();
        $stmt->close();
    }


    foreach ($taskData as $questionId => $questionDetails) {
        $questionText = $_POST['question' . $questionId];

        // Update the question text in the 'questions' table
        $updateQuestionQuery = "UPDATE questions 
                            SET question_text = ? 
                            WHERE question_id = ?";

        if ($stmt = $condb->prepare($updateQuestionQuery)) {
            $stmt->bind_param("si", $questionText, $questionId);
            $stmt->execute();
            $stmt->close();
        }

        // Loop through the options (A, B, C, D) for this question
        foreach ($questionDetails as $option) {
            $optionLabel = $option['option_label'];
            $optionText = $_POST['q' . $questionId . $optionLabel];
            $isCorrect = isset($_POST["answer" . $questionId]) && $_POST["answer" . $questionId] == $optionLabel ? 1 : 0;

            // Update the option text and correctness in the 'options' table
            $updateOptionQuery = "UPDATE options 
                              SET option_text = ?, is_correct = ? 
                              WHERE question_id = ? AND option_label = ?";

            if ($stmt = $condb->prepare($updateOptionQuery)) {
                $stmt->bind_param("siis", $optionText, $isCorrect, $questionId, $optionLabel);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // If adding new questions (in case there are less than 5)
    $questionNumber = count($taskData) + 1; // Assuming this is the next available question number

    while ($questionNumber <= 5) {
        // Assuming the form has 'question' and 'q' fields for the new questions and options
        $newQuestionText = $_POST['question' . $questionNumber];

        // Insert a new question if it doesn't exist in the database
        $insertQuestionQuery = "INSERT INTO questions (question_text) VALUES (?)";

        if ($stmt = $condb->prepare($insertQuestionQuery)) {
            $stmt->bind_param("s", $newQuestionText);
            $stmt->execute();
            $newQuestionId = $condb->insert_id; // Get the ID of the newly inserted question
            $stmt->close();
        }

        // Insert options for the new question (A, B, C, D)
        for ($i = 0; $i < 4; $i++) {
            $optionLabel = chr(65 + $i); // 'A', 'B', 'C', 'D'
            $optionText = $_POST['q' . $questionNumber . $optionLabel];
            $isCorrect = isset($_POST['answer' . $questionNumber]) && $_POST['answer' . $questionNumber] == $optionLabel ? 1 : 0;

            // Insert the options for this new question
            $insertOptionQuery = "INSERT INTO options (question_id, option_label, option_text, is_correct) 
                              VALUES (?, ?, ?, ?)";

            if ($stmt = $condb->prepare($insertOptionQuery)) {
                $stmt->bind_param("isss", $newQuestionId, $optionLabel, $optionText, $isCorrect);
                $stmt->execute();
                $stmt->close();
            }
        }

        $questionNumber++;
    }

    // After successfully inserting, redirect or display a success message
    echo "<script>alert('Task update successfully!'); window.location.href = 'taskList.php';</script>";
}
