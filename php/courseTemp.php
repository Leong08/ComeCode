<!DOCTYPE html>
<html lang="en">
<?php
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

// Get the action from the URL (default to 'view' if not provided)
$action = isset($_GET['action']) ? $_GET['action'] : 'view';
$courseCode = isset($_GET['course']) ? $_GET['course'] : '';

$moduleData = [];
if ($action === 'view' || $action === 'edit') {
    // Fetch module data if action is 'view' or 'edit'
    $sql = "SELECT * FROM courses WHERE course_code = '$courseCode'";
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $moduleData = mysqli_fetch_assoc($result);
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course</title>
    <link rel="stylesheet" href="/comeCode/css/courseTemp.css">
</head>

<body>
    <div class="form-container">
        <h2>Course</h2>
        <p>Please make sure that the module description is clear.</p>
        <form id="course" action="<?php echo $action === 'add' ? 'addCoursePro.php' : ($action === 'edit' ? 'saveCourse.php?course=' . $courseCode : ''); ?>" method="post">
            <label for="courseCode">Course Code</label>
            <input type="text" id="courseCode" name="courseCode" placeholder="courseCode" value="<?php echo $moduleData['course_code'] ?? ''; ?>" <?php echo $action !== 'add' ? 'readonly' : ''; ?>>

            <?php if ($action == 'edit') { ?>
                <script>
                    document.getElementById("courseCode").addEventListener("click", function() {
                        alert("Course code cannot be edited.");
                    });
                </script>
            <?php } ?>

            <label for="CourseName">Course Name</label>
            <input type="text" id="courseName" name="CourseName" placeholder="Java" value="<?php echo $moduleData['course_title'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>>

            <label for="courseDescription">Course Description</label>
            <textarea <?php echo $action === 'view' ? 'readonly' : ''; ?> cols="50" rows="5" name="CourseDescription" id="courseDescription" placeholder="Beginner Java Course"><?php echo $moduleData['course_description'] ?? ''; ?></textarea>

            <?php if ($action !== 'view') { ?>
                <button class="submitButton" type="submit"><?php echo $action === 'edit' ? 'Save Changes' : 'Submit'; ?></button>
            <?php } ?>
            <br>
            <a href="courseList.php" class="return-btn">Return</a>
        </form>
    </div>
</body>

<script>
    document.getElementById("course").addEventListener("submit", function(e) {
        e.preventDefault();

        let courseCode = document.getElementById("courseCode").value;
        let courseName = document.getElementById("courseName").value;
        let description = document.getElementById("courseDescription").value;

        if (courseCode.length === 0) {
            alert("Course code cannot be empty.");
            return;
        }

        this.submit();
    });
</script>

</html>