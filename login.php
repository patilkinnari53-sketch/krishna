<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $conn->real_escape_string($_POST['email']);
  $password = $conn->real_escape_string($_POST['password']);

  $sql = "SELECT * FROM sign_up WHERE Email='$email' AND Password='$password'";
  $result = $conn->query($sql);

  if ($result && $result->num_rows == 1) {
    $user = $result->fetch_assoc();

    // 🔹 Store user basic info
    $_SESSION['email'] = $user['Email'];
    $_SESSION['username'] = $user['Username'];

    // 🔹 Store user_id (make sure sign_up has 'id' column)
    $_SESSION['user_id'] = $user['id'];

    // 🔹 Store contact number (make sure sign_up has 'ContactNumber' column)
    $_SESSION['contact_number'] = isset($user['ContactNumber']) ? $user['ContactNumber'] : "Not Provided";

    // 🔹 Defaults
    $_SESSION['profile_image'] = "https://cdn-icons-png.flaticon.com/512/149/149071.png";
    $_SESSION['credits'] = 100;
    $_SESSION['storage_used'] = "5 MB";

    // 🔹 Fetch latest donation for this user
    $uid = $_SESSION['user_id'];
    $donation_sql = "SELECT amount FROM donations WHERE user_id = '$uid' ORDER BY created_at DESC LIMIT 1";
    $donation_result = $conn->query($donation_sql);

    if ($donation_result && $donation_result->num_rows > 0) {
        $donation_row = $donation_result->fetch_assoc();
        $_SESSION['donation_amount'] = $donation_row['amount'];
    } else {
        $_SESSION['donation_amount'] = null;
    }

    echo "<script>alert('Successfully Logged In!'); window.location.href='index.php';</script>";
    exit;
  } else {
    echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
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
      padding: 30px 25px;
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
    .login-box input[type="password"] {
      width: 100%;
      padding: 15px;
      margin: 10px 0 20px;
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

    .login-box .footer p {
      margin: 8px 0;
    }

    /* Mobile Responsive Styles */
    @media (max-width: 480px) {
      body {
        padding: 15px;
        align-items: flex-start;
        padding-top: 50px;
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
      .login-box input[type="password"] {
        padding: 12px;
        font-size: 16px; /* Prevents zoom on iOS */
        margin: 8px 0 15px;
      }
      
      .login-box button {
        padding: 14px;
        font-size: 16px;
      }
      
      .login-box .footer {
        font-size: 13px;
        margin-top: 15px;
      }
    }

    @media (max-width: 360px) {
      body {
        padding: 10px;
        padding-top: 30px;
      }
      
      .login-box {
        padding: 20px 15px;
      }
      
      .login-box h2 {
        font-size: 1.2rem;
      }
      
      .login-box input[type="text"],
      .login-box input[type="password"] {
        padding: 10px;
        font-size: 14px;
      }
      
      .login-box button {
        padding: 12px;
      }
      
      .login-box .footer {
        font-size: 12px;
      }
    }

    /* Extra small devices */
    @media (max-width: 320px) {
      .login-box {
        padding: 15px 12px;
      }
      
      .login-box h2 {
        font-size: 1.1rem;
        margin-bottom: 15px;
      }
    }

    /* Improve touch targets */
    input, button {
      min-height: 44px; /* Minimum touch target size */
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
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <form action="login.php" method="POST">
      <input type="text" id="email" name="email" placeholder="Email" required>
      <input type="password" id="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <div class="footer">
      <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
      <p><a href="adminlogin.php">Admin Login</a></p>
    </div>
  </div>
</body>
</html>