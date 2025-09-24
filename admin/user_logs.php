<?php
// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Surrogate-Control: no-store');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Under Maintenance</title>
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h1 {
            color: #333;
            margin-top: 20px;
        }
        p {
            color: #666;
        }
        .return-button {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .return-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <img src="https://i.gifer.com/ZZ5H.gif" alt="Under Maintenance" width="300">
    <h1>We'll be back soon!</h1>
    <p>Our site is currently undergoing scheduled maintenance.<br>Please check back later.</p>

    <!-- Return Button -->
    <a href="javascript:history.back()" class="return-button">Return</a>
</body>
</html>
