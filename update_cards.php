
<?php
    ob_start();
    session_start();

    require("db.php");


    if(isset($_SESSION['public_token'])){

        $cardCheck = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id='". $_SESSION['userdata']['user_id'] ."'");

        $cardData = mysqli_fetch_all($cardCheck,MYSQLI_ASSOC);


        //search all cards, find their date, convert to unix timestamp

        //everything updated before 1 day will be updated

        //as defined:
        $siteUrl = "https://localhost/plaidtest/";
        $cardIds = [];

        foreach($cardData as $cardz){
            $cards[$cardz['card_id']] = $cardz;
            $lastUpdated = strtotime($cardz['last_updated']);
            if(microtime(true) - $lastUpdated >= (60 * 60 * 24)){ 
                $cardIds[count($cardIds)] = [$cardz['card_id'],$cardz['official_name']];
            }
        }

    }

?>
Updating cards, please wait.
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    var publicToken = "<?php if(isset($_SESSION['public_token'])){ echo $_SESSION['public_token']; }?>";
    <?php if(isset($_SESSION['metadata'])){ ?>
    var metaData = $.parseJSON('<?php echo json_encode($_SESSION['metadata']); ?>');
    <?php } else { ?>
    var metaData = {};
    <?php } ?>
    console.log(metaData);
    cardsInQ = <?php echo count($cardIds); ?>;
    domainUrl = "https://localhost/plaidtest/";
</script>
<script type="text/javascript">
    numberUpdated = 0;



    
    <?php foreach($cardIds as $cardId): ?>
        $.post("fetch.php?type=transactions&card_id=<?php echo $cardId[0]; ?>&official_name=<?php echo $cardId[1]; ?>",{publicToken: publicToken, metadata: metaData},function(response){
            console.log(response);
            try {
                response = $.parseJSON(response);
                console.log(response);

                if(typeof response.notice !== "undefined" && response.notice === "Updated card's last update time."){
                    numberUpdated++;
                }
            } catch (error){
                console.log("Error updating card.");
                updateCards(id);
            }
        });
    <?php endforeach; ?>
    setInterval(function(){
        if(numberUpdated === cardsInQ){
            numberUpdated++;
            document.write("<br>All cards updated. Redirecting you...");
            setTimeout(function(){
                location.assign = domainUrl;
            },1500);
        }
    },500);   
</script>