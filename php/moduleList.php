<!DOCTYPE html>
<html lang="en">

<?php
session_start();

if (!isset($_SESSION['userId']) || $_SESSION['userType'] != "teacher") {
    echo "<script>alert('Please Login With Teacher Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module List</title>
    <link rel="stylesheet" href="/comeCode/css/teacherPanel.css">
    <?php include_once("connection.php"); ?>
    <style>
        .box-container {
            margin-left: 30%;
            margin-right: 30%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        #courseSelect {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
            outline: none;
        }

        .reset {
            padding: 8px 10px;
            background-color: #7f9cf5;
            color: white;
            border: 1px solid #ccc;
            border-radius: 0 4px 4px 0;
            border-left: none;
            cursor: pointer;
            height: 40px;
            right: 8%;
            position: relative;
        }

        .report-table th,
        .report-table td {
            text-align: left;
            padding: 8px;
        }

        .report-table td:last-child {
            display: flex;
            gap: 5px;
        }

        .bIcon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .bIcon {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logosec">
            <div class="logo"><a class="logo" href="teacherPanel.php">TEACHER PANEL</a></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png"
                class="icn menuicn"
                id="menuicn"
                alt="menu-icon">
        </div>
    </header>

    <div class="main-container">
        <?php include_once("teacherNav.html") ?>
        <div class="main">

            <div class="box-container">
                <form method="GET" action="moduleList.php">
                    <label for="courseSelect">Select Course</label>
                    <?php
                    $sql = "SELECT course_code, course_title FROM courses";
                    $result = mysqli_query($condb, $sql);

                    if ($result->num_rows > 0) {
                        echo '<select id="courseSelect" name="courseSelect"  onchange="this.form.submit()" >';
                        echo '<option value="" disabled selected>Select a course</option>';

                        while ($row = $result->fetch_assoc()) {
                            if (isset($_GET['courseSelect'])) {
                                $selected = ($row['course_code'] === $_GET['courseSelect']) ? 'selected' : '';
                            } else {
                                $selected = "";
                            }
                            echo '<option value="' . $row['course_code'] . '" ' . $selected . '>' . $row['course_title'] . '</option>';
                        }

                        echo '</select>';
                    } else {
                        echo 'No courses available';
                    }
                    ?>
                </form>
                <button class="reset" onclick="window.location.href='moduleList.php'">Reset</button>
            </div>

            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">Module</h1>
                    <button class="view" onclick="window.location.href='moduleTemp.php?action=add'">Add</button>
                </div>

                <div class="report-body">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Module Name</th>
                                <th>Course Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_GET['courseSelect'])) {
                                $code = $_GET['courseSelect'];
                                $query = "SELECT * FROM modules WHERE course_code = '$code' ";
                            } else {
                                $query = "SELECT * FROM modules";
                            }

                            $result = mysqli_query($condb, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['module_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                                    echo "<td>
                                            <button class='bIcon view' onclick=\"window.location.href='moduleTemp.php?action=view&moduleID=" . htmlspecialchars($row['module_id']) . "'\">
                                                <ion-icon name='eye-outline'></ion-icon>
                                            </button>
                                            <button class='bIcon edit' onclick=\"window.location.href='moduleTemp.php?action=edit&moduleID=" . htmlspecialchars($row['module_id']) . "'\">
                                                <ion-icon name='create-outline'></ion-icon>
                                            </button>
                                            <button class='bIcon dlt' onclick=\"showConfirmDialog('" . htmlspecialchars($row['module_id']) . "')\">
                                                <ion-icon name='trash-outline'></ion-icon>
                                            </button>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No data found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function showConfirmDialog(moduleId) {
        if (confirm('Are you sure you want to delete this module?')) {
            window.location.href = 'dltModule.php?moduleID=' + encodeURIComponent(moduleId) + '&confirm=true';
        }
    }
</script>
<script src="/comeCode/js/teacherNav.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>
