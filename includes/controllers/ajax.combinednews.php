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
		case "add":
			$record = new CombinedNews();
			$record->slug 		= create_slug($_REQUEST['title']);
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];
//			$record->brief 		= $_REQUEST['brief'];
			$record->content	= $_REQUEST['content'];
            $record->schema_code    = $_REQUEST['schema_code'] ?? '';
            $record->faq_schema = buildFaqSchema($_REQUEST);
//			$record->type 		= $_REQUEST['type'];
			/*if($_REQUEST['type']==1){
				$record->image		= serialize(array_values(array_filter($_REQUEST['imageArrayname'])));
			}else{
				$record->source 	= $_REQUEST['source'];
                $record->image		= 'a:0:{}';
			}*/
			//$record->display 	= $_REQUEST['display'];
			// $record->linksrc 	= $_REQUEST['linksrc'];
			// $record->linktype 	= $_REQUEST['linktype'];
            $record->fb_upload  = (!empty($_REQUEST['imageArrayname4']) ? $_REQUEST['imageArrayname4'] : "");
			$record->banner_image = $_REQUEST['imageArrayname2'];
			$record->event_stdate 	= $_REQUEST['event_stdate'];
//			$record->event_endate 	= $_REQUEST['event_endate'];
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
            if(!empty($_REQUEST['galleryArrayname'])){
				$record->gallery 		= serialize(array_values(array_filter($_REQUEST['galleryArrayname'])));
			}
            $record->home_image = !empty($_REQUEST['imageArrayname3']) ? $_REQUEST['imageArrayname3'] : '';
            $record->sortorder	= CombinedNews::find_maximum();
			$record->added_date = registered();
			$record->source 	= $_REQUEST['source'];

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['addedSuccess_'], "CombinedNews '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
				log_action("CombinedNews [".$record->title."]".$GLOBALS['basic']['addedSuccess'],1,3);
			else: $db->rollback();
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['unableToSave']));
			endif;
		break;


		case "edit":
			$record = CombinedNews::find_by_id($_REQUEST['idValue']);

			$record->slug 		= create_slug($_REQUEST['title']);
			$record->title 		= $_REQUEST['title'];
			$record->author 	= $_REQUEST['author'];
//			$record->brief 		= $_REQUEST['brief'];
			$record->content	= $_REQUEST['content'];
            $record->schema_code    = $_REQUEST['schema_code'] ?? '';
            $record->faq_schema = buildFaqSchema($_REQUEST);
            $record->fb_upload  = (!empty($_REQUEST['imageArrayname4']) ? $_REQUEST['imageArrayname4'] : "");
//			$record->type 		= $_REQUEST['type'];
			/*if($_REQUEST['type']==1){
				$record->image		= serialize(array_values(array_filter($_REQUEST['imageArrayname'])));
			    $record->source 	= '';
			}else{
				$record->source 	= $_REQUEST['source'];
				$record->image		= 'a:0:{}';
			}   */
			//$record->display 	= $_REQUEST['display'];
			// $record->linksrc 	= $_REQUEST['linksrc'];
			// $record->linktype 	= $_REQUEST['linktype'];
			$record->banner_image = $_REQUEST['imageArrayname2'];
		$record->event_stdate 	= $_REQUEST['event_stdate'];
