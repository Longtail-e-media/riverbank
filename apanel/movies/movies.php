<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename  = "tbl_movies"; // Database table name
$moduleId 		  = 29;				// module id >>>>> tbl_modules
$nArr = array(''=>'None', '1'=>'In Theatres', '2'=>'Coming Soon', '3'=>'Library');
$ntArr = array(''=>'None', '1'=>'Nepali Movies', '2'=>'English Movies', '3'=>'Hindi Movies', '4'=>'Bhojpuri Movies', '5'=>'Hindi Dubbed', '6'=>'Others');

if(isset($_GET['page']) && $_GET['page'] == "movies" && isset($_GET['mode']) && $_GET['mode']=="list"):	
clearImages($moduleTablename, "movies");
clearImages($moduleTablename, "movies/thumbnails");
?>
<h3>
List Movies
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewMovies();">
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
               <th>Type</th>   
               <th>Movies On</th>
               <th>Release Date</th>
               <th class="text-center"><?php echo $GLOBALS['basic']['action'];?></th>
            </tr>
        </thead> 
            
        <tbody>
            <?php $records = Movies::find_by_sql("SELECT * FROM ".$moduleTablename." ORDER BY sortorder DESC ");	
				  foreach($records as $key=>$record): ?>    
            <tr id="<?php echo $record->id;?>">
            	<td style="display:none;"><?php echo $key+1;?></td>
                <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id;?>" /></td>
                <td><div class="col-md-7">
                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id;?>);" class="loadingbar-demo" title="<?php echo $record->title;?>"><?php echo $record->title;?></a>
                </div></td> 
                <td><?php echo ($record->movie_type!=0)?$ntArr[$record->movie_type]:'None'; ?></td> 
                <td><?php echo ($record->action_type!=0)?$nArr[$record->action_type]:'None'; ?></td>      
                <td><?php echo set_na(date('F d, Y', strtotime($record->release_date)));?></td>
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
	$advId 	   	= addslashes($_REQUEST['id']);
	$movieInfo  = Movies::find_by_id($advId);
	$status 	= ($movieInfo->status==1)?"checked":" ";
	$unstatus 	= ($movieInfo->status==0)?"checked":" ";
    // $dtaservice = ($movieInfo->dta_service==1)?"checked":" ";
    // $undtaservice = ($movieInfo->dta_service==0)?"checked":" ";
endif;	
?>
<h3>
<?php echo (isset($_GET['id']))?'Edit Movies':'Add Movies';?>
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewMovieslist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>

