<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Video Player and Details</title>
    <style>
        .panel {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            max-width: 700px;
        }

        .video-title {
            font-size: 1.5em;
            font-weight: bold;
        }

        .video-description {
            margin: 10px 0;
        }

        .thumbnail {
            max-width: 100%;
            height: auto;
        }

        #video-player {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="panel">
        <label for="url-input">Enter YouTube Video URL:</label>
        <input type="text" id="url-input" placeholder="https://www.youtube.com/watch?v=example" oninput="fetchVideoDetails()">
        <div id="video-details">
            <p class="video-title">Title will appear here</p>
            <p id="video-player"></p>
            <img id="thumbnail" class="thumbnail" src="" alt="Video Thumbnail" style="display:none;">
            <p class="video-description">Description will appear here</p>
        </div>
    </div>

    <script>
        const apiKey = 'AIzaSyCp4-2cWwyWUwbiWmeGEiw9baalGlrL7X0'; // Replace with your YouTube API key

        async function fetchVideoDetails() {
            const urlInput = document.getElementById('url-input').value.trim();
            const videoDetails = document.getElementById('video-details');
            const videoTitle = document.querySelector('.video-title');
            const videoDescription = document.querySelector('.video-description');
            const thumbnail = document.getElementById('thumbnail');
            const videoPlayer = document.getElementById('video-player');

            const videoId = extractVideoId(urlInput);

            if (!videoId) {
                videoTitle.textContent = "Invalid or No URL";
                videoPlayer.innerHTML = "";
                thumbnail.style.display = "none";
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
                    videoDescription.textContent = `Description: ${videoData.description}`;
                    thumbnail.src = videoData.thumbnails.high.url;
                    thumbnail.style.display = "block";

                    // Embed the YouTube video player
                    videoPlayer.innerHTML = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                } else {
                    videoTitle.textContent = "Video not found";
                    videoPlayer.innerHTML = "";
                    thumbnail.style.display = "none";
                    videoDescription.textContent = "";
                }
            } catch (error) {
                console.error('Error fetching video details:', error);
                videoTitle.textContent = "Error fetching video details";
                videoPlayer.innerHTML = "";
                thumbnail.style.display = "none";
                videoDescription.textContent = "";
            }
        }

        function extractVideoId(url) {
            const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.+&v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }
    </script>

</body>

</html>