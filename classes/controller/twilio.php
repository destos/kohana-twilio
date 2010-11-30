<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Twilio extends Controller {
	
	public $auto_respond = TRUE;
	
	public $post = false;
	
	public $get = false;
	
	public function before(){	
		
		$this->tw_client = new Twilio_Rest_Client();
		$this->tw_response = new Twilio_Response();

		// setup posted variables
		if(!empty($_POST)){
			$this->post = (object) Security::xss_clean($_POST);
			unset($_POST);
		}
		
		if(!empty($_GET)){
			$this->get = (object) Security::xss_clean($_GET);
			unset($_GET);
		}
		
		return parent::before();
	}

	public function after()
	{
		if ($this->auto_respond === TRUE)
		{
			$this->request->headers['Content-Type'] = File::mime_by_ext('xml');
			$this->request->response = $this->tw_response->Respond();
		}

		return parent::after();
	}

	
}