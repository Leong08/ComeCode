<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "student") {
    echo "<script>alert('Please Login With Student Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

if (isset($_GET['moduleID'])) {
    $moduleID = htmlspecialchars($_GET['moduleID']);
    // Connect to the database
    include_once("connection.php");

    // Fetch course details based on course_code
    $query = "SELECT * FROM modules WHERE module_id = '$moduleID'";
    $result = mysqli_query($condb, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $module = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Module not found.</p>";
        exit();
    }
} else {
    echo "<p>No module id provided.</p>";
    exit();
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module List</title>
    <link rel="stylesheet" href="/comeCode/css/studentModule.css">
    <?php include_once("connection.php"); ?>
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
            <h1><?php echo htmlspecialchars($module['module_name']); ?></h1>
            <h3><?php echo htmlspecialchars($module['description']); ?></h3>

            <div class="panel">
                <div style="display: none;">
                    <label for="url-input">Enter YouTube Video URL:</label>
                    <input type="text" id="url-input" placeholder="https://www.youtube.com/watch?v=example" value="<?php echo htmlspecialchars($module['url_link']); ?>" onchange="fetchVideoDetails()" readonly>
                </div>

                <div id="video-details">
                    <p class="video-title">Title will appear here</p>
                    <p id="video-player"></p>

                </div>
            </div>

            <script>
                const apiKey = 'AIzaSyCp4-2cWwyWUwbiWmeGEiw9baalGlrL7X0'; // Replace with your YouTube API key

                async function fetchVideoDetails() {
                    const urlInput = document.getElementById('url-input').value.trim();
                    const videoDetails = document.getElementById('video-details');
                    const videoTitle = document.querySelector('.video-title');
                    const videoPlayer = document.getElementById('video-player');

                    const videoId = extractVideoId(urlInput);

                    if (!videoId) {
                        videoTitle.textContent = "Invalid or No URL";
                        videoPlayer.innerHTML = "";

                        videoDescription.textContent = "";
                        return;
                    }

                    const apiUrl = `https://www.googleapis.com/youtube/v3/videos?id=${videoId}&key=${apiKey}&part=snippet`;

                    try {
                        const response = await fetch(apiUrl);
                        const data = await response.json();

                        if (data.items && data.items.length > 0) {
                            const videoData = data.items[0].snippet;
                            videoTitle.textContent = `Title: ${videoData.title}`;


                            // Embed the YouTube video player
                            videoPlayer.innerHTML = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                        } else {
                            videoTitle.textContent = "Video not found";
                            videoPlayer.innerHTML = "";

                        }
                    } catch (error) {
                        console.error('Error fetching video details:', error);
                        videoTitle.textContent = "Error fetching video details";
                        videoPlayer.innerHTML = "";

                    }
                }

                function extractVideoId(url) {
                    const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+&v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
                    const match = url.match(regex);
                    return match ? match[1] : null;
                }

                document.addEventListener('DOMContentLoaded', (event) => {
                    fetchVideoDetails();
                });
            </script>
        </div>
    </div>
</body>

<script>
    function showConfirmDialog(moduleId) {
        if (confirm('Are you sure you want to delete this course?')) {
            window.location.href = 'dltCourse.php?course=' + encodeURIComponent(moduleId) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>