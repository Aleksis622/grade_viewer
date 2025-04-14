<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student = $_POST['student'];
    $subject = $_POST['subject'];

    // Get student and subject IDs
    $stmt = $pdo->prepare("SELECT id FROM students WHERE name = ?");
    $stmt->execute([$student]);
    $studentId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = ?");
    $stmt->execute([$subject]);
    $subjectId = $stmt->fetchColumn();

    if ($studentId && $subjectId) {
        $stmt = $pdo->prepare("DELETE FROM grades WHERE student_id = ? AND subject_id = ?");
        $stmt->execute([$studentId, $subjectId]);
    }
}

header("Location: index.php");
exit;