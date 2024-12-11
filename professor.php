<?php
// Include database connection and fetch schedules module
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Portal</title>
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
    <h1>Professor Portal</h1>
    <div class="button-container">
        <!-- Buttons to navigate to other pages -->
        <a href="professor_schedule.php" class="portal-button">View Professor Schedule</a>
        <a href="grade_distribution.php" class="portal-button">View Grade Distribution</a>
    </div>
    <!-- Back to Home -->
    <a href="../index.html" class="back-button">Back to Home</a>
</body>
</html>
