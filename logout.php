<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logging Out...</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>

Swal.fire({

icon:'success',

title:'Logged Out',

text:'You have been logged out successfully.',

confirmButtonColor:'#6C63FF'

}).then(()=>{

window.location='login.php';

});

</script>

</body>
</html>