# Introduction
### About this project?
The TGÖ Services project contains a collection of tools and libraries tailor made for "Turngemeinde Germania Ötigheim e.V." sports club. It extends the commercial management solution "EasyVerein" by some nice features we were missing.
It's not designed to fit any club's needs, however if you think it's useful, feel free to use the whole application or just pieces of code.

### Why PHP?
PHP may not be the most fancy coding language these days. However, there are a lot of advantages...
* It's available at virtually all hosting providers on the market. This makes hosting the application cheap and easy.
* It's easy to setup on local environment for many OS and system architectures. Even with small resources like single board computers.
* Operation and maintenance has a steep learning curve. Due to a huge community, knowledge can be researched on loads of internet sources.

### Used Frameworks
This application is based on some frameworks to reduce individual code and get a solid foundation in terms of structure, best practices and security.
* Composer - used for managing 3rd party libraries
* Ghuzzle Http - Used for API calls.
* CodeIgniter - MVC framework and foundation of the app.

### Used 3rd party services
* Easy Verein - Management solution for clubs and important source of data for this tool.
* Aspose.Cloud - Tools for handling MS Office and PDF documents. It's commercial, but it has a nice free usage tier.
* AdminLTE UI Template - https://github.com/ColorlibHQ/AdminLTE/releases/tag/v3.2.0

# Setup Production Environment
### Setup web server
* Setup webserver to work with PHP 8.2
	* Use HTTPS only for public access to protect passwords and sensitive member information.
	* Do not use built-in web-server of PHP. It's only intended for development environments. Use something like Apache httpd or NGINX.
	* Set web root directory to "public" of the project. For security reasons, internet users must not be able to access any content outside this directory.
	* Modules needed: curl, openssl, intl, mbstring
	* Recommended settings:
		* Some background jobs (e.g. validations or big lists) need a lot of time to query APIs. This usually exceeds PHP's default for maximum execution time. It's highly recommended to set it to a bigger value e.g. 300 (seconds).
* Checkout/export project from Git repository
* Download dependencies
	* Install composer.phar to project directory or other position in PHP's include path.
	* Run 'composer install' (or 'php composer.phar install' depending to your installation and OS) to download dependencies to vendor directory.
* Create Code Igniter environment file
	* Create '.env' file as a copy of '.env-template'
	* Set CI_ENVIRONMENT = production
	* Set app.baseURL to the base URL for the application (used to build absolute redirections URLs)
* Create TGÖ App configuration file 'tgoe-config.ini'
	* Create 'tgoe-config.ini' file and provide all needed values. You can use 'tgoe-config.ini-template' as a template.
	* Make sure position is at a secure place, as it contains secret information as passwords.
	* It's recommended to choose position according to your OS's philosiphy for config files. Just make sure you add it to PHP's include path.

# Setup Development Environment
### Setup and run project
* For easy usage, add PHP installation directory to OS's path.
* Composer
	* Install composer.phar to project directory or other position in PHP's include path.
	* Run 'php composer.phar update' (or 'composer-update.bat') to download dependencies to verndor directory.
* Create 'tgoe-config.ini' file and provide all needed values. You can use 'tgoe-config.ini-template' as a template.
* Run 'php spark serve' (or 'run-php-dev-server.bat') to start PHP's built in development web server with CodeIgniter's Spark extension. 

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
* Consolidate idea of app configuration with CodeIgnitor. Goal is having a central place for all coniguration and no environment related configuration is contained in checked-in code. Maybe integrate content of 'tgoe-config.ini' with Code Igniter's '.env' file (if possible).
* additional member validators
	* user name = membership number (except for administator users)
	* when using bank debit, check for all fields filled (e.g. mandate date)
* Security
	* Lock account after too many wrong logins
	* Change password feature
	* Self-service account creation (with email and mobile number validation)
	* 2FA by email or SMS to mobile ? (e.g. https://www.smsapi.com/en/sms-pricing)
	* Implement access control system, so we can unlock certain features on user level.
	* Implement parameterized roles, e.g. for assigning a trainer to his sports groups.
	* Implement brute force protection, e.g. lock user after 3 failed login attempts.
* Document creation services
	* create welcome letters for new members
	* create cancellation confirmation letters
* Letter services
	* automatic sending of welcome letters via postal service API and e-mail
	* automatic sending of cancellation confirmation letters via postal service API abd e-mail
* Module for trainer's work time recording
