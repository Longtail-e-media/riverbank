<script language="javascript">

function getLocation(){
	return '<?php echo BASE_URL;?>includes/controllers/ajax.movies.php';
}
function getTableId(){
	return 'table_dnd';
}

$(document).ready(function() {
	oTable = $('#example').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers"
	}).rowReordering({ 
		  sURL:"<?php echo BASE_URL;?>includes/controllers/ajax.movies.php?action=sort",
		  fnSuccess: function(message) { 
					var msg = jQuery.parseJSON(message);
					showMessage(msg.action,msg.message);
			   }
		   });
});

$(document).ready(function(){		

	// Datepicker 
	$('#release_date').datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'yy-mm-dd'
	});


	$('.btn-submit').on('click',function(){
		var actVal = $(this).attr('btn-action');
		$('#idValue').attr('myaction',actVal);
	});

	// form submisstion actions		
	jQuery('#movies_frm').validationEngine({				
		autoHidePrompt:true,		
		scroll: true,
		onValidationComplete: function(form, status){
			if(status==true){	
				$('.btn-submit').attr('disabled', 'true');
				var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&" ;	
				/* By Me */
				for ( instance in CKEDITOR.instances ) 
				CKEDITOR.instances[instance].updateElement();	
				/* End By Me */				
				var data = $('#movies_frm').serialize();
				queryString = action+data;
				$.ajax({
				   type: "POST",
				   dataType:"JSON",
				   url:  getLocation(),
				   data: queryString,
				   success: function(data){
					   var msg = eval(data);
					   if(msg.action=='warning'){
						   showMessage(msg.action,msg.message);
						   $('.btn-submit').removeAttr('disabled');						   			   
		 				   $('.formButtons').show();
						   return false
					   }
					   if(msg.action=='success'){
						   showMessage(msg.action,msg.message);	
						   var actionId = $('#idValue').attr('myaction');
						   if(actionId==2)
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>movies/list";},3000);						   	
						   if(actionId==1)	
						   	setTimeout( function(){window.location.href="<?php echo ADMIN_URL?>movies/addEdit";},3000);						   	
						   if(actionId==0)
						   	setTimeout( function(){window.location.href="";},3000);	
					   }
					   if(msg.action=='notice'){
						   showMessage(msg.action,msg.message);		   					   
						   setTimeout( function(){window.location.href=window.location.href;},3000);
					   }			   					   
					   if(msg.action=='error'){
						   showMessage(msg.action,msg.message);
						   $('#buttonsP img').remove();
		 				   $('.formButtons').show();
						   return false;
					   }
				   }
				});
			return false;
			}
		}
	})
});

// Edit records
function editRecord(Re)
{
	$.ajax({
	   type: "POST",
	   dataType:"JSON",
	   url:  getLocation(),
	   data: 'action=editExistsRecord&id='+Re,
	   success: function(data){
		   var msg = eval(data);
		   $("#title").val(msg.title);
		   $("#idValue").val(msg.editId);		   
	   }
	});
}
		
// Deleting Record
function recordDelete(Re){
	$('.MsgTitle').html('<?php echo sprintf($GLOBALS['basic']['deleteRecord_'],"Slider image")?>');															
	$('.pText').html('Click on yes button to delete this image permanently.!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			$.ajax({
			   type: "POST",
			   dataType:"JSON",
			   url:  getLocation(),
			   data: 'action=delete&id='+Re,
			   success: function(data){
				 var msg = eval(data);  
				 showMessage(msg.action,msg.message);
				 $('#'+Re).remove();
				 Re='';
				 reStructureList(getTableId());
			   }
			});
		}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/***************************************** Link Type Choose *******************************************/
function linkTypeSelect(Re){
	if(Re == 0) {		
		$('#linkPage_chosen').removeClass("hide");
		($('#linksrc').val() == 'http://www.') ? $('#linksrc').val('') : null ;
	} else {
		$('#linkPage_chosen').addClass("hide");
		($('#linksrc').val() == '') ? $('#linksrc').val("http://www.") : null ;
	}
}
$(document).ready(function(){	
	$('#linkPage').change(function(){
		$('#linksrc').val($(this).val());
	});

	$(".character-brief").keyup(function(){
		var a=250,b=$(this).val().length;
		if(b>=a)$(".brief-remaining").text(" you have reached the limit");
		else{
			var c=a-b;$(".brief-remaining").text(c+" characters left")
		}
	});
});

/***************************************** View movies Lists *******************************************/
function viewMovieslist()
{
	window.location.href="<?php echo ADMIN_URL?>movies/list";
}

/***************************************** Add New movies *******************************************/
function AddNewMovies()
{
	window.location.href="<?php echo ADMIN_URL?>movies/addEdit";
}

/***************************************** Edit records *****************************************/
function editRecord(Re)
{
	window.location.href="<?php echo ADMIN_URL?>movies/addEdit/"+Re;
}

/******************************** Remove temp upload image ********************************/
function deleteTempimage(Re)
{
	$('#previewUserimage'+Re).fadeOut(1000,function(){
		$('#previewUserimage'+Re).remove(); 
		$('#preview_Image').html('<input type="hidden" name="imageArrayname" value="" class="">');
	});
}

/******************************** Remove saved movies image ********************************/
function deleteSavedMoviesimage(Re)
{
	$('.MsgTitle').html('Do you want to delete the record ?');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
	$('.divMessageBox').fadeIn();
	$('.MessageBoxContainer').fadeIn(1000);
	
	$(".botTempo").on("click",function(){						
		var popAct=$(this).attr("id");						
		if(popAct=='yes'){
			$('#removeSavedimg'+Re).fadeOut(1000,function(){$('#removeSavedimg'+Re).remove(); $('.uploader'+Re).fadeIn(500);});
		}else{Re='';}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

/***************************************** Add New Row *******************************************/
function addnewRow() {	
	var rowNum = Math.floor((Math.random()*999)+1);	
	var newRow ='<div class="form-row my-style" id="NewRow'+rowNum+'">';
        newRow +='<div class="form-label col-md-2"></div>';
		newRow +='<div class="form-input col-md-12">';                    
			newRow+='<div class="col-md-4">';
				newRow+='<input placeholder="youtube link" type="text" name="youtube_video[]" id="youtube_video" class="validate[required]">';
			newRow+='</div>';
			newRow+='<div>';
				newRow+='<a href="javascript:void(0);" class="btn medium bg-blue tooltip-button" data-placement="right" title="Add" onclick="addnewRow(this);">';
					newRow+='<i class="glyph-icon icon-plus-square"></i>';
			   newRow+='</a>';
			    newRow+='<a href="javascript:void(0);" class="btn medium bg-red tooltip-button" data-placement="right" title="Delete" onclick="deletenewRow('+rowNum+');">';
					newRow+='<i class="glyph-icon icon-minus-square"></i>';
			   newRow+='</a>';
			 newRow+='</div>';                                          
		 newRow+='</div>';
		 newRow+='</div>';
	
	$('#video-field').append(newRow);
}

/***************************************** Delete Add Row *******************************************/
function deletenewRow(rnum)
{	
	/*var x = confirm("Are you sure you want to delete?");
	if (x){*/		
		$('#NewRow'+rnum).remove();
	/*}else{
	return false;		
   }*/	
}

function toggleMetadata(){
	$( ".metadata" ).slideToggle("slow",function(){});
}
</script>