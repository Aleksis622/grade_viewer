<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalStudent = trim($_POST['original_student'] ?? '');
    $originalSubject = trim($_POST['original_subject'] ?? '');

    $newStudent = trim($_POST['new_student'] ?? '');
    $newSubject = trim($_POST['new_subject'] ?? '');
    $newGrade = intval($_POST['grade'] ?? 0);

    // Validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $newStudent)) {
        die("❌ Invalid student name. Only letters and spaces are allowed.");
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $newSubject)) {
        die("❌ Invalid subject name. Only letters and spaces are allowed.");
    }

    if (!is_numeric($newGrade) || $newGrade < 1 || $newGrade > 10) {
        die("❌ Invalid grade. Must be between 1 and 10.");
    }

    // Get original student and subject IDs
    $stmt = $pdo->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->execute([$originalStudent]);
    $originalStudentId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->execute([$originalSubject]);
    $originalSubjectId = $stmt->fetchColumn();

    if (!$originalStudentId || !$originalSubjectId) {
        die("❌ Original student or subject not found.");
    }

    // Get or insert new student
    $stmt = $pdo->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->execute([$newStudent]);
    $newStudentId = $stmt->fetchColumn();

    if (!$newStudentId) {
        $stmt = $pdo->prepare("INSERT INTO students (name) VALUES (?)");
        $stmt->execute([$newStudent]);
        $newStudentId = $pdo->lastInsertId();
    }

    // Get or insert new subject
    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->execute([$newSubject]);
    $newSubjectId = $stmt->fetchColumn();

    if (!$newSubjectId) {
        $stmt = $pdo->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
        $stmt->execute([$newSubject]);
        $newSubjectId = $pdo->lastInsertId();
    }

    // Update the grade entry
    $stmt = $pdo->prepare("UPDATE grades 
                           SET student_id = ?, subject_id = ?, grade = ?
                           WHERE student_id = ? AND subject_id = ?");
    $stmt->execute([$newStudentId, $newSubjectId, $newGrade, $originalStudentId, $originalSubjectId]);

    header("Location: index.php");
    exit;
}
?>
