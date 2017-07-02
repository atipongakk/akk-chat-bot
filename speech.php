<?php
	$stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyBH-p8ju3zSJ-dN3CFCSxcwOiB5XccHzUU";
	$upload = file_get_contents("audio.raw");
	$upload = base64_encode($upload);

	$data = array(
		"config"    =>  array(
			"encoding"      =>  "LINEAR16",
			"sampleRate"    =>  16000,
			"languageCode"  =>  "en-US"
		),
		"audio"     =>  array(
			"content"       =>  $upload,
		)
	);

	$jsonData = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $stturl);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

	$result = curl_exec($ch);
	echo $result;
?>