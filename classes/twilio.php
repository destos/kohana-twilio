<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Twilio extends Controller {
	
	public $auto_respond = TRUE;

	public function before(){
			
		// Load in twillio classes
		require Kohana::find_file('vendor', 'twilio/twilio');
		
		$config = Kohana::config('twilio');
		
		$this->tw_client = new TwilioRestClient($config['AccountSid'], $config['AuthToken']);
		$this->tw_response = new Response();
		
		// setup posted variables
		
		return parent::before();
	}

	public function after()
	{
		if ($this->auto_respond === TRUE)
		{
			$this->request->response = $this->tw_response->Respond();
		}

		return parent::after();
	}

	
}