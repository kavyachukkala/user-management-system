<?php
session_start();
include("config.php");

if(isset($_SESSION['user_id']))
{
    header("Location: dashboard.php");
    exit();
}

if(isset($_POST['login']))
{
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password))
    {
        $error = "Please fill all fields!";
    }
    else
    {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            $user = mysqli_fetch_assoc($result);

            if(password_verify($password, $user['password']))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit();
            }
            else
            {
                $error = "Incorrect Password!";
            }
        }
        else
        {
            $error = "Email not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link rel="stylesheet" href="css/login.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="background">

<div class="login-card">

<div class="logo">

<i class="fa-solid fa-user-shield"></i>

<h2>User Management</h2>

<p>Login to Continue</p>

</div>

<?php
if(isset($error))
{
echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<div class="input-box">

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Email Address"
required>

</div>

<div class="input-box">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
id="password"
placeholder="Password"
required>

<span class="eye"
onclick="togglePassword()">

<i class="fa-solid fa-eye" id="eyeIcon"></i>

</span>

</div>

<div class="options">

<label>

<input type="checkbox">

Remember Me

</label>

<a href="#">Forgot Password?</a>

</div>

<button
type="submit"
name="login">

Login

</button>

</form>

<div class="divider">

<span>OR</span>

</div>

<div class="social-login">

<a href="#">
<i class="fab fa-google"></i>
</a>

<a href="#">
<i class="fab fa-github"></i>
</a>

<a href="#">
<i class="fab fa-linkedin"></i>
</a>

</div>

<div class="register-link">

Don't have an account?

<a href="register.php">

Register

</a>

</div>

</div>

</div>

<script>

function togglePassword(){

let password=document.getElementById("password");

let icon=document.getElementById("eyeIcon");

if(password.type==="password")
{
password.type="text";
icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");
}
else
{
password.type="password";
icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");
}

}

</script>

</body>
</html>