<?php defined('SYSPATH') or die('No direct script access.');

class Twilio
{
	// Singleton static instance
	protected static $_instance;
	
/* 	public static  */
	
	public static $AccountSid;
	
	public static $AuthToken;
	
	public static $AppNumber;
	
	public static $ApiUrl;
	
	public static function instance()
	{
		if (self::$_instance === NULL)
		{
			// Create a new instance
			self::$_instance = new self;
		}
		
		$config = Kohana_Config::instance()->load('twilio');
		
		//foreach( $config as $option => $val )
		self::$AccountSid = $config->AccountSid;
		self::$AuthToken = $config->AuthToken;
		self::$AppNumber = $config->AppNumber;
		self::$ApiUrl = $config->ApiUrl;
		
		Kohana::$log->add( Kohana::INFO, "Instantiating Twilio Class" );
				
		return self::$_instance;
	}
	
	public function __construct(){
		
		Kohana::$log->add( Kohana::INFO, "Constructing Twillio Class" );
				
	}
	
	public static function factory()
	{
		return new Twilio();
	}
	
	//private function 
	// --------------------------------------------------------
	// SMS
	//
	
	// Send SMS
	public function send_sms( $options )
	{
		
		$data = array(
			'From' => self::$AppNumber,
			'To' => false,
			'Body' => false
		);
		
		$data = Arr::merge( $data, $options );
		
		if( !$data['To'] || !$data['Body'] ){
			Kohana::$log->add( Kohana::ERROR, "To or Body left blank" );
			return;
		}
		
		// if no callback url is given use default TODO: define route/controller/action in config
		if( !empty($data['StatusCallback']) ){
			$data['StatusCallback'] = URL::site( Route::get('default')->uri(
				array(
				'controller' => 'twilio',
				'action' => 'sms_status'
			)), true);			
		}
		
		Kohana::$log->add( Kohana::DEBUG, "Attempting to send SMS : \"{$message}\" To: {$phone}" );
		
		$sent = Twilio_Rest_Client::instance()->request( "SMS/Messages", 'POST', $data );
		
		//Apply DRY here
		if( (bool) $sent->IsError ){
			Kohana::$log->add( Kohana::ERROR, "SMS error :{$sent->ErrorMessage}" );
		}
		
		return $sent;
	}
	
	// Get SMS
	public function get_sms( $smsid ){
		
		if( empty($smsid) ){
			Kohana::$log->add( Kohana::ERROR, "No SMS Sid given" );
			return;
		}
		
		return Twilio_Rest_Client::instance()->request( "SMS/Messages/{$smsid}", 'GET' );
		
	}
	
	// --------------------------------------------------------
	// Account
	//
	
	public function get_account(){
		return Twilio_Rest_Client::instance()->request( false, 'GET' );
	}
	
	public function set_account_name( $name ){
		return Twilio_Rest_Client::instance()->request( false, 'POST', array( 'FriendlyName' => $name ) );
	}
	
	public function get_sandbox(){
		return Twilio_Rest_Client::instance()->request( "Sandbox", 'GET' );
	}
	
	
	
}