<?php

function db_get_categories(){
        global $db;
        $results = $db->query("SELECT * FROM Category");
        $categories = array();
        while($line = $results->fetch_assoc()){
            $category = array();
            $category['id'] = $line['id_category'];
            $category['name'] = $line['name'];
            $categories[] = $category;
        }
        return array('categories' => $categories);
}

?>
