<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newStudent = trim($_POST['new_student']);
    $selectedSubject = trim($_POST['subject']);
    $newSubject = trim($_POST['new_subject']);
    $grade = intval($_POST['grade']);
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $newStudent = trim($_POST['new_student'] ?? '');
        $newSubject = trim($_POST['new_subject'] ?? '');
        $newGrade = $_POST['new_grade'] ?? '';
    
        // ✅ Validate that names contain only letters and spaces
        if (!preg_match("/^[a-zA-Z\s]+$/", $newStudent)) {
            die("❌ Invalid student name. Only letters and spaces are allowed.");
        }
    
        if (!preg_match("/^[a-zA-Z\s]+$/", $newSubject)) {
            die("❌ Invalid subject name. Only letters and spaces are allowed.");
        }
    
       
    }
   
    // Determine which subject to use
    $subjectToUse = !empty($newSubject) ? $newSubject : $selectedSubject;

    if (!empty($newStudent) && !empty($subjectToUse) && $grade >= 1 && $grade <= 10) {
        // Insert student
        $stmt = $pdo->prepare("INSERT INTO students (name) VALUES (:name)");
        $stmt->execute([':name' => $newStudent]);
        $studentId = $pdo->lastInsertId();

        // Check if subject exists
        $stmt = $pdo->prepare("SELECT id FROM subjects WHERE subject_name = :subject");
        $stmt->execute([':subject' => $subjectToUse]);
        $subjectId = $stmt->fetchColumn();

        // If not found, insert the subject
        if (!$subjectId) {
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_name) VALUES (:subject)");
            $stmt->execute([':subject' => $subjectToUse]);
            $subjectId = $pdo->lastInsertId();
        }

        // Insert grade
        $stmt = $pdo->prepare("INSERT INTO grades (student_id, subject_id, grade) VALUES (:student_id, :subject_id, :grade)");
        $stmt->execute([
            ':student_id' => $studentId,
            ':subject_id' => $subjectId,
            ':grade' => $grade
        ]);
    }
}

header("Location: index.php");
exit;

