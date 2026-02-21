<?php
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* Reset and Global Styling */
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        /* Body Styling */
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
          opacity: 0;
          animation: fadeIn 1.5s ease-in-out forwards;
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
          }
          to {
            opacity: 1;
          }
        }
      
        /* Forgot Password Container */
        .forgot-container {
          background-color: #f8f8ff;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
          text-align: center;
          width: 350px;
          max-width: 100%;
          transform: translateY(-20px);
          opacity: 0;
          animation: slideIn 1s ease-out 0.5s forwards;
        }

        @keyframes slideIn {
          from {
            transform: translateY(-20px);
            opacity: 0;
          }
          to {
            transform: translateY(0);
            opacity: 1;
          }
        }

        @font-face {
    font-family: 'Futura';
    src: url('fonts/Futura-Medium.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

        /* Header Styling */
        h2, button[type="submit"] {
        font-family: 'Futura', sans-serif;
          font-size: 25px;
          color: black;
          margin-bottom: 20px;
          font-weight: bold;
        }

        /* Instruction Message */
        .instructions {
          font-family: 'Futura', sans-serif;
          font-size: 14px;
          color: red;
          margin-bottom: 15px;
          font-weight: bold;
        }

        /* Reminder Message */
        .reminder {
        font-family: 'Futura', sans-serif;
        font-size: 12px;
        color: black;
        margin-bottom: 15px;
        font-weight: normal;
        padding: 10px;
        border: 2px solid #002366; /* Border around reminder */
        border-radius: 8px;
        background-color: #F1F1F1;
          opacity: 0;
        animation: fadeInReminder 2s ease-out 1.5s forwards; /* Animation with delay */
        }

          /* Animation for Reminder */
        @keyframes fadeInReminder {
        from {
        opacity: 0;
        transform: translateY(-20px);
        }
        to {
        opacity: 1;
        transform: translateY(0);
            }
          }

        /* Show Reminder when Input is Focused */
        input[type="text"]:focus + .reminder {
          opacity: 1;
          visibility: visible;
          transform: translateY(0);
        }

        /* Input Field */
        input[type="text"] {
          width: 100%;
          padding: 14px;
          margin-bottom: 10px;
          border: 1px solid blue;
          border-radius: 8px;
          font-size: 16px;
          background-color: #F9FBFF;
          color: blue;
          outline: none;
          transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        input[type="text"]:focus {
          border-color: white;
          background-color: #EAF4F3;
          color: blue;
        }

        /* Submit Button */
        button[type="submit"] {
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
          transform: scale(1.1);
          transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Back to Login Link */
        .back-to-login {
            font-family: 'Futura', sans-serif;
          display: inline-block;
          margin-top: 10px;
          color: #002366;
          text-decoration: none;
          font-size: 14px;
          font-weight: bold;
          transition: color 0.3s ease;
        }

        .back-to-login:hover {
          color: #FFD700;
        }

        .back-to-login:active {
          color: black;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <form method="POST" action="../controller/AccountController.php">
            <div class="instructions">
                Please input your JRU Student ID.
            </div>
            <input type="text" name="id" placeholder="Enter Student ID" required>
            
            <div class="reminder">
                Reminder: Changing your password here will NOT change your email account password or your official JRU account email password.
            </div>

            <button type="submit" name="forgot_password">Submit</button>
        </form>
        <a href="login.php" class="back-to-login">Back to Login</a>
    </div>
</body>
</html>
