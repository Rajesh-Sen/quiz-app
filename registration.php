<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz App Registration</title>
            <style>
            body{
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background: linear-gradient(135deg, #6a11cb, #2575fc); 
                font-family: Arial, sans-serif;
            }
            form{
                width: 610px;
                padding: 30px;
                border-radius: 20px;
                background: #ffffff;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            }
            h2 {
                margin-bottom: 25px;
                font-size: 28px;
                color: #333;
                text-align: center;
                font-family: "Lucida Handwriting", cursive;
            }
            .group{
                display: flex;
                gap: 20px;
                margin-bottom: 16px;
            }
            .field {
                display: flex;
                flex-direction: column; 
                flex: 1; 
            }
            .field label {
                text-align: left;
                font-weight: bold;
                font-size: 15px;
                margin-bottom: 5px;
            }
            .field input {
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 14px;
                box-shadow: 0px 0px 5px #df29ffff;
            }
            .myButton {
                display: inline-block;
                width: 100%;
                margin-top: 20px;
                padding: 12px;
                border-radius: 5px;
                border: none;
                background: linear-gradient(to right, #6a11cb, #2575fc);
                color: white;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                transition: 0.3s;
            }
            .myButton:hover {
                background: linear-gradient(to right, #2575fc, #6a11cb);
            }
            p {
                text-align: center;
                margin-top: 15px;
            }
            p a {
                color: #2575fc;
                text-decoration: none;
                font-weight: bold;
            }
            p a:hover {
                text-decoration: underline;
            }
            .error {
                text-align: center;
                color: red;
                margin-bottom: 10px;
                font-weight: bold;
            }
            .success {
                text-align: center;
                color: green;
                margin-bottom: 10px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <?php
        $success = $error = "";
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $host = "localhost";
            $username = "root";
            $password = "rajesh@08072005";
            $db = "Quizes";

            try{
                $connection = new PDO("mysql:host=$host;dbname=$db",$username,$password);
                $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                $first = trim($_POST['first_name']);
                $last = trim($_POST['last_name']);
                $email = trim($_POST['email']);
                $mobile = trim($_POST['mobile']);
                $pwd = $_POST['password'];
                $c_pwd = $_POST['cpassword'];

                if($pwd !== $c_pwd){
                    $error = "Passwords do not match!";
                } 
                else {
                    $stmt = $connection->prepare("SELECT * FROM Details WHERE email = ?");
                    $stmt->execute([$email]);
                    if($stmt->rowCount() > 0){
                        $error = "Email already registered!";
                    } 
                    else {
                        $hashed = password_hash($pwd, PASSWORD_DEFAULT);
                        $stmt = $connection->prepare("
                            INSERT INTO Details (first_name, last_name, email, mobile, password) 
                            VALUES (:first, :last, :email, :mobile, :password)");
                        $stmt->execute([
                            ':first' => $first,
                            ':last' => $last,
                            ':email' => $email,
                            ':mobile' => $mobile,
                            ':password' => $hashed
                        ]);

                        $success = "Registration successful! <a href='login.php'>Login Here</a>";
                    }
                }
            }catch(PDOException $e){
                $error = "Connection failed: ".$e->getMessage();
            }
        }
        ?>

        <form action="" method="post">
            <h2>Registration Form</h2>
            <?php if($error) echo "<div class='error'>$error</div>"; ?>
            <?php if($success) echo "<div class='success'>$success</div>"; ?>
            <div class="group">
                <div class="field">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>
            <div class="group">
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field">
                    <label>Mobile</label>
                    <input type="text" name="mobile" required>
                </div>
            </div>
            <div class="group">
                <div class="field">
                    <label>Create Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field">
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" required>
                </div>
            </div>
            <p>Already have an account? <a href="login.php">Login</a></p>
            <button type="submit" class="myButton">Register</button>
        </form>
    </body>
</html>