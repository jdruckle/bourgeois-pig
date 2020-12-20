<?php
	//Retrieve form data. 
	//GET - user submitted data using AJAX
	//POST - in case user does not support javascript, we'll use POST instead
	$name2 = ($_GET['name2']) ? $_GET['name2'] : $_POST['name2'];
	$email2 = ($_GET['email2']) ? $_GET['email2'] : $_POST['email2'];
	$comment2 = ($_GET['comment2']) ? $_GET['comment2'] : $_POST['comment2'];
	$date = ($_GET['date']) ? $_GET['date'] : $_POST['date'];
	$phone = ($_GET['phone']) ? $_GET['phone'] : $_POST['phone'];
	$personnum = ($_GET['personnum']) ? $_GET['personnum'] : $_POST['personnum'];
	$branch = ($_GET['branch']) ? $_GET['branch'] : $_POST['branch'];
	$occasion = ($_GET['occasion']) ? $_GET['occasion'] : $_POST['occasion'];
	$food = ($_GET['food']) ? $_GET['food'] : $_POST['food'];
	$booking_type = ($_GET['booking_type']) ? $_GET['booking_type'] : $_POST['booking_type'];
	
	//flag to indicate which method it uses. If POST set it to 1
	if ($_POST) $post=1;
	
	//Simple server side validation for POST data, of course, you should validate the email
	if (!$name2) $errors[count($errors)] = 'Please enter your name.';
	if (!$email2) $errors[count($errors)] = 'Please enter your email.';
	if (!$comment2) $errors[count($errors)] = 'Please enter your comment.';
	if (!$date) $errors[count($errors)] = 'Please enter correct date.';
	if (!$phone) $errors[count($errors)] = 'Please enter your phone.';
	if (!$personnum) $errors[count($errors)] = 'Please enter your Preferred food.';
	if (!$branch) $errors[count($errors)] = 'Please enter your branch.';
	if (!$occasion) $errors[count($errors)] = 'Please enter your occasion.';
	if (!$food) $errors[count($errors)] = 'Please enter your food.';
	if (!$booking_type) $errors[count($errors)] = 'Please enter your booking type.';
	
	//if the errors array is empty, send the mail
	if (!$errors) {
	
		//recipient - YOUR EMAIL.. or whatever
		$to = 'Your Name <example@example.com>';	
		//sender - from the form
		$from = $name2 . ' <' . $email2 . '>';
		
		//subject and the html message
		$subject = 'Reservation Request from ' . $name2;	
		$message = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head></head>
		<body>
		<table>
			<tr><td>Name:</td><td>' . $name2 . '</td></tr>
			<tr><td>Email:</td><td>' . $email2 . '</td></tr>
			<tr><td>Date:</td><td>' . $date . '</td></tr>
			<tr><td>Phone:</td><td>' . $phone . '</td></tr>
			<tr><td>Person:</td><td>' . $personnum . '</td></tr>
			<tr><td>Branch:</td><td>' . $branch . '</td></tr>
			<tr><td>Occasion:</td><td>' . $occasion . '</td></tr>
			<tr><td>Food:</td><td>' . $food . '</td></tr>
			<tr><td>Booking Type:</td><td>' . $booking_type . '</td></tr>
			<tr><td>Comment:</td><td>' . nl2br($comment2) . '</td></tr>
		</table>
		</body>
		</html>';
	
		//send the mail
		$result = sendmail($to, $subject, $message, $from);
		
		//if POST was used, display the message straight away
		if ($_POST) {
			if ($result) echo 'Thank you! We have received your message.';
			else echo 'Sorry, unexpected error. Please try again later';
			
		//else if GET was used, return the boolean value so that 
		//ajax script can react accordingly
		//1 means success, 0 means failed
		} else {
			echo $result;	
		}
	
	//if the errors array has values
	} else {
		//display the errors message
		for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
		echo '<a href="index.html#reservation-forms">Back</a>';
		exit;
	}



//Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: ' . $from . "\r\n";
	
	$result = mail($to,$subject,$message,$headers);
	
	if ($result) return 1;
	else return 0;
} ?>