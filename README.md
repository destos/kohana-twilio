Twilio Controller
=================

#To Install

git submodule add https://destos@github.com/destos/kohana-twilio.git module/twilio

*cd module/twilio*

####intialize the twillio submodule library.

*git submodule init*

####update the twillio submodule library.

*git submodule update*

####Add this your Kohana::modules in your application's bootstrap.php

*'twilio'			=> MODPATH.'twilio',*

####Copy the twilio.php config file to your application's config folder and updated the AccountSid and AuthToken options.