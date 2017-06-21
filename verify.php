<?php
$access_token = 'JELYzFHt3hu/AlyBP53zOWKN+/Mp/Pb1kt4EburU5bw6lcUTUkVsR6E2OmnO1UAFiLV6k5fSzFCHVo5EPqKNpAjkuUCR//wh/fi9BhZT+hmHB7+vI9SAQPjQjH5c30pHS5wy3zatiuPb/GXBwiFwQgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;

?>
