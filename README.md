<a href="http://www.hatimeria.com" title="Hatimeria" ><img src="https://www.hatimeria.com/static/3ec1f16ae64e3d9c5da6315bb43e8509/b284c/logo.png" width="100" align="right" alt="Hatimeria we Innovate Eccomerce" /></a>

# Google Tag Manager Echnanced Eccomerce for Magento 2

#### Install this module and control your store data from the one place.

#### Data analysis is the beginning of success

- Easy to Install.
- Easy to Customize.
- Easy to Extend.
- Real Product Impression and Promotion Tracking. Module sending data only for items which has been viewed, not rendered.


#### Magento 2 module for integrate your store and send all needed data to Google Tag Manager by DataLayer. This module is very easy to install.

For Magento 2.3.x

### Tracking Events:

- Core Data
- Customer Data
- Transaction Data
- Search Data
- Advanced Search Data
- Add To Cart
- Review Add
- Add To Wishlist
- Add To Compare
- Product Click
- Product Impression (AJAX compatible)
- Promotion Click
- Promotion Impression
- Any Element Click
- Any Form Tracking
- Checkout Steps Data

### Installation

**Install via composer:** (recommended)

1. Do not copy files from the downloaded package and run the below command from console:

`composer require hatimeria/m2-gtmpro`

2. You need to set up your magento access keys for be able to use composer installation.
Please run bellow command: 

`composer global config http-basic.repo.magento.com <public_key> <private_key>`

If you don't know where to find your access keys check this tutorial:
https://devdocs.magento.com/guides/v2.4/install-gde/prereq/connect-auth.html

######Important:
You should use keys connected to your account which you used to buy an extension.

This module require Hatimeria_Base module which will be automatically installed during installation via composer.


You can also install Hatimeria_Base manually or separately via Composer (in case when you dont have magento access keys).

**Manually:** (not recommended)

###### GtmPro:
1. Copy files from the downloaded package into app/code/Hatimeria/GtmPro
###### Hatimeria_Base:

OPTION1: 
1. Download module from https://github.com/hatimeria/m2-hatimeria-base/tree/1.0.1
2. Copy files into app/code/Hatimeria/Base.

OPTION2:

You can install Hatimeria_Base via composer directly from our repo.

Add bellow code into composer.json
```
"repositories": [
        ... ,
        {
            "type": "git",
            "url": "git@github.com:hatimeria/m2-gtm-pro.git"
        }
    ]

 "require": {
        ... ,
       "hatimeria/m2-hatimeria-base": "^1.0"
    },
```
run bellow command from console:
  
`  composer update`
### Setup

1. Create GTM Account and copy Container Id
2. Go to Magento2 Admin -> Stores -> Configuration -> Hatimeria -> Gtm Pro
3. Set copied Container Id into Container Id field.
4. Enable and configure all needed Events.
5. Enable the module.
6. Save The Configuration

### Events Configuration

Go to Magento2 Admin -> Stores -> Configuration -> Hatimeria -> Gtm Pro -> Events

####Product Impression Tracking

1. Set `Product Impression Tracking` to `Enable`
2. Set `Product Impression Track Class` which will be used for tracking. Default value is Luma Theme compatible `product-item` class.
If you are using different class naming just add here your own class which is a top parent of your products.

####Product Click Tracking

1. Set `Product Click Tracking` to `Enable`
2. Set `Product Click Element Class` which will be used for tracking. Default value is Luma Theme compatible `product-item` class.
If you are using different class naming just add here your own class which is a top parent of your products.
3. Set `Product Click Link Class` - Add comma separated link href classes which you want to track under `Product Click Element Class` parent.

####Form Tracking
1. Set `Form Tracking` to `Enable`
2. Add needed form data like on bellow screen:
   <image add>
   
   - Add Form Id
   - Add all needed field ids(comma separated)
   - Add event name used in GTM
   
3. U will find there contact and newsletter forms configuration (luma compatible). 

####Transactions Tracking 
1. Set `Transactions Tracking` to `Enable`
2. Set `Transaction Affiliation` value. see 
<a href="http://www.hatimeria.com/#" title="Gtm Transaction">https://developers.google.com/tag-manager/enhanced-ecommerce#purchases</a>

####Add To Cart Tracking
1. Set `Add To Cart Tracking` to `Enable`

####Search Tracking
1. Set `Search Tracking` to `Enable`

This feature will track Search and Advanced Search Queries.

####Product View Tracking
1. Set `Product View Tracking` to `Enable`

####Add Product Review Tracking
1. Set `Add Product Review Tracking` to `Enable`

####Add To Wishlist Tracking
1. Set `Add To Wishlist Tracking` to `Enable`

This feature will track Remove Product From Wishlist as well.

####Add To Compare Tracking
1. Set `Add To Compare Tracking` to `Enable`

This feature will track Remove Product From Compare as well.

####Checkout Steps Tracking
1. Set `Checkout Steps Tracking` to `Enable`

This feature will track Remove Product From Wishlist as well.

####Promotion Tracking
1. Set `Promotion Tracking` to `Enable`

This feture will track promotion click and promotion impression
### Need More Events or Support?
Feel free with contact us for any help.

<a href="http://www.hatimeria.com/" title="Hatimeria">hatimeria.com</a>

[office@hatimeria.com](mailto:office@hatimeria.com)
