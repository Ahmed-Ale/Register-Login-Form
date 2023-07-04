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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $repeat_password = $_POST["repeat_password"];
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($fullname) || empty($email) || empty($password) || empty($repeat_password)) {
                array_push($errors, "All fields are required");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }

            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters");
            }

            if ($password !== $repeat_password) {
                array_push($errors, "Password does not match");
            }

            if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $password)) {
                array_push($errors, "password must contain at least one special character
                , one uppercase letter
                , one lowercase letter and one number");
            }

            require_once "database.php";
            $sql = "SELECT * from users WHERE email = '$email'";
            $result  = mysqli_query($conn,$sql);
            $rowcount = mysqli_num_rows($result);

            if ($rowcount>0){
                array_push($errors, "Email already exists");
            }

            if (count($errors) > 0) {
                foreach($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users (full_name, email, password) VALUES(?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $preparestmt = mysqli_stmt_prepare($stmt, $sql);

                if ($preparestmt) {
                    mysqli_stmt_bind_param($stmt,"sss",$fullname,$email,$passwordhash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>you are registered successfully</div>";
                } else {
                    die("something went wrong");
                }
            }
        }
        ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" name="fullname" class="form-control" placeholder="Full Name" value=<?php echo isset($_POST["fullname"]) ? $_POST["fullname"] : ""; ?>>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" value=<?php echo isset($_POST["email"]) ? $_POST["email"] : ""; ?>>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" value=<?php echo isset($_POST["password"]) ? $_POST["password"] : ""; ?>>
            </div>
            <div class="form-group">
                <input type="password" name="repeat_password" class="form-control" placeholder="Repeat Password" value=<?php echo isset($_POST["repeat_password"]) ? $_POST["repeat_password"] : ""; ?>>
            </div>
            <div class="form-btn">
                <input type="submit" value="Register" class="btn btn-primary" name="submit" >
            </div>
            <br>
            <div><p>Already registered <a href="login.php">Login Here</p></div>
        </form>
    </div>
</body>
</html>
