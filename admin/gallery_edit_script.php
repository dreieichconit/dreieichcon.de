<?php

    /*
        Array
        (
            [page_id] => 4
            [page_gallery_order] => 34
            [page_id_link] => 4
            [page_gallery_text_de] => lorem
            [page_gallery_text_en] => upsum
        )
    */

    $data['page_id']                    = $_POST['page_id'];
    $data['page_gallery_order']         = $_POST['page_gallery_order'];

    if(isset($_POST['page_id_link'])&& $_POST['page_id_link']>0){
        $data['page_id_link']               = $_POST['page_id_link'];
    }else{
        $data['page_id_link'] = null;
    }


    if(isset($_POST['page_gallery_visible']) && $_POST['page_gallery_visible'] == "show"){
        $data['page_gallery_visible']            = 1;
    }else{
        $data['page_gallery_visible']            = 0;
    }


$data['page_gallery_href']       = $_POST['page_gallery_href'];
    
    $data['page_gallery_text_de']       = $_POST['page_gallery_text_de'];
    $data['page_gallery_text_en']       = $_POST['page_gallery_text_en'];

    $data['page_gallery_edit_id']       = $_SESSION['admin_user_id'];
    $data['page_gallery_edit_ts']       = time();

    $where = array();
    $wh['col'] = "page_gallery_id";
    $wh['typ'] = "=";
    $wh['val'] = $_POST['page_gallery_id'];
    array_push($where, $wh);

    $query		= "Galerie-Punkt bearbeiten";
    $db_result 	= db_update("page_gallery", $data, $where);

    
    include("gallery.php");













?>