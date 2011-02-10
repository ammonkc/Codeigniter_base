<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  authorize_net
* 
* Author: Ammon Casey
* 		  ammonkc@gmail.com
*         @ammonkc
*          
* 
* Location: 
*          
* Created:  01.26.2011 
* 
* Description:  Config for Authorize_net AIM api library. Includes api keys
* and logins for developers test api and live production api.
* 
*/

/*
|————————————————————————————————————-
| Authorize.net AIM api library Authorize_net.php Credentials and Info
|————————————————————————————————————-
*/
$config['authorize_net_test_mode'] = 'FALSE'; // Set this to FALSE for live processing

$config['authorize_net_live_x_login'] = '';
$config['authorize_net_live_x_tran_key'] = '';
$config['authorize_net_live_api_host'] = 'https://secure.authorize.net/gateway/transact.dll';


$config['authorize_net_test_x_login'] = '6T9Yau2jDr';
$config['authorize_net_test_x_tran_key'] = '6dJ52q6c2S77fsAW';
$config['authorize_net_test_api_host'] = 'https://test.authorize.net/gateway/transact.dll';

// Lets setup some other values so we dont have to do it everytime
// we process a transaction
$config['authorize_net_x_version'] = '3.1';
$config['authorize_net_x_type'] = 'AUTH_CAPTURE';
$config['authorize_net_x_relay_response'] = 'FALSE';
$config['authorize_net_x_delim_data'] = 'TRUE';
$config['authorize_net_x_delim_char'] = '|';
$config['authorize_net_x_encap_char'] = '';
$config['authorize_net_x_url'] = 'FALSE';

$config['authorize_net_x_method'] = 'CC';
$config['authorize_net_x_currency_code'] = 'USD';

$config['authorize_net_x_duplicate_window'] = '120';



/* End of file config.php */
/* Location: ./application/config/config.php */