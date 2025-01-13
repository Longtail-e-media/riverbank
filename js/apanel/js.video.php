<script language="javascript">

function getLocation(){
	return '<?php echo BASE_URL;?>includes/controllers/ajax.video.php';
}
function getTableId(){
	return 'table_dnd';
}

/*************************** Shorting Video Postion *******************************/
$(document).ready(function() {
	$(function(){
	$(".video-sort").sortable({	
		//connectWith: ".video-sort",
		start: function(event, ui) {
				var start_pos = ui.item.index();
				ui.item.data('start_pos', start_pos);
			},
		update: function (event, ui) {
				//var start_pos = ui.item.data('start_pos');
				var id		  = ui.item.context.id;
				var end_pos   = ui.item.index();				
				$.ajax({
				   type: "POST",
				   dataType:"JSON",
				   url:  getLocation(),
				   data: "action=sort&id="+id+"&toPosition="+end_pos,
				   success: function(data){
					   var msg = eval(data);
					   showMessage(msg.action,msg.message);
					}
				});
			}
		});
	});
});

$(document).ready(function(){		
	// form submisstion actions		
	jQuery('#video_frm').validationEngine({
		autoHidePrompt:true,
		scroll: false,
		onValidationComplete: function(form, status){
			if(status==true){	
				$('#btn-submit').attr('disabled', 'true');
				var action = ($('#idValue').val() == 0) ? "action=add&" : "action=edit&" ;
				var data = $('#video_frm').serialize();
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
						   $('#btn-submit').removeAttr('disabled');						   			   
		 				   $('.formButtons').show();
						   return false
					   }
					   if(msg.action=='success'){
						   showMessage(msg.action,msg.message);							   
						   setTimeout( function(){window.location.href=window.location.href;},3000);
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

/*************************************** Toggle AddEdit Form ********************************************/	
function toggleAddEdit(){
	$( ".addEdit" ).slideToggle("slow",function(){
		var icval = $("#iconcols").attr("icoval");
		newicval  = (icval == 1) ? 0 : 1;
		$('#iconcols').attr({'icoval':newicval});
		if(icval==1){
			$("#iconcols").removeClass('icon-plus-square');
			$("#iconcols").addClass('icon-minus-square');	
			$(".newtext").html('Cancel');		
		}else{
			$("#iconcols").removeClass('icon-minus-square');
			$("#iconcols").addClass('icon-plus-square');
			$(".newtext").html('Add New');		
			$('#video_frm')[0].reset();
		}
		
	});
}

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
		   $("#source").val(msg.vsource);		
		   $("#idValue").val(msg.editId);
	   }
	});
}

$(function(){
	$('#video_frm')[0].reset();
	$('#btn-submit').removeAttr('disabled');	
});
		
// Deleting Record
function recordDelete(Re){
	$('.MsgTitle').html('Do you want to delete the record ?');															
	$('.pText').html('Clicking yes will be delete this record permanently. !!!');
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
				 //showMessage(msg.action,msg.message);
				 $('#'+Re).remove();
				 Re=null;
			   }
			});
		}else{ Re=null;}
		$('.divMessageBox').fadeOut();
		$('.MessageBoxContainer').fadeOut(1000);
	});	
}

$(function(){
/*************************************** USer Video Status Toggler ******************************************/		
	$('.videoStatusToggle').on('click', function(){
		var Re 		= $(this).attr('rowId');
		var status 	= $(this).attr('status');		
		newStatus = (status == 1) ? 0 : 1;
		$.ajax({
		   type: "POST",
		   url:  getLocation(),
		   data: "action=toggleStatus&id="+Re,
		   success: function(msg){}
		});
		$(this).attr({'status':newStatus});
		if(status==1){
			$('#toggleImg'+Re).removeClass("icon-check-circle-o");
			$('#toggleImg'+Re).addClass("icon-clock-os-circle-o");
		}else{
			$('#toggleImg'+Re).removeClass("icon-clock-os-circle-o");
			$('#toggleImg'+Re).addClass("icon-check-circle-o");
		}
	});
});
</script>