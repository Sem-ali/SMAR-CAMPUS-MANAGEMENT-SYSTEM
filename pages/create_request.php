<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f4f7f6; color: #333; display: flex; justify-content: center; min-height: 100vh; padding: 40px 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); padding: 40px; width: 100%; max-width: 600px; text-align: center; }
        h2, h3 { color: #1a3a5c; margin-bottom: 20px; }
        form { display: flex; flex-direction: column; align-items: center; text-align: center; }
        label { font-weight: 600; color: #475569; margin: 10px 0 5px; }
        input[type="text"], textarea { width: 100%; max-width: 400px; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-family: 'Inter', sans-serif; background: #f8fafc; box-sizing: border-box; margin-bottom: 15px; }
        button { background: #3498db; color: white; border: none; padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: bold; margin-top: 10px; transition: background 0.2s; }
        button:hover { background: #2980b9; }
        a { color: #3498db; text-decoration: none; font-weight: 600; display: inline-block; margin-top: 20px; }
        a:hover { text-decoration: underline; }
        #search { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Request</h2>
        
        <h3>Search Requests</h3>
        <input type="text" id="search" placeholder="Search by title...">
        <div id="result"></div>

        <form action="process/create_request_process.php" method="POST">
            <label>Title:</label>
            <input type="text" name="title" required>
            
            <label>Description:</label>
            <textarea name="description" required rows="4"></textarea>
            
            <button type="submit">Submit</button>
        </form>

        <a href="dasboard/student_dasboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
