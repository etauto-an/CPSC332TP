<?php
// Include database connection
include_once 'db_connect.php';

// Initialize variables to hold schedules and error messages
$schedules = [];
$error = "";
$filterSchedules = [];

// Fetch all professor schedules
try {
   $queryAll = "SELECT Section.ProfSSN AS ProfessorSSN, 
                    Course.Title AS CourseTitle, 
                    Section.Classroom, 
                    GROUP_CONCAT(DISTINCT SectionMeetingDays.Day 
                                 ORDER BY CASE SectionMeetingDays.Day
                                     WHEN 'Monday' THEN 1
                                     WHEN 'Tuesday' THEN 2
                                     WHEN 'Wednesday' THEN 3
                                     WHEN 'Thursday' THEN 4
                                     WHEN 'Friday' THEN 5
                                     WHEN 'Saturday' THEN 6
                                     WHEN 'Sunday' THEN 7
                                 END SEPARATOR ', ') AS MeetingDays, 
                    Section.StartTime, 
                    Section.EndTime
             FROM Section
             JOIN Course ON Section.CourseNumber = Course.CourseNumber
             LEFT JOIN SectionMeetingDays ON Section.CourseNumber = SectionMeetingDays.CourseNumber 
                                           AND Section.SectionNumber = SectionMeetingDays.SectionNumber
             GROUP BY Section.ProfSSN, Course.Title, Section.Classroom, Section.StartTime, Section.EndTime";
    $stmt = $pdo->query($queryAll);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store all schedules
} catch (PDOException $e) {
    // Catch any errors fetching all schedules
    $error = "Error fetching all schedules: " . $e->getMessage();
}

// Handle form submission for filtering schedules by SSN
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profSSN = $_POST['profSSN']; // Get SSN input from form

    // Validate the input SSN (check if it's 9 digits)
    if (!empty($profSSN) && preg_match('/^\d{9}$/', $profSSN)) {
        try {
            // Query to filter schedules by the professor's SSN
    $queryFiltered = "SELECT Section.ProfSSN AS ProfessorSSN, 
                             Course.Title AS CourseTitle, 
                             Section.Classroom, 
                             GROUP_CONCAT(DISTINCT SectionMeetingDays.Day 
                                          ORDER BY CASE SectionMeetingDays.Day
                                              WHEN 'Monday' THEN 1
                                              WHEN 'Tuesday' THEN 2
                                              WHEN 'Wednesday' THEN 3
                                              WHEN 'Thursday' THEN 4
                                              WHEN 'Friday' THEN 5
                                              WHEN 'Saturday' THEN 6
                                              WHEN 'Sunday' THEN 7
                                          END SEPARATOR ', ') AS MeetingDays, 
                             Section.StartTime, 
                             Section.EndTime
                      FROM Section
                      JOIN Course ON Section.CourseNumber = Course.CourseNumber
                      LEFT JOIN SectionMeetingDays ON Section.CourseNumber = SectionMeetingDays.CourseNumber 
                                                    AND Section.SectionNumber = SectionMeetingDays.SectionNumber
                      WHERE Section.ProfSSN = ?
                      GROUP BY Section.ProfSSN, Course.Title, Section.Classroom, Section.StartTime, Section.EndTime";

            $stmt = $pdo->prepare($queryFiltered);
            $stmt->execute([$profSSN]); // Execute the query with the provided SSN
            $filterSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store filtered schedules
        } catch (PDOException $e) {
            // Catch any errors fetching filtered schedules
            $error = "Error fetching filtered schedules: " . $e->getMessage();
        }
    } else {
        // If SSN is invalid, display an error message
        $error = "Please enter a valid 9-digit Professor SSN.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Schedule</title>
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
    <h1>Professor Schedule</h1>

    <!-- Form to filter schedules by professor SSN -->
    <div class="form-container">
        <form method="POST">
            <label for="profSSN">Enter Professor SSN:</label>
            <input type="text" id="profSSN" name="profSSN" placeholder="Enter 9-digit SSN">
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Display error message if there's any -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Display filtered schedules if available -->
    <?php if (!empty($filterSchedules)): ?>
        <h2>Filtered Schedule for Professor SSN: <?= htmlspecialchars($profSSN) ?></h2>
        <table>
            <tr>
                <th>Professor SSN</th>
                <th>Course Title</th>
                <th>Classroom</th>
                <th>Meeting Days</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
            <?php foreach ($filterSchedules as $schedule): ?>
                <tr>
                    <td><?= htmlspecialchars($schedule['ProfessorSSN']) ?></td>
                    <td><?= htmlspecialchars($schedule['CourseTitle']) ?></td>
                    <td><?= htmlspecialchars($schedule['Classroom']) ?></td>
                    <td><?= htmlspecialchars($schedule['MeetingDays']) ?></td>
                    <td><?= htmlspecialchars($schedule['StartTime']) ?></td>
                    <td><?= htmlspecialchars($schedule['EndTime']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Display all professor schedules -->
    <h2>All Professor Schedules</h2>
    <table>
        <tr>
            <th>Professor SSN</th>
            <th>Course Title</th>
            <th>Classroom</th>
            <th>Meeting Days</th>
            <th>Start Time</th>
            <th>End Time</th>
        </tr>
        <?php foreach ($schedules as $schedule): ?>
            <tr>
                <td><?= htmlspecialchars($schedule['ProfessorSSN']) ?></td>
                <td><?= htmlspecialchars($schedule['CourseTitle']) ?></td>
                <td><?= htmlspecialchars($schedule['Classroom']) ?></td>
                <td><?= htmlspecialchars($schedule['MeetingDays']) ?></td>
                <td><?= htmlspecialchars($schedule['StartTime']) ?></td>
                <td><?= htmlspecialchars($schedule['EndTime']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back Button -->
    <a href="professor.php" class="back-button">Back to Portal</a>
</body>
</html>
