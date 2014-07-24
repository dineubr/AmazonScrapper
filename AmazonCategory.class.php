<?php
require_once("LittleCurl.class.php");

/*
 * AmazonCategory represents the Category object of Amazon Website
 * It has some lists inside, such as "Subcategory" and "Product"
 * 
 * This code is part of AmazonScrapper and its also under GPL V3 license
 */
class AmazonCategory
{

	var $m_name;
	var $m_url;
	var $subCategoryList;
	var $productList;
	var $categoryList;
	var $mainUrl;
	var $m_bestSellerLink;
	var $limit_page;
	var $uTimeSleep;
	//Constructor. Sets the maximum pages will look for a product
	function AmazonCategory($p_uTimeSleep = 200, $p_limitpage = 20)
	{
		$this->mainUrl = "http://www.amazon.com";
		$this->uTimeSleep = $p_uTimeSleep;
		$this->limit_page = $p_limitpage;
	}
	function test()
	{
		echo ("This is Category Class");
	}
	
	//Searches for Best Seller Categories. If parameter is setted as true, searches also for SubCategories names
	function getBestSellerCategories($getSubCatNames = true)
	{
		// create curl resource 
		$output = new LittleCurl($this->mainUrl."/Best-Sellers/zgbs/ref=zg_bs_unv_t_0_t_1",$this->uTimeSleep);        
        $this->categoryList = array();
        
        $output = substr($output,strpos($output,"<span class=\"zg_selected\"> Any Department</span>")+strlen("<span class=\"zg_selected\"> Any Department</span>"),strlen($output)-strpos($output,"<span class=\"zg_selected\"> Any Department</span>")+strlen("<span class=\"zg_selected\"> Any Department</span>"));
        $output = explode(" <li><a href='",$output);
        
        
        	//Add Category and its subcategories to the list
        	for($cOutput = 1; $cOutput < count($output); $cOutput++)
        	{
        		
        		$rawCategory = $output[$cOutput];
        		//Get category name and Url
				$categoryItem = new AmazonCategory($this->uTimeSleep);
				
        		$categoryItem->m_name = html_entity_decode(substr($rawCategory,strpos($rawCategory,">")+strlen(">"),
        				strpos($rawCategory,"</a>")-strlen("</")-strpos($rawCategory,">")+strlen(">")));
        		$categoryItem->m_url = substr($rawCategory,0,strpos($rawCategory,"'"));

        		if($getSubCatNames == true)
        		{
        			// create curl resource
        			$outputSubCat = new LittleCurl($categoryItem->m_url,$this->uTimeSleep); 
        			$outputSubCat = substr($outputSubCat,strpos($outputSubCat,"<li><a href=")+strlen("<li><a href="),strlen($outputSubCat)-strpos($outputSubCat,"<li><a href=")+strlen("<li><a href="));
        			$outputSubCat = substr($outputSubCat,0,strpos($outputSubCat,"</ul>"));
        			
	        		//GetSubcategories
	        		$subCategoryArr = explode("<li><a href='",$outputSubCat);	
	        		$categoryItem->subCategoryList = array();
	        		
	        		//Starts from 2 to avoid the first two items ([0] = Main Category; [1] = Main Category URL)
	        		for($cSubCategory = 0; $cSubCategory < count($subCategoryArr);$cSubCategory++)
	        		{
	        			$rawSubcategory = $subCategoryArr[$cSubCategory];
	        			$subCategoryItem = new AmazonCategory($this->uTimeSleep);
	        			$subCategoryItem->m_url = substr($rawSubcategory,strpos($rawSubcategory,"http://"),strlen($rawSubcategory)-strpos($rawSubcategory,"http://"));
		        			$subCategoryItem->m_url = substr($subCategoryItem->m_url,0,strpos($subCategoryItem->m_url,"'"));
	        			$subCategoryItem->m_name = substr($rawSubcategory,strpos($rawSubcategory,">")+strlen(">"),strlen($rawSubcategory)-strpos($rawSubcategory,">")+strlen(">"));
		        			$subCategoryItem->m_name = html_entity_decode(substr($subCategoryItem->m_name,0,strpos($subCategoryItem->m_name,"<")));
	        			array_push($categoryItem->subCategoryList,$subCategoryItem);
	        		}
	        		if($categoryItem != null)
	        		array_push($this->categoryList,$categoryItem);
        		}
        		
        	}
		return $this->categoryList;
	}
	
	
	//Searches for all Amazon WebSite categories. If parameter is true, retrieves also the subcategories
	function getCategories($getSubCatNames = true)
	{
		// create curl resource
		$curl = new LittleCurl($this->mainUrl."/b?ie=UTF8&node=165795011",$this->uTimeSleep);
		$output = $curl->output;         
        $this->categoryList = array();
        
        $output = explode("<b class=\"small\">",$output);
        	//Add Category and its subcategories to the list
        	for($cOutput = 1; $cOutput < count($output); $cOutput++)
        	{
        		
        		$rawCategory = $output[$cOutput];
        		//Get category name and Url
				$categoryItem = new AmazonCategory($this->uTimeSleep);
				
        		$categoryItem->m_name = html_entity_decode(substr($rawCategory,0,strpos($rawCategory,"</b>")));
        		$categoryItem->m_url = substr($rawCategory,strpos($rawCategory,"<a href=\"")+strlen("<a href=\""),strlen($rawCategory)-strpos($rawCategory,"<a href=\"")+strlen("<a href=\""));
        		$categoryItem->m_url = $this->mainUrl.substr($categoryItem->m_url,0,strpos($categoryItem->m_url,"\""));
        		
        		if($getSubCatNames == true)
        		{
	        		//GetSubcategories
	        		$subCategoryArr = explode("<a href=\"",$rawCategory);
	
	        		$categoryItem->subCategoryList = array();
	        		
	        		//Starts from 2 to avoid the first two items ([0] = Main Category; [1] = Main Category URL)
	        		for($cSubCategory = 2; $cSubCategory < count($subCategoryArr);$cSubCategory++)
	        		{
	        			$rawSubcategory = $subCategoryArr[$cSubCategory];
	        			$subCategoryItem = new AmazonCategory($this->uTimeSleep);
		        			$subCategoryItem->m_url = substr($rawSubcategory,0,strpos($rawSubcategory,"\""));
		        			$subCategoryItem->m_name = substr($rawSubcategory,strpos($rawSubcategory,"\">")+strlen("\">"),strlen($rawSubcategory)-strpos($rawSubcategory,"\">")+strlen("\">"));
		        			$subCategoryItem->m_name = html_entity_decode(substr($subCategoryItem->m_name,0,strpos($subCategoryItem->m_name,"<")));
	        			array_push($categoryItem->subCategoryList,$subCategoryItem);
	        		}
	        		if($categoryItem != null)
	        		array_push($this->categoryList,$categoryItem);
        		}
        	}
		return $this->categoryList;
	}
	
	
	//Look for a BestSeller link in a page with a list of products
	function findBestSellerLink()
	{
		
		if($this->categoryList != NULL)
		foreach($this->categoryList as $i=>$category)
		{
			$bestSeller_flag = false;
			$count = 1;
			while(!$bestSeller_flag && $count <= $this->limit_page)
			{
				$url =$category->m_url;
				if(strpos($url,"&page=") > 0)
				{
					$prePage = strpos($url,0,strpos($url,"&page="));
					$posPage = strpos($url,strpos($url,"&page=")+strlen("&page="),strlen($url)-(strpos($url,"&page=")+strlen("&page=")));
					$url = $prePage."&page=".$count.$posPage;	
				}
				else
				$url .= "&page=".$count;
				
				$curl = new LittleCurl($url,$this->uTimeSleep);
				$output = $curl->output;
			    if(strpos($output,"bestSellerMessage") > 0)
		        {
		        	$link = substr($output,strpos($output,"bestSellerMessage"),strlen($output)-strpos($output,"bestSellerMessage"));
			        	$link = substr($link,strpos($link,"<a href=\"")+strlen("<a href=\""),strlen($link)-strpos($link,"<a href=\"")+strlen("<a href=\""));
			        	$link = substr($link,0,strpos($link,"\""));
		        	$category->m_bestSellerLink = $this->mainUrl.$link;
		        	$bestSeller_flag = true;
		        }
		        $count++;
			}
			echo($category->m_name." - ".$category->m_bestSellerLink."<br/>");
			
			if($category->subCategoryList != NULL)
			foreach($category->subCategoryList as $subcategory)
			{
				
				$bestSeller_flag = false;
				$count = 1;
				while(!$bestSeller_flag && $count <= $this->limit_page)
				{
				
					$url =$subcategory->m_url;
					if(strpos($url,"&page=") > 0)
					{
						$prePage = strpos($url,0,strpos($url,"&page="));
						$posPage = strpos($url,strpos($url,"&page=")+strlen("&page="),strlen($url)-(strpos($url,"&page=")+strlen("&page=")));
						$url = $prePage."&page=".$count.$posPage;	
					}
					else
					$url .= "&page=".$count;
					
					// create curl resource
					 $curl = new LittleCurl($url,$this->uTimeSleep);
					 $output = $curl->output;
					if(strpos($output,"bestSellerMessage") > 0)
			        {
			        	$link = substr($output,strpos($output,"bestSellerMessage"),strlen($output)-strpos($output,"bestSellerMessage"));
				        	$link = substr($link,strpos($link,"<a href=\"")+strlen("<a href=\""),strlen($link)-strpos($link,"<a href=\"")+strlen("<a href=\""));
				        	$link = substr($link,0,strpos($link,"\""));
			        	$subcategory->m_bestSellerLink = $this->mainUrl.$link;
			        	$bestSeller_flag = true;
			        }
		      	$count++;
				}
				echo($category->m_name." Subcat:".$subcategory->m_name." - ".$subcategory->m_bestSellerLink."<br/>");
			}
			
	        
		}
		
		
	}
	
