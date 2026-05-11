<?php 
	// Load the header files first
	header("Expires: 0"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("cache-control: no-store, no-cache, must-revalidate"); 
	header("Pragma: no-cache");

	// Load necessary files then...
	require_once('../initialize.php');
	
	$action = $_REQUEST['action'];
	
	switch($action) 
	{		
		case "add":	
			$record = new Movies();
			
			$record->slug 			= create_slug($_REQUEST['title']);
			$record->title 			= $_REQUEST['title'];
			$record->image			= $_REQUEST['imageArrayname'];
			$record->banner_image	= $_REQUEST['imageArrayname2'];
			$record->release_date 	= $_REQUEST['release_date'];			
			$record->youtube_video  = serialize($_REQUEST['youtube_video']);
			$record->brief			= $_REQUEST['brief'];	
			$record->content		= $_REQUEST['content'];	
			$record->movie_type		= $_REQUEST['movie_type'];	
			$record->action_type	= $_REQUEST['action_type'];		
			$record->status			= $_REQUEST['status'];			
			$record->sortorder		= Movies::find_maximum();
			$record->added_date 	= registered();
			$record->meta_keywords	= $_REQUEST['meta_keywords'];
			$record->meta_description = $_REQUEST['meta_description'];

			$checkDupliTitle=Movies::checkDupliTitle($record->title);			
			if($checkDupliTitle):
				echo json_encode(array("action"=>"warning","message"=>"Movies Title Already Exists."));		
				exit;		
			endif;
			
			if(empty($_REQUEST['imageArrayname'])):				
				echo json_encode(array("action"=>"warning","message"=>"Required Upload Image !"));
				exit;					
			endif;
			
			$db->begin();
			if($record->save()): $db->commit();
			   	$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Movies '".$record->title."'");
				echo json_encode(array("action"=>"success","message"=>$message));
				log_action("Movies [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":
			$record = Movies::find_by_id($_REQUEST['idValue']);
			
			if($record->title!=$_REQUEST['title']){
				$checkDupliTitle=Movies::checkDupliTitle($_REQUEST['title']);
				if($checkDupliTitle):
					echo json_encode(array("action"=>"warning","message"=>"Movies title is already exist."));		
					exit;		
				endif;
			}

			$record->slug 			= create_slug($_REQUEST['title']);
			$record->title 			= $_REQUEST['title'];
			if(!empty($_REQUEST['imageArrayname'])):				
				$record->image		= $_REQUEST['imageArrayname'];				
			endif;	
			$record->banner_image	= $_REQUEST['imageArrayname2'];
			$record->release_date 	= $_REQUEST['release_date'];			
			$record->youtube_video  = serialize($_REQUEST['youtube_video']);
			$record->brief			= $_REQUEST['brief'];	
			$record->content		= $_REQUEST['content'];	
			$record->movie_type		= $_REQUEST['movie_type'];	
			$record->action_type	= $_REQUEST['action_type'];		
			$record->status			= $_REQUEST['status'];	

			$record->meta_keywords	= $_REQUEST['meta_keywords'];
			$record->meta_description = $_REQUEST['meta_description'];

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Movies '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Movies [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Movies::find_by_id($id);
			log_action("Moviess  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->query("DELETE FROM tbl_movies WHERE id='{$id}'");
			
			reOrder("tbl_movies", "sortorder");			
			
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Movies '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));					
			log_action("Movies  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Movies::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Movies::find_by_id($allid[$i]);
				$record->status = ($record->status == 1) ? 0 : 1 ;
				$record->save();
			}
			echo "";
		break;
			
		case "bulkDelete":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			$db->begin();
			for($i=1; $i<count($allid); $i++){
				$record = Movies::find_by_id($allid[$i]);
				log_action("Movies  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);				
				$res = $db->query("DELETE FROM tbl_movies WHERE id='".$allid[$i]."'");				
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_movies", "sortorder");
			
			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Movies"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":			
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Movies::field_by_id($id,'action_type');
			datatableReordering('tbl_movies', $sortIds, "sortorder", "action_type",$posId,1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Movies"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;
	}
?>