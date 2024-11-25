    <?php   
    // zieht eine Karte aus dem Kartenstapel verdeckt (true) oder nicht
    //  Verwendete $_SESSION Variablen:
    
    // $_SESSION["userid"] - ID des eingeloggten Users
    // $_SESSION["playerCounter"] - Kartenwert des Spielers
    // $_SESSION["dealerCounter"] - Kartenwert des Dealers
    // $_SESSION["cardSet"] - Kartenstapel
    // $_SESSION["dealerCards"] - Array mit den Karten des Dealers
    // $_SESSION["playerCards"] - Array mit den Karten des Spielers
    function drawCard(&$cardSet,$showBack){
        $card=array_pop($cardSet);
        if($showBack==false)
            return $card;
        else {
            $card->showBack=true;
            return $card;
        }

    }    
    //Vorbereitung einer neuen Partie und zurücksetzen der Spieler und Dealer Bereiche 
    //zieht auch die ersten zwei Karten für den Dealer, die zweite verdeckt
    function startGame(){
        $dealerCards = array();        
        $_SESSION["betSet"]=false;
        $_SESSION["playerBlackJack"]=false;
        $_SESSION["playerCards"]=array();     
        $_SESSION["playerCounter"]=0;
        $_SESSION["dealerCounter"]=0;
        $_SESSION["stand"]=null;
        $cards=new Cards("karo","ass");
        $cardSet=$cards->newCardSet();
        shuffle($cardSet);
        $_SESSION["cardSet"]=$cardSet;
        
        $dealerCards[0]=drawCard($_SESSION["cardSet"],false);
        $dealerCards[1]=drawCard($_SESSION["cardSet"],true);
        foreach($dealerCards as $card)
        {
            $_SESSION["dealerCounter"]+=$card->cardValue();
        }
        $_SESSION["dealerCards"]=$dealerCards;
        //Hat dealer Blackjack?
        $_SESSION["dealerBlackJack"]=checkBlackJack("dealer");
        //wenn zwei Asse gezogen werden
        if($_SESSION["dealerCounter"]>21)
        downsizeAces("dealer");
    }
                                            
    function drawGameArea($param)
    {
        if($param=="dealer"){
            $_SESSION["dealerSpace"]="<div class='gamewrapper' id='dealer'>";
            foreach ($_SESSION["dealerCards"] as $dCards)
            {
                $_SESSION["dealerSpace"].=$dCards->showCard();
            }
            echo $_SESSION["dealerSpace"]."</div>";
        }
        else if($param=="player"){
        $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'>";              
        
        foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }

        echo $_SESSION["playerSpace"]."<div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
        }
        else if($param=="buttons"){
            $_SESSION["buttonSpace"]="<div class='buttonBar' id='buttonBar'>";
            $_SESSION["hitButton"]="<input type=\"button\" value=\"hit\" name=\"hit\" onclick=\"sendRequest('hit=1','gamebody')\" />";
            $_SESSION["standButton"]="<input type='button' value='stand' name='stand' onclick=\"sendRequest('stand=1','gamebody')\" />";
            $_SESSION["quitGameButton"]="<input type=\"button\" value=\"Quit Game\" onclick=\"sendRequest('quitGame=1','gamebody')\" />";
            echo $_SESSION["buttonSpace"].$_SESSION["hitButton"].$_SESSION["standButton"].$_SESSION["quitGameButton"]."</div>";
        }
        else if($param=="newGameButton"){
            $_SESSION["buttonSpace"]="<div class='buttonBar' id='buttonBar'>";
            $_SESSION["newGameButton"]="<input type=\"button\" value=\"New Game\" name=\"newGame\" onclick=\"sendRequest('start=1','gamebody')\" />";
            echo $_SESSION["buttonSpace"].$_SESSION["newGameButton"]."</div>";
        }
         else if($param=="continueButton"){
            $_SESSION["buttonSpace"]="<div class='buttonBar' id='buttonBar'>";
            $_SESSION["newGameButton"]="<input type=\"button\" value=\"Continue\" name=\"continue\" onclick=\"sendRequest('continue=1','gamebody')\" />";
            echo $_SESSION["buttonSpace"].$_SESSION["newGameButton"]."</div>";
        }
        else if($param=="bettingForm")
        {   
            $_SESSION["bettingSpace"]="<div class='buttonBar' id='buttonBar'>";
            $_SESSION["bettingForm"]="<input type=\"number\" onkeydown=\"validateInput(event)\" id=\"betInput\" placeholder=\"Bet is 5 - 50$\" min=\"5\" max=\"50\" pattern=\"[5-9]|[1-4][0-9]|50\" required><input type=\"button\" value=\"Bet\" onclick=\"sendBetRequest('gamebody')\"/>";
            echo $_SESSION["bettingSpace"].$_SESSION["bettingForm"]."</div>";
            
        }
        else if($param=="quitGameButton")
        {
            $_SESSION["buttonSpace"]="<div class='buttonBar' id='buttonBar'>";
            $_SESSION["quitGameButton"]="<input type=\"button\" value=\"Quit Game\" onclick=\"sendRequest('quitGame=1','gamebody')\" />";
            echo $_SESSION["buttonSpace"].$_SESSION["quitGameButton"]."</div>";
        }
        else if($param=="betError"){
            echo "<div class=\"moneyBar\">Ineligible betting amount!</div>";
        }
    }
    function drawBusted($player){
        if($player=="dealer")
        {
            //flip cards to visible
            foreach($_SESSION["dealerCards"] as $dCards)
                {
                    $dCards->showBack=false;
                }
            //Draw card space normally, add a "BUSTED" screen
            $_SESSION["dealerSpace"]="<div class='gamewrapper' id='dealer'><div class='cardwrapper'>";
            foreach ($_SESSION["dealerCards"] as $dCards)
            {
                $_SESSION["dealerSpace"].=$dCards->showCard();
            }
            $_SESSION["dealerSpace"].='</div>';
            $_SESSION["dealerSpace"].="<div class='picturewrapper'><img class=\"busted\" src=\"img/busted.png\">";

            echo $_SESSION["dealerSpace"]."</div></div>";
        }
        if($player=="player")
        {
            $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"busted\" src=\"img/busted.png\">";

            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
        }
    }
    function drawBlackJack($player){
        if($player=="dealer")
        {
            //flip cards to visible
            foreach($_SESSION["dealerCards"] as $dCards)
                {
                    $dCards->showBack=false;
                }
            //Draw card space normally, add a "Blackjack" screen
            $_SESSION["dealerSpace"]="<div class='gamewrapper' id='dealer'><div class='cardwrapper'>";
            foreach ($_SESSION["dealerCards"] as $dCards)
            {
                $_SESSION["dealerSpace"].=$dCards->showCard();
            } 
            $_SESSION["dealerSpace"].='</div>';
            $_SESSION["dealerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/blackjack.png\">";
            echo $_SESSION["dealerSpace"]."</div></div>";
        }
          if($player=="player")
        {
            $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/blackjack.png\">";
            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
        }
    }
    function drawPlayerWins(){
        $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/youwin.png\">";
            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
    }
    function drawNoWin(){
        $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/nowin.png\">";
            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
    } 
    function drawLose() {
        $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/youlose.png\">";
            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
    }
     function drawGameOver() {
        $_SESSION["playerSpace"]="<div class='gamewrapper' id='player'><div class='cardwrapper'>";              
        
            foreach ($_SESSION["playerCards"] as $dCards)
            {
                $_SESSION["playerSpace"].=$dCards->showCard();
            }
            $_SESSION["playerSpace"].='</div>';
            $_SESSION["playerSpace"].="<div class='picturewrapper'><img class=\"blackjack\" src=\"img/gameover.png\">";
            echo $_SESSION["playerSpace"]."</div><div class=\"moneyBar\">Money remaining:".$_SESSION["playerChips"]."$</div></div>";
    }
    function hit()
    {
        $card=drawCard($_SESSION["cardSet"],false);
        array_push($_SESSION["playerCards"],$card);
        $_SESSION["playerCounter"]+=$card->cardValue();
        downSizeAces("player");
    }
    //this is different for the dealer because he needs to stand on 17
    function downsizeAces($player){
        if($player=="dealer")
        {
            //checks if there is an ace with value 11
            $containsElevenPointAce=false;
            foreach($_SESSION["dealerCards"] as $dCards){
                if($dCards->value == "ass"&&$dCards->aceValOne==false)
                $containsElevenPointAce=true;
            }
            $aceFound=false;
            while(!$aceFound&&$_SESSION["dealerCounter"]>16&&$containsElevenPointAce){
                foreach($_SESSION["dealerCards"] as $key => $card){
                    if($card->value=="ass"&&!$card->aceValOne){
                        $_SESSION["dealerCounter"]-=10;
                        $_SESSION["dealerCards"][$key]->aceValOne=true;
                        $aceFound = true;
                        break;
                    }
                }
            } 
        }
        if($player=="player"){
            $containsElevenPointAce=false;
            foreach($_SESSION["playerCards"] as $dCards){
                if($dCards->value == "ass"&&$dCards->aceValOne==false)
                $containsElevenPointAce=true;
            }
            $aceFound=false;
            while(!$aceFound&&$_SESSION["playerCounter"]>21&&$containsElevenPointAce){
                foreach($_SESSION["playerCards"] as $key => $card){
                    if($card->value=="ass"&&!$card->aceValOne){
                        $_SESSION["playerCounter"]-=10;
                        $_SESSION["playerCards"][$key]->aceValOne=true;
                        $aceFound = true;
                        break;
                    }
                }
            }
        }
    }
    //Lässt den Dealer seine Karten spielen. Hier müsste ich noch die variablen 
    //austauschen damit er mit der $_SESSION vergleicht, nicht mit einer Kopie des Counters
    function stand() {
        $_SESSION["stand"]=true;
        $_SESSION["dealerCards"][1]->showBack = false;
        while($_SESSION["dealerCounter"]<17) {
            $card=drawCard($_SESSION["cardSet"],false);            
            array_push($_SESSION["dealerCards"],$card);
            $_SESSION["dealerCounter"]+=$card->cardValue();
            if($_SESSION["dealerCounter"]>16) {
                downSizeAces("dealer");
            }
        }
              
    }
    
    function checkBusted($player) {
        if ($player=="dealer")
        {                   
            if($_SESSION["dealerCounter"]>21)
                return true;
            else
                return false;
        }
        else if ($player == "player")
        {
                   
            if($_SESSION["playerCounter"]>21)
                return true;
            else
                return false;
        }
    }
    function checkBlackjack($player){
        if($player == "dealer") {
            if($_SESSION["dealerCards"][0]->cardValue()+$_SESSION["dealerCards"][1]->cardValue()==21)
                return true;        
            else
                return false;
        }
        else if($player == "player") {
            if(count($_SESSION["playerCards"])==2){
                if($_SESSION["playerCards"][0]->cardValue()+$_SESSION["playerCards"][1]->cardValue()==21) 
                    return true;
                else
                    return false;
                }
                else 
                   return false;
            }
    }
    function checkplayerWins(){        
        if($_SESSION["playerCounter"]>$_SESSION["dealerCounter"]||checkBusted("dealer")||!checkBlackjack("dealer")&&checkBlackJack("player"))
        return true;
    return false;

    }
    function checkTie(){
        if($_SESSION["playerCounter"]==$_SESSION["dealerCounter"]||checkBlackjack("dealer")&&checkBlackJack("player"))
        return true;
    return false;
    }

    include("cards.php");
    include("connect.php");
    session_start();
    if($con)
    echo "";
    else echo "Verbindung zur Datenbank hat nicht gefunzt";
    //Stellt sicher, dass nur eingeloggte user das Skript verwenden
    if(!isset($_SESSION["userid"]))
    header:("Location:index.html");
    if(!isset($_SESSION["playerChips"]))
    $_SESSION["playerChips"]=100;
    //Startet eine neue Partie
    if(isset($_GET["start"]))
    {
        startGame();
        drawGameArea("dealer");
        drawGameArea("player");
        drawGameArea("bettingForm");        
        $_GET["start"]="";
    }
    if(isset($_POST["bet"]))
        {
            $_SESSION["betAmount"]=intval($_POST["bet"]);

            if( $_SESSION["betAmount"]>$_SESSION["playerChips"])
            {
                drawGameArea("dealer");
                drawGameArea("player");
                drawGameArea("bettingForm");
                drawGameArea("betError");
                $_POST["bet"]="";
            }
            else if( $_SESSION["betAmount"]<5||$_SESSION["betAmount"]>50)
            {
                drawGameArea("dealer");
                drawGameArea("player");
                drawGameArea("bettingForm");
                drawGameArea("betError");
                $_POST["bet"]="";
            }
            else {
            $_SESSION["playerChips"]-=$_SESSION["betAmount"];
            //$_SESSION["betSet"]=true;
            $_POST["bet"]="";
            header("Location:game.php?hit=1");
            }

        }
    //Führt hit für den player aus
    if (isset($_GET["hit"])&&!isset($_SESSION["stand"]))
        {
            hit();
            if(!checkBusted("player")&&!checkBlackjack("player")) 
            {
                drawGameArea("dealer");    
                drawGameArea("player");
                drawGameArea("buttons");
            }
            else if(checkBusted("player"))
            {
                $_SESSION["playerCounter"]=0;
                drawGameArea("dealer");
                drawBusted("player");
                drawGameArea("continueButton");
            }
            else if(checkBlackjack("player"))
            {
                //Der Blackjack-Wert wird noch für den Einsatz benötigt, daher hier in der SESSION gespeichert
                $_SESSION["playerBlackJack"]=checkBlackjack("player");
                stand();
                if($_SESSION["dealerBlackJack"])
                drawBlackJack("dealer");
                else if(checkBusted("dealer"))
                drawBusted("dealer");
                else
                drawGameArea("dealer");
                drawBlackJack("player");
                drawGameArea("continueButton");
            }
            $_GET["hit"]="";
        }                  
    if(isset($_GET["stand"]))
        {            
            
            
            if(!$_SESSION["dealerBlackJack"])
            {
                stand();
                if(!checkBusted("dealer")&&!checkBlackjack("player")){
                    drawGameArea("dealer");
                    drawGameArea("player");
                    drawGameArea("continueButton");
                }
                else if(checkBusted("dealer")&&!checkBlackjack("player"))
                {                
                    drawBusted("dealer");
                    drawGameArea("player");
                    drawGameArea("continueButton");                
                }
                else if(checkBusted("dealer")&&checkBlackjack("player"))
                {                
                    drawBusted("dealer");
                    drawBlackJack("player");
                    drawGameArea("continueButton");                
                }
            }
                else 
                {
                    drawBlackJack("dealer");
                    drawGameArea("player");
                    drawGameArea("continueButton");
                }
           $_GET["stand"]=""; 
        }
        if(isset($_GET["continue"]))
        {
            if(checkplayerWins())
            {
                drawGameArea("dealer");
                if($_SESSION["playerBlackJack"])
                $_SESSION["playerChips"]+=$_SESSION["betAmount"]*2.5;                                
                else
                $_SESSION["playerChips"]+=$_SESSION["betAmount"]*2;
                drawPlayerWins();
                drawGameArea("newGameButton");
            }
            else if(checkTie())
            {
                drawGameArea("dealer");
                $_SESSION["playerChips"]+=$_SESSION["betAmount"];
                drawNoWin();
                drawGameArea("newGameButton");
            }
            else
            {
                if($_SESSION["playerChips"]<5){
                drawGameArea("dealer");
                drawGameOver(); 
                drawGameArea("quitGameButton");                
                //Man müsste es anderes handhaben, aber aus Zeitgründen setze ich hier 
                //die Spielerchips auf 100 Zurück fürs neue Spiel
                $_SESSION["playerChips"]=100;
                }
                else{
                drawGameArea("dealer");
                drawLose();
                drawGameArea("newGameButton");
                }
            }
            $_GET["continue"]="";
        }
            if(isset($_GET["quitGame"]))
            {
                $chips=$_SESSION["playerChips"];
                $userNr=$_SESSION["usernr"];
                $sql="INSERT into session(UserNr,BossNr,Schaden,Sieg) VALUES ('$userNr','1','$chips','0'); ";
                if(mysqli_query($con,$sql))
                {
                    echo "Query erfolgreich";
                }
                else {
                    //Query gescheitert
                    $err = mysqli_error($con);
                    echo "Error: " . $err;
                }
                $_GET["quitGame"]="";
                $_SESSION["playerChips"]=100;
                header("Location:user.php");                
            }
        
        

//  to do/Ideen für umsetzung: 

//   hmm unnötig, womöglich? callback functions in die html bauen, damit verschiedene Buttons zur Serversteuerung möglich sind: hit, stand, newgame (Wenn Zeit: Split, Double, Insurance)
//   Quitgame button hinter continue button
//   Einsatzmechanik einbauen dazu: checkWin() - Vergleich spieler mit Dealer, Dealer busted
//   checkBjack() für instant win (Bei Zeit: Insurance an dieser Stelle)

  
//   Auszahlung einsatz:
//   regulär gewonnen 1:1
//   Blackjack gewonnen 1.5:1

//   Bosswerte angeben aus Datenbank

  
// 
// echo "Playercounter";
// print_r($_SESSION["playerCounter"]);
// echo "<br>Playercards";
// print_r ($_SESSION["playerCards"]);
?>