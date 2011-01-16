<?
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?access_token=175240632512462|2.VM1V_aDdXF7kjz_er1btPA__.3600.1295017200-1349936095|IhUIP1bz4_w5ZqzCB6gBqmTXz3Y");
curl_setopt($ch, CURLOPT_VERBOSE, 1);

// Turn off the server and peer verification (TrustManager Concept).
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$httpResponse = curl_exec($ch);
var_dump($httpResponse);exit;