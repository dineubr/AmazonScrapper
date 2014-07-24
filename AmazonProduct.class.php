<?php
require_once("LittleCurl.class.php");
 
 /*
 * AmazonProduct represents the Product object of Amazon Website
 * 
 * This code is part of AmazonScrapper and its also under GPL V3 license
 */
class AmazonProduct
{
	var $m_name;
	var $m_shortName;
	var $m_imgLink;
	var $m_url;
	var $m_listPrice;
	var $m_price;
	var $m_description;
	var $m_stars;
	var $imgPath;
	var $uTimeSleep;

	//Constructor
	function AmazonProduct($p_uTimeSleep = 200)
	{
		$this->uTimeSleep = $p_uTimeSleep;
	}

	function test()
	{
		echo ("This is Product Class");
	}
	
	//Retrieves Name, Description and Image link if the parameter is setted as true. 
	function getFullDetails()
	{
		$curl= new LittleCurl($this->m_url,$this->uTimeSleep);
		$output = $curl->output;
        
        //Product Title
        if(strpos($output,"btAsinTitle") > 0)
        {
        $this->m_name = substr($output,strpos($output,"btAsinTitle")+strlen("btAsinTitle"),strlen($output)-strpos($output,"btAsinTitle")+strlen("btAsinTitle"));
        $this->m_name = substr($this->m_name,strpos($this->m_name,">")+strlen(">"),strlen($this->m_name)-strpos($this->m_name,">")+strlen(">"));
        	$this->m_name = trim(substr($this->m_name,0,strpos($this->m_name,"<")));
        }
        else
        {
        	$this->m_name = substr($output,strpos($output,"\"title\":\"")+strlen("\"title\":\""),strlen($output)-strpos($output,"\"title\":\"")+strlen("\"title\":\""));
        	$this->m_name = trim(substr($this->m_name,0,strpos($this->m_name,"\"")));
        }
        
        $this->m_description = substr($output,strpos($output,"class=\"productDescriptionWrapper\">")+strlen("class=\"productDescriptionWrapper\">"),strlen($output)-strpos($output,"class=\"productDescriptionWrapper\">")+strlen("class=\"productDescriptionWrapper\">"));
			$this->m_description = trim(substr($this->m_description,0,strpos($this->m_description,"<div class=\"emptyClear\">")));
		
    		$extension = substr($this->m_imgLink,strrpos($this->m_imgLink,".")+strlen("."));
    		$imageNameThumb = substr($this->m_imgLink,0,strrpos($this->m_imgLink,"."));
    			$imageNameThumb = substr($imageNameThumb,strrpos($this->m_imgLink,"/")+strlen("/"),strlen($imageNameThumb)-strrpos($this->m_imgLink,"/")+strlen("/"));
			
    		$imageName= substr($this->m_imgLink,0,strrpos($this->m_imgLink,"."));
    			$imageName= substr($imageName,strrpos($this->m_imgLink,"/")+strlen("/"),strlen($imageNameThumb)-strrpos($this->m_imgLink,"/")+strlen("/"));
    			$imageName= substr($imageName,0,strpos($imageName,"."));
    		$imgLink = substr($this->m_imgLink,0,strrpos($this->m_imgLink,"."));
    		$imgLink = substr($imgLink,0,strrpos($imgLink,"."));       
	}
	
	
} // End AmazonProduct

?>
