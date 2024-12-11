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
        .button-container {
            margin-top: 20px;
        }
        .portal-button {
            display: inline-block;
            margin: 10px 5px;
            padding: 15px 25px;
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
        }
        .portal-button:hover {
            background-color: #004494;
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
    <h1>Student Portal</h1>
    <div class="button-container">
        <!-- Buttons to navigate to other pages -->
        <a href="course_sections.php" class="portal-button">View Course Sections</a>
        <a href="student_course.php" class="portal-button">View Grades</a>
    </div>
    <!-- Back to Home -->
    <a href="../index.html" class="back-button">Back to Home</a>
</body>
</html>
