<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename  = "tbl_package"; // Database table name
$moduleId 		  = 7;				// module id >>>>> tbl_modules
$moduleFoldername = "package";		// Image folder name

if(isset($_GET['page']) && $_GET['page'] == "package" && isset($_GET['mode']) && $_GET['mode']=="list"):
// serclearImages($moduleTablename, "package/banner");
// serclearImages($moduleTablename, "package/banner/thumbnails");
clearImages($moduleTablename, $moduleFoldername . "/social", "fb_upload");
clearImages($moduleTablename, $moduleFoldername . "/social/thumbnails", "fb_upload");
JsonclearImages($moduleTablename, "package");
JsonclearImages($moduleTablename, "package/thumbnails");	
?>
<h3>
List Packages
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewPackage();">
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
               <th class="text-center">Title</th>    
               <th>Sub Package</th>     
               <th class="text-center"><?php echo $GLOBALS['basic']['action'];?></th>
            </tr>
        </thead> 
            
        <tbody>
            <?php $records = Package::find_all();	
				  foreach($records as $key=>$record): ?>    
            <tr id="<?php echo $record->id;?>">
            	<td style="display:none;"><?php echo $key+1;?></td>
                <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id;?>" /></td>
                <td>
                    <a href="javascript:void(0);" title="" class="user-ico clearfix" onclick="editRecord(<?php echo $record->id;?>);">
                        <span><?php echo $record->title;?></span>
                    </a>          
                </td>                
                <td>
                	<a class="primary-bg medium btn loadingbar-demo" title="" onClick="viewSubpackagelist(<?php echo $record->id; ?>);" href="javascript:void(0);">
                        <span class="button-content">
                            <span class="badge bg-orange radius-all-4 mrg5R" title="" data-original-title="Badge with tooltip"><?php echo $countImages = Subpackage::getTotalSub($record->id);?></span>
                            <span class="text-transform-upr font-bold font-size-11">View Lists</span>
                        </span>
                    </a>
                    <?php $makasroom = ($record->type == 1) ? "icon-circle" : "icon-circle-o";?> 
                    <a href="javascript:void(0);" class="btn small tooltip-button" data-placement="top" title="">
                        <i class="glyph-icon <?php echo $makasroom;?>"></i>
                    </a>
                </td>                
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
    $packageId  = addslashes($_REQUEST['id']);
    $packageInfo   = Package::find_by_id($packageId);
    $status     = ($packageInfo->status==1)?"checked":" ";
    $unstatus   = ($packageInfo->status==0)?"checked":" ";
    $masrom     = ($packageInfo->type==1)?"checked":" ";
    $unmasrom   = ($packageInfo->type==0)?"checked":" ";
endif;  
?>
<h3>
<?php echo (isset($_GET['id']))?'Edit Package':'Add Package';?>
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewPackagelist();">
    <span class="glyph-icon icon-separator">
        <i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>

