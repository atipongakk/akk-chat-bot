<?php
$access_token = 'JELYzFHt3hu/AlyBP53zOWKN+/Mp/Pb1kt4EburU5bw6lcUTUkVsR6E2OmnO1UAFiLV6k5fSzFCHVo5EPqKNpAjkuUCR//wh/fi9BhZT+hmHB7+vI9SAQPjQjH5c30pHS5wy3zatiuPb/GXBwiFwQgdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$file = fopen("log.txt","w+");
fwrite($file,$content);
fclose($file);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		} 
		//Reply only when message sent is in 'image' format
		else if($event['type'] == 'message' && $event['message']['type'] == 'image'){
			// Get message Id
			$messageId = $event['message']['id'];
			
			$url = 'https://api.line.me/v2/bot/message/' . $messageId . '/content';
			$headers = array('Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$file = fopen('IMG_' . $messageId . '.jpg','w+');
			fwrite($file,$result);
			fclose($file);

			// Get replyToken
			$replyToken = $event['replyToken'];
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'Thank for submit images [' . $messageId . ']'
			];
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
		//Reply only when message sent is in 'image' format
		else if($event['type'] == 'message' && $event['message']['type'] == 'audio'){
			// Get message Id
			
			$messageId = $event['message']['id'];
			
			$url = 'https://api.line.me/v2/bot/message/' . $messageId . '/content';
			$headers = array('Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			$file = fopen('AUD_' . $messageId . '.mp4','w+');
			fwrite($file,$result);
			fclose($file);
			
			$url = 'https://atipongdev.herokuapp.com/AUD_' . $messageId . '.mp4';
			$data = json_decode(file_get_contents('http://api.rest7.com/v1/sound_convert.php?url=' . $url . '&format=flac'));
			$flac = file_get_contents($data->file);
			file_put_contents('SOU_' . $messageId . '.flac', $flac);
			
			$stturl = "https://speech.googleapis.com/v1beta1/speech:syncrecognize?key=AIzaSyBH-p8ju3zSJ-dN3CFCSxcwOiB5XccHzUU";
			$upload = file_get_contents('SOU_' . $messageId . '.flac');
			$upload = base64_encode($upload);
			$data = array(
				"config"    =>  array(
					"encoding"      =>  "FLAC",
					"sampleRate"    =>  16000,
					"languageCode"  =>  "th-TH"
				),
				"audio"     =>  array(
					"content"   	=>  $upload,
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
			
			$file = fopen('result.txt','w+');
			fwrite($file,$result);
			fclose($file);
			
			
			/*$url = 'https://speech.googleapis.com/v1/speech:recognize';
			$config = [
				'encoding' => 'FLAC',
				'sampleRateHertz' => 16000,
				'languageCode' => 'en-US'
			];
			$audio = [
				'config' => [$config],
				
			];*/
			
			
			
			
			// Get replyToken
			$replyToken = $event['replyToken'];
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'Thank for submit audio [' . $messageId . ']'
			];
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK";
?>
