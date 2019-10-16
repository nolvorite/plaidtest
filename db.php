<?php 

    $dbCon = mysqli_connect("localhost","root","","plaidtest");

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

?>