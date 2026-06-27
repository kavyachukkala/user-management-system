<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Total Users
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$row = mysqli_fetch_assoc($result);
$totalUsers = $row['total'];

// Recent Users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="container">

<!-- Sidebar -->

<div class="sidebar">

<div class="logo">

<h2>User</h2>

<h2 class="management">Management</h2>

<p>System</p>

</div>

<ul>

<li class="active">
<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
Dashboard
</a>
</li>

<li>
<?php
if($_SESSION['role']=="Admin")
{
?>

<a href="add_user.php">

Add User

</a>

<?php
}
?>
</li>

<li>
<a href="view_users.php">
<i class="fa-solid fa-users"></i>
View Users
</a>
</li>

<li>
<a href="profile.php">
<i class="fa-solid fa-user"></i>
Profile
</a>
</li>

<li>
<a href="logout.php">
<i class="fa-solid fa-right-from-bracket"></i>
Logout
</a>
</li>

</ul>

<div class="profile-box">

<?php
$profileImage = "uploads/kavya.jpg";

$result = mysqli_query($conn, "SELECT image FROM users WHERE id='".$_SESSION['user_id']."'");

if($result)
{
    $user = mysqli_fetch_assoc($result);

    if(!empty($user['image']))
    {
        $profileImage = "uploads/".$user['image'];
    }
}
?>

<img src="<?php echo $profileImage; ?>" class="profile-image" alt="Profile">

<h3><?php echo $_SESSION['name']; ?></h3>

<span><?php echo $_SESSION['role']; ?></span>

</div>

</div>

<!-- Main -->

<div class="main">

<div class="topbar">

<div class="menu-icon">

<i class="fa-solid fa-bars"></i>

</div>

<div class="top-right">

<i class="fa-solid fa-bell notification"></i>

<div class="top-profile">

<div>

<h4><?php echo $_SESSION['name']; ?></h4>

<p><?php echo $_SESSION['role']; ?></p>

</div>

<div class="avatar">

<i class="fa-solid fa-user"></i>

</div>

</div>

</div>

</div>

<!-- Welcome Banner -->

<div class="banner">

<div>

<h1>Welcome Back,
<?php echo $_SESSION['name']; ?> 👋</h1>

<p>Here's what's happening with your system today.</p>

</div>

<div class="banner-icon">

<i class="fa-solid fa-chart-line"></i>

</div>

</div>

<!-- Cards -->

<div class="cards">

<div class="card">

<div class="icon purple">

<i class="fa-solid fa-users"></i>

</div>

<div>

<h4>Total Users</h4>

<h2><?php echo $totalUsers; ?></h2>

<p>Registered Users</p>

</div>

</div>

<div class="card">

<div class="icon green">

<i class="fa-solid fa-user-check"></i>

</div>

<div>

<h4>Active Users</h4>

<h2><?php echo $totalUsers; ?></h2>

<p>Currently Active</p>

</div>

</div>

<div class="card">

<div class="icon orange">

<i class="fa-solid fa-user-shield"></i>

</div>

<div>

<h4>Role</h4>

<h2><?php echo $_SESSION['role']; ?></h2>

<p>Current Access</p>

</div>

</div>

<div class="card">

<div class="icon blue">

<i class="fa-solid fa-calendar-days"></i>

</div>

<div>

<h4>Today</h4>

<h2><?php echo date("d"); ?></h2>

<p><?php echo date("M Y"); ?></p>

</div>

</div>

</div>

<!-- Bottom -->

<div class="bottom-section">

<div class="table-box">

<h2>Recent Users</h2>

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>

</tr>

<?php while($user=mysqli_fetch_assoc($users)){ ?>

<tr>

<td><?php echo $user['id']; ?></td>

<td><?php echo $user['name']; ?></td>

<td><?php echo $user['email']; ?></td>

<td>

<span class="badge">

<?php echo $user['role']; ?>

</span>

</td>

</tr>

<?php } ?>

</table>

</div>

<div class="chart-box">

<h2>User Roles</h2>

<canvas id="roleChart"></canvas>

</div>

</div>

</div>

</div>

<script>

const ctx=document.getElementById('roleChart');

new Chart(ctx,{

type:'doughnut',

data:{

labels:['Users','Admins'],

datasets:[{

data:[70,30],

borderWidth:0

}]

},

options:{

responsive:true

}

});

</script>

</body>
</html>  