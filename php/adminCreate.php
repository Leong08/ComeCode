<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/comeCode/css/adminCreate.css">

</head>

<body>

    <header>

        <div class="logosec">
            <div class="logo"><a class="logo" href="/comeCode/php/adminPanel.php">ADMIN PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>


    </header>

    <div class="main-container">
        <?php include_once("adminNav.html") ?>
        <div class="main">

            <h1> Create Account </h1>

            <div class="box-container">


                <div class="box box1" onclick="window.location.href='stdTemp.php?action=add'">
                    <div class="text">

                        <h2 class="topic">Student </h2>
                    </div>

                    <img src="/comeCode/img/student.png" alt="student report">
                </div>

                <div class="box box2" onclick="window.location.href='teacherTemp.php?action=add';">
                    <div class="text">

                        <h2 class="topic"> Teacher </h2>
                    </div>

                    <img src="/comeCode/img/teacher.png" alt="teacher report">

                </div>

            </div>

</body>
<script src="/comeCode/js/teacherNav.js"></script>

</html>