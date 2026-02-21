<?php
session_start();
if (!isset($_SESSION["reset_studentid"])) {
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Serif, sans-serif;
            background-image: url('background.jpg'); 
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
            image-rendering: crisp-edges;
            opacity: 0;
            animation: fadeIn 2s forwards; /* Animation added here */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @font-face {
    font-family: 'Futura';
    src: url('fonts/Futura-Medium.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

        .welcome-msg {
            font-family: 'Futura', sans-serif;
            font-size: 40px;
            color: #0D2B66; 
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 0;
            text-shadow: 5px 5px 5px rgba(0, 0, 0, 0.3);
        }

        .login-container {
            background-color: #f8f8ff; 
            padding: 20px;
            border-radius: 5px;
            border: black;
            box-shadow: 0 50px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
            max-width: 100%;
            animation: slideIn 1s ease-out forwards; /* Slide-in animation added here */
            opacity: 0; /* Initially hidden */
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px); /* Start position */
                opacity: 0;
            }
            to {
                transform: translateY(0); /* End position */
                opacity: 1;
            }
        }

        h2, input[type="text"], input[type="password"], button[type="submit"] {
            font-family: 'Futura', sans-serif;
            font-size: 25px; 
            margin-bottom: 30px;
            font-weight: Bold;
        }

        input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid blue; 
            border-radius: 8px;
            font-size: 16px;
            background-color: #F9FBFF;
            color: blue; 
            outline: none;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="password"]:focus {
            border-color: white;
            background-color: #EAF4F3;
            color: blue; 
        }

        button {
            width: 100%;
  padding: 16px;
  background-color: #0D2B66; 
  color: white; 
  border: none;
  border-radius: 8px;
  font-size: 18px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-weight: bold;
  display: block;
margin-bottom: 10px;
}


button:hover {
    background-color: #ECAA05;
    transform: scale(1.1); /* Scale the button up */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition for background color and scale */
}
        

        .forgot-password {
            font-family: 'Futura', sans-serif;
            color: #002366; 
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #FFD700;
        }

        .forgot-password:active {
            color: black;
        }
    </style>
</head>
<body>
    <div class="welcome-msg">Reset Your Password</div>
    <div class="login-container">
        <h2>Enter Your New Password</h2>
        <form method="POST" action="../controller/AccountController.php">
            <input type="password" name="new_password" placeholder="New Password" required><br>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
            <button type="submit" name="reset_password">Submit</button>
        </form>
        <p><a class="forgot-password" href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
