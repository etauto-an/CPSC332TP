<?php
// Include database connection
include_once 'db_connect.php';

// Initialize variables to hold courses and error messages
$courses = [];
$error = "";
$filterCourses = [];

// Fetch all student course records
try {
    $queryAll = "SELECT 
                    er.CWID,
                    c.CourseNumber,
                    c.Title AS CourseTitle,
                    er.Grade
                 FROM 
                    EnrollmentRecords er
                 JOIN Course c ON er.CourseNumber = c.CourseNumber
                 ORDER BY 
                    er.CWID, c.CourseNumber";
    $stmt = $pdo->query($queryAll);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store all student course records
} catch (PDOException $e) {
    // Catch any errors fetching all records
    $error = "Error fetching all student courses: " . $e->getMessage();
}

// Handle form submission for filtering courses by CWID
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cwid = $_POST['cwid']; // Get CWID input from form

    // Validate the input CWID (alphanumeric format)
    if (!empty($cwid)) {
        try {
            // Query to filter courses by CWID
            $queryFiltered = "SELECT 
                                er.CWID,
                                c.CourseNumber,
                                c.Title AS CourseTitle,
                                er.Grade
                              FROM 
                                EnrollmentRecords er
                              JOIN Course c ON er.CourseNumber = c.CourseNumber
                              WHERE 
                                er.CWID = ?
                              ORDER BY 
                                c.CourseNumber";
            $stmt = $pdo->prepare($queryFiltered);
            $stmt->execute([$cwid]); // Execute the query with the provided CWID
            $filterCourses = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store filtered results
        } catch (PDOException $e) {
            // Catch any errors fetching filtered records
            $error = "Error fetching courses for CWID: " . $e->getMessage();
        }
    } else {
        // If CWID is invalid, display an error message
        $error = "Please enter a valid CWID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses</title>
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
        .back-button {
            display: inline-block;
            margin-top: 20px;
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
    <h1>Student Courses</h1>

    <!-- Form to filter courses by CWID -->
    <div class="form-container">
        <form method="POST">
            <label for="cwid">Enter CWID:</label>
            <input type="text" id="cwid" name="cwid" placeholder="Enter CWID">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Display error message if there's any -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Display filtered courses if available -->
    <?php if (!empty($filterCourses)): ?>
        <h2>Filtered Courses for CWID: <?= htmlspecialchars($cwid) ?></h2>
        <table>
            <tr>
                <th>CWID</th>
                <th>Course Number</th>
                <th>Course Title</th>
                <th>Grade</th>
            </tr>
            <?php foreach ($filterCourses as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['CWID']) ?></td>
                    <td><?= htmlspecialchars($course['CourseNumber']) ?></td>
                    <td><?= htmlspecialchars($course['CourseTitle']) ?></td>
                    <td><?= htmlspecialchars($course['Grade']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Display all student courses -->
    <h2>All Student Courses</h2>
    <table>
        <tr>
            <th>CWID</th>
            <th>Course Number</th>
            <th>Course Title</th>
            <th>Grade</th>
        </tr>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['CWID']) ?></td>
                <td><?= htmlspecialchars($course['CourseNumber']) ?></td>
                <td><?= htmlspecialchars($course['CourseTitle']) ?></td>
                <td><?= htmlspecialchars($course['Grade']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back Button -->
    <a href="student.php" class="back-button">Back to Portal</a>
</body>
</html>
