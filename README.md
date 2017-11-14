# About
This is a Magento 2 module that takes a customer to your magento store and adds a product(s) to the cart automatically.

# Requirements

- Magento Composer Installer: To copy the module contents under app/code/ folder.
In order to install it run the below command on the root directory:

        composer require magento/magento-composer-installer

- Add the VCS repository: So that composer can find the module. Add the following lines in your composer.json

        "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/timbits07/magento-direct-to-cart"
        }],


# Installation

- Add the module to composer:

        composer require envoy-test/direct-to-cart

- Add the new entry in `app/etc/config.php`, under the 'modules' section:

        'EnvoyTest_DirectToCart' => 1,

- Clear cache

# Usage

### Create your custom link
	1. Start with you magento store base URL (eg. https://mystore.com)
	2. Add the frontname for the custom route /directtocart
	3. Add the parameters that will determine the products added to the cart and the expiration date of the link
	...* products=1,5,17 (a comma separated list of product IDs)
	...* expires=1510607741 (a UNIX timestamp that represents when the link should expire)
	4. Your end result should look something like this https://mystore.com/directtocart?products=1,5,17&expires=1510607741


# Version

	Current version 1.0.0