<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
        <form action="" class="col-md-12 center-margin" id="package_frm">
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Title :
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <input placeholder="Package Title" class="col-md-6 validate[required,length[0,200]]" type="text" name="title" id="title" value="<?php echo !empty($packageInfo->title)?$packageInfo->title:"";?>">
                </div>                
            </div>   

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">Slug :</label>
                </div>                
                <div class="form-input col-md-20">
                    <?php echo BASE_URL;?><input placeholder="Slug" class="col-md-3 validate[required,length[0,200]]" type="text" name="slug" id="slug" value="<?php echo !empty($packageInfo->slug)?$packageInfo->slug:"";?>">
                    <span id="error"></span>
                </div>                
            </div> 

            <div class="form-row add-image">
                <div class="form-label col-md-2">
                    <label for="">
                        Banner Image :
                    </label>
                </div> 
                <div class="form-input col-md-10 uploader">          
                   <input type="file" name="banner_upload" id="banner_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (1500 px X 600 px)</small></label>
                </div>                
                <!-- Upload user image preview -->
                <div id="preview_banner"><input type="hidden" name="imageArrayname2[]" /></div>
            </div>      

            <div class="form-row">
            <?php 
                if(!empty($packageInfo->banner_image)):
                    $imageRec = unserialize($packageInfo->banner_image);
                if($imageRec):
                    foreach($imageRec as $k=>$imageRow): ?>
                        <div class="col-md-3" id="removeSavedimg<?php echo $k;?>">
                            <div class="infobox info-bg">                                                               
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php 
                                            if(file_exists(SITE_ROOT."images/package/banner/".$imageRow)):
                                                $filesize = filesize(SITE_ROOT."images/package/banner/".$imageRow);
                                                echo 'Size : '.getFileFormattedSize($filesize);
                                            endif;
                                        ?>
                                    </span> 
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedPackageimage(<?php echo $k;?>);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>                                                       
                                </div>
                                <img src="<?php echo IMAGE_PATH.'package/banner/thumbnails/'.$imageRow;?>"  style="width:100%"/>                                                                                   
                                <input type="hidden" name="imageArrayname2[]" value="<?php echo $imageRow;?>" />
                                
                            </div> 
                        </div>
                <?php endforeach;
                endif;
                endif;?>
            </div>

            <div class="form-row add-image">
                <div class="form-label col-md-2">
                    <label for="">
                        List Image :
                    </label>
                </div> 
                <div class="form-input col-md-10 uploader">          
                   <input type="file" name="package_upload" id="package_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (1500 px X 600 px)</small></label>
                </div>                
                <!-- Upload user image preview -->
                <div id="preview_Image"><input type="hidden" name="imageArrayname7[]" /></div>
            </div>      

            <div class="form-row">
            <?php 
                if(!empty($packageInfo->image)):
                    $imageRec = unserialize($packageInfo->image);
                if($imageRec):
                    foreach($imageRec as $k=>$imageRow): ?>
                        <div class="col-md-3" id="removeSavedimg0011">
                            <div class="infobox info-bg">                                                               
                                <div class="button-group" data-toggle="buttons">
                                    <span class="float-left">
                                        <?php 
                                            if(file_exists(SITE_ROOT."images/package/".$imageRow)):
                                                $filesize = filesize(SITE_ROOT."images/package/".$imageRow);
                                                echo 'Size : '.getFileFormattedSize($filesize);
                                            endif;
                                        ?>
                                    </span> 
                                    <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedPackageimage('0011');">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>                                                       
                                </div>
                                <img src="<?php echo IMAGE_PATH.'package/thumbnails/'.$imageRow;?>"  style="width:100%"/>                                                                                   
                                <input type="hidden" name="imageArrayname7[]" value="<?php echo $imageRow;?>" />
                                
                            </div> 
                        </div>
                <?php endforeach;
                endif;
                endif;?>
            </div>
            
             <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            FB Sharing Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader3 <?php echo !empty($packageInfo->fb_upload) ? "hide" : ""; ?>">
                        <input type="file" name="fb_upload" id="fb_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (1200px X 680px)</small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image3"></div>
                    <?php if (!empty($packageInfo->fb_upload)): ?>
                        <div class="col-md-2" id="removeSavedimg3">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/package/social/" .$packageInfo->fb_upload)):
                                        $filesize = filesize(SITE_ROOT . "images/package/social/" .$packageInfo->fb_upload);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                    onclick="deleteSavedPackageimage(3);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'package/social/thumbnails/' . $packageInfo->fb_upload; ?>"
                                    style="width:100%"/>
                                <input type="hidden" name="imageArrayname3" value="<?php echo $packageInfo->fb_upload; ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Brief :
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <textarea placeholder="Brief" class="col-md-6 validate[length[0,200]]" type="text" name="sub_title" id="sub_title"><?php echo !empty($packageInfo->sub_title)?$packageInfo->sub_title:"";?></textarea>
                </div>                
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Schema Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                        <textarea placeholder="Schema Code" name="schema_code" id="schema_code"
                                  class="large-textarea"><?php echo !empty($packageInfo->schema_code) ? $packageInfo->schema_code : ""; ?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-12">
                    <label for="">
                        Content :
                    </label>
                    <textarea name="content" id="content" class="large-textarea validate[required]"><?php echo !empty($packageInfo->content)?$packageInfo->content:"";?></textarea>
                    <!-- <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore" href="javascript:void(0);">
                        <span class="button-content">Read More</span>
                    </a> -->
                </div>            
            </div>

            <div class="form-row">   
                <div class="form-label col-md-2">
                    <label for="">
                        Make as Room :
                    </label>
                </div>             
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="type" id="check1" value="1" <?php echo !empty($masrom)?$masrom:"";?>>
                    <label for="">Yes</label>
                    <input type="radio" class="custom-radio" name="type" id="check0" value="0" <?php echo !empty($unmasrom)?$unmasrom:"checked";?>>
                    <label for="">No</label>
                </div>                
            </div>
            
            <div class="form-row">   
                <div class="form-label col-md-2">
                    <label for="">
                        Published :
                    </label>
                </div>             
                <div class="form-checkbox-radio col-md-9">
                    <input type="radio" class="custom-radio" name="status" id="check1" value="1" <?php echo !empty($status)?$status:" checked";?>>
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
            <div class="form-row <?php echo (!empty($packageInfo->meta_keywords) || !empty($packageInfo->meta_description) || !empty($packageInfo->meta_title))?'':'hide';?> metadata"> 
                <div class="col-md-12">  
                    <div class="form-input col-md-12">
                        <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text" name="meta_title" id="meta_title" value="<?php echo !empty($packageInfo->meta_title)?$packageInfo->meta_title:"";?>">
                    </div>
                    <br />
                    <div class="form-input col-md-12"> 
                        <div class="row">
                            <div class="col-md-6">
                                <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords" class="character-keyword validate[required]"><?php echo !empty($packageInfo->meta_keywords)?$packageInfo->meta_keywords:"";?></textarea>
                                <div class="keyword-remaining clear input-description">250 characters left</div>
                            </div>  
                            <div class="col-md-6">
                                <textarea placeholder="Meta Description" name="meta_description" id="meta_description" class="character-description validate[required]"><?php echo !empty($packageInfo->meta_description)?$packageInfo->meta_description:"";?></textarea>
                                <div class="description-remaining clear input-description">160 characters left</div>
                            </div>  
                        </div>      
                    </div> 
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
            <input myaction='0' type="hidden" name="idValue" id="idValue" value="<?php echo !empty($packageInfo->id)?$packageInfo->id:0;?>" />
         </form>    
    </div>
