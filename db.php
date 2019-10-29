<?php 

    if(!isset($_SESSION['userdata'])){
        $_SESSION['userdata'] = ['username' => 'test_user', 'user_id' => 1];
    }

    $dbCon = mysqli_connect("localhost","root","","plaidtest");

    $env = 'development';

    $clientId = '5bcf2ebae188cf0013527c9c'; //'5d95f5c8c08af900131e573c';
    $secret = ($env === 'sandbox') ? 'aa4848a29c00fb894ffa43b43874d3' : '605f6745c0b64d2e2f86b92deea33f';//'784086cf20dde185d2e11d0fcfc896';
    $publicKey = 'a098c6bb0a982318837f9c7018573a';

    $_SESSION['public_token'] = 'access-development-60ff44f2-5109-4971-a116-2403b01dd94d';

    function filterQ($value){
        global $dbCon;
        return mysqli_real_escape_string($dbCon, $value);

    }

    function updateTransactions(){
        global $dbCon;
        //get date of last transaction
    }

    function submitToDb($table,$array){
        global $dbCon; //replace dbCon with PHP variable containing mysqli connection (mysqli_connect)
        /*
            format:
                for every key value pair, the key is a column and its value is the data to be inserted under said column.
                eg. [animal_type: cat, age: 6] would be INSERT INTO animals (animal_type,age) VALUES ('cat','6');
        */
        $query = "INSERT INTO $table (";
        $count = 0;
        foreach(array_keys($array) as $column){
            $count++;
            
            $query .= $column;
            if($count < count(array_keys($array))){
                $query .= ",";
            }
            
        }

        $query .= ")";
        $query .= " VALUES(";
        $count = 0;
        foreach($array as $value){
            $count++;
            $query .= "'".mysqli_real_escape_string($dbCon,$value)."'";
            if($count < count($array)){
                $query .= ",";
            }
        }

        $query .= ");";
        $submission = mysqli_query($dbCon, $query) or die(mysqli_error($submission));
        return ($submission) ? true : false;

    }

    function tableAppend($bankData,$cardName){
        $cardNameDisplay = isset($bankData['nickname']) ? htmlspecialchars($bankData['nickname']) : '('.$bankData["bank"].') '.$cardName;
        $htmlAppend = '            <div class="table-container" c_id="'.$bankData['card_id'].'">
<button class="btn btn-primary toggle-btn" type="button" data-toggle="collapse">
    '.$cardNameDisplay.'
</button>
<div class="collapse">
    <div class="card-menu">
        <button class="btn btn-warning nickname-btn" type="button">
            Manage Nickname
        </button>
        <button class="btn btn-danger delete-card-feed-btn" type="button">
            Remove Card Feed
        </button>
        <button class="btn btn-dark reconnect-card-feed-btn d-none" type="button">
            Re-Connect
        </button>
    </div>
    <div class="table-canvas">
        <table class="table table-striped transaction">
            <thead><tr><th title="Field #1">Type</th>
            <th title="Field #2">Trans Date</th>
            <th title="Field #3">Post Date</th>
            <th title="Field #4">Description</th>
            <th title="Field #5">Amount</th>
            </tr></thead>
            <tbody>';

        

        foreach($bankData['transactions'] as $key=>$trnsct){
            $htmlAppend .= "<tr>";
            $trnsct = (array) $trnsct;
            if(!isset($trnsct['date'])){
                $trnsct['date'] = $trnsct['date_transacted'];
            }
            if(!isset($trnsct['name'])){
                $trnsct['name'] = $trnsct['description'];
            }
            $datePosted = (isset($trnsct['date_posted'])) ? date("Y-m-d",strtotime($trnsct['date_posted'])) : date("Y-m-d");
            $htmlAppend .= "<td>$trnsct[transaction_type]</td>
        <td>$trnsct[date]</td>
        <td>". $datePosted ."</td>
        <td>$trnsct[name]</td>
        <td>$trnsct[amount]</td>
        ";
            $htmlAppend .= "</tr>";

        }


    
        
        

        $htmlAppend .= '</tbody>
        </table>
    </div>
</div>
</div>';

        return $htmlAppend;

    }

    function getCards(){
        global $dbCon;
        $userId = $_SESSION['userdata']['user_id'];
        $getCards = mysqli_query($dbCon, "SELECT * FROM cards WHERE user_id = ".$userId);
        return mysqli_fetch_all($getCards, MYSQLI_ASSOC);
    }

    function cUrlQuick($postData,$url,$typeCast = true,$returnAs = "json", $local = false){
        $data = json_encode($postData);

        $ch4 = curl_init($url);

        curl_setopt($ch4, CURLOPT_SSL_VERIFYHOST, 0); //DEVELOPMENT ONLY
        curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, 0); //DEVELOPMENT ONLY
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch4, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch4, CURLOPT_POST, true);
        curl_setopt($ch4, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch4, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        
         
        // Submit the POST request

        $result = curl_exec($ch4);
        
        switch($returnAs){
            case "json":
                if(!$result){
                    return json_encode(curl_error($ch4));
                }else{
                    $result = json_decode($result);
                    if($typeCast){
                        $result = (array) $result;
                    }
                    return $result;
                }   
            break;
            case "plain":
                return $result;
            break;
        }

        curl_close($ch4);
        
    }

    function getInstitution($institutionId){

        global $publicKey;

        return cUrlQuick(['public_key' => $publicKey, 'institution_id' => $institutionId],'https://sandbox.plaid.com/institutions/get_by_id');
        
    }




?>