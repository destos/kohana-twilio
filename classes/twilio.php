<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Twilio Module
 *
 * @author Patrick Forringer, Twilio team
 * @version 0.1
 * @copyright mothtom, 5 May, 2011
 * @package Destos/Twilio
 **/

class Twilio
{
	// Singleton static instance
	
	protected static $_instance;
	
/* 	public static  */
	
	public static $AccountSid;
	
	public static $AuthToken;
	
	public static $AppNumber;
	
	public static $ApiUrl;
	
	/**
	 * Returns Twilio instance
	 *
	 * @return self
	 **/
	
	public static function instance()
	{
		if (self::$_instance === NULL)		{
			Kohana::$log->add( Kohana::INFO, "Instantiating Twilio Class" );
			// Create a new instance
			self::$_instance = self::factory();
		}
			
		return self::$_instance;
	}
	
	public function __construct()
	{
						
		$config = Kohana::config('twilio');
		//Kohana_Config::instance()->load('twilio');
		
		#TODO do this more gracefully
		//foreach( $config as $option => $val )
		self::$AccountSid = $config->AccountSid;
		self::$AuthToken = $config->AuthToken;
		self::$AppNumber = $config->AppNumber;
		self::$ApiUrl = $config->ApiUrl;
		
		#Kohana::$log->add( Kohana::INFO, "Constructing Twillio Class" );
				
	}
	
	public static function factory()
	{
		return new self;
	}
	
	/**
	 * Send SMS
	 *
	 * @return new Twilio_Rest_Response
	 **/
	
	#TODO: add functionality/option to send multiple sms' if longer than 160 char.
	public function send_sms( $options )
	{
		
		$data = array(
			'From' => self::$AppNumber,
			'To' => false,
			'Body' => false,
			'StatusCallback' => false,
			'Limit' => true
		);
		
		$data = Arr::merge( $data, (array) $options );
		
		if( !$data['To'] || !$data['Body'] ){
			Kohana::$log->add( Kohana::ERROR, "Can't leave To or Body blank whens sending an SMS" );
			return;
		}
		
		// if no callback url is given use default TODO: define route/controller/action in config
		if( empty($data['StatusCallback']) ){
			$data['StatusCallback'] = URL::site( Route::get('default')->uri(
				array(
				'controller' => 'twilio',
				'action' => 'sms_status'
			)), true);			
		}
		
		//
		if( (bool) $data['Limit'] and strlen($data['Body']) > 160 )
			$data['Body'] = Text::limit_chars_left($data['Body'], 160 , '...');
		
		Kohana::$log->add( Kohana::DEBUG, "Attempting to send SMS : \"{$data['Body']}\" To: {$data['To']}" );
		
		unset($data['Limit']);
		
		$sent = Twilio_Rest_Client::instance()->request( "SMS/Messages", 'POST', $data );
		
		#TODO Apply DRY here
		if( (bool) $sent->IsError ){
			Kohana::$log->add( Kohana::ERROR, "SMS error :{$sent->ErrorMessage}" );
		}
		
		return $sent;
	}
	
	/**
	 * Get SMS
	 *
	 * @return new Twilio_Rest_Response
	 **/
	
	public function get_sms( $smsid = null ){
		
		if( empty($smsid) ){
			Kohana::$log->add( Kohana::ERROR, "No SMS Sid given" );
			return;
		}
		
		return Twilio_Rest_Client::instance()->request( "SMS/Messages/{$smsid}", 'GET' );
	}
	
	/**
	 * Get Account
	 *
	 * @return new Twilio_Rest_Response
	 **/
	
	public function get_account(){
		return Twilio_Rest_Client::instance()->request( false, 'GET' );
	}
	
	/**
	 * Set Account Name
	 * 
	 * @return new Twilio_Rest_Response
	 **/
	
	public function set_account_name( $name ){
		return Twilio_Rest_Client::instance()->request( false, 'POST', array( 'FriendlyName' => $name ) );
	}
	
	/**
	 * Get Sandbox
	 * 
	 * @return new Twilio_Rest_Response
	 **/
	public function get_sandbox(){
		return Twilio_Rest_Client::instance()->request( "Sandbox", 'GET' );
	}
	
	
	
}