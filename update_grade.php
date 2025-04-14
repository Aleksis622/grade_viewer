<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize & validate input
    $student = trim($_POST['student'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $grade = $_POST['grade'] ?? null;

    // Basic validations
    if (empty($student) || empty($subject)) {
        die("❌ Student and subject fields are required.");
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $student)) {
        die("❌ Invalid student name. Only letters and spaces allowed.");
    }

    if (!preg_match('/^[a-zA-Z\s]+$/', $subject)) {
        die("❌ Invalid subject name. Only letters and spaces allowed.");
    }

    if (!is_numeric($grade) || $grade < 1 || $grade > 10) {
        die("❌ Invalid grade. Must be a number between 1 and 10.");
    }

    // Fetch student ID
    $stmt = $pdo->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->execute([$student]);
    $studentId = $stmt->fetchColumn();

    // Fetch subject ID
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->execute([$subject]);
    $subjectId = $stmt->fetchColumn();

    if (!$studentId || !$subjectId) {
        die("❌ Could not find matching student or subject.");
    }

    // Perform update
    $stmt = $pdo->prepare("UPDATE grades SET grade = ? WHERE student_id = ? AND subject_id = ?");
    $stmt->execute([$grade, $studentId, $subjectId]);
}

header("Location: index.php");
exit;
