<!--Connection to establish the database-->
<?php
$servername = 'localhost:4306';
$username = 'root';
$password = '';
$dbname = 'wowershr';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
s