<link href="<?php echo ASSETS_PATH; ?>uploadify/uploadify.css" rel="stylesheet" type="text/css" />
<?php
$moduleTablename  = "tbl_articles"; // Database table name
$moduleId 		  = 3;				// module id >>>>> tbl_modules
$moduleFoldername = "articles";		// Image folder name

if(isset($_GET['page']) && $_GET['page'] == "articles" && isset($_GET['mode']) && $_GET['mode']=="list"):	
SerclearImages($moduleTablename, $moduleFoldername);
SerclearImages($moduleTablename, $moduleFoldername."/thumbnails"); 
clearImages($moduleTablename, $moduleFoldername . "/social", "fb_upload");
clearImages($moduleTablename, $moduleFoldername . "/social/thumbnails", "fb_upload");
?>
<h3>
List Articles
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="AddNewArticles();">
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
               <th class="text-center"><?php echo $GLOBALS['basic']['action'];?></th>
            </tr>
        </thead> 
            
        <tbody>
            <?php $records = Article::find_by_sql("SELECT * FROM ".$moduleTablename." ORDER BY sortorder DESC ");	
				  foreach($records as $key=>$record): ?>    
            <tr id="<?php echo $record->id;?>">
            	<td style="display:none;"><?php echo $key+1;?></td>
                <td><input type="checkbox" class="bulkCheckbox" bulkId="<?php echo $record->id;?>" /></td>
                <td><div class="col-md-7">
                    <a href="javascript:void(0);" onClick="editRecord(<?php echo $record->id;?>);" class="loadingbar-demo" title="<?php echo $record->title;?>"><?php echo $record->title;?></a>
                    </div>
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
	$articlesId 	= addslashes($_REQUEST['id']);
	$articlesInfo   = Article::find_by_id($articlesId);
	$status 		= ($articlesInfo->status==1)?"checked":" ";
	$unstatus 	    = ($articlesInfo->status==0)?"checked":" ";
	$homepage 		= ($articlesInfo->homepage==1)?"checked":" ";
	$nothomepage 	= ($articlesInfo->homepage==0)?"checked":" ";
endif;	
?>
<h3>
<?php echo (isset($_GET['id']))?'Edit Article':'Add Article';?>
<a class="loadingbar-demo btn medium bg-blue-alt float-right" href="javascript:void(0);" onClick="viewarticleslist();">
    <span class="glyph-icon icon-separator">
    	<i class="glyph-icon icon-arrow-circle-left"></i>
    </span>
    <span class="button-content"> Back </span>
</a>
</h3>

