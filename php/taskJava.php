<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
} else if (!isset($_GET['taskID'])) {
    echo "<script>alert('Please select a task!!!'); window.location.href = 'studentTask.php';</script>";
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <link rel="stylesheet" href="/comeCode/css/taskJava.css">
    <?php include_once("connection.php"); ?>


    <style>
        .button {
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .correct {
            background-color: green;
            color: white;
        }

        .wrong {
            background-color: red;
            color: white;
        }

        .selected {
            background-color: darkblue !important;
            color: white;
        }

        .option {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #f0f0f0;
            color: #333;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .option:hover {
            background-color: #ddd;
        }

        .option.selected {
            background-color: darkblue;
            color: white;
        }

        .back {
            font-size: 20px; 
            padding: 15px 30px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease; 
        }
        .back:hover {
            background-color: #45a049; 
            transform: scale(1.05); 
        }
        .back:active {
            background-color: #397d3f; 
            transform: scale(1); 
        }
    </style>

</head>

<?php
$query = "
     SELECT
         t.task_id,
         t.teacher_id,
         t.due_date,
         q.question_id,
         q.question_text,
         c.option_id,
         c.option_label,
         c.option_text,
         c.is_correct
     FROM tasks t
     JOIN questions q ON t.task_id = q.task_id
     JOIN options c ON q.question_id = c.question_id
     WHERE t.task_id = ? 
     ORDER BY q.question_id, c.option_label
 ";

// Prepare and bind parameters for the task query
if ($stmt = $condb->prepare($query)) {
    $stmt->bind_param("s", $_GET['taskID']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $taskData[$row['question_id']][] = $row;
    }
    $stmt->close();
} else {
    echo "Error in query execution: " . $condb->error;
}


if (!empty($taskData)) {
    // Get the first question's data (since all questions share the same due_date)
    $firstQuestion = reset($taskData); // This will give you the first array element (the first question)
    $tchID = isset($firstQuestion[0]['teacher_id']) ? $firstQuestion[0]['teacher_id'] : '';
} else {
    $tchID = ''; // Default empty value if taskData is empty
}

// Fetch teacher data for the logged-in user
$tchQuery = "SELECT * FROM teachers WHERE teacher_id = ?";
if ($tchStmt = $condb->prepare($tchQuery)) {
    $tchStmt->bind_param("i", $tchID);
    $tchStmt->execute();
    $tchResult = $tchStmt->get_result();
    $tch = $tchResult->fetch_assoc();
    $tchStmt->close();
}
?>

<body>
    <div class="main-container">
        <div class="main">
            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">
                        <?php echo $_GET["taskID"]; ?>
                    </h1>
                    <h4>Create By: <?php echo $tch["first_name"] . " " . $tch["last_name"] ?></h4>
                    <form action="studentTask.php" method="get">
                        <button type="submit" class="back">Back</button>
                    </form>
                </div>

                <!-- Form with taskID dynamically added -->
                <form id="quizForm" action="processTask.php" method="GET">
                    <?php
                    $questionNum = 1;
                    foreach ($taskData as $questionId => $questionDetails) {
                        $questionText = $questionDetails[0]['question_text'];
                    ?>
                        <div class="report-body">
                            <h2 class="question"><?php echo "Q." . $questionNum . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" .  htmlspecialchars($questionText) ?> </h2>
                            <div class="options" id="question_<?php echo $questionId; ?>">
                                <?php

                                foreach ($questionDetails as $option) {
                                    $optionKey = "answer" . $questionId . $option['option_label'];
                                ?>

                                    <button type="button" id="<?php echo $optionKey; ?>" class="option" onclick="selectOption('<?php echo $optionKey; ?>', '<?php echo $questionId; ?>', '<?php echo $option['option_id']; ?>')">
                                        <span><?php echo $option['option_label']; ?></span>：
                                        <?php echo  htmlspecialchars($option['option_text']); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                        $questionNum++;
                    } ?>

                    <!-- Include taskID in form submission -->
                    <input type="hidden" name="taskID" value="<?php echo $_GET['taskID']; ?>" />

                    <button type="submit" class="submit">Submit</button>
                </form>

            </div>
        </div>
    </div>

    

    <script>
        function selectOption(optionKey, questionId, optionId) {
            // Get all options for this question
            const options = document.querySelectorAll(`#question_${questionId} .option`);

            // Remove 'selected' class from all options
            options.forEach(option => {
                option.classList.remove('selected');
            });

            // Add 'selected' class to clicked option
            const selectedOption = document.getElementById(optionKey);
            selectedOption.classList.add('selected');

            // Remove existing hidden input if exists
            const existingInput = document.querySelector(`#selected_answer_${questionId}`);
            if (existingInput) {
                existingInput.remove();
            }

            // Create new hidden input with selected option value
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.id = 'selected_answer_' + questionId;
            hiddenInput.name = 'question_' + questionId; // Use a name to correlate with the question
            hiddenInput.value = optionId;

            // Append the hidden input field to the form
            document.getElementById('quizForm').appendChild(hiddenInput);
        }
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>

