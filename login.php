<?php
$userid=$_POST["userid"];
$pw=$_POST["pw"];
include("connect.php");
session_start();
session_cache_expire(10);
if($con)
    echo "";
else echo "Verbindung zur Datenbank hat nicht gefunzt";
$sql="SELECT * FROM user WHERE Username ='$userid';";
$erg=mysqli_query($con,$sql);
if($erg == false) 
    echo "Error" . mysqli_error($con);
$row=mysqli_fetch_Assoc($erg);
// User wird in session angemeldet wenn passwörter übereinstimmen
if(password_verify($pw,$row["Password"]))
{
    $_SESSION["userid"] = $userid;
    $_SESSION["usernr"] = $row["UserID"];
    $hashedpw=$row["Password"];
    header("Location:user.php");
}

else header("location:login.html");
?>