<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }
        h1 {
            color: #0056b3;
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
        .content-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Student Portal</h1>
    <form method="POST">
        <label for="action">Choose an action:</label>
        <select name="action" id="action">
            <option value="enrollment">View Course Sections</option>
            <option value="grades">View Grades</option>
        </select>
        <button type="submit">Go</button>
    </form>
    <div class="content-container" id="content">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'];
            // Dynamically include content based on the selected action
            if ($action == 'enrollment') {
                echo "<h2>Course Sections</h2>";
                include 'course_sections.php';
            } elseif ($action == 'grades') {
                echo "<h2>Student Grades</h2>";
                include 'student_course.php';
            } else {
                echo "<p>Please select a valid action from the dropdown above.</p>";
            }
        } else {
            echo "<p>Select an action to get started.</p>";
        }
        ?>
    </div>
    <!-- Back to Home -->
    <a href="../index.html" class="back-button">Back to Home</a>
</body>
</html>
