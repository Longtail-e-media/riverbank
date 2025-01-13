<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename  = "tbl_theaters"; // Database table name
$moduleId 		  = 19;				// module id >>>>> tbl_modules
$moduleFoldername = "theaters";		// Image folder name

if(isset($_GET['page']) && $_GET['page'] == "theaters" && isset($_GET['mode']) && $_GET['mode']=="list"):	
clearImages($moduleTablename, $moduleFoldername);
clearImages($moduleTablename, $moduleFoldername."/thumbnails");
?>
<h3>
List Theaters
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewTheaters();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-plus-square"></i>
    </span>
    <span class="button-content"> Add New </span>
</a>
</h3>
<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">    
    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
        <thead>
            <tr>
               <th style="display:none;"></th>
               <th class="text-center"><input class="check-all" type="checkbox" /></th>
               <th>Title</th>
               <th>Address</th>   
               <th>Now Showing</th>
               <th>Next Release</th>
               <th class="text-center"><?php echo $GLOBALS['basic']['action'];?></th>
            </tr>
        </thead> 
            
        <tbody>
            <?php $parentId = (isset($_REQUEST['id']) and !empty($_REQUEST['id']))?addslashes($_REQUEST['id']):0;
                $records  =  Theaters::find_all_byparnt($parentId);	
				  foreach($records as $key=>$record): ?>    
            <tr id="<?php echo $record->id;?>">
            	<td style="display:none;"><?php echo $key+1;?></td>
                <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id;?>" /></td>
                <td><div class="col-md-7">
                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id;?>);" class="loadingbar-demo" title="<?php echo $record->title;?>"><?php echo $record->title;?></a>
                    </div>
                </td>      
                <td><?php echo $record->address;?></td>
                <td><?php $resshow='';
                if(!empty($record->showing_movies)){
                    $sRec = unserialize($record->showing_movies);
                    $sArr = array();
                    foreach($sRec as $sRow){
                        $sArr[] = Movies::getmoviesName($sRow);
                    }
                    $resshow.= implode(' , ', $sArr);
                } 
                echo $resshow; ?></td>
                <td><?php $rescome='';
                if(!empty($record->coming_movies)){
                    $cRec = unserialize($record->coming_movies);
                    $cArr = array();
                    foreach($cRec as $cRow){
                        $cArr[] = Movies::getmoviesName($cRow);
                    }
                    $rescome.= implode(' , ', $cArr);
                }                 
                echo $rescome; ?></td>                     
                <td class="text-center">
					<?php	
                        $statusImage = ($record->status == 1) ? "bg-green" : "bg-red" ; 
                        $statusText = ($record->status == 1) ? $GLOBALS['basic']['clickUnpub'] : $GLOBALS['basic']['clickPub'] ; 
                    ?>                                             
                    <a href="javascript:void(0);" class="btn small <?php echo $statusImage;?> tooltip-button statusToggler" data-placement="top" title="<?php echo $statusText;?>" status="<?php echo $record->status;?>" id="imgHolder_<?php echo $record->id;?>" moduleId="<?php echo $record->id;?>">
                        <i class="glyph-icon icon-flag"></i>
                    </a>
                    <a href="javascript:void(0);" class="loadingbar-demo btn small bg-blue-alt tooltip-button" data-placement="top" title="Edit" onclick="editRecord(<?php echo $record->id;?>);">
                        <i class="glyph-icon icon-edit"></i>
                    </a>
                    <a href="javascript:void(0);" class="btn small bg-red tooltip-button" data-placement="top" title="Remove" onclick="recordDelete(<?php echo $record->id;?>);">
                        <i class="glyph-icon icon-remove"></i>
                    </a>
					<input name="sortId" type="hidden" value="<?php echo $record->id;?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="pad0L col-md-2">
    <select name="dropdown" id="groupTaskField" class="custom-select">
        <option value="0"><?php echo $GLOBALS['basic']['choseAction'];?></option>
        <option value="delete"><?php echo $GLOBALS['basic']['delete'];?></option>
        <option value="toggleStatus"><?php echo $GLOBALS['basic']['toggleStatus'];?></option>
    </select>
    </div>
    <a class="btn medium primary-bg" href="javascript:void(0);" id="applySelected_btn">
        <span class="glyph-icon icon-separator float-right">
          <i class="glyph-icon icon-cog"></i>
        </span>
        <span class="button-content"> Click </span>
    </a>
</div>