//			$record->event_endate 	= $_REQUEST['event_endate'];
			$record->status		= $_REQUEST['status'];
			$record->meta_keywords		= $_REQUEST['meta_keywords'];
			$record->meta_description	= $_REQUEST['meta_description'];
            if(!empty($_REQUEST['galleryArrayname'])){
				$record->gallery 		= serialize(array_values(array_filter($_REQUEST['galleryArrayname'])));
			}
			$record->source 	= $_REQUEST['source'];
			//$record->modified_date 	= registered();
            $record->home_image = !empty($_REQUEST['imageArrayname3']) ? $_REQUEST['imageArrayname3'] : '';

			$db->begin();
			if($record->save()): $db->commit();
			   $message  = sprintf($GLOBALS['basic']['changesSaved_'], "CombinedNews '".$record->title."'");
			   echo json_encode(array("action"=>"success","message"=>$message));
			   log_action("CombinedNews [".$record->title."] Edit Successfully",1,4);
			else: $db->rollback(); echo json_encode(array("action"=>"notice","message"=>$GLOBALS['basic']['noChanges']));
			endif;
		break;

		case "delete":
			$id = $_REQUEST['id'];
			$record = CombinedNews::find_by_id($id);
			$db->begin();
			$res = $db->query("DELETE FROM tbl_conbined_news WHERE id='{$id}'");
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_conbined_news", "sortorder");

			$message  = sprintf($GLOBALS['basic']['deletedSuccess_'], "CombinedNews '".$record->title."'");
			echo json_encode(array("action"=>"success","message"=>$message));
			log_action("CombinedNews  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
		break;

		// Module Setting Sections  >> <<
		case "toggleStatus":
			$id = $_REQUEST['id'];
			$record = CombinedNews::find_by_id($id);
			$record->status = ($record->status == 1) ? 0 : 1 ;
			$record->save();
			echo "";
		break;

		case "bulkToggleStatus":
			$id = $_REQUEST['idArray'];
			$allid = explode("|", $id);
			$return = "0";
			for($i=1; $i<count($allid); $i++){
				$record = CombinedNews::find_by_id($allid[$i]);
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
				$record = CombinedNews::find_by_id($allid[$i]);
				log_action("CombinedNews  [".$record->title."]".$GLOBALS['basic']['deletedSuccess'],1,6);
				$res = $db->query("DELETE FROM tbl_conbined_news WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_conbined_news", "sortorder");

			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "CombinedNews");
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;

		case "sort":
			$id 	 = $_REQUEST['id']; 	// IS a line containing ids starting with : sortIds
			$sortIds = $_REQUEST['sortIds'];
			datatableReordering('tbl_conbined_news', $sortIds, "sortorder", '', '',1);
			$message  = sprintf($GLOBALS['basic']['sorted_'], "CombinedNews");
			echo json_encode(array("action"=>"success","message"=>$message));
		break;

		case "deleteComment":
			$id = $_REQUEST['id'];
			$record = NewsComment::find_by_id($id);
			log_action("Comment [".$record->person_name."]".$GLOBALS['basic']['deletedSuccess'],1,6);
			$db->begin();

			$res   = $db->query("DELETE FROM tbl_news_comment WHERE id='{$id}'");
  		    if($res):$db->commit();	else: $db->rollback();endif;
			reOrder("tbl_news_comment", "sortorder");
			echo json_encode(array("action"=>"success","message"=>"Comment [".$record->person_name."]".$GLOBALS['basic']['deletedSuccess']));
		break;

		// Module Setting Sections  >> <<
		case "SubtoggleStatus":
			$id = $_REQUEST['id'];
			$record = NewsComment::find_by_id($id);
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
				$record = NewsComment::find_by_id($allid[$i]);
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
				$record = NewsComment::find_by_id($allid[$i]);
				log_action("NewsComment  [".$record->person_name."]".$GLOBALS['basic']['deletedSuccess'],1,6);
				$res = $db->query("DELETE FROM tbl_news_comment WHERE id='".$allid[$i]."'");
				$return = 1;
			}
			if($res)$db->commit();else $db->rollback();
			reOrder("tbl_news_comment", "sortorder");

			if($return==1):
				$message  = sprintf($GLOBALS['basic']['deletedSuccess_bulk'], "NewsComment");
				echo json_encode(array("action"=>"success","message"=>$message));
			else:
				echo json_encode(array("action"=>"error","message"=>$GLOBALS['basic']['noRecords']));
			endif;
		break;

		case "subSort":
            $id = $_REQUEST['id'];    // IS a line containing ids starting with : sortIds
            $sortIds = $_REQUEST['sortIds'];
            datatableReordering('tbl_news_comment', $sortIds, "sortorder", '', '',1);
            $message = sprintf($GLOBALS['basic']['sorted_'], "NewsComment");
            echo json_encode(array("action" => "success", "message" => $message));
		break;

	}
?>