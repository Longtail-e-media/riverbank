$(function() {  
  /* widget Form */
  var form = $("#my-booking-form").show();
   
  form.steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex) {
      // Allways allow previous action even if the current form is not valid!
      if (currentIndex > newIndex) {
        return true;
      }

      if (newIndex === 1) {
        var chkoutdt = $('#checkout').val();
        var queryString = 'action=getRoomsdetails&getdate='+chkoutdt;
        $.ajax({
            type: "POST",
            dataType:"JSON",
            url:  'includes/controllers/ajax.package.php',
            data: queryString,
            success: function(data){
                var res = eval(data);
                $('.showresult').html(res.roomresult);
            }
        });
      }

      // Needed in some cases if the user went back (clean up)
      if (currentIndex < newIndex) {
        // To remove error styles
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }

      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
      // Used to skip the "Warning" step if the user is old enough.
      /*if (currentIndex === 1) {
        form.steps("next");
      }*/
  
      /*// Used to skip the "Warning" step if the user is old enough and wants to the previous step.
      if (currentIndex === 2 && priorIndex === 3) {
        form.steps("previous");
      }*/
    },
    onFinishing: function (event, currentIndex) {
      form.validate().settings.ignore = ":disabled";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      $(this).submit();
      //var myaction = $(this).serialize();
      //alert(myaction);
    }
  }).validate({
      errorPlacement: function errorPlacement(error, element) { element.before(error); },
      rules: {
        checkin: {required: true },    
        checkout: {required: true },    
        fname: {required: true},
        lname: {required: true},
        address: {required:true},
        city: {required: true},
        zipcode: {required: true},
        country: {required: true},
        mailaddress: {required: true, email: true},
        contactno: {required: true}
      },
      messages:{  
        checkin: {required: "Please Select CheckIn Date"},
        checkout: {required: "Please Select CheckOut Date"},
        fname: {required: "Please Enter Your First Name"},
        lname: {required: "Please Enter Your Last Name"},
        address: {required: "Please Enter Your Address"},
        city: {required: "Please Enter Your City"},
        zipcode: {required: "Please Enter Your Zip Code"},
        country: {required: "Please Choose Your County"},
        mailaddress: {required: "Enter your email address", email: "Enter a VALID email address"},
        contactno: {required: "Please Enter Your Contact No."}                
      }
  });

  /* Check In Date Section */ 
  $('#checkin').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy-mm-dd',
    minDate: '0',
    maxDate: '+2Y',
    onSelect: function(dateStr) {
          $(this).removeClass('error');
          $('#checkin-error').addClass('hide');


            var d1 = $(this).datepicker("getDate");
            d1.setDate(d1.getDate() + 1); // change to + 1 if necessary
            var d2 = $(this).datepicker("getDate");
            d2.setDate(d2.getDate() + 180); // change to + 180 if necessary   
            $("#checkout").datepicker("option", "minDate", d1);
            $("#checkout").datepicker("option", "maxDate", d2); 

            var start = $("#checkin").datepicker("getDate");
            var end   = $("#checkout").datepicker("getDate");
            var days   = (end - start)/1000/60/60/24;
        
            if(end!=null)
              $(".total-night").html(days);    
              $('input[name=totnight]').val(days);
              //calculationRoom()
        }
  });

  /* Check Out Date Section */ 
  $('#checkout').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'yy-mm-dd',   
    minDate: $("#checkin").datepicker("getDate"),
        maxDate: '+2Y',
        onSelect: function(){
          $(this).removeClass('error');
          $('#checkout-error').addClass('hide');

          var start = $("#checkin").datepicker("getDate");
          var end   = $("#checkout").datepicker("getDate");
          var days   = (end - start)/1000/60/60/24;
      
          if(end!=null)
            $(".total-night").html(days);
            $('input[name=totnight]').val(days);
            //calculationRoom()
        }
  }); 

  /* Room Selection Section */ 
  $(function(){
     $(document).on('change','.select-room',function(){
  	   var no_of_days  =  $('.total-night').text(); 
  	   var sub_total =  0;
  	   var total =  0;
  	   var sn=1;
  	   var tot=0;
  	    var _html = '';
         $('.select-room').each(function(index){
  		     var sub_total =  0;
               var no_of_room    =  $(this).val();
  			 if(no_of_room>0 && no_of_days>0){
  			 var no_of_person  =  $(this).data('person');
  			 var price         =  $(this).data('price');
  			 var currency      =  $(this).data('currency');
  			 var room      =  $(this).data('room');
  			 sub_total  =  parseInt(no_of_days)*parseInt(no_of_room)*parseInt(price);
  			 total  += sub_total;
  			 _html +='<div class="each_room_calculation">';
         _html +='<div class="cal_sn">'+sn+'</div>';
         _html +='<div class="cal_room">'+room+' - '+no_of_person+'</div>';
         _html +='<input type="hidden" name="room_label[]" value="'+no_of_person+'">';
         _html +='<input type="hidden" name="roomtype[]" value="'+room+'">';
         _html +='<div class="cal_no_room">'+no_of_room+'</div>';
         _html +='<input type="hidden" name="room_no[]" value="'+no_of_room+'">';
         _html +='<div class="cal_price">'+currency+''+sub_total+'</div>';
         _html +='<input type="hidden" name="curncy_type[]" value="'+currency+'">';
         _html +='<input type="hidden" name="sub_total[]" value="'+sub_total+'">';
         _html +='<div class="clear"></div></div>';
  			 tot++;	
         sn++;
  			 }	
  			 //alert($(this).parent().next('li').find('span.ind-total').html());
  			 $(this).parent().next('li').find('span.ind-total').html(sub_total);	 
         }); 
  	   if(tot>0){ $(".calculate_td").html(_html);
  	   $('#total_price').html(total);
       $('input[name=total_price]').val(total);
  	   finalcalculation(total);
  	   }
  	});   
  });
});

// Function for Final Calcuation
function finalcalculation(total_amount){
  // Disabled Datepicker inputs When added rooms
  // $('#checkIn').attr('disabled','true');
  // $('#checkOut').attr('disabled','true');

  // Total Discount Price    
  var discount_price = ((total_amount/100)*0).toFixed(2);
  var final_dis_price = parseFloat(total_amount) - parseFloat(discount_price);
  $('#final_dis_price').text(final_dis_price);
  $('input[name=final_dis_price]').val(final_dis_price);

  // Total Service Charge    
  var service_charge = ((final_dis_price/100)*10).toFixed(2);
  $('#service_charge').text(service_charge);
  $('input[name=service_charge]').val(service_charge);

  // Total Tax Price
  var tax_price = (((parseFloat(final_dis_price) + parseFloat(service_charge)) /100)*13).toFixed(2);
  $('#tax_price').text(tax_price);
  $('input[name=tax_price]').val(tax_price);  

  // Grand Total Price 
  var grand_total = (parseFloat(total_amount) - parseFloat(discount_price) + parseFloat(service_charge) + parseFloat(tax_price)).toFixed(2);
  $("#grand_total").text(grand_total);
  $('input[name=grand_total]').val(grand_total);
}