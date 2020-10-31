<?php 
/*
-------------------------------------------------------------------------------- 
| Developed by: Tauseef Ahmad
| Last Upate: 31-OCT-2020 05:25 PM
| Facebook: www.facebook.com/ahmadlogs
| Twitter: www.twitter.com/ahmadlogs
| YouTube: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
| Blog: https://ahmadlogs.wordpress.com/
--------------------------------------------------------------------------------
 */ 
 
 
/* 
--------------------------------------------------------------------------------
| JAZZCASH Configuration 
--------------------------------------------------------------------------------
 */
 
define('JAZZCASH_MERCHANT_ID', 'enter_merchant_id');
define('JAZZCASH_PASSWORD', 'enter_password');
define('JAZZCASH_INTEGERITY_SALT', 'enter_integerity_salt');
define('JAZZCASH_CURRENCY_CODE', 'PKR');
define('JAZZCASH_LANGUAGE', 'EN');
define('JAZZCASH_API_VERSION_1', '1.1');
define('JAZZCASH_API_VERSION_2', '2.0');

define('JAZZCASH_RETURN_URL', 'enter_return_url');
define('JAZZCASH_HTTP_POST_URL', 'https://sandbox.jazzcash.com.pk/ApplicationAPI/API/2.0/Purchase/DoMWalletTransaction');


/* 
--------------------------------------------------------------------------------
| Database Configuration 
--------------------------------------------------------------------------------
 */ 
 
define('DB_HOST', 'enter_database_host'); 
define('DB_USERNAME', 'enter_database_username'); 
define('DB_PASSWORD', 'enter_database_password'); 
define('DB_NAME', 'enter_database_name');

/* 
--------------------------------------------------------------------------------
| Website Configuration 
--------------------------------------------------------------------------------
 */
 
//define('BASE_URL', 'http://localhost/jazzcash-rest-api/');
define('BASE_URL', 'enter_website_url');

define('TITLE', 'Jazzcssh REST API');