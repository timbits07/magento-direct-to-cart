# About
This is a Magento 2 module that takes a customer to your magento store and adds a product(s) to the cart automatically.

# Installation
Run these two commands in the root of your magento installation and they will add the repository to your composer.json file and then clone the repo into your magento installation.

		composer config repositories.direct-to-cart git https://github.com/timbits07/magento-direct-to-cart
		composer require envoy-test/direct-to-cart:dev-master

Then while still in the root of your magento install you must enable the module and upgrade the magento setup

		php bin/magento module:enable EnvoyTest_DirectToCart
		php bin/magento setup:ugrade

# Usage

### Create your custom link
	1. Start with you magento store base URL (eg. https://mystore.com)
	2. Add the frontname for the custom route /directtocart
	3. Add the parameters that will determine the products added to the cart and the expiration date of the link
		- products=1,5,17 (a comma separated list of product IDs)
		- expires=1510607741 (a UNIX timestamp that represents when the link should expire)
	4. Your end result should look something like this https://mystore.com/directtocart?products=1,5,17&expires=1510607741


# Version

	Current version 1.0.0