# Setup
### Setup and run project
* For easy usage, add PHP installation directory to OS's path.
* Composer
	* Install composer.phar to project directory or other position in PHP's include path.
	* Run 'php composer.phar update' (or composer-update.bat) to download dependencies to verndor directory.
* Create 'tgoe-config.ini' file and provide all needed values. You can use 'tgoe-config.ini-template' as a template.

### Setup IDE
* 

# Troubleshooting
## While running project

* Error "cURL error 60: SSL certificate problem: unable to get local issuer certificate"
	* Download cacert.pem from https://curl.se/docs/caextract.html
	* Open your php.ini file and insert or update the following line:
	* curl.cainfo = “[pathtofile]cacert.pem”

## IDE issues