<div class="my-msg"></div>
<div class="example-box">
    <div class="example-code">
    	<form action="" class="col-md-12 center-margin" id="articles_frm">        	
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Title :
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <input placeholder="Title" class="col-md-6 validate[required,length[0,200]]" type="text" name="title" id="title" value="<?php echo !empty($articlesInfo->title)?$articlesInfo->title:"";?>">
                </div>                
            </div>    

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">Slug :</label>
                </div>                
                <div class="form-input col-md-20">
                    <?php echo BASE_URL;?><input placeholder="Slug" class="col-md-3 validate[required,length[0,200]]" type="text" name="slug" id="slug" value="<?php echo !empty($articlesInfo->slug)?$articlesInfo->slug:"";?>">
                    <span id="error"></span>
                </div>                
            </div>   

            <!-- <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="" class="row">
                        Sub Title :
                    </label>
                </div>                
                <div class="form-input col-md-20">
                    <input placeholder="Sub Title" class="col-md-6 validate[required,length[0,200]]" type="text" name="sub_title" id="sub_title" value="<?php // echo !empty($articlesInfo->sub_title)?$articlesInfo->sub_title:"";?>">
                </div>                
            </div>   -->   

            <div class="form-row add-image">
                <div class="form-label col-md-2">
                    <label for="">
                        Image :
                    </label>
                </div>                
                <div class="form-input col-md-10 uploader">          
                   <input type="file" name="gallery_upload" id="gallery_upload" class="transparent no-shadow">
                   <label><small>Image Dimensions (<?php echo Module::get_properties($moduleId,'imgwidth');?> px X <?php echo Module::get_properties($moduleId,'imgheight');?> px)</small></label>
                </div>                
                <!-- Upload user image preview -->
                <div id="preview_Image"><input type="hidden" name="imageArrayname[]" /></div>
                <?php 
                if(!empty($articlesInfo->image)){
                $imgRec = unserialize($articlesInfo->image);                
                if(is_array($imgRec)){
                foreach($imgRec as $key=>$recimg){                     
                    $deleteid = rand(0,99999);
                    $imagePath = SITE_ROOT.'images/articles/'.$recimg;
                    if(file_exists($imagePath)){ ?>
                <div class="col-md-3" id="removeSavedimg<?php echo $deleteid;?>">
                    <div class="infobox info-bg">                                                               
                        <div class="button-group" data-toggle="buttons">
                            <span class="float-left">
                                <?php 
                                    if(file_exists(SITE_ROOT."images/articles/".$recimg)):
                                        $filesize = filesize(SITE_ROOT."images/articles/".$recimg);
                                        echo 'Size : '.getFileFormattedSize($filesize);
                                    endif;
                                ?>
                            </span> 
                            <a class="btn small float-right" href="javascript:void(0);" onclick="deleteSavedArticlesimage(<?php echo $deleteid;?>);">
                                <i class="glyph-icon icon-trash-o"></i>
                            </a>                                                       
                        </div>
                        <img src="<?php echo IMAGE_PATH.'articles/thumbnails/'.$recimg;?>"  style="width:100%"/>  
                        <input type="hidden" name="imageArrayname[]" value="<?php echo $recimg;?>" class="validate[required,length[0,250]]" />                                                                                  
                    </div> 
                </div>
                <?php } } } }?>
            </div>
            
            <div class="form-row">
                    <div class="form-label col-md-2">
                        <label for="">
                            FB Sharing Image :
                        </label>
                    </div>
                    <div class="form-input col-md-10 uploader3 <?php echo !empty($articlesInfo->fb_upload) ? "hide" : ""; ?>">
                        <input type="file" name="fb_upload" id="fb_upload" class="transparent no-shadow">
                        <label>
                            <small>Image Dimensions (1200px X 680px)</small>
                        </label>
                    </div>
                    <!-- Upload user image preview -->
                    <div id="preview_Image3"></div>
                    <?php if (!empty($articlesInfo->fb_upload)): ?>
                        <div class="col-md-2" id="removeSavedimg3">
                            <div class="infobox info-bg">
                                <div class="button-group" data-toggle="buttons">
                                <span class="float-left">
                                    <?php
                                    if (file_exists(SITE_ROOT . "images/articles/social/" .$articlesInfo->fb_upload)):
                                        $filesize = filesize(SITE_ROOT . "images/articles/social/" .$articlesInfo->fb_upload);
                                        echo 'Size : ' . getFileFormattedSize($filesize);
                                    endif;
                                    ?>
                                </span>
                                    <a class="btn small float-right" href="javascript:void(0);"
                                    onclick="deleteSavedArticlesimage(3);">
                                        <i class="glyph-icon icon-trash-o"></i>
                                    </a>
                                </div>
                                <img src="<?php echo IMAGE_PATH . 'articles/social/thumbnails/' . $articlesInfo->fb_upload; ?>"
                                    style="width:100%"/>
                                <input type="hidden" name="imageArrayname3" value="<?php echo $articlesInfo->fb_upload; ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Link Type :
                    </label>
                </div>
                <div class="form-checkbox-radio col-md-9">
                    <input id="" class="custom-radio" type="radio" name="linktype" value="0" onClick="linkTypeSelect(0);" <?php echo !empty($internal)?$internal:"checked";?>>
                    <label for="">Internal Link</label>
                    <input id="" class="custom-radio" type="radio" name="linktype" value="1" onClick="linkTypeSelect(1);" <?php echo !empty($external)?$external:"";?>>
                    <label for="">External Link</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Link :
                    </label>
                </div>
                <div class="form-input col-md-8">
                    <div class="col-md-4" style="padding-left:0px !important;">
                        <input  placeholder="Link" class="" type="text" name="linksrc" id="linksrc" value="<?php echo !empty($articlesInfo->linksrc)?$articlesInfo->linksrc:"";?>">                    
                    </div>
                    <div class="col-md-6" style="padding-left:0px !important;">
                        <select data-placeholder="Select Link Page" class="col-md-4 chosen-select" id="linkPage">
                            <option value=""></option>
                            <?php 
                                $Lpageview = !empty($articlesInfo->linksrc)?$articlesInfo->linksrc:"";
                                $LinkTypeview = !empty($articlesInfo->linktype)?$articlesInfo->linktype:"";
                                // Article Page Link
                                echo Article::get_internal_link($Lpageview,$LinkTypeview);
                            ?>
                        </select>  
                    </div>                    
                </div>
            </div>

            <div class="form-row">
                <div class="form-label col-md-2">
                    <label for="">
                        Schema Code :
                    </label>
                </div>
                <div class="form-input col-md-6">
                    <?php $siteRegular = Config::find_by_id(1);
                    $schema_placeholder = '<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "WebSite",
            "@id": "' . BASE_URL . '#website",
            "url": "' . BASE_URL . '",
            "name": "' . $siteRegular->sitetitle . '"
        },
        {
            "@type": "Hotel",
            "@id": "' . BASE_URL . '#hotel",
            "name": "' . $siteRegular->sitetitle . '",
            "url": "' . BASE_URL . '",
            "logo": "' . BASE_URL . 'images/preference/mBlyc-logo.webp",
        },
        {
            "@type": "WebPage",
            "@id": "' . BASE_URL . '#webpage",
            "name": "' . $siteRegular->sitetitle . '",
            "description": "' . $siteRegular->site_description . '",
            "url": "' . BASE_URL . '/clubhimalaya/"
        }
    ]
}
</script>';?>
                    <textarea placeholder="<?= htmlentities($schema_placeholder) ?>" name="schema_code" id="schema_code"
                              class="large-textarea"><?php echo !empty($articlesInfo->schema_code) ? $articlesInfo->schema_code : ""; ?></textarea>
                </div>
            </div>

            <!-- ======== FAQ Schema Builder ======== -->
            <div class="form-row">
                <div class="form-label col-md-2">
                    <label>FAQ Schema :</label>
                </div>
                <div class="col-md-10">
                    <div id="faq-fields-container" style="display:flex;flex-wrap:wrap;"></div>
                    <a href="javascript:void(0);"
                       class="btn medium bg-blue tooltip-button"
                       style="margin-top:6px;"
                       title="Add FAQ row"
                       onclick="addFaqRow();">
                        <i class="glyph-icon icon-plus-square mrg10R"></i>
                        <span class="button-content"> Add FAQ </span>
                    </a>
                </div>
            </div>
            <?php
            // Output existing FAQ data as a safe JS literal so the builder can pre-populate rows.
            // json_encode with JSON_HEX_TAG prevents </script> injection.
            $_faqRaw = isset($articlesInfo->faq_schema) ? trim((string)$articlesInfo->faq_schema) : '';
            if ($_faqRaw !== '') {
                $_faqArr = json_decode($_faqRaw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($_faqArr) && count($_faqArr) > 0) {
                    echo '<script>window._existingFaqData='
                        . json_encode($_faqArr, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES)
                        . ';</script>' . "\n";
                }
            }
            ?>
            <!-- ======== end FAQ Schema Builder ======== -->

    		<div class="form-row">
            	<div class="form-label col-md-12">
                    <label for="">
                        Content :
                    </label>
                    <textarea name="content" id="content" class="large-textarea validate[required]"><?php echo !empty($articlesInfo->content)?$articlesInfo->content:"";?></textarea>
                    <a class="btn medium bg-orange mrg5T" title="Read More" id="readMore" href="javascript:void(0);">
                    	<span class="button-content">Read More</span>
                    </a>
                </div>            
            </div>

            <!--<div class="form-row">
                <div class="form-label col-md-12">
                    <label for="">
                        Extra Content :
                    </label>
                </div>         
                <textarea name="sub_title" id="sub_title" class="large-textarea"><?php echo !empty($articlesInfo->sub_title)?$articlesInfo->sub_title:"";?></textarea>
            </div>-->

            <div class="form-row">                
                <div class="form-checkbox-radio col-md-9">
                   <input type="radio" class="custom-radio" name="homepage" id="homepage1" value="1" <?php echo !empty($homepage)?$homepage:"";?>>
                   <label for="">Homepage</label>
                   <input type="radio" class="custom-radio" name="homepage" id="homepage0" value="0" <?php echo !empty($nothomepage)?$nothomepage:"checked";?>>
                   <label for="">Not at Homepage</label>
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
            <div class="form-row <?php echo (!empty($articlesInfo->meta_keywords) || !empty($articlesInfo->meta_description) || !empty($articlesInfo->meta_title))?'':'hide';?> metadata"> 
                <div class="col-md-12">  
                    <div class="form-input col-md-12">
                        <input placeholder="Meta Title" class="col-md-6 validate[required]" type="text" name="meta_title" id="meta_title" value="<?php echo !empty($articlesInfo->meta_title)?$articlesInfo->meta_title:"";?>">
                    </div>
                    <br />
                    <div class="form-input col-md-12"> 
                        <div class="row">
                            <div class="col-md-6">
                                <textarea placeholder="Meta Keyword" name="meta_keywords" id="meta_keywords" class="character-keyword validate[required]"><?php echo !empty($articlesInfo->meta_keywords)?$articlesInfo->meta_keywords:"";?></textarea>
                                <div class="keyword-remaining clear input-description">250 characters left</div>
                            </div>  
                            <div class="col-md-6">
                                <textarea placeholder="Meta Description" name="meta_description" id="meta_description" class="character-description validate[required]"><?php echo !empty($articlesInfo->meta_description)?$articlesInfo->meta_description:"";?></textarea>
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
            <input myaction='0' type="hidden" name="idValue" id="idValue"  value="<?php echo !empty($articlesInfo->id)?$articlesInfo->id:0;?>" />
         </form>    
    </div>
