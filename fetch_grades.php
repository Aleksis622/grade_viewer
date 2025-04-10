<?php  

require_once "config.php";

//vai kads ir izvelejies specifisku studentu? ja jā, tad atceramies kurs tas ir
$studentFilter = isset($_GET['student']) ? $_GET['student'] : '';
//vai kads ir izvelejies specifisku prieksmetu? ja jā, tad atceramies kurs tas ir
$subjectFilter = isset($_GET['subject']) ? $_GET['subject'] : '';


$sql = "

SELECT 
    students.name AS student_name, 
    subjects.subject_name,
    grades.grade
FROM grades
    JOIN students ON grades.student_id = students.id
    JOIN subjects ON grades.subject_id = subjects.id
    

";
//  VAI nu mes meklejam specifisku audzekni un/vai specifisku tikai prieksmetu
$conditions = [];// seit es saglabasu visus musu mazos "noteikumus"
$params = [];// un seit es saglabasu visus studenta vardus vai prieksmetus ko lietotajs ir izvelejies

// ja kads izvelas tikai vienu noteiktu audzekni tad paradam tikai vinu
if(!empty($studentFilter)){
    $conditions[] = "students.name = :student";
    $params[':student'] =$studentFilter;  
}
// ja kads izvelas tikai vienu noteiktu prieksmetu tad paradam tikai to prieksmetu
if(!empty($subjectFilter)){
    $conditions[] = "subjects.name = :subject";
    $params[':subject'] =$subjectFilter;  
}
// ja ir kadi noteikumi izveleti (audzeknis vai pireksmetu) 
if(!empty($conditions)){
    // notiekumi tiek ideoti PC kas attelos attiecigo audzekni vai prieksmetu
    $sql .= "WHERE" . implode("AND" , $conditions);
}

$sql .= "ORDER BY students.name, subjects.subject_name";


$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC);


$students = $pdo->query("SELECT name FORM students ORDER BY  name")->fetchAll(PDO::FETCH_COLUMN);
$subjects = $pdo->query("SELECT subject_name FORM subjects ORDER BY  subject_name")->fetchAll(PDO::FETCH_COLUMN);


?>