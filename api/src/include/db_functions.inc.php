<?php

function get_categories(){
        global $db;
        $results = $db->query("SELECT * FROM Category");
        $categories = array();
        while($line = $results->fetch_assoc()){
            $categories[] = $line['name'];
        }
        return $categories;
}

?>
