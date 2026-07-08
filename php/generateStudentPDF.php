<?php
require('fpdf186/fpdf.php');
include_once("connection.php");
session_start();

if (!isset($_SESSION['userId']) || ($_SESSION['userType'] != "teacher" && $_SESSION['userType'] != "admin")) {
    echo "<script>alert('Please Login With Teacher or Admin Account!!!'); window.location.href = 'loginPanel.php';</script>";
    exit();
}

$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
if ($student_id) {
    $query = "SELECT s.student_id, s.first_name, s.last_name, s.date_of_birth, s.gender, s.phone_number, s.email, s.tasks_done, s.points, u.username, u.password 
              FROM students s 
              JOIN user u ON s.userId = u.userId 
              WHERE s.student_id = '$student_id'";

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
        $pdf->Cell(0, 10, 'StudentID: ' . $row['student_id'], 0, 1);
        $pdf->Cell(0, 10, 'Full Name: ' . $row['first_name'] . ' ' . $row['last_name'], 0, 1);
        $pdf->Cell(0, 10, 'DOB: ' . $row['date_of_birth'], 0, 1);
        $pdf->Cell(0, 10, 'Gender: ' . $row['gender'], 0, 1);
        $pdf->Cell(0, 10, 'Phone Number: ' . $row['phone_number'], 0, 1);
        $pdf->Cell(0, 10, 'Email: ' . $row['email'], 0, 1);

        // Log In Details
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Log In Details', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Username: ' . $row['username'], 0, 1);
        $pdf->Cell(0, 10, 'Password: ' . $row['password'], 0, 1);

        // Learning
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Learning', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Task Done: ' . $row['tasks_done'], 0, 1);
        $pdf->Cell(0, 10, 'Points: ' . $row['points'], 0, 1);

        $full_name = $row['first_name'] . '_' . $row['last_name'];
        $pdf->Output('D', $full_name . '_studentReport.pdf');
    } else {
        echo "No data found.";
    }
} else {
    echo "No student ID provided.";
}
?>
