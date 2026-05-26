<?php 
	// Load the header files first
	header("Expires: 0"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("cache-control: no-store, no-cache, must-revalidate"); 
	header("Pragma: no-cache");

	// Load necessary files then...
	require_once('../initialize.php');

/**
 * Build the faq_schema JSON string from paired form arrays.
 *
 * Expects $_REQUEST['faq_questions'][] and $_REQUEST['faq_answers'][].
 * Filters out any pair where either the question or the answer is blank.
 * Returns a compact JSON string, or '' when no valid pairs exist.
 *
 * @param array $req  Typically $_REQUEST
 * @return string
 */
function buildFaqSchema(array $req)
{
    $questions = isset($req['faq_questions']) && is_array($req['faq_questions'])
        ? array_values($req['faq_questions']) : [];
    $answers   = isset($req['faq_answers'])   && is_array($req['faq_answers'])
        ? array_values($req['faq_answers'])   : [];

    $items = [];
    foreach ($questions as $i => $q) {
        $q = trim((string)($q ?? ''));
        $a = trim((string)($answers[$i] ?? ''));
        if ($q !== '' && $a !== '') {
            $items[] = ['q' => $q, 'a' => $a];
        }
    }

    return !empty($items)
        ? json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        : '';
}
	
	$action = $_REQUEST['action'];
	
	switch($action) 
	{			
		case "slug":
			$slug=$msg='';
			if(!empty($_REQUEST['title'])) {
				$nslug = create_slug($_REQUEST['title']);	
				$chk = check_slug($_REQUEST['actid'], $nslug);
				if($chk=='1') {					
					$msg="Slug already exists !";				
				}
				else {
					$slug = $nslug;					
				}				
			}
			echo json_encode(array('msgs'=>$msg, 'result'=>$slug));
			break;

		case "add":
			
			$Package = new Package();
			
			$Package->slug 			= $_REQUEST['slug'];
			$Package->title    		= $_REQUEST['title'];	
			$Package->sub_title    	= $_REQUEST['sub_title'];	
			$Package->content   	= $_REQUEST['content'];
            $Package->schema_code   = $_REQUEST['schema_code'] ?? '';
            $Package->faq_schema    = buildFaqSchema($_REQUEST);
			$Package->type 		= $_REQUEST['type'];	
			$Package->meta_title		= $_REQUEST['meta_title'];
			$Package->meta_keywords		= $_REQUEST['meta_keywords'];
			$Package->meta_description	= $_REQUEST['meta_description'];
			$Package->fb_upload  = !empty($_REQUEST['imageArrayname3']) ? $_REQUEST['imageArrayname3'] : '';
			$Package->banner_image	= serialize(array_values(array_filter($_REQUEST['imageArrayname2'])));
			$Package->image	= serialize(array_values(array_filter($_REQUEST['imageArrayname7'])));
			
			$Package->status		= $_REQUEST['status'];
			$Package->sortorder		= Package::find_maximum();
			$Package->added_date 	= registered();
			
			$checkDupliTitle = Package::checkDupliTitle($Package->title);			
			if($checkDupliTitle):
				echo json_encode(array("action"=>"warning","message"=>"Package Title Already Exists."));		
				exit;		
			endif;
			
			$db->begin();
			if($Package->save()):  $db->commit();
			// Global slug table storeSlug(class name, main slug, store id);
			$act_id = $db->insert_id();
			storeSlug('Package', $_REQUEST['slug'], $act_id);
			// End function
			$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Package Image '".$Package->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
			log_action("Package [".$Package->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
		else: $db->rollback();
		echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
	endif;				
	break;
	
	case "edit":			
		$Package = Package::find_by_id($_REQUEST['idValue']);
		
		if($Package->title!=$_REQUEST['title']){
			$checkDupliTitle = Package::checkDupliTitle($_REQUEST['title']);
			if($checkDupliTitle):
				echo json_encode(array("action"=>"warning","message"=>"Package Title is already exist."));		
				exit;		
			endif;
		}
		$Package->fb_upload  = !empty($_REQUEST['imageArrayname3']) ? $_REQUEST['imageArrayname3'] : '';
		$Package->banner_image	= serialize(array_values(array_filter($_REQUEST['imageArrayname2'])));
		$Package->image	= serialize(array_values(array_filter($_REQUEST['imageArrayname7'])));
		$Package->slug 	   = $_REQUEST['slug'];
		$Package->title    = $_REQUEST['title'];	
		$Package->sub_title = $_REQUEST['sub_title'];
        $Package->schema_code   = $_REQUEST['schema_code'] ?? '';
        $Package->faq_schema    = buildFaqSchema($_REQUEST);
		$Package->content  = $_REQUEST['content'];	
		$Package->status   = $_REQUEST['status'];	
		$Package->type 		= $_REQUEST['type'];
		$Package->meta_title		= $_REQUEST['meta_title'];
		$Package->meta_keywords		= $_REQUEST['meta_keywords'];
		$Package->meta_description	= $_REQUEST['meta_description'];
		
		$db->begin();				
		if($Package->save()):$db->commit();	
		// Global slug table storeSlug(class name, main slug, store id);
		$act_id = $_REQUEST['idValue'];
		storeSlug('Package', $_REQUEST['slug'], $act_id);
		// End function
		$message  = sprintf($GLOBALS['basic']['changesSaved_'], "Package '".$Package->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("Package ".$Package->title." Edit Successfully",1,4);
			else:$db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;							
		break;
								
		case "delete":
			$id = $_REQUEST['id'];
			$record = Package::find_by_id($id);
			log_action("Package  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_package WHERE id='{$id}'");
  		    if($res):$db->commit();	else: $db->rollback();endif;
			reOrder("tbl_package", "sortorder");						
			echo json_encode(array("action"=>"success","message"=>"Package  [".$record->title."]".$GLOBALS['basic']['deletedSuccess']));							
		break;
		
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = Package::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$db->begin();						
				$res   =  $record->save();
				   if($res):$db->commit();	else: $db->rollback();endif;
			echo "";
		break;

		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Package::find_by_id($allid[$i]);
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
						$db->query("DELETE FROM tbl_package_sub WHERE type='".$allid[$i]."'");
				$res  = $db->query("DELETE FROM tbl_package WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_package", "sortorder");
			
			if($return==1):
			    $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Package"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;
				
		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_package', $sortIds, "sortorder", '', '',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Package "); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;				

		/*********************** Sub Package Transaction Section *************************/
		case "addSubpackage":
			$record	= new Subpackage();

			$newArr = array();
			$fparent = (isset($_REQUEST['fparent']) and !empty($_REQUEST['fparent']))?$_REQUEST['fparent']:'';
			$feature = (isset($_REQUEST['feature']) and !empty($_REQUEST['feature']))?$_REQUEST['feature']:'';
			if(!empty($fparent) and !empty($feature)){				
				foreach($fparent as $kk=>$vv){ 
					$final_fpt = !empty($fparent[$kk])?$fparent[$kk]:'';
					$final_ft  = !empty($feature[$kk])?$feature[$kk]:'';
					$newArr[$kk] = array($final_fpt,$final_ft); 
				}
			}

			$record->type 			= $_REQUEST['type'];
			$record->slug 			= $_REQUEST['slug'];
			$record->title 			= $_REQUEST['title'];
			$record->detail 		= !empty($_REQUEST['detail'])?$_REQUEST['detail']:'';
			$record->header_image		= !empty($_REQUEST['imageArrayname5'])?$_REQUEST['imageArrayname5']:'';
			$record->image2			= !empty($_REQUEST['imageArrayname2'])?$_REQUEST['imageArrayname2']:'';
			$record->image 			= !empty($_REQUEST['imageArrayname'])? serialize(array_values(array_filter($_REQUEST['imageArrayname']))):'';
			$record->fb_upload      = !empty($_REQUEST['imageArrayname7']) ? $_REQUEST['imageArrayname7'] : '';
			$record->feature		= serialize($newArr);
            $record->schema_code    = $_REQUEST['schema_code'] ?? '';
            $record->faq_schema     = buildFaqSchema($_REQUEST);
			$record->content 		= $_REQUEST['content'];			
			$record->status			= $_REQUEST['status'];
			$record->number_room    = !empty($_REQUEST['number_room'])?$_REQUEST['number_room']:'';
			$record->currency 		= !empty($_REQUEST['currency'])?$_REQUEST['currency']:'';			
			$record->people_qnty 	= !empty($_REQUEST['people_qnty'])?$_REQUEST['people_qnty']:'';
			$record->onep_price 	= !empty($_REQUEST['onep_price'])?$_REQUEST['onep_price']:'';
			$record->twop_price 	= !empty($_REQUEST['twop_price'])?$_REQUEST['twop_price']:'';
			$record->threep_price 	= !empty($_REQUEST['threep_price'])?$_REQUEST['threep_price']:'';

			$record->oneb_price 	= !empty($_REQUEST['oneb_price'])?$_REQUEST['oneb_price']:'';
			$record->twob_price 	= !empty($_REQUEST['twob_price'])?$_REQUEST['twob_price']:'';
			$record->threeb_price 	= !empty($_REQUEST['threeb_price'])?$_REQUEST['threeb_price']:'';

			$record->extra_bed 		= !empty($_REQUEST['extra_bed'])?$_REQUEST['extra_bed']:'';
// 			$record->theater_a		= !empty($_REQUEST['theater_a'])?$_REQUEST['theater_a']:'';
// 			$record->theater_b		= !empty($_REQUEST['theater_b'])?$_REQUEST['theater_b']:'';
// 			$record->theater_c		= !empty($_REQUEST['theater_c'])?$_REQUEST['theater_c']:'';
// 			$record->theater_d		= !empty($_REQUEST['theater_d'])?$_REQUEST['theater_d']:'';
// 			$record->u_shape_a		= !empty($_REQUEST['u_shape_a'])?$_REQUEST['u_shape_a']:'';
// 			$record->u_shape_b		= !empty($_REQUEST['u_shape_b'])?$_REQUEST['u_shape_b']:'';
// 			$record->u_shape_c		= !empty($_REQUEST['u_shape_c'])?$_REQUEST['u_shape_c']:'';
// 			$record->u_shape_d		= !empty($_REQUEST['u_shape_d'])?$_REQUEST['u_shape_d']:'';
// 			$record->round_table_a		= !empty($_REQUEST['round_table_a'])?$_REQUEST['round_table_a']:'';
// 			$record->round_table_b		= !empty($_REQUEST['round_table_b'])?$_REQUEST['round_table_b']:'';
// 			$record->round_table_c		= !empty($_REQUEST['round_table_c'])?$_REQUEST['round_table_c']:'';
// 			$record->round_table_d		= !empty($_REQUEST['round_table_d'])?$_REQUEST['round_table_d']:'';
// 			$record->fixed_seating_a		= !empty($_REQUEST['fixed_seating_a'])?$_REQUEST['fixed_seating_a']:'';
// 			$record->fixed_seating_b		= !empty($_REQUEST['fixed_seating_b'])?$_REQUEST['fixed_seating_b']:'';
// 			$record->fixed_seating_c		= !empty($_REQUEST['fixed_seating_c'])?$_REQUEST['fixed_seating_c']:'';
// 			$record->fixed_seating_d		= !empty($_REQUEST['fixed_seating_d'])?$_REQUEST['fixed_seating_d']:'';
			$record->meta_title		= $_REQUEST['meta_title'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
			$record->sortorder		= Subpackage::find_maximum_byparent("sortorder",$_REQUEST['type']);														
			$record->added_date 	= registered();

			$db->begin();
			if($record->save()): $db->commit();
				// Global slug table storeSlug(class name, main slug, store id);
				$act_id = $db->insert_id();
				storeSlug('Subpackage', $_REQUEST['slug'], $act_id);
				// End function
				$message  = sprintf($GLOBALS['basic']['addedSuccess_'], "Sub Package '".$record->title."'");
				echo json_encode(array("action"=>"success","message"=>$message));
				log_action($message,1,3);
			else: $db->rollback(); echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;								
		break;

		case "editSubpackage":
			$record = Subpackage::find_by_id($_REQUEST['idValue']);

			$newArr = array();
			$fparent = (isset($_REQUEST['fparent']) and !empty($_REQUEST['fparent']))?$_REQUEST['fparent']:'';
			$feature = (isset($_REQUEST['feature']) and !empty($_REQUEST['feature']))?$_REQUEST['feature']:'';
			if(!empty($fparent) and !empty($feature)){				
				foreach($fparent as $kk=>$vv){ 
					$final_fpt = !empty($fparent[$kk])?$fparent[$kk]:'';
					$final_ft  = !empty($feature[$kk])?$feature[$kk]:'';
					$newArr[$kk] = array($final_fpt,$final_ft); 
				}
			}

			$record->type 			= $_REQUEST['type'];
			$record->slug 			= $_REQUEST['slug'];
			$record->title 			= $_REQUEST['title'];
			$record->detail 		= !empty($_REQUEST['detail'])?$_REQUEST['detail']:'';			
			$record->image2			= !empty($_REQUEST['imageArrayname2'])?$_REQUEST['imageArrayname2']:'';	
			$record->header_image		= !empty($_REQUEST['imageArrayname5'])?$_REQUEST['imageArrayname5']:'';
			$record->image 			= !empty($_REQUEST['imageArrayname'])? serialize(array_values(array_filter($_REQUEST['imageArrayname']))):'';
			$record->fb_upload      = !empty($_REQUEST['imageArrayname7']) ? $_REQUEST['imageArrayname7'] : '';
			$record->feature		= serialize($newArr);
            $record->schema_code    = $_REQUEST['schema_code'] ?? '';
            $record->faq_schema     = buildFaqSchema($_REQUEST);
			$record->content 		= $_REQUEST['content'];
			$record->status			= $_REQUEST['status'];						
			$record->number_room    = !empty($_REQUEST['number_room'])?$_REQUEST['number_room']:'';
			$record->currency 		= !empty($_REQUEST['currency'])?$_REQUEST['currency']:'';			
			$record->people_qnty 	= !empty($_REQUEST['people_qnty'])?$_REQUEST['people_qnty']:'';
			$record->onep_price 	= !empty($_REQUEST['onep_price'])?$_REQUEST['onep_price']:'';
			$record->twop_price 	= !empty($_REQUEST['twop_price'])?$_REQUEST['twop_price']:'';
			$record->threep_price 	= !empty($_REQUEST['threep_price'])?$_REQUEST['threep_price']:'';

			$record->oneb_price 	= !empty($_REQUEST['oneb_price'])?$_REQUEST['oneb_price']:'';
			$record->twob_price 	= !empty($_REQUEST['twob_price'])?$_REQUEST['twob_price']:'';
			$record->threeb_price 	= !empty($_REQUEST['threeb_price'])?$_REQUEST['threeb_price']:'';
			
			$record->extra_bed 		= !empty($_REQUEST['extra_bed'])?$_REQUEST['extra_bed']:'';
// 			$record->theater_a		= !empty($_REQUEST['theater_a'])?$_REQUEST['theater_a']:'';
// 			$record->theater_b		= !empty($_REQUEST['theater_b'])?$_REQUEST['theater_b']:'';
// 			$record->theater_c		= !empty($_REQUEST['theater_c'])?$_REQUEST['theater_c']:'';
// 			$record->theater_d		= !empty($_REQUEST['theater_d'])?$_REQUEST['theater_d']:'';
// 			$record->u_shape_a		= !empty($_REQUEST['u_shape_a'])?$_REQUEST['u_shape_a']:'';
// 			$record->u_shape_b		= !empty($_REQUEST['u_shape_b'])?$_REQUEST['u_shape_b']:'';
// 			$record->u_shape_c		= !empty($_REQUEST['u_shape_c'])?$_REQUEST['u_shape_c']:'';
// 			$record->u_shape_d		= !empty($_REQUEST['u_shape_d'])?$_REQUEST['u_shape_d']:'';
// 			$record->round_table_a		= !empty($_REQUEST['round_table_a'])?$_REQUEST['round_table_a']:'';
// 			$record->round_table_b		= !empty($_REQUEST['round_table_b'])?$_REQUEST['round_table_b']:'';
// 			$record->round_table_c		= !empty($_REQUEST['round_table_c'])?$_REQUEST['round_table_c']:'';
// 			$record->round_table_d		= !empty($_REQUEST['round_table_d'])?$_REQUEST['round_table_d']:'';
// 			$record->fixed_seating_a		= !empty($_REQUEST['fixed_seating_a'])?$_REQUEST['fixed_seating_a']:'';
// 			$record->fixed_seating_b		= !empty($_REQUEST['fixed_seating_b'])?$_REQUEST['fixed_seating_b']:'';
// 			$record->fixed_seating_c		= !empty($_REQUEST['fixed_seating_c'])?$_REQUEST['fixed_seating_c']:'';
// 			$record->fixed_seating_d		= !empty($_REQUEST['fixed_seating_d'])?$_REQUEST['fixed_seating_d']:'';
			$record->meta_title		= $_REQUEST['meta_title'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
			
			$db->begin();

			if($record->save()): $db->commit();
				// Global slug table storeSlug(class name, main slug, store id);
				$act_id = $_REQUEST['idValue'];
				storeSlug('Subpackage', $_REQUEST['slug'], $act_id);
				// End function
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "Sub Package '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action($message,1,4);
			else: $db->rollback();echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;	
		break;

		case "deletesubpackage":
			$id = $_REQUEST['id'];
			$record = Subpackage::find_by_id($id);
			log_action("Sub Package [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();

			$db->query("DELETE FROM tbl_package_sub WHERE id='{$id}'");
			$res = $db->query("DELETE FROM tbl_facilityOptions WHERE facility_id='{$id}'");
  		    if($res):$db->commit();	else: $db->rollback();endif;
			reOrder("tbl_package_sub", "sortorder");						
			echo json_encode(array("action"=>"success","message"=>"Sub Package [".$record->title."]".$GLOBALS['basic']['deletedSuccess']));							
		break;

		case "SubtoggleStatus":
			$id = $_REQUEST['id'];
			$record = Subpackage::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$db->begin();						
				$res   =  $record->save();
				if($res):$db->commit();	else: $db->rollback();endif;
			echo "";
		break;

		case "subbulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = Subpackage::find_by_id($allid[$i]);
				$record->status = ($record->status == 1) ? 0 : 1 ;
				$record->save();
			}
			echo "";
		break;
			
		case "subbulkDelete":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			$db->begin();
			for($i=1; $i<count($allid); $i++){
				$record = Subpackage::find_by_id($allid[$i]);
				$res  = $db->query("DELETE FROM tbl_package_sub WHERE id='".$allid[$i]."'");				
				reOrderSub("tbl_package_sub", "sortorder", "type",$record->type);

				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();

			if($return==1):
			    $message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "Package"); 
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;

		case "subSort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			$posId   = Subpackage::field_by_id($id,'type');
			datatableReordering('tbl_package_sub', $sortIds, "sortorder", "type",$posId,1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "Sub Package"); 
			echo json_encode(array("action"=>"success","message"=>$message));
		break;	

		case "getRoomsdetails":
			$result='';
			$getdate = addslashes($_REQUEST['getdate']);
			$roomCat  = Subpackage::getPackage_limit(1);
	    	if($roomCat):
	    		foreach($roomCat as $roomRow){ 
	    			$rec = Subpackage::find_by_id($roomRow->id); 
	    			$nos = json_decode($rec->image, true);
	    			global $db;
	    			$sql = "SELECT ss.season,ss.date_from, ss.date_to, rp.one_person, rp.two_person, rp.three_person
	    					FROM 
	    					tbl_seasion AS ss
	    					INNER JOIN tbl_room_price AS rp
	    					ON ss.id = rp.season_id
	    					WHERE ss.date_to>='$getdate' LIMIT 1";
	    			$dtResult = $db->query($sql);

	    			$sql2 = "SELECT rp.one_person, rp.two_person, rp.three_person
	    			 		FROM 
	    			 		tbl_room_price AS rp
	    			 		WHERE rp.season_id='0' AND rp.room_id= $rec->id LIMIT 1";
	    			$dfltResult = $db->query($sql2);
	    				
	    			$myArr='';
	    			if($db->num_rows($dtResult)>0){
	    				$myArr = $dtResult;
	    			}else{
	    				$myArr = $dfltResult;
	    			}

	    			$romprice = array();
	    			while ($row = $db->fetch_array($myArr)) {
	    				foreach($row as $key=>$val){$$key=$val;}
	    				$romprice = array(1=>$one_person,2=>$two_person,3=>$three_person);
	    			}
	    	  $result.='<div class="main_imgdiv">
	    					<img alt="'.$rec->title.'" src="'.IMAGE_PATH.'subpackage/'.$nos[0].'">
	    				</div>
	    				<div class="main_listing">';
	    				for($i=1; $i<=$rec->people_qnty; $i++){ 
					$result.='<ul>
							 	<li>'.$i.'</li>
							 	<li>'.$rec->currency.' '.$romprice[$i].'</li>
							 	<li>
								 	<select name="" id="" class="select-room" data-person="'.$i.'" data-currency="'.$rec->currency.'" data-price="'.$romprice[$i].'"
                                    data-room="'.$rec->title.'">
								 		<option value="0">0</option>';
				    					 for($j=1; $j<=$rec->no_rooms; $j++){
				    						$result.='<option value="'.$j.'">'.$j.'</option>';
				    					} 
						  $result.='</select>
							 	</li>
							 	<li><span class="ind-total">0</span></li>
							</ul>
							<div class="clear"></div>';
						 } 								
				$result.='</div>
						<div class="clear"></div>';
    	  		 } 
			endif;

			echo json_encode(array("roomresult"=>$result));
		break;
	}
?>