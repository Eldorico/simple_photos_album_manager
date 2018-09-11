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

function db_album_exists_from_id($id_album){
    global $db;
    $id_album = intval($id_album);

    $stmt = $db->prepare("SELECT * FROM Album WHERE id_album LIKE ?");
    if( $stmt
        && $stmt->bind_param('i', $id_album)
        && $stmt->execute()){
        $result = $stmt->get_result();
        $album_allready_exists = ($result->num_rows == 1) ? true : false;
    }else{
        throw new Exception($db->error);
    }

    return $album_allready_exists;
}

function db_album_exists($albumName, $category){
    global $db;

    $stmt = $db->prepare("SELECT * FROM Album WHERE name LIKE ? AND category LIKE ?");
    if( $stmt
        && $stmt->bind_param('si', $albumName, $category)
        && $stmt->execute()){
        $result = $stmt->get_result();
        $album_allready_exists = ($result->num_rows == 1) ? true : false;
    }else{
        throw new Exception($db->error);
    }

    return $album_allready_exists;
}

function db_create_album($albumName, $category){
    global $db;

    if(intval($category) == 0){
        return array("error" => array("err_msg" => "invalid category\n", "status_code" => FORBIDDEN_CODE));
    }
    if(db_album_exists($albumName, $category)){
        return array("error" => array("err_msg" => "album allready exists\n", "status_code" => FORBIDDEN_CODE));
    }

    $stmt = $db->prepare("INSERT INTO Album VALUES (null, ?, ?)");
    if( $stmt
        && $stmt->bind_param('si', $albumName, $category)
        && $stmt->execute()){
        $result = $stmt->get_result();
    }else{
        throw new Exception($db->error);
    }

    return $db->insert_id;
}

function db_add_img_to_album($img_path, $album_id){
    global $db;

    $stmt = $db->prepare("INSERT INTO Photo VALUES (null, ?, ?)");
    if( $stmt
        && $stmt->bind_param('si', $img_path, $album_id)
        && $stmt->execute()){
        $result = $stmt->get_result();
    }else{
        throw new Exception($db->error);
    }

    return $db->insert_id;
}

function db_link_miniature_to_img($miniature_path, $img_id){
    global $db;

    $stmt = $db->prepare("INSERT INTO Miniature VALUES (null, ?, ?)");
    if( $stmt
        && $stmt->bind_param('is', $img_id, $miniature_path)
        && $stmt->execute()){
        $result = $stmt->get_result();
    }else{
        throw new Exception($db->error);
    }

    return $db->insert_id;
}

?>
