<!DOCTYPE html>
<html>
<head>
   <link rel="stylesheet" type="text/css" href="css/bjd.css" />
</head>    
<body class="loginwrapper">      
<?php
    session_start();
    if(!isset($_SESSION["userid"])) {
        header("Location:index.html");
    }
    include("connect.php");
    $user= $_SESSION["userid"];
    $userid=$_SESSION["usernr"];
    $sql="SELECT sessionid, bossnr, schaden, sieg FROM session inner join user on userID = usernr where usernr = '$userid';";
    $erg=mysqli_query($con,$sql);
    echo "<h1>Willkommen bei Blackjack DOWN, $user!</h1><br>";
?>
<div class="flex">
<form action="game.html" method="get">
    <input type="submit" value="SPIELEN">
</form>
<form action="logout.php" method="get">
    <input type="submit" value="LOGOUT">
</form>
</div>
<br>
<?php
//fetch table header
$row=mysqli_fetch_assoc($erg);
if(isset($row)) {
    echo "<table>";
    echo "<tr>";
    foreach ($row as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "</tr>";
    //output table
    do {
        echo "<tr>";
        foreach($row as $value)    
        echo "<td>$value</td>"; 
        echo "</tr>";
        $row = mysqli_fetch_assoc($erg);
    }
    while($row);
    echo "</table>";
}

?>

</body>
</html>