<?php
	$keyAPI = "AIzaSyBuB0uo4TYtyK0RPnFbA9zG2VdNjkiJxh4";
	$urlAudio = "https://atipongdev.herokuapp.com/SOU_6299211264895.flac";
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $keyAPI);
	$url = "https://speech.googleapis.com/v1/speech:recognize";
	
	$config = [
		'encoding' => 'FLAC',
		'sampleRateHertz' => 16000,
		'languageCode' => 'en-US'
	];
	
	$uri = [
		'uri' => $url
	];
	
	$data = [
		'config' => [$config],
		'uri' => [$uri]
	];
	
	$post = json_encode($data);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	echo $result;
	curl_close($ch);
?>
