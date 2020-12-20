<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */
// MailChimp API credentials and Audience ID
$apiKey = 'YOURAPIKEY';
$audienceID = 'YOURAUDIENCEID';
// Message that will be displayed when everything is OK :)
$okMessage = 'You\'ve successfully signed up. Thank you!';
// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';
/*
 *  LET'S SUBMIT EVERYTHING TO MAILCHIMP
 */
// If you are not debugging and don't need error reporting, turn this off by error_reporting(0);
try {
    if (!empty($_POST)) {
        //Grab post data from form
        $postEmail = $_POST['email'];
        $postFirstName = $_POST['firstname'];
        $postLastName = $_POST['surname'];

		// Build the MailChimp API URL
        $memberID = md5(strtolower($postEmail));
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $audienceID . '/members/' . $memberID;
        
        // User Information to sent to MailChimp
        $json = json_encode([
            'email_address' => $postEmail,
            'status'        => 'subscribed',
            'update_existing'   => true // YES, update existing subscribers!
        ]);
        
        // send a HTTP POST request with curl to MailChimp
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
       $responseArray = array('type' => 'success', 'message' => $okMessage);
    }
} catch (\Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}
// If requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
	header('Content-Type: application/json');
	echo $encoded;
}
// Else just display the message
else {
    echo $responseArray['message'];
}