<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In</title>
    <link rel="stylesheet" href="\comeCode\css\adminLP.css">
</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png" alt="illustration" class="illustration" />
                <h1 class="opacity">ADMIN LOGIN</h1>
                <form action="adminLoginProcess.php" autocomplete="off" method="post" id="adminLogin">
                    <input type="text" id="uname" placeholder="USERNAME" name="username" />
                    <input type="password" id="password" placeholder="PASSWORD" name="password" />
                    <button class="opacity" type="submit">SUBMIT</button>
                </form>

            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>
</body>

<script>
    document.getElementById("adminLogin").addEventListener("submit", function(e) {

        e.preventDefault();

        let uname = document.getElementById("uname").value;
        let pass = document.getElementById("password").value;

        if (uname.length === 0 || pass.length === 0) {
            alert("Please fill up both username and password.");
        } else {
            this.submit();
        }
    });
</script>

</html>