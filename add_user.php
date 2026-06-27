<?php
session_start();
include("config.php");
if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

if($_SESSION['role'] != "Admin")
{
    echo "<script>
    alert('Access Denied!');
    window.location='dashboard.php';
    </script>";
    exit();
}
if(!isset($_SESSION['user_id']))
{
header("Location:login.php");
exit();
}

if(isset($_POST['save']))
{

$name=trim($_POST['name']);
$email=trim($_POST['email']);
$password=$_POST['password'];
$role=$_POST['role'];

if(empty($name)||empty($email)||empty($password))
{
$error="Please fill all fields.";
}
else
{

$check=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($check)>0)
{
$error="Email already exists!";
}
else
{

$hash=password_hash($password,PASSWORD_DEFAULT);

$sql="INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$hash','$role')";

if(mysqli_query($conn,$sql))
{
echo "<script>
alert('User Added Successfully');
window.location='view_users.php';
</script>";
exit();
}
else
{
$error="Unable to add user.";
}

}

}

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add User</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{

background:#eef2ff;
font-family:Poppins,sans-serif;

}

.container{

width:500px;
margin:50px auto;
background:white;
padding:35px;
border-radius:20px;
box-shadow:0 15px 40px rgba(0,0,0,.15);

}

h2{

text-align:center;
margin-bottom:25px;
color:#5B55FF;

}

.input{

margin-bottom:18px;

}

.input label{

display:block;
margin-bottom:8px;
font-weight:600;

}

.input input,
.input select{

width:100%;
padding:14px;
border:1px solid #ddd;
border-radius:10px;
font-size:15px;

}

button{

width:100%;
padding:15px;
background:#6C63FF;
color:white;
border:none;
border-radius:12px;
font-size:17px;
cursor:pointer;
transition:.3s;

}

button:hover{

background:#4F46E5;

}

.error{

background:#ffebee;
padding:12px;
color:red;
border-radius:8px;
margin-bottom:18px;

}

.back{

display:block;
margin-top:20px;
text-align:center;
text-decoration:none;
color:#6C63FF;
font-weight:600;

}

</style>

</head>

<body>

<div class="container">

<h2><i class="fa-solid fa-user-plus"></i> Add User</h2>

<?php

if(isset($error))
{

echo "<div class='error'>$error</div>";

}

?>

<form method="POST">

<div class="input">

<label>Full Name</label>

<input
type="text"
name="name"
required>

</div>

<div class="input">

<label>Email</label>

<input
type="email"
name="email"
required>

</div>

<div class="input">

<label>Password</label>

<input
type="password"
name="password"
required>

</div>

<div class="input">

<label>Role</label>

<select name="role">

<option>User</option>

<option>Admin</option>

</select>

</div>

<button
type="submit"
name="save">

<i class="fa-solid fa-floppy-disk"></i>

Save User

</button>

</form>

<a class="back" href="dashboard.php">

← Back to Dashboard

</a>

</div>

</body>

</html>