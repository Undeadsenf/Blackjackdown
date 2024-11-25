<?php
session_start();
include("connect.php");
if($con)
    echo "";
else echo "Verbindung zur Datenbank hat nicht gefunzt";
$userid=$_POST["userid"];
$pw1=$_POST["pw1"];
$pw2=$_POST["pw2"];
$sql="SELECT * FROM user WHERE Username = '$userid';";
$erg=mysqli_query($con,$sql);

// Wenn User schon in db oder DB-Fehler, Fehlermeldung ausgeben bzw. zurück zur registrierung
if($erg==false)
    echo "Error" . mysqli_error($con);
if(mysqli_num_rows($erg)>0)
    header("Location:register.html");
// Wenn Passwörter übereinstimmen, User anlegen und User-Seite anlegen, User auf User-Seite weiterleiten
    if($pw1 == $pw2){
    $row=mysqli_fetch_assoc($erg);
    $_SESSION["usernr"]=$row["UserID"];
    $pw_hashed=password_hash($pw1, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (Username, Password) VALUES ('$userid', '$pw_hashed');";
    if(mysqli_query($con,$sql))
    {
        echo "Query erfolgreich";
        $_SESSION["userid"] = $userid;
        header("Location:user.php");
    }
    else {
        //Query gescheitert
        $err = mysqli_error($con);
        echo "Error: " . $err;
    }
    
}