</div>   
<script>
var base_url =  "<?php echo ASSETS_PATH; ?>";
var editor_arr = ["content", "sub_title"];
create_editor(base_url,editor_arr);
</script>

<script type="text/javascript" src="<?php echo ASSETS_PATH;?>uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
    $(document).ready(function() {
    $('#gallery_upload').uploadify({
    'swf'  : '<?php echo ASSETS_PATH;?>uploadify/uploadify.swf',
    'uploader'   : '<?php echo ASSETS_PATH;?>uploadify/uploadify.php',
    'formData'   : {PROJECT : '<?php echo SITE_FOLDER;?>',targetFolder:'images/articles/',thumb_width:200,thumb_height:200},
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
        $.post('<?php echo BASE_URL;?>apanel/articles/uploaded_image.php',{imagefile:filename},function(msg){           
               $('#preview_Image').append(msg).show();
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
                    targetFolder: 'images/articles/social/',
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
                    $.post('<?php echo BASE_URL;?>apanel/articles/uploaded_fb_image.php', {imagefile: filename}, function (msg) {
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
<!-- <script>
    $(document).ready(function() {
/************************************ Editor for message *****************************************/
    var base_url =  "<?php // echo ASSETS_PATH; ?>";
    CKEDITOR.replace( 'sub_title',{
            toolbar :
            [   
                { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },                   
                { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },'/',
                { name: 'colors', items : [ 'TextColor','BGColor' ] },
                { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
            ]
        });
    });
</script> -->
<?php endif; ?>