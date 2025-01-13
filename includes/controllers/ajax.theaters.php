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
		case "filteractivity":
			$desId = addslashes($_REQUEST['destid']);
			$selId = addslashes($_REQUEST['selct']);
			$rec = Theaters::get_all_filterdata($desId,$selId);	
			echo json_encode(array("action"=>"success","result"=>$rec))	;
		break;

		case "add":	
			$record = new Theaters();
						
			$record->slug 			= create_slug($_REQUEST['title']);
			$record->title 			= $_REQUEST['title'];
			// $record->image		= $_REQUEST['imageArrayname'];	
			$record->address		= $_REQUEST['address'];
			$record->nserver_name	= $_REQUEST['nserver_name'];
			$record->ndta_service	= $_REQUEST['ndta_service'];
			$record->cserver_name	= $_REQUEST['cserver_name'];
			$record->cdta_service	= $_REQUEST['cdta_service'];
			$record->showing_date 	= $_REQUEST['showing_date'];
			$record->coming_date 	= $_REQUEST['coming_date'];
			$record->showing_movies = isset($_REQUEST['showing_movies'])?serialize($_REQUEST['showing_movies']):'';
			$record->coming_movies 	= isset($_REQUEST['coming_movies'])?serialize($_REQUEST['coming_movies']):'';
			$record->status			= $_REQUEST['status'];
			$record->sortorder		= Theaters::find_maximum_byparent("sortorder",0);
			$record->added_date 	= registered();
			
			$checkDupliName=Theaters::checkDupliName($record->title);			
			if($checkDupliName):
				echo json_encode(array("action"=>"warning","message"=>"Theaterss Title Already Exists."));		
				exit;		
			endif;
			$db->begin();
			if($record->save()): $db->commit();

				$rcid = $db->insert_id();
				// Insert Showing Movie in Relation table
				if(isset($_REQUEST['showing_movies'])){
					foreach($_REQUEST['showing_movies'] as $k=>$sr){
						$query = "SELECT COUNT(theaters_id) AS srtot FROM tbl_movie_theaters WHERE theaters_id='$rcid' AND movies_id='$sr' AND type='1' ";
						$sql = $db->query($query);
						$ret = $db->fetch_array($sql);
						if($ret['srtot']=='0'){
							$insquery = "INSERT INTO tbl_movie_theaters SET theaters_id='$rcid', movies_id='$sr', type='1' ";
							$db->query($insquery);
						}
					}
				}

				// Insert Coming Movie in Relation table
				if(isset($_REQUEST['coming_movies'])){
					foreach($_REQUEST['coming_movies'] as $k=>$cr){
						$query2 = "SELECT COUNT(theaters_id) AS crtot FROM tbl_movie_theaters WHERE theaters_id='$rcid' AND movies_id='$cr' AND type='2' ";
						$sql2 = $db->query($query2);
						$ret2 = $db->fetch_array($sql2);
						if($ret2['crtot']=='0'){
							$insquery2 = "INSERT INTO tbl_movie_theaters SET theaters_id='$rcid', movies_id='$cr', type='2' ";
							$db->query($insquery2);
						}
					}
				}

				$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Theaters '".$record->title."'");
				echo json_encode(array("action"=>"success","message"=>$message));
				log_action($message,1,3);
			else: $db->rollback(); echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;
			
		case "edit":

			$record = Theaters::find_by_id($_REQUEST['idValue']);
						
			if($record->title!=$_REQUEST['title']){
				$checkDupliName=Theaters::checkDupliName($_REQUEST['title']);
				if($checkDupliName):
					echo json_encode(array("action"=>"warning","message"=>"Theaterss name is already exist."));		
					exit;		
				endif;
			}
			
			$record->slug 			= create_slug($_REQUEST['title']);
			$record->title 			= $_REQUEST['title'];
			// $record->image		= $_REQUEST['imageArrayname'];
			$record->address		= $_REQUEST['address'];
			$record->nserver_name	= $_REQUEST['nserver_name'];
			$record->ndta_service	= $_REQUEST['ndta_service'];
			$record->cserver_name	= $_REQUEST['cserver_name'];
			$record->cdta_service	= $_REQUEST['cdta_service'];
			$record->showing_date 	= $_REQUEST['showing_date'];
			$record->coming_date 	= $_REQUEST['coming_date'];
			$record->showing_movies = isset($_REQUEST['showing_movies'])?serialize($_REQUEST['showing_movies']):'';
			$record->coming_movies 	= isset($_REQUEST['coming_movies'])?serialize($_REQUEST['coming_movies']):'';
			$record->status			= $_REQUEST['status'];

			// Insert Showing Movie in Relation table
			if(isset($_REQUEST['showing_movies'])){
				foreach($_REQUEST['showing_movies'] as $k=>$sr){
					$query = "SELECT COUNT(theaters_id) AS srtot FROM tbl_movie_theaters WHERE theaters_id='$record->id' AND movies_id='$sr' AND type='1' ";
					$sql = $db->query($query);
					$ret = $db->fetch_array($sql);
					if($ret['srtot']=='0'){
						$insquery = "INSERT INTO tbl_movie_theaters SET theaters_id='$record->id', movies_id='$sr', type='1' ";
						$db->query($insquery);
					}
				}
			}

			// Insert Coming Movie in Relation table
			if(isset($_REQUEST['coming_movies'])){
				foreach($_REQUEST['coming_movies'] as $k=>$cr){
					$query2 = "SELECT COUNT(theaters_id) AS crtot FROM tbl_movie_theaters WHERE theaters_id='$record->id' AND movies_id='$cr' AND type='2' ";
					$sql2 = $db->query($query2);
					$ret2 = $db->fetch_array($sql2);
					if($ret2['crtot']=='0'){
						$insquery2 = "INSERT INTO tbl_movie_theaters SET theaters_id='$record->id', movies_id='$cr', type='2' ";
						$db->query($insquery2);
					}
				}
			}

			$db->begin();
			if($record->save()):$db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Theaters '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action($message,1,4);
			else: $db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;
			
		case "delete":
			$id = $_REQUEST['id'];
			$record = Theaters::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_theaters WHERE id='{$id}'");
			if($res):$db->commit();	else: $db->rollback();endif;
			reOrderSub("tbl_theaters", "sortorder", "parentOf",$record->parentOf);
			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "Theaters '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));	
			log_action("Theaterss  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;
		
		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Theaters::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;
			
		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Theaters::find_by_id($allid[$i]);
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
				$res  = $db->query("DELETE FROM tbl_theaters WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_theaters", "sortorder");
			
			if($return==1):
			    $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Theaters"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
			
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Theaters::field_by_id($id,'parentOf');
			datatableReordering('tbl_theaters', $sortIds, "sortorder", "parentOf",$posId);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Theaters"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;	
	}
?>