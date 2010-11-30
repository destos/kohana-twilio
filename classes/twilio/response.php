<?php defined('SYSPATH') or die('No direct script access.');

class Twilio_Response extends Twilio_Verb {

	private $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response></Response>";

	protected $nesting = array( 'Say', 'Play', 'Gather', 'Record', 'Dial', 'Redirect', 'Pause', 'Hangup', 'Sms' );

	function __construct()
	{
		parent::__construct(NULL);
	}

	function Respond()
	{
		$simplexml = new SimpleXMLElement($this->xml);
		$this->write($simplexml, FALSE);
		return $simplexml->asXML();
	}

	function asURL($encode = TRUE)
	{
		$simplexml = new SimpleXMLElement($this->xml);
		$this->write($simplexml, FALSE);
		if ($encode)
			return urlencode($simplexml->asXML());
		else
			return $simplexml->asXML();
	}
	
}
