<?php
session_start();

if (isset($_POST['username']) && !empty($_POST['username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['username']);
    $_SESSION['score'] = 0;
    $_SESSION['cleared'] = []; 
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CTF Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h1>CTF Challenge Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter your name" required>
        <button type="submit">Enter</button>
    </form>
    <p style="margin-top:20px; color:#00ff41;">Developed by: DrakeDev23</p>
</div>
</body>
</html>