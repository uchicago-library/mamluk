<?php
// Connects to your Database
$link = mysqli_connect("your.hostaddress.com", "username", "password", "Database_Name") or die(mysqli_error($link));

//Checks if there is a login cookie
if(isset($_COOKIE['ID_my_site']))

//if there is, it logs you in and directes you to the members page
{
$username = $_COOKIE['ID_my_site'];
$pass = $_COOKIE['Key_my_site'];
$check = mysqli_query($link, "SELECT * FROM users WHERE username = '$username'")or die(mysqli_error($link));
while($info = mysqli_fetch_array( $check ))
{
if ($pass != $info['password'])
{
}
else
{
header("Location: members.php");

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
$_POST['email'] = addslashes($_POST['email']);
}
$check = mysqli_query($link, "SELECT * FROM users WHERE username = '".$_POST['username']."'")or die(mysqli_error($link));

//Gives error if user dosen't exist
$check2 = mysqli_num_rows($check);
if ($check2 == 0) {
die('That user does not exist in our database. <a href=add.php>Click Here to Register</a>');
}
while($info = mysqli_fetch_array( $check ))
{
$_POST['pass'] = stripslashes($_POST['pass']);
$info['password'] = stripslashes($info['password']);
$_POST['pass'] = md5($_POST['pass']);

//gives error if the password is wrong
if ($_POST['pass'] != $info['password']) {
die('Incorrect password, please try again.');
}
