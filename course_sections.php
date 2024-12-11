<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the course number from the form
    $course_number = $_POST['course_number'];

    // Validate input
    if (!empty($course_number)) {
        // Prepare the SQL query to fetch course sections
        $query = "SELECT SectionNumber, Classroom, MeetingDays, StartTime, EndTime, COUNT(Enrollment.StudentID) AS Enrolled
                  FROM Sections
                  LEFT JOIN Enrollment
                  ON Sections.CourseID = Enrollment.CourseID AND Sections.SectionNumber = Enrollment.SectionNumber
                  WHERE Sections.CourseID = ?
                  GROUP BY Sections.SectionNumber";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $course_number);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            echo "<h2>Course Sections</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Section Number</th>
                    <th>Classroom</th>
                    <th>Meeting Days</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Enrolled Students</th>
                  </tr>";
            // Display the results in a table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['SectionNumber']}</td>
                        <td>{$row['Classroom']}</td>
                        <td>{$row['MeetingDays']}</td>
                        <td>{$row['StartTime']}</td>
                        <td>{$row['EndTime']}</td>
                        <td>{$row['Enrolled']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No sections found for the given course number.</p>";
        }

        // Free the result set
        $result->free();
    } else {
        echo "<p>Please enter a valid course number.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Sections</title>
</head>
<body>
    <form method="POST">
        <label for="course_number">Enter Course Number:</label>
        <input type="text" id="course_number" name="course_number" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
