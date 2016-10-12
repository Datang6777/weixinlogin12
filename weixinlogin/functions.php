<?php
function httpsrequest($url, $data = null)
  {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)){
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($curl);
	curl_close($curl); 
	return $res;
   
  }

?>
