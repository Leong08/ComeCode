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
$moduleID = isset($_GET['moduleID']) ? $_GET['moduleID'] : '';

$moduleData = [];
if ($action === 'view' || $action === 'edit') {
    // Fetch module data if action is 'view' or 'edit'
    $sql = "SELECT * FROM modules WHERE module_id = '$moduleID'";
    $result = mysqli_query($condb, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $moduleData = mysqli_fetch_assoc($result);
    }
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODULE TEMPLATE</title>
    <link rel="stylesheet" href="/comeCode/css/moduleTemp.css">
</head>

<body>
    <div class="form-container">
        <h2>Module Form</h2>
        <p>Please make sure that the module description is clear.</p>
        <form id="module" action="<?php echo $action === 'add' ? 'addModulePro.php' : ($action === 'edit' ? 'saveModule.php' : ''); ?>" method="post">
            <label for="module_id">Module ID</label>
            <input type="text" id="module_id" name="module_id" placeholder="Module ID will be auto generate" value="<?php echo $moduleData['module_id'] ?? ''; ?>" readonly ?>

            <label for="module_name">Module Name</label>
            <input type="text" id="module_name" name="module_name" placeholder="Chapter 1 - Let's learn Java" value="<?php echo $moduleData['module_name'] ?? ''; ?>" <?php echo $action === 'view' ? 'readonly' : ''; ?>>

            <label for="courseSelect">Select Course</label>
            <?php
            $sql = "SELECT course_code, course_title FROM courses";
            $result = mysqli_query($condb, $sql);


            if ($result->num_rows > 0) {
                echo '<select id="courseSelect" name="courseSelect" ' . ($action === 'view' ? 'disabled' : '') . ' >';
                echo '<option value="" disabled selected>Select a course</option>';

                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['course_code'] === $moduleData['course_code']) ? 'selected' : '';
                    echo '<option value="' . $row['course_code'] . '" ' . $selected . '>' . $row['course_title'] . '</option>';
                }

                echo '</select>';
            } else {
                echo 'No courses available';
            }
            ?>


            <label for="moduleDescription">Module Description</label>
            <textarea cols="50" rows="5" name="moduleDescription" id="moduleDescription" placeholder="The first chapter of Java coding is variable." <?php echo $action === 'view' ? 'readonly' : ''; ?>><?php echo $moduleData['description'] ?? ''; ?></textarea>

            <label for="url-input" class="url-upload-label">Enter URL:</label>
            <input type="text" id="url-input" class="url-upload-input" name="url" placeholder="https://www.youtube.com/watch?v=example" value="<?php echo $moduleData['url_link'] ?? ''; ?>" oninput="fetchUrlTitle()" <?php echo $action === 'view' ? 'readonly' : ''; ?>>
            <span id="url-title" class="url-title-text">
                <?php echo isset($moduleData['url_link']) && !empty($moduleData['url_link']) ? '' : 'No URL entered'; ?>
            </span>



            <?php if ($action !== 'view') { ?>
                <button class="submit-button" type="submit"><?php echo $action === 'edit' ? 'Save Changes' : 'Submit'; ?></button>
            <?php } ?>
            <br>
            <a href="moduleList.php" class="return-btn">Return</a>
        </form>

    </div>
</body>

<script>
    document.getElementById("module").addEventListener("submit", function(e) {
        e.preventDefault();

        let moduleName = document.getElementById("module_name").value;
        let course = document.getElementById("courseSelect").value;
        let description = document.getElementById("moduleDescription").value;
        let url = document.getElementById("url-input").value;
        let urlText = document.getElementById('url-title').textContent;

        if (moduleName.length === 0) {
            alert("Please fill up the module name.");
            return;
        }

        if (course.length === 0) {
            alert("Please select a course .");
            return;
        }

        if (description.length === 0) {
            alert("Please fill up the module description.");
            return;
        }

        if (url.length === 0) {
            alert("Please fill up the url link for this module.");
            return;
        }

        if (urlText === "Invalid URL") {
            alert("Please fill up with a valid URL link.");
            return;
        }

        this.submit();

    })

    const apiKey = 'AIzaSyCp4-2cWwyWUwbiWmeGEiw9baalGlrL7X0';

    async function fetchUrlTitle() {
        const urlInput = document.getElementById('url-input');
        const urlTitleText = document.getElementById('url-title');
        const url = urlInput.value.trim();

        if (!url) {
            urlTitleText.textContent = 'No URL entered';
            return;
        }

        const videoId = extractVideoId(url);

        if (!videoId) {
            urlTitleText.textContent = 'Invalid URL';
            return;
        }

        try {
            const apiUrl = `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${apiKey}&part=snippet`;
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.items && data.items.length > 0) {
                const title = data.items[0].snippet.title;
                urlTitleText.textContent = `Title: ${title}`;
            } else {
                urlTitleText.textContent = 'Video not found';
            }
        } catch (error) {
            console.error('Error fetching title:', error);
            urlTitleText.textContent = 'Error fetching title';
        }
    }

    function extractVideoId(url) {
        const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+&v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
        const match = url.match(regex);
        return match ? match[1] : null;
    }
</script>


</html>