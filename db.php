<?php 

    if(!isset($_SESSION['userdata'])){
        $_SESSION['userdata'] = ['username' => 'test_user', 'user_id' => 1];
    }
    

    $dbCon = mysqli_connect("localhost","root","","plaidtest");

    function filterQ($value){
        global $dbCon;
        return mysqli_real_escape_string($dbCon, $value);

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
        $htmlAppend = '            <div class="table-container">
<button class="btn btn-primary toggle-btn" type="button" data-toggle="collapse">
    '.$cardName.'
</button>
<div class="collapse">
    <div class="card-menu">
        <button class="btn btn-warning nickname-btn" type="button">
            Manage Nickname
        </button>
        <button class="btn btn-danger delete-card-feed-btn" type="button">
            Delete Card Feed
        </button>
        <button class="btn btn-dark reconnect-card-feed-btn" type="button">
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
                $trnsct['date'] = $trnsct['date_posted'];
            }
            if(!isset($trnsct['name'])){
                $trnsct['name'] = $trnsct['description'];
            }
            $htmlAppend .= "<td>$trnsct[transaction_type]</td>
        <td>$trnsct[date]</td>
        <td>". date("Y-m-d") ."</td>
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


?>