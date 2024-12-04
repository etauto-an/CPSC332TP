<?php
// Include the database connection file
include 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the SSN from the form input
    $ssn = $_POST['ssn'];

    // Validate input
    if (!empty($ssn)) {
        // Prepare the SQL query to fetch the professor's schedule
        $query = "SELECT CourseTitle, Classroom, MeetingDays, StartTime, EndTime 
                  FROM Sections
                  WHERE ProfessorSSN = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("s", $ssn);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            echo "<h2>Professor's Schedule</h2>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr>
                    <th>Course Title</th>
                    <th>Classroom</th>
                    <th>Meeting Days</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                  </tr>";
            // Display the results in a table
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['CourseTitle']}</td>
                        <td>{$row['Classroom']}</td>
                        <td>{$row['MeetingDays']}</td>
                        <td>{$row['StartTime']}</td>
                        <td>{$row['EndTime']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No schedule found for the given SSN.</p>";
        }

        // Free the result set
        $result->free_result();
    } else {
        echo "<p>Please enter a valid SSN.</p>";
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
        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1>Professor Schedule</h1>
    <form method="POST">
        <label for="ssn">Enter Professor SSN:</label>
        <input type="text" id="ssn" name="ssn" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
