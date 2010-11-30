Twilio Controller
=================

#To Install

git submodule add https://destos@github.com/destos/kohana-twilio.git module/twilio

####Add this your Kohana::modules in your application's bootstrap.php

*'twilio' => MODPATH.'twilio',*

####Copy the twilio.php config file to your application's config folder and update it with your account AccountSid and AuthToken.