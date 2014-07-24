<?php
/*
 * AmazonScrapper v1.0 (07/2014)
 *  This is a simple Scrapper Engine for Amazon WebSite
 *  It basically searches for important stuff inside it, 
 *  as Categories, Sub-Categories and Products details.
 *  THIS CODE IS UNDER GPL V3 LICENSE
 *
 *  Any suggestions? Write dineu.assis@gmail.com
 *  This file works as a Controller, and also has a sample at the end, where it retrieves all Amazon Categories as a linked list.
 */
 
date_default_timezone_set('America/Sao_Paulo');
require_once("AmazonCategory.class.php");
require_once("AmazonProduct.class.php");

class AmazonScrapper
{
	
	//Time between requests (in micro-seconds: 1000000 = 1 sec)
	var $uTimeUpdate;
	var $productCounterLimit = 20; // How many products to download for each category
	var $categoryObj;

	//Constructor. The parameter is the sleeping time between requests in micro-seconds 
	
	function AmazonScrapper($uTimeUpdate = 0)
	{
		$this->uTimeUpdate = $uTimeUpdate;		
	}
	
	//Retrieves the list of Amazon categories
	function getAllCategories()
	{
		$this->categoryObj = new AmazonCategory($this->uTimeUpdate);
		$this->categoryObj = $this->categoryObj->getCategories();
	}

	//Function that retrieves the Best Seller categories
	function getBestSellerCategories()
	{
		$this->categoryObj = new AmazonCategory();
		$this->categoryObj = $this->categoryObj->getBestSellerCategories();
	}
	
	/*
	 * Finds the first Best Seller from each category and fills 
	 * a new category with only best seller products.
	 */
	function getBestSellerProducts()
	{
		if($this->categoryObj == NULL)
		$this->refreshCategoryList();
		$this->categoryObj->findBestSellerLink();
	}
	
	/*
	 * Retrieves Product List with price and shortname for every subcategory
	 */
	function getProductList($p_category)
	{
			if(is_object($p_category) && $category->subCategoryList != NULL)
			foreach($p_category->subCategoryList as $c_subcat=>$subCategory)
			{
					$subCategory->getProductList($this->productCounterLimit);
			}
	}
	
	/* Retrieves other product details (Complete name, Description) and 
	 * also creates an image_list in case $p_getImageList is setted as true
	 */
	function getProductDetails($p_product,$p_getImageList = false)
	{				
		$p_product->getFullDetails($p_getImageList);
	}
} // End AmazonScrapper

/* Sample, retrieving all the categories */
$amazon = new AmazonScrapper(200);
$amazon->getAllCategories();

print_r($amazon->categoryObj);

?>

