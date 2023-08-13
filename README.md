# Introduction
### That's this project?
* [TODO]

### Why PHP?
* PHP may not be the most fancy coding language these days. However, there are a lot of advantages...
* It's available at virtually all hosting providers on the market.
* It's easy to setup on local environment for many OS and system architectures. Even with small resources like single board computers.
* Operation and maintenance has a steep learning curve. Due to a huge community, knowledge can be researched on loads of internet sources.

# Setup Production Environment
### Setup web server
* Setup webserver to work with PHP 8.
** Use HTTPS only for public access to protect passwords and sensitive member information.
** Do not use built-in web-server of PHP. It's only intended for development environments. Use something like Apache httpd or NGINX.
** Set web root directory to "www-root" of the project. For security reasons, internet users must not be able to access any content outside this directory.
** Modules needed: [TODO]
** Recommended settings:
*** Some background jobs (e.g. validations or big lists) need a lot of time to query APIs. This usually exceeds PHP's default for maximum execution time. It's highly recommended to set it to a bigger value e.g. 300 (seconds).
* Checkout/export project from Git repository
* Download dependencies
	* Install composer.phar to project directory or other position in PHP's include path.
	* Run 'php composer.phar update' (or composer-update.bat) to download dependencies to verndor directory.
* Create configuration file
** Create 'tgoe-config.ini' file and provide all needed values. You can use 'tgoe-config.ini-template' as a template.
** Make sure position is at a secure place, as it contains secret information as passwords.
** It's recommended to choose position according to your OS's philosiphy for config files. Just make sure you add it to PHP's include path.

# Setup Development Environment
### Setup and run project
* For easy usage, add PHP installation directory to OS's path.
* Composer
	* Install composer.phar to project directory or other position in PHP's include path.
	* Run 'php composer.phar update' (or composer-update.bat) to download dependencies to verndor directory.
* Create 'tgoe-config.ini' file and provide all needed values. You can use 'tgoe-config.ini-template' as a template.

### Setup IDE
* For easy and platform independent setup, try Eclipse PDT.


# Troubleshooting
### While running project

* Error "cURL error 60: SSL certificate problem: unable to get local issuer certificate"
	* Download cacert.pem from https://curl.se/docs/caextract.html
	* Open your php.ini file and insert or update the following line:
	* curl.cainfo = “[pathtofile]cacert.pem”

### IDE issues
* If code completion does not work or it shows warnings about missing classes, try right click on project -> composer -> update build path.

# Roadmap
### Planned features
* Extended validation for member data (as Easyverein only provides very basic validation)
** DOSB sports category assignment (migration from existing Java implementation)
*** make sure it's set
*** make sure only one is set
*** make sure assigned category is matching category of an assigned sports group membership
* Document creation services
** create list fors trainers to regularly check member's data
** create welcome letters for new members
** create cancellation confirmation letters
* Letter services
** automatic sending of welcome letters via postal service API and e-mail
** automatic sending of cancellation confirmation letters via postal service API abd e-mail
