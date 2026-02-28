<?php 
include("db.php");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $conn->real_escape_string($_POST['name']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO sign_up (Username, Email, Password, Role, is_deleted)
            VALUES ('$name', '$email', '$hashedPassword', 'user', 0)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Account created successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .login-box {
      background-color: white;
      padding: 35px 25px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-box h2 {
      margin-bottom: 25px;
      text-align: center;
      color: #333;
      font-size: 1.5rem;
    }

    .login-box input[type="text"],
    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 15px;
      margin: 8px 0 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      -webkit-appearance: none;
    }

    .login-box button {
      width: 100%;
      padding: 15px;
      background-color: #4CAF50;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: bold;
      margin-top: 10px;
    }

    .login-box button:hover {
      background-color: #45a049;
    }

    .login-box .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .login-box .footer a {
      color: #4CAF50;
      text-decoration: none;
      font-weight: 500;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 480px) {
      body {
        padding: 15px;
        align-items: flex-start;
        padding-top: 40px;
      }
      
      .login-box {
        padding: 25px 20px;
        margin: 0;
      }
      
      .login-box h2 {
        font-size: 1.3rem;
        margin-bottom: 20px;
      }
      
      .login-box input[type="text"],
      .login-box input[type="email"],
      .login-box input[type="password"] {
        padding: 12px;
        font-size: 16px;
        margin: 6px 0 12px;
      }
      
      .login-box button {
        padding: 14px;
        font-size: 16px;
        margin-top: 5px;
      }
      
      .login-box .footer {
        font-size: 13px;
        margin-top: 15px;
      }
    }

    @media (max-width: 360px) {
      body {
        padding: 10px;
        padding-top: 20px;
      }
      
      .login-box {
        padding: 20px 15px;
      }
      
      .login-box h2 {
        font-size: 1.2rem;
        margin-bottom: 15px;
      }
      
      .login-box input[type="text"],
      .login-box input[type="email"],
      .login-box input[type="password"] {
        padding: 10px;
        font-size: 14px;
        margin: 5px 0 10px;
      }
      
      .login-box button {
        padding: 12px;
        font-size: 14px;
      }
      
      .login-box .footer {
        font-size: 12px;
      }
    }

    @media (max-width: 320px) {
      .login-box {
        padding: 15px 12px;
      }
      
      .login-box h2 {
        font-size: 1.1rem;
      }
      
      .login-box input[type="text"],
      .login-box input[type="email"],
      .login-box input[type="password"] {
        padding: 8px;
        font-size: 13px;
      }
    }

    /* Touch target improvements */
    input, button {
      min-height: 44px;
    }

    a {
      min-height: 44px;
      line-height: 44px;
      display: inline-block;
    }

    /* Focus styles for accessibility */
    input:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
    }

    button:focus {
      outline: 2px solid #45a049;
      outline-offset: 2px;
    }

    /* Password match indicator */
    .password-match {
      border-color: #4CAF50 !important;
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
    }

    .password-mismatch {
      border-color: #ff4444 !important;
      box-shadow: 0 0 5px rgba(255, 68, 68, 0.3);
    }
  </style>
</head>
<body>

<div class="login-box">
  <h2>Sign Up</h2>
  <form action="signup.php" method="POST" onsubmit="return checkPasswords()">
    <input type="text" id="name" name="name" placeholder="Username" required>
    <input type="email" id="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" id="password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
    <button type="submit">Create Account</button>
  </form>
  <div class="footer">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

<script>
  function checkPasswords() {
    const pw = document.getElementById("password").value;
    const cpw = document.getElementById("confirm_password").value;
    if (pw !== cpw) {
      alert("Passwords do not match!");
      return false;
    }
    return true;
  }

  // Real-time password matching indicator
  document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function checkPasswordMatch() {
      if (password.value && confirmPassword.value) {
        if (password.value === confirmPassword.value) {
          confirmPassword.classList.add('password-match');
          confirmPassword.classList.remove('password-mismatch');
        } else {
          confirmPassword.classList.add('password-mismatch');
          confirmPassword.classList.remove('password-match');
        }
      } else {
        confirmPassword.classList.remove('password-match', 'password-mismatch');
      }
    }
    
    password.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);
  });
</script>

</body>
</html>