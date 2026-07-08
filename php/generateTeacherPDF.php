<?php
require('fpdf186/fpdf.php');
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "teacher" && $_SESSION['userType'] != "admin")) {
    echo "<script>alert('Please Login With Teacher or Admin Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

$teacher_id = isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '';
if ($teacher_id) {
    $query = "SELECT t.teacher_id, t.first_name, t.last_name, t.phone_number, t.email, u.userId, u.username, u.password, 
                     GROUP_CONCAT(DISTINCT s.first_name SEPARATOR ', ') AS students,
                     GROUP_CONCAT(DISTINCT tk.task_id SEPARATOR ', ') AS tasks,
                     GROUP_CONCAT(DISTINCT m.module_name SEPARATOR ', ') AS modules
              FROM teachers t
              LEFT JOIN tasks tk ON t.teacher_id = tk.teacher_id
              LEFT JOIN courses c ON c.course_code IN (SELECT course_code FROM modules WHERE module_id IN (SELECT module_id FROM tasks WHERE teacher_id = t.teacher_id))
              LEFT JOIN modules m ON m.course_code = c.course_code
              LEFT JOIN students s ON s.student_id IN (SELECT student_id FROM studenttask WHERE task_id IN (SELECT task_id FROM tasks WHERE teacher_id = t.teacher_id))
              LEFT JOIN user u ON t.userId = u.userId
              WHERE t.teacher_id = '$teacher_id'
              GROUP BY t.teacher_id";

    $result = mysqli_query($condb, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Personal Information
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Personal Information', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'TeacherID: ' . $row['teacher_id'], 0, 1);
        $pdf->Cell(0, 10, 'Full Name: ' . $row['first_name'] . ' ' . $row['last_name'], 0, 1);
        $pdf->Cell(0, 10, 'Phone Number: ' . $row['phone_number'], 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $row['email'], 0, 1);

        // Log In Details
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Log In Details', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Username: ' . $row['username'], 0, 1);
        $pdf->Cell(0, 10, 'Password: ' . $row['password'], 0, 1);

        // Teaching Information
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Teaching Information', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Students: ' . $row['students'], 0, 1);
        $pdf->Cell(0, 10, 'Tasks: ' . $row['tasks'], 0, 1);

        // Modules
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Modules', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $modules = explode(', ', $row['modules']);
        foreach ($modules as $index => $module) {
            $pdf->Cell(0, 10, ($index + 1) . ') ' . $module, 0, 1);
        }


        $full_name = $row['first_name'] . '_' . $row['last_name'];
        $pdf->Output('D', $full_name . '_teacherReport.pdf');

    } else {
        echo "No data found.";
    }
} else {
    echo "No teacher ID provided.";
}
?>