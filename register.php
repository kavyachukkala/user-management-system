<?php
include("config.php");

if(isset($_POST['register']))
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if(empty($name) || empty($email) || empty($password) || empty($confirm_password))
    {
        $error = "Please fill all fields!";
    }
    elseif($password != $confirm_password)
    {
        $error = "Passwords do not match!";
    }
    else
    {
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0)
        {
            $error = "Email already exists!";
        }
        else
        {
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(name,email,password,role)
                    VALUES('$name','$email','$hashedPassword','$role')";

            if(mysqli_query($conn,$sql))
            {
                echo "<script>
                alert('Registration Successful');
                window.location='login.php';
                </script>";
            }
            else
            {
                $error="Registration Failed!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link rel="stylesheet" href="css/register.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="background">

<div class="register-card">

<div class="logo">

<i class="fa-solid fa-user-plus"></i>

<h2>Create Account</h2>

<p>User Management System</p>

</div>

<?php
if(isset($error))
{
echo "<div class='error'>$error</div>";
}
?>

<form method="POST">

<div class="input-box">

<i class="fa-solid fa-user"></i>

<input
type="text"
name="name"
placeholder="Full Name"
required>

</div>

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
onclick="togglePassword('password','eye1')">

<i class="fa-solid fa-eye" id="eye1"></i>

</span>

</div>

<div class="input-box">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="confirm_password"
id="confirmPassword"
placeholder="Confirm Password"
required>

<span class="eye"
onclick="togglePassword('confirmPassword','eye2')">

<i class="fa-solid fa-eye" id="eye2"></i>

</span>

</div>

<div class="input-box">

<i class="fa-solid fa-user-shield"></i>

<select name="role">

<option value="User">User</option>

<option value="Admin">Admin</option>

</select>

</div>

<button
type="submit"
name="register">

Create Account

</button>

</form>

<div class="divider">

<span>OR</span>

</div>

<div class="social-login">

<a href="#"><i class="fab fa-google"></i></a>

<a href="#"><i class="fab fa-github"></i></a>

<a href="#"><i class="fab fa-linkedin"></i></a>

</div>

<div class="login-link">

Already have an account?

<a href="login.php">

Login

</a>

</div>

</div>

</div>

<script>

function togglePassword(field,icon){

let password=document.getElementById(field);

let eye=document.getElementById(icon);

if(password.type==="password")
{
password.type="text";
eye.classList.remove("fa-eye");
eye.classList.add("fa-eye-slash");
}
else
{
password.type="password";
eye.classList.remove("fa-eye-slash");
eye.classList.add("fa-eye");
}

}

</script>

</body>
</html>