<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
    	<form action="" class="col-md-12 center-margin" id="movies_frm" method="post">
        	<div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Title :
                    </label>
                </div>                
                <div class="form-input col-md-8">
                    <input placeholder="Movies Title" class="col-md-6 validate[required,length[0,50]]" type="text" name="title" id="title" value="<?php echo !empty($movieInfo->title)?$movieInfo->title:"";?>">
                </div>                
            </div>             
            
            <div class="form-row">
            	<div class="form-label col-md-2">
                    <label for="">
                       Image :
                    </label>
                </div> 
                
                <?php if(!empty($movieInfo->image)):?>
                <div class="col-md-3" id="removeSavedimg1">
                    <div class="infobox info-bg">                            	                                
                        <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php 
                                    if(file_exists(SITE_ROOT."images/movies/".$movieInfo->image)):
                                        $filesize = filesize(SITE_ROOT."images/movies/".$movieInfo->image);
                                        echo 'Size : '.getFileFormattedSize($filesize);
                                    endif;
                                ?>
                            </span> 
                            <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedMoviesimage(1);">
                                <i class="glyph-icon icon-trash-o"></i>
                            </a>                                                       
                        </div>
                        <img src="<?php echo IMAGE_PATH.'movies/thumbnails/'.$movieInfo->image;?>"  style="width:100%"/>                                                                                   
                    </div> 
                </div>
                <?php endif;?>
                <div class="form-input col-md-10 uploader1 <?php echo !empty($movieInfo->image)?"hide":"";?>">          
                   <input type="file" name="background_upload" id="background_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (280px X 375px)</small></label>
                </div>                
                <!-- Upload user image preview -->
            	<div id="preview_Image"><input type="hidden" name="imageArrayname" value="" class="" /></div>
            </div>

            <div class="form-row add-image">
                <div class="form-label col-md-2">
                    <label for="">
                        Banner Image :
                    </label>
                </div> 
                
                <?php if(!empty($movieInfo->banner_image)):?>
                <div class="col-md-3" id="removeSavedimgb<?php echo $movieInfo->id;?>">
                    <div class="infobox info-bg">                                                               
                        <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php 
                                    if(file_exists(SITE_ROOT."images/movies/banner/".$movieInfo->banner_image)):
                                        $filesize = filesize(SITE_ROOT."images/movies/banner/".$movieInfo->banner_image);
                                        echo 'Size : '.getFileFormattedSize($filesize);
                                    endif;
                                ?>
                            </span> 
                            <a class="btn small float-right bannerImg" href="javascript:void(0);" onclick="deleteSavedMoviesimage('b<?php echo $movieInfo->id;?>');">
                                <i class="glyph-icon icon-trash-o"></i>
                            </a>                                                       
                        </div>
                        <img src="<?php echo IMAGE_PATH.'movies/banner/thumbnails/'.$movieInfo->banner_image;?>"  style="width:100%"/>                                                                                   
                    </div> 
                </div>
                <?php endif;?>
                <div class="form-input col-md-10 uploaderb<?php echo $movieInfo->id;?> <?php echo !empty($movieInfo->banner_image)?"hide":"";?>">          
                   <input type="file" name="banner_upload" id="banner_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (1350px X 650px)</small></label>
                </div>                
                <!-- Upload user image preview -->
                <div id="preview_Image2"><input type="hidden" name="imageArrayname2" value="<?php echo !empty($movieInfo->banner_image)?$movieInfo->banner_image:"";?>" class="" /></div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Movie Type :
                    </label>
                </div>                
                <div class="form-input col-md-2">
                    <select name="movie_type" id="movie_type" class="validate[required]">
                        <?php foreach($ntArr as $kk=>$ntRow){
                            $sel2 = (!empty($movieInfo->movie_type) and $movieInfo->movie_type== $kk)?'selected':'';
                            echo '<option value="'.$kk.'" '.$sel2.'>'.$ntRow.'</option>';
                        } ?>
                    </select>
                </div>                
            </div> 

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Movie On :
                    </label>
                </div>                
                <div class="form-input col-md-2">
                    <select name="action_type" id="action_type" class="validate[required]">
                        <?php foreach($nArr as $k=>$nRow){
                            $sel = (!empty($movieInfo->action_type) and $movieInfo->action_type== $k)?'selected':'';
                            echo '<option value="'.$k.'" '.$sel.'>'.$nRow.'</option>';
                        } ?>
                    </select>
                </div>                
            </div> 

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Release Date :
                    </label>
                </div>                
                <div class="form-input col-md-8">
                    <input placeholder="Movies Release Date" class="col-md-6 validate[required]" type="text" name="release_date" id="release_date" value="<?php echo !empty($movieInfo->release_date)?$movieInfo->release_date:"";?>">
                </div>                
            </div>

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Name of Servers :
                    </label>
                </div>                
                <div class="form-input col-md-8">
                    <input placeholder="Name of Servers" class="col-md-6 validate[required]" type="text" name="server_name" id="server_name" value="<?php //echo !empty($movieInfo->server_name)?$movieInfo->server_name:"";?>">
                </div>                
            </div>

            <div class="form-row">     
                <div class="form-label col-md-2">
                    <label for="">
                        Has DTA Service ? :
                    </label>
                </div>           
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="dta_service" id="check1" value="1" <?php //echo !empty($dtaservice)?$dtaservice:"";?>>
                    <label for="">Yes</label>
                    <input type="radio" class="custom-radio" name="dta_service" id="check0" value="0" <?php //echo !empty($undtaservice)?$undtaservice:"checked";?>>
                    <label for="">No</label>
                </div>                
            </div> -->

            <?php if(!empty($movieInfo->youtube_video)){
                $yRec = unserialize($movieInfo->youtube_video);
                if(count($yRec)>0){
                    $yi=1;
                    foreach($yRec as $yRow){ $rand = rand(1,999); ?>
                        <div class="form-row my-style">     
                            <div class="form-label col-md-2">
                                <?php if($yi==1):?>
                                <label for="">Trailer :</label>
                                <?php endif;?>
                            </div>       
                            <div class="form-input col-md-12" id="NewRow<?php echo $rand;?>">                    
                                <div class="col-md-4">                                    
                                    <input placeholder="youtube link" type="text" name="youtube_video[]" id="youtube_video" class="validate[required]" value="<?php echo $yRow;?>">
                                </div>
                                <div>
                                    <a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" data-placement="right" title="Add" onclick="addnewRow(this);">
                                        <i class="glyph-icon icon-plus-square"></i>
                                    </a>
                                    <?php if($yi!=1):?>
                                    <a href="javascript:void(0);" class="btn medium bg-red tooltip-button" data-placement="right" title="Delete" onclick="deletenewRow(<?php echo $rand;?>);">
                                        <i class="glyph-icon icon-minus-square"></i>
                                    </a>
                                    <?php endif;?>
                                </div>                                          
                            </div>                                                  
                        </div>
                    <?php $yi++; }
                }
            }else{ ?>
                <div class="form-row my-style">     
                    <div class="form-label col-md-2">
                        <label for="">
                            Trailer :
                        </label>
                    </div>           
                    <div class="form-input col-md-12" id="NewRow0">                    
                        <div class="col-md-4">
                            <input placeholder="youtube link" type="text" name="youtube_video[]" id="youtube_video" class="validate[required]">
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" data-placement="right" title="Add" onclick="addnewRow(this);">
                                <i class="glyph-icon icon-plus-square"></i>
                           </a>
                         </div>                                          
                     </div>                                  
                </div>
            <?php } ?>

            <div id="video-field"></div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Brief :
                    </label>
                </div>
                <div class="form-input col-md-10">  
                    <textarea name="brief" id="brief" class="medium-textarea character-brief validate[required]"><?php echo !empty($movieInfo->brief)?$movieInfo->brief:"";?></textarea>                    
                    <div class="brief-remaining clear input-description">250 characters left</div>
                </div>                           
            </div>
            
            <div class="form-row">
                <div class="form-label col-md-12">
                    <label for="">
                        Content :
                    </label>
                    <textarea name="content" id="content" class="large-textarea validate[required]"><?php echo !empty($movieInfo->content)?$movieInfo->content:"";?></textarea>
                    <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore" href="javascript:void(0);">
                        <span class="button-content">Read More</span>
                    </a>
                </div>            
            </div>
    		
            <div class="form-row">     
            	<div class="form-label col-md-2">
                    <label for="">
                        Status :
                    </label>
                </div>           
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo !empty($status)?$status:"checked";?>>
                    <label for="">Published</label>
                    <input type="radio" class="custom-radio" name="status" id="check0" value="0" <?php echo !empty($unstatus)?$unstatus:"";?>>
                    <label for="">Un-Published</label>
                </div>                
            </div>        

            <!-- Meta Tags-->
            <div class="form-row">              
                <div class="form-checkbox-radio col-md-9">
                    <a class="btn medium bg-blue" href="javascript:void(0);" onClick="toggleMetadata();">
                        <span class="glyph-icon icon-separator float-right">
                            <i class="glyph-icon icon-caret-down"></i>
                        </span>
                        <span class="button-content"> Metadata Info </span>
                    </a>
                </div>                
            </div>  
            <div class="form-row <?php echo (!empty($movieInfo->meta_keywords) || !empty($movieInfo->meta_description))?'':'hide';?> metadata">   
                <div class="col-md-6">
                    <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords" class="character-keyword validate[required]"><?php echo !empty($movieInfo->meta_keywords)?$movieInfo->meta_keywords:"";?></textarea>
                    <div class="keyword-remaining clear input-description">250 characters left</div>
                </div>  
                <div class="col-md-6">
                    <textarea placeholder="Meta Description" name="meta_description" id="meta_description" class="character-description validate[required]"><?php echo !empty($movieInfo->meta_description)?$movieInfo->meta_description:"";?></textarea>
                    <div class="description-remaining clear input-description">160 characters left</div>
                </div>                
            </div>

            <button btn-action='0' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save
                </span>
            </button>
            <button btn-action='1' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & More
                </span>
            </button>
            <button btn-action='2' type="submit" name="submit" class="btn-submit btn large primary-bg text-transform-upr font-bold font-size-11 radius-all-4" id="btn-submit" title="Save">
                <span class="button-content">
                    Save & quit
                </span>
            </button>
            <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($movieInfo->id)?$movieInfo->id:0;?>" />
         </form>    
    </div>
