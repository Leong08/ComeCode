<!DOCTYPE html>
<html>
<?php
include_once("connection.php");
session_start();

// Check if user is logged in and has teacher access
if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

$userId = $_SESSION['userId'];
$action = isset($_GET['action']) ? $_GET['action'] : 'view'; // Default action is 'view'
$taskId = isset($_GET['taskID']) ? $_GET['taskID'] : '';
$taskData = [];

if ($taskId && ($action == 'view' || $action == 'edit')) {
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

    // Prepare and bind parameters for the task query
    if ($stmt = $condb->prepare($query)) {
        $stmt->bind_param("s", $taskId); // 'i' for integer parameter
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
        $dueDate = isset($firstQuestion[0]['due_date']) ? $firstQuestion[0]['due_date'] : '';
    } else {
        $dueDate = ''; // Default empty value if taskData is empty
    }
}



// Fetch teacher data for the logged-in user or the specific task's teacher
if ($action == 'view' || $action == 'edit') {
    $tchQuery = "SELECT first_name, last_name FROM teachers WHERE teacher_id = (SELECT teacher_id FROM tasks WHERE task_id = ?)";
    if ($tchStmt = $condb->prepare($tchQuery)) {
        $tchStmt->bind_param("s", $taskId);
        $tchStmt->execute();
        $tchResult = $tchStmt->get_result();
        $tch = $tchResult->fetch_assoc();
        $tchStmt->close();
    }
} else {
    // Fetch teacher data for the logged-in user
    $tchQuery = "SELECT * FROM teachers WHERE userId = ?";
    if ($tchStmt = $condb->prepare($tchQuery)) {
        $tchStmt->bind_param("i", $userId);
        $tchStmt->execute();
        $tchResult = $tchStmt->get_result();
        $tch = $tchResult->fetch_assoc();
        $tchStmt->close();
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Task</title>
    <link rel="stylesheet" href="/comeCode/css/taskTemp.css">
</head>

<body>
    <div class="form-container">
        <h2>Task</h2>
        <p>Please make sure that the question is clear.</p>
        <form id="taskSubmit" action="<?php echo $action === 'add' ? 'addTaskPro.php?tchID=' . $tch['teacher_id'] : ($action === 'edit' ? 'saveTask.php' : ''); ?>" method="post">
            <div class="form-group">
                <label for="taskID">Task ID:</label>
                <input type="text" id="taskID" name="taskID" value="<?php echo $taskId ?? ''; ?>" <?php echo $action === 'add' ? '' : 'readonly'; ?> placeholder="TSKXXX">
            </div>

            <div class="form-group">
                <label for="teacherName">Teacher Name:</label>
                <input type="text" id="teacherName" name="teacherName" value="<?php echo $tch['first_name'] . ' ' . $tch['last_name']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="dueDate">Due Date:</label>
                <input type="date" id="dueDate" name="dueDate" value="<?php echo $dueDate; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>>
            </div>

            <h3>Questions (Remember to Select the Correct Answer)</h3>

            <?php
            if ($action === 'add') {
                // Initialize 5 empty questions for 'add'
                for ($i = 1; $i <= 5; $i++) {
            ?>

                    <p>
                        Question <?php echo $i; ?>:
                        <input type="text" id="question<?php echo $i; ?>" name="question<?php echo $i; ?>" placeholder="Enter question">
                    </p>

                    <!-- Options for the question -->
                    <div class="option">
                        <label>
                            <input type="radio" id="answer<?php echo $i; ?>" name="answer<?php echo $i; ?>" value="A">
                            <span>A</span>
                        </label>
                        <input type="text" id="q<?php echo $i; ?>A" name="q<?php echo $i; ?>A" placeholder="Enter Option A">
                    </div>

                    <div class="option">
                        <label>
                            <input type="radio" id="answer<?php echo $i; ?>" name="answer<?php echo $i; ?>" value="B">
                            <span>B</span>
                        </label>
                        <input type="text" id="q<?php echo $i; ?>B" name="q<?php echo $i; ?>B" placeholder="Enter Option B">
                    </div>

                    <div class="option">
                        <label>
                            <input type="radio" id="answer<?php echo $i; ?>" name="answer<?php echo $i; ?>" value="C">
                            <span>C</span>
                        </label>
                        <input type="text" id="q<?php echo $i; ?>C" name="q<?php echo $i; ?>C" placeholder="Enter Option C">
                    </div>

                    <div class="option">
                        <label>
                            <input type="radio" id="answer<?php echo $i; ?>" name="answer<?php echo $i; ?>" value="D">
                            <span>D</span>
                        </label>
                        <input type="text" id="q<?php echo $i; ?>D" name="q<?php echo $i; ?>D" placeholder="Enter Option D">
                    </div>

                <?php
                }
            } else {

                $questionNumber = 1;
                // Loop through the task data to populate existing questions and options
                foreach ($taskData as $questionId => $questionDetails) {
                    $questionText = $questionDetails[0]['question_text'];
                    $answerKey = "answer" . $questionId;
                ?>

                    <p>
                        Question <?php echo $questionNumber; ?>:
                        <input type="text" id="question<?php echo $questionNumber; ?>" name="question<?php echo $questionId; ?>"
                            value="<?php echo $questionText; ?>"
                            <?php echo $action === 'view' ? 'readonly' : ''; ?>
                            placeholder="Enter question">
                    </p>

                    <?php
                    // Loop over the answer options (A, B, C, D)
                    foreach ($questionDetails as $option) {
                        $optionKey = "q" . $questionId . $option['option_label'];
                        $optionKeyID =  "q" . $questionNumber . $option['option_label'];

                        $isChecked = $option['is_correct'] ? 'checked' : '';  // Check if the option is correct
                    ?>
                        <div class="option">
                            <label>
                                <input type="radio" id="answer<?php echo $questionNumber; ?>" name="<?php echo $answerKey; ?>" value="<?php echo $option['option_label']; ?>"
                                    <?php echo $isChecked; ?>
                                    <?php echo $action === 'view' ? 'disabled' : ''; ?>>
                                <span><?php echo $option['option_label']; ?></span>
                            </label>
                            <input type="text" id="<?php echo $optionKeyID; ?>" name="<?php echo $optionKey; ?>"
                                value="<?php echo $option['option_text']; ?>"
                                <?php echo $action === 'view' ? 'readonly' : ''; ?>
                                placeholder="Enter Option <?php echo $option['option_label']; ?>">
                        </div>
            <?php
                    }
                    $questionNumber++; // Increment the question number after each question
                }

                // If there are less than 5 questions, fill the remaining with empty questions

            }
            ?>

            <?php if ($action !== 'view') { ?>
                <button id="submitButton" class="submit-button" type="submit">
                    <?php echo $action === 'edit' ? 'Save Changes' : 'Submit'; ?>
                </button>
            <?php } ?>
            <br><br>
            <a href="taskList.php" class="return-btn">Return</a>
        </form>
    </div>
</body>

<script>
    document.getElementById("taskSubmit").addEventListener("submit", function(e) {
        e.preventDefault();


        let taskID = document.getElementById("taskID").value;
        let dueDate = document.getElementById("dueDate").value;
        let now = Date.now();
        let due = new Date(dueDate);
        let questions = [];
        let answers = [];
        let radioChk = 0;

        if (!taskID.startsWith("TSK")) {
            alert("Oops! 😬 Your Task ID must start with 'TSK'. Please check and try again.");
            return;
        }

        if (isNaN(due.getTime())) {
            alert("Oops! 🚫 The date is invalid or empty. Please provide a valid date.");
            return;
        }

        if (due < now) {
            alert("Oh no! 😟 The Due Date must be after today. Please select a valid future date.");
            return;
        }

        for (let i = 1; i <= 5; i++) {
            let question = document.getElementById(`question${i}`).value;

            if (question.length === 0) {
                alert(`Please fill in the question text for Question ${i}. It’s important to complete it!`);
                return;
            }
            questions.push(question);
        }

        for (let i = 1; i <= 5; i++) {
            for (let x = 65; x <= 68; x++) {
                let answer = document.getElementById(`q${i}${String.fromCharCode(x)}`).value;
                if (answer.length === 0) {
                    alert(`Oops! 😬 Please fill in the option for Question ${i}, Option ${String.fromCharCode(x)}.`);
                    return;
                }
                answers.push(answer);
            }
        }



        for (let z = 1; z <= 5; z++) {
            let radios = document.querySelectorAll(`input[id="answer${z}"]`);

            for (let a = 0; a < radios.length; a++) {
                if (radios[a].checked) {
                    radioChk++;
                }
            }
        }

        if (radioChk !== 5) {
            alert("Please make sure you have selected an answer for all 5 questions. 😊");
            return;
        }


        this.submit();

    });
</script>

</html>