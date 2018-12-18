<?php
if(isset($_POST) && !empty($_POST)){
	$name = (isset($_POST['name']))?$_POST['name']:'';
	$email = (isset($_POST['email']))?$_POST['email']:'';
	$subject = (isset($_POST['subject']))?$_POST['subject']:'';
	$phone = (isset($_POST['phone']))?$_POST['phone']:'';
	$message = (isset($_POST['message']))?$_POST['message']:'';
	
	$sendMessage = $mailSubject = '';
	if($_POST['form_type'] == 'contact'){
		$mailSubject = 'Contact Details';
		$sendMessage = "<p>Hello,</p><p>".$name." has sent a message having </p><p><b>Subject:</b> ".$subject."</p><p><b>Email id:</b> ".$email."</p><p><b>Query is:</b> ".$message." <p><b>Phone Number is:</b> ".$phone."</p>" ;
	}elseif($_POST['form_type'] == 'inquiry'){
		$mailSubject = 'Inquiry Details';
		$sendMessage = '';
	}
	
	if($sendMessage != ''){
		$fromEmail = 'support@animalpark.com';
		$toEmail = 'support@templatebundle.net';
		
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: <$fromEmail>" . "\r\n"; 

		if(mail($toEmail , $mailSubject , $sendMessage , $headers )){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}else{
	echo 0;
}

?>