</div>   

<script type="text/javascript" src="<?php echo ASSETS_PATH;?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
$(document).ready(function() {
    $('#background_upload').uploadify({
        'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
        'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
        'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/movies/',thumb_width:200,thumb_height:200},
        'method'     : 'post',
        'cancelImg'  : '<?php echo BASE_URL;?>uploadify/cancel.png',
        'auto'       : true,
        'multi'      : false,	
        'hideButton' : false,	
        'buttonText' : 'Upload Image',
        'width'      : 125,
        'height'	 : 21,
        'removeCompleted' : true,
        'progressData' : 'speed',
        'uploadLimit' : 100,
        'fileTypeExts' : '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
        'buttonClass' : 'button formButtons',
        /* 'checkExisting' : '/uploadify/check-exists.php',*/
            'onUploadSuccess' : function(file, data, response) {
                $('#uploadedImageName').val('1');
                var filename =  data;
                $.post('<?php echo BASE_URL;?>apanel/movies/uploaded_image.php',{imagefile:filename},function(msg){			
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

    // Banner image
    $('#banner_upload').uploadify({
        'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
        'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
        'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/movies/banner/',thumb_width:200,thumb_height:200},
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
            $('#uploadedImageName2').val('1');
            var filename =  data;
            $.post('<?php echo BASE_URL;?>apanel/movies/banner_image.php',{imagefile:filename},function(msg){           
                   $('#preview_Image2').html(msg).show();
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
<script>
    var base_url =  "<?php echo ASSETS_PATH; ?>";
    var editor_arr = ["content"];
    create_editor(base_url,editor_arr);
</script>
<?php endif; ?>