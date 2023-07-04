<?php
    session_start();
    if(isset($_SESSION["user"])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
            if (isset($_POST["login"])) {
                $email = $_POST["email"];
                $password = $_POST["password"];

                if (empty($email) || empty($password)) {
                    array_push($errors, "All fields are required");
                }

                require_once "database.php";
                $sql = "SELECT * from users WHERE email = '$email'";
                $result  = mysqli_query($conn,$sql);
                $user = mysqli_fetch_array($result);
                if($user) {
                    if(password_verify($password, $user["password"])) {
                        session_start();
                        $_SESSION["user"] = "yes";
                        header("Location: index.php");
                        die();
                    } else {
                    echo "<div class='alert alert-danger'>Password does not match </div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email does not match </div>";
                }
            }
            ?>
        <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" value=<?php echo isset($_POST["email"]) ? $_POST["email"] : ""; ?>>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?>>
                </div>
                <div class="form-btn">
                    <input type="submit" value="Login" class="btn btn-primary" name="login" >
                </div>
                <br>
                <div><p>Not registered yet <a href="register.php">Register Here</p></div>
            </form>
    </div>
</body>
</html>