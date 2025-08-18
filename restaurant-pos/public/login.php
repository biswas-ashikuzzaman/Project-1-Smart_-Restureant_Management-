<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <title>Login - POS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <form id="loginForm" method="POST" action="../api/login.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e){
        e.preventDefault();

        const formData = new FormData(this);

        fetch('../api/login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if(data.status === 'success'){
                window.location.href = 'index.php';
            }
        })
        .catch(error => alert('Error: ' + error));
    });
    </script>
</body>
</html>
