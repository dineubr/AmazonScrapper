AmazonScrapper
==============

This is a simple scrapper code build in PHP that retrieves the most important data from Amazon (Category Lists, Subcategory Lists, Product List) using cURL. It uses the MVC concept, described below:

* AmazonScrapper.php -> This is the Controller, but to simplify it also has an example at the end, meaning this file is also a "view". It initiates the engine and shows the results.
* AmazonProduct and AmazonCategory classes files are models, both representing the Amazon Category object and the Amazon Product object.
* LittleCurl class is a helper for the cURL function.


All project files are under GPL v3 License. 
Any questions? Write me an e-mail: dineu.assis@gmail.com
