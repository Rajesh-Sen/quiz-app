<?php
session_start(); 
$error = "";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $host = "localhost";
    $username = "root";
    $password = "rajesh@08072005";
    $db = "Quizes";

    try {
        $connection = new PDO("mysql:host=$host;dbname=$db",$username,$password);
        $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $email = trim($_POST['email']);
            $pwd = $_POST['password'];

            $stmt = $connection->prepare("SELECT * FROM Details WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($pwd, $user['password'])){
                $_SESSION['user'] = $user['name']; 
                header("Location: quiz.php"); 
                exit;
            } 
            else{
                $error = "Invalid email or password!";
            }
        } 
        else {
            $error = "Please enter email and password.";
        }
    } catch(PDOException $e){
        $error = "Connection failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App Login</title>
    <style>
      body {
        margin: 0;
        height: 100vh;
        display: flex;
        font-family: Arial, sans-serif;
      }
      .welcome {
        flex: 1;
        background: linear-gradient(to bottom right, #302df9ff, #068cfaff);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 40px;
        text-align: center;
      }
      .welcome h1 { font-size: 40px; margin-bottom: 20px; }
      .welcome p { font-size: 18px; max-width: 300px; }

      .login {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f4f4f4;
      }

      .login-box {
        width: 350px;
        padding: 30px;
        border-radius: 20px;
        background: white;
        box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
      }

      .login-box h2 {
        text-align: center;
        margin-bottom: 10px;
        font-family: "Lucida Handwriting", sans-serif;
      }

      .login-box label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
      }

      .login-box input[type="email"],
      .login-box input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #0688fa;
        border-radius: 5px;
        box-shadow: 0px 0px 5px #29bbff;
      }

      .login-box p {
        margin-top: 10px;
        text-align: center;
        font-style: italic;
      }

      .login-box a { color: #0688fa; cursor: pointer; }

      .myButton {
        width: 100%;
        padding: 15px;
        background: linear-gradient(to right, #2dabf9, #0688fa);
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
      }
      .myButton:hover { opacity: 0.9; }

      .error-msg {
        color: red;
        text-align: center;
        margin-bottom: 10px;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <div class="welcome">
      <h1>Welcome Back!</h1>
      <p>Log in to access your account and continue the quiz.</p>
    </div>

    <div class="login">
      <div class="login-box">
        <h2>Login Form</h2>

        <?php if($error != ""): ?>
          <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="post">
          <label for="email">EMAIL :</label>
          <input type="email" placeholder="Enter your email" name="email" required>

          <label for="password">PASSWORD :</label>
          <input type="password" placeholder="Enter your password" name="password" required>

          <p>Don't have an account? <a href="registration.php">Register</a></p>

          <button type="submit" class="myButton">Login</button>
        </form>
      </div>
    </div>
  </body>
</html>
