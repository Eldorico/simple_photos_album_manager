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

function db_category_exists($id_category){
    global $db;

    $stmt = $db->prepare("SELECT * FROM Category WHERE id_category = ?");
    if( $stmt
        && $stmt->bind_param('i', $id_category)
        && $stmt->execute()){
        $result = $stmt->get_result();
        $category_exists = ($result->num_rows == 1) ? true : false;
    }else{
        throw new Exception($db->error);
    }

    return $category_exists;
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

function db_get_albums_info($cat_id){
    global $db;

    // with this we won't have to make a stmt as the unique parameter is allready checked by db_category_exists()
    if(!db_category_exists($cat_id)){
        return array("error" => array("err_msg" => "invalid category\n", "status_code" => FORBIDDEN_CODE));
    }

    $albums = array();
    $albums_tables_info = $db->query("SELECT * FROM Album WHERE category = $cat_id");
    while($album_row = $albums_tables_info->fetch_assoc()){
        $album_photos = array();
        $album_photos_rows = $db->query("SELECT * FROM Photo WHERE id_album = " . $album_row['id_album']);
        while($album_photos_row = $album_photos_rows->fetch_assoc()){
            $album_photos[] = $album_photos_row['id_photo'];
        }
        $album_row['photos'] = $album_photos;
        $albums[] = $album_row;
    }

    return $albums;
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

/**
* @description:
* @param: $imgId
* @param: $miniature (boolean) : if true, returns the path miniature version of img id.
*         else the normal img path
*/
function db_get_img_path($imgId, $miniature){
    global $db;

    $table = $miniature ? 'Miniature' : 'Photo';

    $stmt = $db->prepare("SELECT * FROM $table WHERE id_photo = ?");
    if( $stmt
        && $stmt->bind_param('i', $imgId)
        && $stmt->execute()){
        $result = $stmt->get_result();
    }else{
        throw new Exception($db->error);
    }

    if($result->num_rows == 0){
        return array("error" => "imageId doesnt corresponds to an image");
    }

    if($miniature){
        return $result->fetch_assoc()['miniature_full_path'];
    }else{
        return $result->fetch_assoc()['img_full_path'];
    }
}

function db_get_all_urls_from_album($albumId){
    global $db;

    $stmt = $db->prepare("  SELECT Photo.img_full_path, Photo.id_photo, Miniature.miniature_full_path FROM Photo
                            JOIN Miniature on Photo.id_photo = Miniature.id_photo
                            WHERE Photo.id_album = ?");
    if( $stmt
        && $stmt->bind_param('i', $albumId)
        && $stmt->execute()){
        $result = $stmt->get_result();
    }else{
        throw new Exception($db->error);
    }

    $to_return = array();
    while($row = $result->fetch_assoc()){
        $to_return[] = $row;
    }
    return $to_return;
}

?>
