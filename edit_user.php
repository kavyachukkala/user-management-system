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

if(!isset($_GET['id']))
{
    header("Location:view_users.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");

if(mysqli_num_rows($result)==0)
{
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = $_POST['role'];
$password = $_POST['password'];
$imageName = $user['image'];

if(isset($_FILES['image']) && $_FILES['image']['error']==0)
{

$imageName = time()."_".$_FILES['image']['name'];

move_uploaded_file(

$_FILES['image']['tmp_name'],

"uploads/".$imageName

);

}

if(empty($name) || empty($email))
{
$error="Please fill all fields.";
}
else
{

if(!empty($password))
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET
            name='$name',
            email='$email',
            role='$role',
            password='$hashedPassword',
            image='$imageName'
            WHERE id='$id'";
}
else
{
    $sql = "UPDATE users SET
            name='$name',
            email='$email',
            role='$role',
            image='$imageName'
            WHERE id='$id'";
}

if(mysqli_query($conn,$sql))
{

echo "<script>
window.location='view_users.php?updated=1';
</script>";

exit();

}
else
{
$error="Unable to update user.";
}

}

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit User</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
font-family:Poppins,sans-serif;
background:#eef2ff;
}

.container{

width:500px;
margin:50px auto;
background:white;
padding:35px;
border-radius:20px;
box-shadow:0 15px 35px rgba(0,0,0,.15);

}

h2{

text-align:center;
color:#6C63FF;
margin-bottom:25px;

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
border-radius:10px;
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
.preview{

width:100px;
height:100px;
border-radius:50%;
object-fit:cover;
display:block;
margin:15px auto;
border:4px solid #6C63FF;

}

.input{
    margin-bottom:18px;
}

.password-box{
    position:relative;
    width:100%;
}

.password-box input{
    width:100%;
    padding:14px;
    padding-right:50px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
    box-sizing:border-box;
}

.eye{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#666;
    font-size:18px;
}

.eye:hover{
    color:#6C63FF;
}
</style>

</head>

<body>

<div class="container">

<h2>

<i class="fa-solid fa-user-pen"></i>

Edit User

</h2>

<?php

if(isset($error))
{
echo "<div class='error'>$error</div>";
}

?>

<form method="POST" enctype="multipart/form-data">

<div class="input">

<label>Full Name</label>

<input
type="text"
name="name"
value="<?php echo htmlspecialchars($user['name']); ?>"
required>

</div>

<div class="input">

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo htmlspecialchars($user['email']); ?>"
required>

</div>

<div class="input">

<label>Role</label>

<select name="role">

<option value="User"
<?php if($user['role']=="User") echo "selected"; ?>>
User
</option>

<option value="Admin"
<?php if($user['role']=="Admin") echo "selected"; ?>>
Admin
</option>

</select>

</div>
<div class="input">

<label>Profile Image</label>

<?php
if(!empty($user['image']) && file_exists("uploads/".$user['image']))
{
?>

<img
src="uploads/<?php echo $user['image']; ?>"
class="preview">

<?php
}
else
{
?>

<img
src="uploads/kavya.jpg"
class="preview">

<?php
}
?>

<input type="file" name="image" id="image" accept="image/*">

</div>


<!-- 👆 IMAGE CODE ENDS HERE -->
 <div class="input">

    <label>New Password (Optional)</label>

    <div class="password-box">

        <input
            type="password"
            name="password"
            id="password"
            placeholder="Leave blank to keep current password">

        <i class="fa-solid fa-eye eye" onclick="togglePassword()"></i>

    </div>

</div>

<button type="submit" name="update">
<i class="fa-solid fa-floppy-disk"></i>
Update User
</button>
</form>

<a class="back" href="view_users.php">

← Back to Users

</a>

</div>
<script>

const fileInput=document.getElementById("image");
const preview=document.querySelector(".preview");

fileInput.onchange=function(){

if(this.files.length>0)
{
preview.src=URL.createObjectURL(this.files[0]);
}

};

function togglePassword(){

let password=document.getElementById("password");

password.type=password.type==="password"?"text":"password";

}

</script>
</body>

</html>