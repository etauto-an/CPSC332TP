<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the course number and section number from the form
    $course_number = $_POST['course_number'];
    $section_number = $_POST['section_number'];

    // Validate input
    if (!empty($course_number) && !empty($section_number)) {
        // Prepare the SQL query to fetch grade distribution
        $query = "SELECT Grade, COUNT(*) AS Count
                  FROM Enrollment
                  WHERE CourseID = ? AND SectionNumber = ?
                  GROUP BY Grade
                  ORDER BY Grade ASC";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ss", $course_number, $section_number);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            echo "<h2>Grade Distribution</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Grade</th>
                    <th>Count</th>
                  </tr>";
            // Display the results in a table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Grade']}</td>
                        <td>{$row['Count']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No grades found for the given course and section.</p>";
        }

        // Free the result set
        $stmt->free_result();
    } else {
        echo "<p>Please enter valid course and section numbers.</p>";
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
    <h1>Grade Distribution</h1>
    <form method="POST">
        <label for="course_number">Course Number:</label>
        <input type="text" id="course_number" name="course_number" required>
        <br><br>
        <label for="section_number">Section Number:</label>
        <input type="text" id="section_number" name="section_number" required>
        <br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
