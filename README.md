# QueueingSoftware

## Introduction

This is a queueing software in PHP and uses Zend Framework 3 tools to run the system.

## Pre-requisite
```PHP 5 and >
   git ```

## Installation
``` cd to_your_path ```
``` git clone https://github.com/acaballes/Queueing-Software-in-PHP.git ```

## Web server setup

### Apache setup

```apache
<VirtualHost *:80>
    ServerName qs.localhost
    DocumentRoot /path/to/qs/public
    <Directory /path/to/qs/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
        Require all granted
        </IfModule>
    </Directory>
</VirtualHost>
```

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

```nginx
http {
    # ...
    include sites-enabled/*.conf;
}
```


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/qs.localhost.conf`
it should look something like below:

```nginx
server {
    listen       80;
    server_name  qs.localhost;
    root         /path/to/qs/public;

    location / {
        index index.php;
        try_files $uri $uri/ @php;
    }

    location @php {
        # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME /path/to/qs/public/index.php;
        include fastcgi_params;
    }
}
```

Restart the nginx, now you should be ready to go!

## Additional Information for the setup
``` http://qs.localhost/ - the main user login page ```
``` http://qs.localhost/customer-front - the portal to get customer priority number ```
``` http://qs.localhost/queue-front - the live queue priority number monitor```

## Database setup
Create a mysql database and import data/qms_db.sql file sql table structure.
