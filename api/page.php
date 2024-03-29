<?php

//check information
  if(!isset($_GET['page_id'])){
      http_response_code(400);
      die;
  }

  $page_id = $_GET['page_id'];

//load librarys
  include("../admin/include/functions.php");
  include("../admin/include/db_connect.php");
  include("../admin/include/db_querys.php");
  include("../admin/include/times.php");

  $return_array = array();

//load general content (title and type => requires join)

  $sql		= "SELECT * FROM page p, page_type t WHERE p.page_type_id = t.page_type_id AND p.page_id = :pageid ";
                                                          
  $pdo 		= new PDO($pdo_mysql, $pdo_db_user, $pdo_db_pwd);

  $statement	= $pdo->prepare($sql);

  $statement->bindParam(':pageid', $page_id);

  $statement->execute();

  $db_array = array();

  while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      foreach ($row as $key => $value){
          $row[$key] = db_parse($value);
      }
      array_push($db_array, $row);
  }

//if no page is found, return error 404
  if(count($db_array)>0){
    $return_array = $db_array[0];
  }else{
    http_response_code(404);
    die;
  }
    
//load page_content
  $page_content = array();
//load bios first


  $sql		= "SELECT * FROM page_bio WHERE page_id = :pageid AND page_bio_visible = 1 ORDER BY page_bio_name_de";
                                                          
  $pdo 		= new PDO($pdo_mysql, $pdo_db_user, $pdo_db_pwd);

  $statement	= $pdo->prepare($sql);

  $statement->bindParam(':pageid', $page_id);

  $statement->execute();

  $bios = array();

  while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      foreach ($row as $key => $value){
          $row[$key] = db_parse($value);
      }
      $where = array();
      $wh['col'] = "page_bio_id";
      $wh['typ'] = "=";
      $wh['val'] =  $row['page_bio_id'];
      array_push($where, $wh);
    
      $order = array();
      $or['col'] = "page_bio_gallery_order";
      $or['dir'] = "ASC";
      array_push($order, $or);
    
      $row['gallery'] = db_select("page_bio_gallery", $where, $order);

      array_push($bios, $row);
  }

  

  $page_content['bio'] = $bios;
 

//load blog-posts second


  $sql		= "SELECT * FROM page_blog b, page_blog_content_type t WHERE t.page_blog_content_type_id = b.page_blog_content_type_id AND b.page_id = :page_id AND b.page_blog_show = 1 ORDER BY b.page_blog_order, b.page_blog_headline_de";
                                                          
  $pdo 		= new PDO($pdo_mysql, $pdo_db_user, $pdo_db_pwd);

  $statement	= $pdo->prepare($sql);

  $statement->bindParam(':page_id', $page_id);

  $statement->execute();

  $blog = array();

  while($row = $statement->fetch(PDO::FETCH_ASSOC)){
      foreach ($row as $key => $value){
          $row[$key] = db_parse($value);
      }
      array_push($blog, $row);
  }
    

  

  

  $page_content['blog'] = $blog;


//load gallerys
    

  $where = array();
  $wh['col'] = "page_id";
  $wh['typ'] = "=";
  $wh['val'] =  $page_id;
  array_push($where, $wh);

  $order = array();
  $or['col'] = "page_gallery_order";
  $or['dir'] = "ASC";
  array_push($order, $or);


  $sql = "SELECT * FROM page_gallery WHERE page_id  = :page_id AND page_gallery_visible = 1 ORDER BY page_gallery_order ASC;";

$gallery = array();

    $pdo 		= new PDO($pdo_mysql, $pdo_db_user, $pdo_db_pwd);

    $statement	= $pdo->prepare($sql);

    $statement->bindParam(':page_id', $page_id);

    $statement->execute();

    $blog = array();

    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
        foreach ($row as $key => $value){
            $row[$key] = db_parse($value);
        }
        array_push($gallery, $row);
    }




  $page_content['gallery'] = $gallery;



//add page_content to return array
  $return_array['page_content'] = $page_content;

//convert array to json and deliver
  if(isset($_GET['debug'])){
    echo"<pre>";
    print_r($return_array);
    echo"</pre>";
    die;
  }

  header('Content-Type: application/json');
  echo json_encode($return_array);

?>