</div>  

<script>
var base_url =  "<?php echo ASSETS_PATH; ?>";
var editor_arr = ["content"];
create_editor(base_url,editor_arr);
</script> 
<script type="text/javascript" src="<?php echo ASSETS_PATH;?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
$(document).ready(function() {
    $('#package_upload').uploadify({
    'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
    'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
    'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/package/',thumb_width:200,thumb_height:200},
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
        $.post('<?php echo BASE_URL;?>apanel/package/uploaded_image.php',{imagefile:filename},function(msg){           
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

    $('#banner_upload').uploadify({
    'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
    'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
    'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/package/banner/',thumb_width:200,thumb_height:200},
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
        $.post('<?php echo BASE_URL;?>apanel/package/banner_image.php',{imagefile:filename},function(msg){           
               $('#preview_banner').append(msg).show();
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

    $('#header_upload').uploadify({
        'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
        'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
        'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/package/imgheader/',thumb_width:200,thumb_height:200},
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
            var filename =  data;
            $.post('<?php echo BASE_URL;?>apanel/package/header_image.php',{imagefile:filename},function(msg){           
                $('#preview_himage').html(msg).show();
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
<script type="text/javascript">
        $(document).ready(function () {
            $('#fb_upload').uploadify({
                'swf': '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
                'uploader': '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
                'formData': {
                    PROJECT: '<?php echo SITE_FOLDER;?>',
                    targetFolder: 'images/package/social/',
                    thumb_width: 200,
                    thumb_height: 200
                },
                'method': 'post',
                'cancelImg': '<?php echo BASE_URL;?>uploadify/cancel.png',
                'auto': true,
                'multi': false,
                'hideButton': false,
                'buttonText': 'Upload Image',
                'width': 125,
                'height': 21,
                'removeCompleted': true,
                'progressData': 'speed',
                'uploadLimit': 100,
                'fileTypeExts': '*.gif; *.jpg; *.jpeg;  *.png; *.GIF; *.JPG; *.JPEG; *.PNG;',
                'buttonClass': 'button formButtons',
                /* 'checkExisting' : '/uploadify/check-exists.php',*/
                'onUploadSuccess': function (file, data, response) {
                    $('#uploadedImageName').val('1');
                    var filename = data;
                    $.post('<?php echo BASE_URL;?>apanel/package/uploaded_fb_image.php', {imagefile: filename}, function (msg) {
                        $('#preview_Image3').append(msg).show();
                    });

                },
                'onDialogOpen': function (event, ID, fileObj) {
                },
                'onUploadError': function (file, errorCode, errorMsg, errorString) {
                    alert(errorMsg);
                },
                'onUploadComplete': function (file) {
                    //alert('The file ' + file.name + ' was successfully uploaded');
                }
            });
        });
    </script>

<?php endif; 
include("sub_package.php"); ?>