<?php

if(!isset($page_bio_id)){
    if(isset($_GET['page_bio_id'])){
       $page_bio_id = $_GET['page_bio_id'];
    }else{
        include("400.php");
        include("include/html_footer.php");
        die;
    }
}

    $where = array();
    $wh['col'] = "page_bio_id";
    $wh['typ'] = "=";
    $wh['val'] = $page_bio_id;
    array_push($where, $wh);

    $db_array = db_select("page_bio", $where);

if(count($db_array)>0){
    $data = $db_array[0];
}else{
    include("999.php");
    include("include/html_footer.php");
    die;
}

?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo($global_application_name_header);?> Galerie zur Biografie von <?php echo $data['page_bio_name_de'];?> bearbeiten</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Startseite</a></li>
              <li class="breadcrumb-item">Biografie</li>
              <li class="breadcrumb-item"><?php echo $data['page_bio_name_de'];?></li>
              <li class="breadcrumb-item active">Galerie</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<?php include("include/html_result.php"); ?>





<!-- Main content -->
<section class="content">


<!-- <div class="row">
  <div class="col-12">
    <div class="card bg-danger">
      <div class="card-header">
        <h3 class="card-title">Hinweis</h3>
        <div class="card-tools"></div>
      </div>
      <div class="card-body" style="height: auto;">
      Die Einträge auf dieser Seite stellen die Kern-Konfiguration des Front-Ends dar. Es ist nicht empfohlen, Einträge zu löschen oder Keys zu ändern, sofern nicht auch der Quellcode angepasst wird. Das Ändern der Values stellt kein Problem dar.
      </div>
    </div>
  </div>
</div> -->



<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Galerie verwalten</h3>

				<div class="card-tools">
					<a href="index.php?page=bio_gallery_add" title="neuen Galeriepunkt anlegen" class="btn btn-primary"><span class="fa fa-plus-circle"> </span> neu</a>
				</div>
			</div>
				
			<div class="card-body table-responsive p-0" style="height: auto;">
				<table class="table table-head-fixed">
					<thead>
							<tr>
								<th>Text </th>
								<th>Bild</th>
								<th></th>
								
								
								<th>letzte Änderung</th>
								<th>Aktionen</th>
								
							</tr>
						</thead>
						
						<tbody >
							<?php
							
                               

                                $sql		= "SELECT * FROM page_bio_gallery WHERE page_bio_id = :bioid ORDER BY page_bio_gallery_order";
                                                        
                                $pdo 		= new PDO($pdo_mysql, $pdo_db_user, $pdo_db_pwd);

                                $statement	= $pdo->prepare($sql);

                                $statement->bindParam(':bioid', $data['page_bio_id']);

                                $statement->execute();

                                $db_array = array();

                                while($row = $statement->fetch(PDO::FETCH_ASSOC)){
                                    foreach ($row as $key => $value){
                                        $row[$key] = db_parse($value);
                                    }
                                    array_push($db_array, $row);
                                }
						
							

						
							foreach($db_array as $line){
								
								$page_bio_gallery_id		    	= $line['page_bio_gallery_id'];
								$page_bio_gallery_order				= $line['page_bio_gallery_order'];
								$page_bio_gallery_image_href_de		= $line['page_bio_gallery_image_href_de'];
								$page_bio_gallery_image_href_en		= $line['page_bio_gallery_image_href_en'];
								$page_bio_gallery_image_alt_de 		= $line['page_bio_gallery_image_alt_de'];
								$page_bio_gallery_image_alt_en 		= $line['page_bio_gallery_image_alt_en'];
								$page_bio_gallery_text_de 			= $line['page_bio_gallery_text_de'];
								
								$modify_ts   					= UnixToTime($line['page_bio_gallery_edit_ts']);
								$modify_id   					= db_get_user($line['page_bio_gallery_edit_id'])['user_full'];

                                

								$target_dir = "../upload/bio_gallery/img/";
								$noimg = $target_dir."noimg.png";

								$file_de = $target_dir.$page_bio_gallery_image_href_de;
								$file_en = $target_dir.$page_bio_gallery_image_href_en;

								if(is_file($file_de)){
									$image_de = $file_de;
								}else{
									$image_de = $noimg;
								}

								if(is_file($file_en)){
									$image_en = $file_en;
								}else{
									$image_en = $noimg;
								}
                              
								echo "
									<tr>
										<th>$page_bio_gallery_text_de</th>
										<td><img src='$image_de' width='75%' alt='$page_bio_gallery_image_alt_de'><br>$page_bio_gallery_image_alt_de</td>
										<td><img src='$image_en' width='75%' alt='$page_bio_gallery_image_alt_en'><br>$page_bio_gallery_image_alt_en</td>
										
										
										
										<td>$modify_ts<br>$modify_id</td>
										<td><a href='index.php?page=bio_gallery_edit&bio_gallery_id=$page_bio_gallery_id'><span class='fa fa-edit'></span></a> &nbsp;&nbsp;
										<a href='index.php?page=bio_gallery_edit_image&page_bio_gallery_id=$page_bio_gallery_id'><span class='fa fa-image'></span></a> &nbsp;&nbsp;
										<a href='index.php?page=bio_gallery_delete&bio_gallery_id=$page_bio_gallery_id' class='text-danger'><span class='fa fa-trash'></span></a></td>
									</tr>
								
								";


							}
								
								
							
							?>
						
						</tbody>
				
				
				</table>
	  
	  
			</div>
		</div>
	</div>
</div>