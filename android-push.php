<?php
//CONNECTION CALL
include "conn.php";
   function send_push_notification($reg_id,$message) {
		// NOTIFICATION PARAMETERS
	   $message=array(
			'title' => $message,
			'vibrate' => 1,
			'sound' => 1
	   );
	   //TOKEN PASS
        $registatoin_ids=array($reg_id);

        // Set POST variables
		//GCM URL
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );

		//GOOGLE AUTHORIZATION KEY
        $headers = array(
            'Authorization: key=AIzaSyCtKNJKijR_TF-Ej7fl8AiW3subMlP12Fo',
            'Content-Type: application/json'
        );
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		//echo "before curl_exec";
        // Execute post
        $result = curl_exec($ch);
		// print_r($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
    }
	
//NOTIFICATION TEXT
$content  = "Discount on all products";

//FETCH DEVICE TOKEN FROM DATABASE
$query ="select * from deviceregister";
$result = mysql_query($query,$link) or die('Errant query:  '.$query);
while($Array = mysql_fetch_array($result)){
	if(strtolower($Array['devicetype'])=="android"){
		//NOTIFICATION FUNCTION CALL FOR SEND
		send_push_notification($Array['regid'],$content);
	}
}
?>
