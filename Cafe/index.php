<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="css/index.css" />
    <script src="ajax/main.js" defer></script>
    <title>Log in</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="images/logo.jpg?=2" />
</head>
<body>
    <div class="wrapper">
        <form method="POST" onsubmit="return loginBtn()">
            <h1>Better Brews Cafe</h1>
            <div class="input-box">
                <input id="username" type="text" placeholder="Username" required />
                <i class="fa fa-user"></i>
            </div>
            <div class="input-box" style="position: relative;">
                <input id="password" type="password" placeholder="Password" class="passwordf" required />
                <i id="togglePassword" class="fa-solid fa-eye-low-vision" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
            </div>
            <div class="rememberme">
                <label><input id="rememberme" type="checkbox" /> Remember Me</label>
                <a href="forgotpossword.php">Forgot Password</a>
            </div>
            <button id="loginbutton" type="submit" class="btn">Login</button>
            <div id="errorMsg" class="error-msg"></div>
        </form>
    </form>
</body>
</html>