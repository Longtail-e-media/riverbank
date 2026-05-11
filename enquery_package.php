<?php
require_once("includes/initialize.php");

if($_POST['action']=="bookingmail"):
	$usermail = User::get_UseremailAddress_byId(1);
	$ccusermail = User::field_by_id(1,'optional_email');
	$sitename = Config::getField('sitename',true);
  
  $body='';
  foreach($_POST as $key=>$val){$$key=$val;}
  $body.='
  <table width="100%" border="0" cellpadding="0" style="font:12px Arial, serif;color:#222;">
    <tr>
    <td><p>Dear Sir,</p>
    </td>
    </tr>
    <tr>
    <td><p><span style="font-size:14px; font-weight:bold">Offer Inquiry</span><br />
      The details provided are:</p>';
      
      if($offer_type==1){
          $body.='<h3>Offer Details</h3>';
      for ($i=0; $i <count($package_title) ; $i++) { 
      	if($no_pax[$i]!=0){
      	    $total_price = (float)($no_pax[$i] * $package_price[$i]);
	      $body.= '<p><strong>Offer Title</strong> : '.$package_title[$i].'<br />   
	      <strong>No of Pax</strong> : '.$no_pax[$i].'<br />   
	      <strong>Price per Pax('.$currency.')</strong> : '.$package_price[$i].'<br /> 
	      <strong>Total('.$currency.')</strong> : '.$total_price.'<br />';  
      	}
      }
    }
    elseif($offer_type==2){
        $body.='<h3>Offer Details</h3>';
      $body .='<table class="table" border="1" >
      <tr>
        <th >Items</th>
        <th >Price of Item('.$currency.')</th>
        <th >no of Item</th>
        <th >total</th>
      </tr>';
      // pr($package__item,1);
      for ($i=0; $i <count($multi_item) ; $i++) { 
      	if($no_pax[$i]!=0){
	      $body.= '
        <tr class="parent">
										<td class="col-sm-3 text-center">
                    '.$package__item[$i].'
                    </td>
                    <td class="col-sm-3 text-center">
                    '.$package_price[$i].'
                    </td>
                    <td class="col-sm-3 text-center">
                    '.$no_pax[$i].'
                    </td>
                    <td class="col-sm-3 text-center">
                    '.$row_hidden[$i] .'
                    </td>
                    
        </tr>
                ';  
      	}
      }
      $body.= '<tr>
      <td></td>
      <td></td>
      <td>Grand Total('.$currency.')</td>
      <td >'.$multitotal .'</td>
      </tr>
      </table>';




    }
    elseif($offer_type==0){
        $body.='<h3>Offer Details</h3>';
        
        $dynamicvalues= explode(';;',$radio_type);
      	if(!empty($radio_type)){
	      $body.= '<p><strong>Offer Title</strong> : '.$package_title[0].'<br />   
	      <strong>No of Pax</strong> : '.$dynamicvalues[0].'<br />   
	      <strong>Price('.$currency.')</strong> : '.$dynamicvalues[1].'<br />';  
      	
      }
    }
    else{
        $body .='<strong>Offer Name</strong> : '.$package_title.'<br />';
    }
  
      $body.='<strong>Date Of arrival</strong> : '.$person_checkin.'</p><br /> 
       <h3>Persons Details </h3>
      <strong>First Name</strong>: '.$person_first.'<br />
      <strong>Last Name</strong>: '.$person_last.'<br />
      <strong>Contact No.</strong>: '.$person_contact.'<br />
      <strong>Email Address</strong>: '.$person_email.'<br />
      <strong>Country</strong>: '.$person_country.'<br />
      <strong>Address</strong>: '.$person_address.'<br />
      
      <strong>Message</strong>:<br /> '.$person_message.'<br />
      </p>
    </td>
    </tr>
    <tr>
    <td>
    <p>Thank you,<br />
    '.$person_first.' '.$person_last.'
    </p></td>
    </tr>
  </table>
  ';
  
  /*
  * mail info
  */
  
  $mail = new PHPMailer(); // defaults to using php "mail()"
  
  $mail->SetFrom($person_email, $person_first);
  $mail->AddReplyTo($person_email,$person_first);
  
  $mail->AddAddress($usermail, $sitename);
  $mail->AddBCC('ota@longtail.info',"Longtail e-media");
  $mail->AddBCC('sunita@longtail.info',"Longtail e-media");
  $mail->AddBCC('swarna@longtail.info',"Longtail e-media");
  // if add extra email address on back end
  if(!empty($ccusermail)){
    $rec = explode(';', $ccusermail);
    if($rec){
      foreach($rec as $row){
        $mail->AddCC($row,$sitename);
      }   
    }
  }
  
  $mail->Subject    = 'Offer Inquiry mail from  '.$person_first.' '.$person_last;
  
  $mail->MsgHTML($body);
  
  if(!$mail->Send()) {
    echo json_encode(array("action"=>"unsuccess","message"=>"We could not sent your request at the time. Please try again later."));
  }else{
    echo json_encode(array("action"=>"success","message"=>"Your request has been successfully received, You will be shortly informed through mail with you verified by admin."));
  }
endif;
?>