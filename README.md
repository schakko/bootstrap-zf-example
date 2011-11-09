Zend Framework with bootstrap-zf
================================

Installation on Ubuntu
======================
Apache 2.2 and MySQL should be already installed.
Ensure, you use NameVirtualHost inside /etc/apache2/httpd.conf - if not, add it:
	sudo vim /etc/apache2/apache2.conf # add NameVirtualHost *:80 BEFORE "Include sites-enabled/"

Create a new apache configuration inside /etc/apache2/sites-available:
	sudo touch /etc/apache2/sites-available/exampleapp

Paste the following into it
<VirtualHost *:80>
	ServerName	exampleapp
	ServerAlias *.exampleapp
	ServerAdmin webmaster@localhost

	DocumentRoot <path to bootstrap-zf-example>/public

	ErrorLog ${APACHE_LOG_DIR}/nostradamus-error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog ${APACHE_LOG_DIR}/bootstrap-zf-example-access.log combined

    <Directory "<path to bootstrap-zf-example>/public">
	AllowOverride All
	Options +ExecCGI
	Order allow,deny
	Allow from all
    </Directory>
</VirtualHost>

Add a new DNS entry to /etc/hosts:
	su
	echo "127.0.0.1    exampleapp" >> /etc/hosts

Enable vhost:
	sudo a2ensite exampleapp

Restart apache:
	sudo /etc/init.d/apache2 reload
	
Create a new MySQL database:
	mysqladmin create bootstrap_zf_example -u root -p

Download Addendum (annotations support for PHP) from http://code.google.com/p/addendum/downloads/list and extract it to <path to bootstrap-zf-example>/library.
Addendum is *not* shipped with this examples.

