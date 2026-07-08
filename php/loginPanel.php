<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="/comeCode/css/loginPanel.css">
    <style>
        .navbar {
            display: flex;
            justify-content: flex-end;
            background: transparent;
            padding: 1rem;
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1rem;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 5px;
        }
        .navbar a:hover {
            background: skyblue;
        }
        
        .homepage_button {
            margin-right: 1rem;
            height: 50px;
        }

    </style>
</head>

<body>
    <div class="navbar">
        <a class="homepage_button"  href="/comeCode/homepage/homepage.html">Home</a>
    </div>
    <h1 class="title">Welcome to Come Code</h1>
    <div class="logInForm">
        <form class="form" autocomplete="off" action="loginProcess.php" method="post" id="loginForm">
            <div class="control">
                <h1>Login</h1>
            </div>

            <div class="control block-cube block-input">
                <input name="username" id="uname" type="text" placeholder="Username" />
                <div class="bg-top"></div>
                <div class="bg-right"></div>
            </div>

            <div class="control block-cube block-input">
                <input name="password" id="password" type="password" placeholder="Password" />
                <div class="bg-top"></div>
                <div class="bg-right"></div>
            </div>

            <button class="btn block-cube block-cube-hover" type="submit">
                <div class="bg-top"></div>
                <div class="bg-right"></div>
                <div class="text">Log In</div>
            </button>

        </form>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(e) {
            e.preventDefault();

            let uname = document.getElementById("uname").value;
            let pass = document.getElementById("password").value;

            console.log("Username:", uname);
            console.log("Password:", pass);

            if (uname.length === 0 || pass.length === 0) {
                alert("Please fill up both username and password.");
            } else {
                this.submit();
            }

        });
    </script>

</body>

</html>
