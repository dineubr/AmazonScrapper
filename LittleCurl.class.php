<?php

/*
 * Little Curl is a helper that uses Curl function
 * This code is part of AmazonScrapper and its also under GPL V3 license
 */

class LittleCurl
{
	var $output;
	var $uTimeSleep;
	function LittleCurl($p_url = "",$p_uTimeSleep = 200)
	{
		$this->uTimeSleep = $p_uTimeSleep;
		$ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $p_url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $this->output = curl_exec($ch); 
        curl_close($ch);
        usleep($this->uTimeSleep);
	}
}

?>

