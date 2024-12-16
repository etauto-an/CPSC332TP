<?php
// Include database connection
include_once 'db_connect.php';

// Initialize variables to hold sections and error messages
$sections = [];
$error = "";
$filterSections = [];

// Fetch all course sections
try {
    $queryAll = "SELECT
                s.CourseNumber,
                s.SectionNumber,
                s.Classroom,
                GROUP_CONCAT(DISTINCT sm.Day 
                             ORDER BY CASE sm.Day
                                 WHEN 'Monday' THEN 1
                                 WHEN 'Tuesday' THEN 2
                                 WHEN 'Wednesday' THEN 3
                                 WHEN 'Thursday' THEN 4
                                 WHEN 'Friday' THEN 5
                                 WHEN 'Saturday' THEN 6
                                 WHEN 'Sunday' THEN 7
                             END SEPARATOR ', ') AS MeetingDays,
                CONCAT(TIME_FORMAT(s.StartTime, '%H:%i'), ' - ', TIME_FORMAT(s.EndTime, '%H:%i')) AS MeetingTime,
                COUNT(DISTINCT er.CWID) AS NumStudentsEnrolled
             FROM
                Section s
                LEFT JOIN SectionMeetingDays sm 
                    ON s.CourseNumber = sm.CourseNumber AND s.SectionNumber = sm.SectionNumber
                LEFT JOIN (
                    SELECT DISTINCT CourseNumber, SectionNumber, CWID
                    FROM EnrollmentRecords
                ) er 
                    ON s.CourseNumber = er.CourseNumber AND s.SectionNumber = er.SectionNumber
             GROUP BY
                s.CourseNumber, s.SectionNumber, s.Classroom, s.StartTime, s.EndTime
             ORDER BY
                s.CourseNumber, s.SectionNumber";
    $stmt = $pdo->query($queryAll);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store all sections
} catch (PDOException $e) {
    // Catch any errors fetching all sections
    $error = "Error fetching all sections: " . $e->getMessage();
}

// Handle form submission for filtering sections by course number
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courseNumber = $_POST['courseNumber']; // Get course number input from form

    // Validate the input course number (check if it's numeric)
    if (!empty($courseNumber) && is_numeric($courseNumber)) {
        try {
            // Query to filter sections by course number
            $queryFiltered = "SELECT
                     s.CourseNumber,
                     s.SectionNumber,
                     s.Classroom,
                     GROUP_CONCAT(DISTINCT sm.Day 
                                  ORDER BY CASE sm.Day
                                      WHEN 'Monday' THEN 1
                                      WHEN 'Tuesday' THEN 2
                                      WHEN 'Wednesday' THEN 3
                                      WHEN 'Thursday' THEN 4
                                      WHEN 'Friday' THEN 5
                                      WHEN 'Saturday' THEN 6
                                      WHEN 'Sunday' THEN 7
                                  END SEPARATOR ', ') AS MeetingDays,
                     CONCAT(TIME_FORMAT(s.StartTime, '%H:%i'), ' - ', TIME_FORMAT(s.EndTime, '%H:%i')) AS MeetingTime,
                     COUNT(DISTINCT er.CWID) AS NumStudentsEnrolled
                  FROM
                     Section s
                     LEFT JOIN SectionMeetingDays sm 
                         ON s.CourseNumber = sm.CourseNumber AND s.SectionNumber = sm.SectionNumber
                     LEFT JOIN (
                         SELECT DISTINCT CourseNumber, SectionNumber, CWID
                         FROM EnrollmentRecords
                     ) er 
                         ON s.CourseNumber = er.CourseNumber AND s.SectionNumber = er.SectionNumber
                  WHERE
                     s.CourseNumber = ?
                  GROUP BY
                     s.CourseNumber, s.SectionNumber, s.Classroom, s.StartTime, s.EndTime
                  ORDER BY
                      s.CourseNumber, s.SectionNumber";

            $stmt = $pdo->prepare($queryFiltered);
            $stmt->execute([$courseNumber]); // Execute the query with the provided course number
            $filterSections = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store filtered sections
        } catch (PDOException $e) {
            // Catch any errors fetching filtered sections
            $error = "Error fetching filtered sections: " . $e->getMessage();
        }
    } else {
        // If course number is invalid, display an error message
        $error = "Please enter a valid course number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Sections</title>
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
    <h1>Course Sections</h1>

    <!-- Form to filter sections by course number -->
    <div class="form-container">
        <form method="POST">
            <label for="courseNumber">Enter Course Number:</label>
            <input type="text" id="courseNumber" name="courseNumber" placeholder="Enter course number">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Display error message if there's any -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Display filtered sections if available -->
    <?php if (!empty($filterSections)): ?>
        <h2>Filtered Sections for Course Number: <?= htmlspecialchars($courseNumber) ?></h2>
        <table>
            <tr>
                <th>Course Number</th>
                <th>Section Number</th>
                <th>Classroom</th>
                <th>Meeting Days</th>
                <th>Meeting Time</th>
                <th>Students Enrolled</th>
            </tr>
            <?php foreach ($filterSections as $section): ?>
                <tr>
                    <td><?= htmlspecialchars($section['CourseNumber']) ?></td>
                    <td><?= htmlspecialchars($section['SectionNumber']) ?></td>
                    <td><?= htmlspecialchars($section['Classroom']) ?></td>
                    <td><?= htmlspecialchars($section['MeetingDays']) ?></td>
                    <td><?= htmlspecialchars($section['MeetingTime']) ?></td>
                    <td><?= htmlspecialchars($section['NumStudentsEnrolled']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Display all course sections -->
    <h2>All Course Sections</h2>
    <table>
        <tr>
            <th>Course Number</th>
            <th>Section Number</th>
            <th>Classroom</th>
            <th>Meeting Days</th>
            <th>Meeting Time</th>
            <th>Students Enrolled</th>
        </tr>
        <?php foreach ($sections as $section): ?>
            <tr>
                <td><?= htmlspecialchars($section['CourseNumber']) ?></td>
                <td><?= htmlspecialchars($section['SectionNumber']) ?></td>
                <td><?= htmlspecialchars($section['Classroom']) ?></td>
                <td><?= htmlspecialchars($section['MeetingDays']) ?></td>
                <td><?= htmlspecialchars($section['MeetingTime']) ?></td>
                <td><?= htmlspecialchars($section['NumStudentsEnrolled']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back Button -->
    <a href="student.php" class="back-button">Back to Portal</a>
</body>
</html>
