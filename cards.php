<?php
class Cards {
    public $suit;
    public $value;
    public $aceValOne;
    public $file;
    public $back;
    public $showBack;
public function __construct($suit,$value) {
    $this->suit = $suit;
    $this->value = $value;
    $this->aceValOne=false;
    $this->file = "img/".$suit.$value.".jpg";
    $this->back = "img/rueckseiteblau.jpg";
    $this->showBack = false;
}
public function newCardSet(){
    $suits = array("karo", "herz", "pik", "kreuz");
    $values = array("2","3","4","5","6","7","8","9","10","bube","dame","koenig","ass");
    $deck=array();
    $counter = 0;
    for($i = 0; $i<6; $i++){
        foreach($suits as $suit){
            foreach($values as $value){
                $deck["$counter"] = new Cards($suit,$value);
                $counter+=1;
            }
        }
    }
    return $deck;
}
public function showCard(){
    if(!$this->showBack)    
        return "<div class='card'><img src=".$this->file." /></div>";
    else 
        return "<div class='card'><img src=".$this->back." /></div>";
    }

 public function cardValue(){
        switch($this->value){
            case "ass": {if ($this->aceValOne== false) return 11;
                        else return 1;}
            case "bube": return 10;
            case "koenig" : return 10;
            case "dame" : return 10;
           default: return(intval($this->value));
        }
    }
}
// $cards = new Cards("karo","2");
// $test = $cards->newCardSet();
// echo count($test);
// var_dump($test);
?>