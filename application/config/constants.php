<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| Authorization Messages 
|--------------------------------------------------------------------------
|
|
*/
// weibo
define( "WB_AKEY" , '1401769607' );
define( "WB_SKEY" , '85f705b36f3fd2c1eec81caec8274fbf' );
define( "WB_CALLBACK_URL" , 'http://127.0.0.1/SNS/sns_authorize/weibo_authorize' );

// renren 
define ( "APP_KEY", '554f0b79093642918f322e31ced90960' );
define ( "APP_SECRET", '7d8492648f044ec6a7eb1141be77dd27' );
define ( "CALLBACK_URL", "http://127.0.0.1/sns/sns_authorize/renren_authorize" );

// tx weibo
define( "TX_AKEY" , '801440907' );
define( "TX_SKEY" , 'c10940ff57340dc6d474f3add1dc5a4e' );
define( "TX_CALLBACK_URL" , 'http://127.0.0.1/SNS/sns_authorize/txweibo_authorize' );


/* End of file constants.php */
/* Location: ./application/config/constants.php */
