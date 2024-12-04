<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the student ID from the form
    $student_id = $_POST['student_id'];

    // Validate input
    if (!empty($student_id)) {
        // Prepare the SQL query to fetch courses and grades
        $query = "SELECT Courses.CourseTitle, Enrollment.Grade
                  FROM Enrollment
                  JOIN Courses ON Enrollment.CourseID = Courses.CourseID
                  WHERE Enrollment.StudentID = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $student_id);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            echo "<h2>Student Courses and Grades</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Course Title</th>
                    <th>Grade</th>
                  </tr>";
            // Display the results in a table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CourseTitle']}</td>
                        <td>{$row['Grade']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No courses or grades found for the given student ID.</p>";
        }

        // Free the result set
        $stmt->close();
    } else {
        echo "<p>Please enter a valid Student ID.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Courses and Grades</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
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
    </style>
</head>
<body>
    <h1>Student Courses and Grades</h1>
    <form method="POST">
        <label for="student_id">Enter Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
