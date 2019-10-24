<?php
    ob_start();
    session_start();

    require("db.php");


    //search all cards, find their date, convert to unix timestamp

    foreach($cardData as $cardz){
        $cards[$cardz['card_id']] = $cardz;
        $lastUpdated = strtotime($cardz['last_updated']);
        if(microtime(true) - $lastUpdated >= (60 * 60 * 24)){
        	//split update into functions
        }
    }

?>