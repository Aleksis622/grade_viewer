<?php include 'fetch_grades.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Grade Viewer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>📚 Student Grade Viewer</h1>

<form method="get" style="text-align: center; margin-bottom: 30px;">
  <label for="student">👤 Student:</label>
  <select name="student" id="student">
    <option value="">All Students</option>
    <?php foreach ($students as $student): ?>
      <option value="<?= htmlspecialchars($student) ?>" <?= ($student === $studentFilter) ? 'selected' : '' ?>>
        <?= htmlspecialchars($student) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label for="subject">📘 Subject:</label>
  <select name="subject" id="subject">
    <option value="">All Subjects</option>
    <?php foreach ($subjects as $subject): ?>
      <option value="<?= htmlspecialchars($subject) ?>" <?= ($subject === $subjectFilter) ? 'selected' : '' ?>>
        <?= htmlspecialchars($subject) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn" type="submit">🔍 Filter</button>
  <a href="index.php" class="btn">🔄 Reset</a>
</form>

<h2 style="text-align:center; margin-top: 50px;">➕ Add New Student & Grade</h2>

<form method="post" action="add_student.php" style="max-width: 500px; margin: 0 auto; text-align: center;">
  <input type="text" name="new_student" placeholder="Student Name" required style="padding: 10px; margin-bottom: 10px; width: 100%;">

  <select name="subject" style="padding: 10px; margin-bottom: 10px; width: 100%;">
    <option value="">Choose Existing Subject</option>
    <?php foreach ($subjects as $subject): ?>
      <option value="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></option>
    <?php endforeach; ?>
  </select>

  <input type="text" name="new_subject" placeholder="Or Enter New Subject" style="padding: 10px; margin-bottom: 10px; width: 100%;">

  <input type="number" name="grade" min="1" max="10" placeholder="Grade (1–10)" required style="padding: 10px; margin-bottom: 10px; width: 100%;">

  <button class="btn" type="submit">✅ Add Student & Grade</button>
</form>
<table>
  <thead>
    <tr>
      <th>Student</th>
      <th>Subject</th>
      <th>Grade</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($grades) > 0): ?>
      <?php foreach ($grades as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= htmlspecialchars($row['subject_name']) ?></td>
          <td><?= htmlspecialchars($row['grade']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="3" style="text-align:center;">No data found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>