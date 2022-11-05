<?php
require("funcs.php");
// Connects to your Database
$link = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $db_name)
    or die('Could not connect: ' . mysqli_error($link));
mysqli_query($link, "SET NAMES 'utf8'");

//Checks if there is a login cookie
if(isset($_COOKIE['ID_my_site']))

//if there is, it logs you in and directes you to the members page
{
$username = $_COOKIE['ID_my_site'];
$pass = $_COOKIE['Key_my_site'];
$check = mysqli_query($link, "SELECT * FROM member WHERE username = '$username'")or die(mysqli_error($link));
while($info = mysqli_fetch_array( $check ))
{ 	
if ($pass != $info['password'])
{
}
else
{
header("Location: start.php");

}
}
}

//if the login form is submitted
if (isset($_POST['submit'])) { // if form has been submitted

// makes sure they filled it in
if(!$_POST['username'] | !$_POST['pass']) {
die('You did not fill in a required field.');
}
// checks it against the database

if (!get_magic_quotes_gpc()) {
//$_POST['email'] = addslashes($_POST['email']);
}


$check = mysqli_query($link, "SELECT * FROM member WHERE username = '".$_POST['username']."'")or die(mysqli_error($link));

//Gives error if user dosen't exist
$check2 = mysqli_num_rows($check);
if ($check2 == 0) {
die('That user does not exist in our database.');
}
while($info = mysqli_fetch_array( $check ))
{
//echo 'password = ' . $info['password'];
//echo 'pass = ' . $_POST['pass'];
	
//$_POST['pass'] = stripslashes($_POST['pass']);
//$info['password'] = stripslashes($info['password']);
$_POST['pass'] = md5($_POST['pass']);
//echo 'pass = ' . $_POST['pass'];

//gives error if the password is wrong
if ($_POST['pass'] != $info['password']) {
die('Incorrect password, please try again.');
}
else
{

// if login is ok then we add a cookie
$_POST['username'] = stripslashes($_POST['username']);
$hour = time() + 3600;
setcookie(ID_my_site, $_POST['username'], $hour);
setcookie(Key_my_site, $_POST['pass'], $hour);

//then redirect them to the members area
header("Location: start.php");
}
}
}
else
{

// if they are not logged in
?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<table border="0">
<tr><td colspan=2><h1>Login</h1></td></tr>
<tr><td>Username:</td><td>
<input type="text" name="username" maxlength="40">
</td></tr>
<tr><td>Password:</td><td>
<input type="password" name="pass" maxlength="50">
</td></tr>
<tr><td colspan="2" align="right">
<input type="submit" name="submit" value="Login">
</td></tr>
</table>
</form>
<?php
}

?>

