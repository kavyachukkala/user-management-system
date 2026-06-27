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

/* Get user details */
$result = mysqli_query($conn,"SELECT image FROM users WHERE id='$id'");

if(mysqli_num_rows($result)==0)
{
    header("Location:view_users.php");
    exit();
}

$user = mysqli_fetch_assoc($result);

/* Delete uploaded image */
if(!empty($user['image']) && $user['image']!="kavya.jpg")
{
    $imagePath = "uploads/".$user['image'];

    if(file_exists($imagePath))
    {
        unlink($imagePath);
    }
}

/* Delete user */
$delete = mysqli_query($conn,"DELETE FROM users WHERE id='$id'");

if($delete)
{
    header("Location:view_users.php?deleted=1");
    exit();
}
else
{
    echo "Unable to delete user.";
}
?>