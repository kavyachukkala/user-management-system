<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

$id = $_SESSION['user_id'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");

if(mysqli_num_rows($result)==0)
{
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);
if(isset($_POST['update']))
{

$imageName = $user['image'];

if(isset($_FILES['image']) && $_FILES['image']['error']==0)
{

$imageName=time()."_".$_FILES['image']['name'];

move_uploaded_file(

$_FILES['image']['tmp_name'],

"uploads/".$imageName

);

}

$password=trim($_POST['password']);

if(!empty($password))
{

$password=password_hash($password,PASSWORD_DEFAULT);

$sql="UPDATE users SET

password='$password',
image='$imageName'

WHERE id='$id'";

}
else
{

$sql="UPDATE users SET

image='$imageName'

WHERE id='$id'";

}

mysqli_query($conn,$sql);

header("Location:profile.php?updated=1");

exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<link rel="stylesheet" href="css/profile.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<form class="profile-card" method="POST" enctype="multipart/form-data">

<?php

if(!empty($user['image']))
{
?>

<img
src="uploads/<?php echo $user['image']; ?>"
class="profile-image">

<?php
}
else
{
?>

<img
src="uploads/kavya.jpg"
class="profile-image">

<?php
}
?>
<div class="input">

<label>Change Profile Picture</label>

<input
type="file"
name="image"
accept="image/*">

</div>
<div class="input">

<label>New Password</label>

<div class="password-box">

<input
type="password"
name="password"
id="password"
placeholder="Leave blank to keep current password">

<i class="fa-solid fa-eye"
onclick="togglePassword()"></i>

</div>

</div>
<h2>

<?php echo htmlspecialchars($user['name']); ?>

</h2>

<p>

<i class="fa-solid fa-envelope"></i>

<?php echo htmlspecialchars($user['email']); ?>

</p>

<p>

<i class="fa-solid fa-user-shield"></i>

<?php echo htmlspecialchars($user['role']); ?>

</p>

<a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn">

<i class="fa-solid fa-user-pen"></i>

Edit Profile

</a>
<button
type="submit"
name="update"
class="btn">

<i class="fa-solid fa-floppy-disk"></i>

Update Profile

</button>
<a href="dashboard.php" class="btn back">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</form>
<?php
if(isset($_GET['updated']))
{
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

Swal.fire({

icon:'success',

title:'Profile Updated',

text:'Your profile has been updated successfully.',

confirmButtonColor:'#6C63FF'

});

</script>

<?php
}
?>
<script>

function togglePassword(){

let pass=document.getElementById("password");

if(pass.type==="password")
{
pass.type="text";
}
else
{
pass.type="password";
}

}

const file=document.querySelector("input[type=file]");
const image=document.querySelector(".profile-image");

if(file)
{

file.addEventListener("change",function(){

if(this.files.length>0)
{

image.src=URL.createObjectURL(this.files[0]);

}

});

}

</script>
</body>

</html>