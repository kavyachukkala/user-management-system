<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id']))
{
    header("Location:login.php");
    exit();
}

$search="";

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1)
{
    $page = 1;
}

$offset = ($page - 1) * $limit;

$search = "";

if(isset($_GET['search']))
{
    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $countQuery = "SELECT COUNT(*) AS total
                   FROM users
                   WHERE name LIKE '%$search%'
                   OR email LIKE '%$search%'";

    $countResult = mysqli_query($conn,$countQuery);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalRecords = $countRow['total'];

    $sql = "SELECT *
            FROM users
            WHERE name LIKE '%$search%'
            OR email LIKE '%$search%'
            ORDER BY id DESC
            LIMIT $offset,$limit";
}
else
{
    $countQuery = "SELECT COUNT(*) AS total FROM users";

    $countResult = mysqli_query($conn,$countQuery);

    $countRow = mysqli_fetch_assoc($countResult);

    $totalRecords = $countRow['total'];

    $sql = "SELECT *
            FROM users
            ORDER BY id DESC
            LIMIT $offset,$limit";
}

$totalPages = ceil($totalRecords / $limit);


$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>View Users</title>

<link rel="stylesheet" href="css/view_users.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

<div class="header">

<h1>
<i class="fa-solid fa-users"></i>
User Management
</h1>

<a href="dashboard.php" class="dashboard-btn">

<i class="fa-solid fa-house"></i>

Dashboard

</a>

</div>

<div class="search-box">

<form method="GET">

<input
type="text"
name="search"
placeholder="Search by Name or Email..."
value="<?php echo $search; ?>">

<button>

<i class="fa-solid fa-magnifying-glass"></i>

Search

</button>

</form>

</div>

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>

<th>Profile</th>

<th>Name</th>

<th>Email</th>

<th>Role</th>

<?php
if($_SESSION['role']=="Admin")
{
?>
<th>Edit</th>
<th>Delete</th>
<?php
}
?>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php

if(!empty($row['image']))
{

?>

<?php

if(!empty($row['image']))
{

?>

<img
src="uploads/<?php echo $row['image']; ?>"
class="profile">

<?php

}
else
{

?>

<img
src="uploads/kavya.jpg"
class="profile">

<?php

}

?>

<?php

}
else
{

?>

<img
src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=6C63FF&color=fff"
class="profile">

<?php

}

?>

</td>

<td>

<?php echo $row['name']; ?>

</td>

<td>

<?php echo $row['email']; ?>

</td>

<td>

<span class="badge">

<?php echo $row['role']; ?>

</span>

</td>

<?php
if($_SESSION['role']=="Admin")
{
?>

<td>

<a
href="edit_user.php?id=<?php echo $row['id']; ?>"
class="edit">

Edit

</a>

</td>

<td>

<a
href="#"
class="delete"
onclick="confirmDelete(<?php echo $row['id']; ?>)">

Delete

</a>

</td>

<?php
}
?>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="7">

No Users Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>
<div class="pagination">

<?php

if($page>1)
{

?>

<a href="?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>">

Previous

</a>

<?php

}

for($i=1;$i<=$totalPages;$i++)
{

?>

<a
class="<?php if($i==$page) echo 'active'; ?>"
href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">

<?php echo $i; ?>

</a>

<?php

}

if($page<$totalPages)
{

?>

<a href="?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">

Next

</a>

<?php

}

?>

</div>
<div class="bottom">

<a
href="add_user.php"
class="add">

<i class="fa-solid fa-user-plus"></i>

Add New User

</a>

</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

function confirmDelete(id)
{

Swal.fire({

title:'Delete User?',

text:'This action cannot be undone.',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#EF4444',

cancelButtonColor:'#6C63FF',

confirmButtonText:'Yes, Delete',

cancelButtonText:'Cancel'

}).then((result)=>{

if(result.isConfirmed)
{

window.location='delete_user.php?id='+id;

}

});

}

</script>
<?php
if(isset($_GET['updated']))
{
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

Swal.fire({

icon:'success',

title:'Success',

text:'User updated successfully!',

confirmButtonColor:'#6C63FF'

});

</script>

<?php
}
?>

<?php
if(isset($_GET['deleted']))
{
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

Swal.fire({

icon:'success',

title:'Deleted!',

text:'User deleted successfully.',

confirmButtonColor:'#6C63FF'

});

</script>

<?php
}
?>
</body>

</html>