<?php
// Include database connection
include_once 'db_connect.php';

// Initialize variables
$records = [];
$gradeDistribution = [];
$error = "";

// Fetch all enrollment records, sorted by CourseNumber and SectionNumber
try {
    $queryAll = "SELECT * FROM EnrollmentRecords ORDER BY CourseNumber ASC, SectionNumber ASC";
    $stmt = $pdo->query($queryAll);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching enrollment records: " . $e->getMessage();
}

// Handle form submission for filtering and generating grade distribution
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseNumber = $_POST['courseNumber'];
    $sectionNumber = $_POST['sectionNumber'];

    // Validate the input fields
    if (!empty($courseNumber) && !empty($sectionNumber) && is_numeric($courseNumber) && is_numeric($sectionNumber)) {
        try {
            $queryGrades = "SELECT Grade, COUNT(*) AS StudentCount 
                            FROM EnrollmentRecords 
                            WHERE CourseNumber = ? AND SectionNumber = ?
                            GROUP BY Grade 
                            ORDER BY Grade ASC";
            $stmt = $pdo->prepare($queryGrades);
            $stmt->execute([$courseNumber, $sectionNumber]);
            $gradeDistribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = "Error fetching grade distribution: " . $e->getMessage();
        }
    } else {
        $error = "Please enter valid CourseNumber and SectionNumber.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Distribution</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }
        h1, h2 {
            color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ccc;
            text-align: left;
            padding: 8px;
        }
        table th {
            background-color: #0056b3;
            color: #fff;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        .back-button-container {
            margin-top: 20px;
            display: flex;
            justify-content: flex-start;
        }
        .back-button {
            padding: 10px 15px;
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <h1>Grade Distribution</h1>

    <!-- Form to filter records by CourseNumber and SectionNumber -->
    <div class="form-container">
        <form method="POST">
            <label for="courseNumber">Course Number:</label>
            <input type="text" id="courseNumber" name="courseNumber" placeholder="Enter Course Number">
            <label for="sectionNumber">Section Number:</label>
            <input type="text" id="sectionNumber" name="sectionNumber" placeholder="Enter Section Number">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Error message -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Grade Distribution for the selected CourseNumber and SectionNumber -->
    <?php if (!empty($gradeDistribution)): ?>
        <h2>Grade Distribution for CourseNumber: <?= htmlspecialchars($courseNumber) ?>, SectionNumber: <?= htmlspecialchars($sectionNumber) ?></h2>
        <table>
            <tr>
                <th>Grade</th>
                <th>Student Count</th>
            </tr>
            <?php foreach ($gradeDistribution as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Grade']) ?></td>
                    <td><?= htmlspecialchars($row['StudentCount']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- All records, sorted by CourseNumber and SectionNumber -->
    <h2>All Enrollment Records (Sorted by Course and Section Numbers)</h2>
    <table>
        <tr>
            <th>CWID</th>
            <th>CourseNumber</th>
            <th>SectionNumber</th>
            <th>Grade</th>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['CWID']) ?></td>
                <td><?= htmlspecialchars($record['CourseNumber']) ?></td>
                <td><?= htmlspecialchars($record['SectionNumber']) ?></td>
                <td><?= htmlspecialchars($record['Grade']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back Button -->
    <div class="back-button-container">
        <a href="professor.php" class="back-button">Back to Portal</a>
    </div>
</body>
</html>