<?php elseif(isset($_GET['mode']) && $_GET['mode'] == "addEdit"): 
if(isset($_GET['id']) && !empty($_GET['id'])):
	$theatersId 	= addslashes($_REQUEST['id']);
	$theatersInfo   = Theaters::find_by_id($theatersId);
	$status 		= ($theatersInfo->status==1)?"checked":" ";
	$unstatus 	    = ($theatersInfo->status==0)?"checked":" ";
    $ndtaservice = ($theatersInfo->ndta_service==1)?"checked":" ";
    $nundtaservice = ($theatersInfo->ndta_service==0)?"checked":" ";
    $cdtaservice = ($theatersInfo->cdta_service==1)?"checked":" ";
    $cundtaservice = ($theatersInfo->cdta_service==0)?"checked":" ";
endif;	
?>
<h3>
<?php echo (isset($_GET['id']))?'Edit Theaters':'Add Theaters';?>
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewtheaterslist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>

<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
    	<form action="" class="col-md-12 center-margin" id="theaters_frm">                                 	
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Title :
                    </label>
                </div>                
                <div class="form-input col-md-10">
                    <input placeholder="Theaters Title" class="col-md-6 validate[required,length[0,100]]" type="text" name="title" id="title" value="<?php echo !empty($theatersInfo->title)?$theatersInfo->title:"";?>">
                </div>                
            </div>  

            <!-- <div class="form-row add-image">
                <div class="form-label col-md-2">
                    <label for="">
                        Image :
                    </label>
                </div> 
                
                <?php //if(!empty($theatersInfo->image)):?>
                <div class="col-md-3" id="removeSavedimg<?php //echo $theatersInfo->id;?>">
                    <div class="infobox info-bg">                                                               
                        <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php 
                                    // if(file_exists(SITE_ROOT."images/theaters/".$theatersInfo->image)):
                                    //     $filesize = filesize(SITE_ROOT."images/theaters/".$theatersInfo->image);
                                    //     echo 'Size : '.getFileFormattedSize($filesize);
                                    // endif;
                                ?>
                            </span> 
                            <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedTheatersimage(<?php //echo $theatersInfo->id;?>);">
                                <i class="glyph-icon icon-trash-o"></i>
                            </a>                                                       
                        </div>
                        <img src="<?php //echo IMAGE_PATH.'theaters/thumbnails/'.$theatersInfo->image;?>"  style="width:100%"/>                                                                                   
                    </div> 
                </div>
                <?php //endif;?>
                <div class="form-input col-md-10 uploader">          
                   <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (<?php //echo Module::get_properties($moduleId,'imgwidth');?> px X <?php //echo Module::get_properties($moduleId,'imgheight');?> px)</small></label>
                </div>                
                <!-- Upload user image preview
                <div id="preview_Image"><input type="hidden" name="imageArrayname" value="<?php //echo !empty($theatersInfo->image)?$theatersInfo->image:"";?>" class="" /></div>
            </div> -->

    		<div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Address :
                    </label>
                </div>
                <div class="form-input col-md-10">  
                    <input type="text" placeholder="Address" name="address" id="address" class="col-md-6 validate[required]" value="<?php echo !empty($theatersInfo->address)?$theatersInfo->address:"";?>">                                  
                </div>                           
            </div>  

            <div class="row">
                <div class="col-md-6">
                    <div class="form-label ">
                        <label for="">
                            Showing Date :
                        </label>
                    </div>
                    <div class="form-input ">  
                        <input type="text" placeholder="Showing Date" name="showing_date" id="showing_date" class="col-md-6" value="<?php echo !empty($theatersInfo->showing_date)?$theatersInfo->showing_date:"";?>">                                  
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-label ">
                        <label for="">
                            Release Date :
                        </label>
                    </div>
                    <div class="form-input ">  
                        <input type="text" placeholder="Coming Date" name="coming_date" id="coming_date" class="col-md-6" value="<?php echo !empty($theatersInfo->coming_date)?$theatersInfo->coming_date:"";?>">                                  
                    </div>  
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-label">
                        <label for="">
                            Name of Servers :
                        </label>
                    </div>                
                    <div class="form-input">
                        <input placeholder="Name of Servers" class="col-md-6 validate[required]" type="text" name="nserver_name" id="nserver_name" value="<?php echo !empty($theatersInfo->nserver_name)?$theatersInfo->nserver_name:"";?>">
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-label">
                        <label for="">
                            Name of Servers :
                        </label>
                    </div>                
                    <div class="form-input">
                        <input placeholder="Name of Servers" class="col-md-6 validate[required]" type="text" name="cserver_name" id="cserver_name" value="<?php echo !empty($theatersInfo->cserver_name)?$theatersInfo->cserver_name:"";?>">
                    </div> 
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-label">
                        <label for="">
                            Has DTA Service ? :
                        </label>
                    </div>           
                    <div class="form-checkbox-radio">
                        <input type="radio" class="custom-radio" name="ndta_service" id="check1" value="1" <?php echo !empty($ndtaservice)?$ndtaservice:"";?>>
                        <label for="">Yes</label>
                        <input type="radio" class="custom-radio" name="ndta_service" id="check0" value="0" <?php echo !empty($nundtaservice)?$nundtaservice:"checked";?>>
                        <label for="">No</label>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-label">
                        <label for="">
                            Has DTA Service ? :
                        </label>
                    </div>           
                    <div class="form-checkbox-radio">
                        <input type="radio" class="custom-radio" name="cdta_service" id="check1" value="1" <?php echo !empty($cdtaservice)?$cdtaservice:"";?>>
                        <label for="">Yes</label>
                        <input type="radio" class="custom-radio" name="cdta_service" id="check0" value="0" <?php echo !empty($cundtaservice)?$cundtaservice:"checked";?>>
                        <label for="">No</label>
                    </div>  
                </div>
            </div>

            <div class="row">
                <!-- Now Showing -->
                <?php $nsmovRec = Movies::getmoviestype_by(1); ?>
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">Now Showing Movies:</label>                            
                            <?php if($nsmovRec){ ?>
                                <div class="form-input">                                                          
                                <?php foreach($nsmovRec as $nsmovRow){
                                    $selshow='';
                                    if(!empty($theatersInfo->showing_movies)){
                                        $saveShow = unserialize($theatersInfo->showing_movies);
                                        $selshow = in_array($nsmovRow->id, $saveShow)?'checked':'';
                                    } ?>
                                    <div class="checkbox">
                                        <input type="checkbox" name="showing_movies[]" value="<?php echo $nsmovRow->id; ?>" class="check-class" <?php echo $selshow; ?>><?php echo $nsmovRow->title;?>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php } ?>
                        </div>                                                      
                    </div>
                </div>

                <!-- Next Release -->
                <?php $nrmovRec = Movies::getmoviestype_by(2); ?>
                <div class="col-md-6">
                    <div class="form-row">
                        <div class="form-label col-md-12">
                            <label for="">Next Release Movies:</label>
                            <?php if($nrmovRec){ ?>
                                <div class="form-input"> 
                                <?php foreach($nrmovRec as $nrmovRow){
                                    $selcoming='';
                                    if(!empty($theatersInfo->coming_movies)){
                                        $saveComing = unserialize($theatersInfo->coming_movies);
                                        $selcoming = in_array($nrmovRow->id, $saveComing)?'checked':'';
                                    } ?>
                                    <div class="checkbox">
                                        <input type="checkbox" name="coming_movies[]" value="<?php echo $nrmovRow->id; ?>" class="check-class" <?php echo $selcoming; ?> ><?php echo $nrmovRow->title; ?>
                                    </div>
                                <?php } ?>
                                </div>
                            <?php } ?>
                        </div>                                                      
                    </div>
                </div>
            </div>

            <div class="form-row">                
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo !empty($status)?$status:"checked";?>>
                    <label for="">Published</label>
                    <input type="radio" class="custom-radio" name="status" id="check0" value="0" <?php echo !empty($unstatus)?$unstatus:"";?>>
                    <label for="">Un-Published</label>
                </div>                
            </div>     
                        
            <button btn-action='0' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save
                </span>
            </button>
            <?php if(!isset($_GET['id'])){?>
            <button btn-action='1' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & More
                </span>
            </button>
            <?php }?>
            <button btn-action='2' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & quit
                </span>
            </button>
            <input myaction='0' type="hidden" name="idValue" id="idValue"  value="<?php echo !empty($theatersInfo->id)?$theatersInfo->id:0;?>" />
         </form>    
    </div>
</div>   

<script type="text/javascript" src="<?php echo ASSETS_PATH;?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    $('#gallery_upload').uploadify({
    'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
    'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
    'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/theaters/',thumb_width:200,thumb_height:200},
    'method'     : 'post',
    'cancelImg'  : '<?php echo BASE_URL;?>uploadify/cancel.png',
    'auto'       : true,
    'multi'      : true,    
    'hideButton' : false,   
    'buttonText' : 'Upload Image',
    'width'      : 125,
    'height'     : 21,
    'removeCompleted' : true,
    'progressData' : 'speed',
    'uploadLimit' : 100,
    'fileTypeExts' : '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
    'buttonClass' : 'button formButtons',
        /* 'checkExisting' : '/uploadify/check-exists.php',*/
        'onUploadSuccess' : function(file, data, response) {
            $('#uploadedImageName').val('1');
            var filename =  data;
            $.post('<?php echo BASE_URL;?>apanel/theaters/uploaded_image.php',{imagefile:filename},function(msg){           
                $('#preview_Image').html(msg).show();
            });                 
        },
        'onDialogOpen'      : function(event,ID,fileObj) {      
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
              alert(errorMsg);
        },
        'onUploadComplete' : function(file) {
            //alert('The file ' + file.name + ' was successfully uploaded');
        }   
    });
    
});
    // ]]>
</script>
<?php endif; ?>