	//Get product name, url and price
	function getProductList()
	{
		$curl = new LittleCurl($this->m_url,$this->uTimeSleep);
		$output = $curl->output;
		
        $productListCounter = substr($output,strpos($output,"zg_pagination")+strlen("zg_pagination"),strlen($output)-strpos($output,"zg_pagination")+strlen("zg_pagination"));
	        $productListCounter = explode("<a page=\"",$productListCounter);
	        $productListCounter =$productListCounter[count($productListCounter)-1];
	        $productListCounter =substr($productListCounter,0,strpos($productListCounter,"\""));
        
        $this->productList = array();
        
        $cPage = 1;
        while($cPage <= $productListCounter && $cPage <= $this->limit_page)
        {
        	$products = explode("zg_itemImmersion\">",$output);
        	for($cProduct=1;$cProduct < count($products);$cProduct++)
        	{
        		$product = new AmazonProduct($this->uTimeSleep);
        		$product->m_url = substr($products[$cProduct],strpos($products[$cProduct],"href=\"")+strlen("href=\""),strlen($products[$cProduct])-strpos($products[$cProduct],"href=\"")+strlen("href=\""));
        			$product->m_url = trim(substr($product->m_url,0,strpos($product->m_url,"\"")));
        		$product->m_shortName = substr($products[$cProduct],strpos($products[$cProduct],"title=\"")+strlen("title=\""),strlen($products[$cProduct])-strpos($products[$cProduct],"title=\"")+strlen("title=\""));
        			$product->m_shortName = substr($product->m_shortName,0,strpos($product->m_shortName,"\""));
        		$product->m_stars = strpos($products[$cProduct],"swSprite s_star") > 0 ? substr($products[$cProduct],strpos($products[$cProduct],"swSprite s_star")+strlen("swSprite s_star"),strlen($products[$cProduct])-strpos($products[$cProduct],"swSprite s_star")-strlen("swSprite s_star")) : 0;
        			$product->m_stars = $product->m_stars === 0 ?  0 : substr($product->m_stars,strpos($product->m_stars,"title=\"")+strlen("title=\""),strlen($product->m_stars)-strpos($product->m_stars,"title=\"")+strlen("title=\""));
        			$product->m_stars = $product->m_stars === 0 ? 0 : substr($product->m_stars,0,strpos($product->m_stars,"\""));
        		$product->m_imgLink = substr($products[$cProduct],strpos($products[$cProduct],"src=\"")+strlen("src=\""),strlen($products[$cProduct])-strpos($products[$cProduct],"src=\"")+strlen("src=\""));
        			$product->m_imgLink = substr($product->m_imgLink,0,strpos($product->m_imgLink,"\""));
        		$product->m_listPrice = strpos($products[$cProduct],"listprice\">") > 0 ? substr($products[$cProduct],strpos($products[$cProduct],"listprice\">")+strlen("listprice\">"),strlen($products[$cProduct])-strpos($products[$cProduct],"listprice\">")+strlen("listprice\">")) : 0;
        			$product->m_listPrice = $product->m_listPrice === 0 ?  0 : substr($product->m_listPrice,0,strpos($product->m_listPrice,"<"));
        		$product->m_price = strpos($products[$cProduct],"class=\"price\">") > 0 ? substr($products[$cProduct],strpos($products[$cProduct],"class=\"price\">")+strlen("class=\"price\">"),strlen($products[$cProduct])-strpos($products[$cProduct],"class=\"price\">")+strlen("class=\"price\">")) : 0;
        			$product->m_price = $product->m_price === 0 ? 0 : substr($product->m_price,0,strpos($product->m_price,"<"));
        		array_push($this->productList,$product);        		
        	}
        	
        	
        	$cPage++;
        	if($cPage <= $productListCounter)
        	{
	        $curl= new LittleCurl($this->m_url."&pg=".$productListCounter,$this->uTimeSleep);
	        $output = $curl->output;
        	} 
        }
	}
	
} // End AmazonObj
?>
