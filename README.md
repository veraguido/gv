# GV

GV is a PHP framework focused on usability, fastness and reliableness. 

  - Redis cache
  - Docker
  - A validation strategy
  - Http handling
  - Shell scripts + ORM management through CLI
  - Event mechanism
  - Command pattern
  - Unit tests
  - Only one configuration file with only a few REALLY needed lines
  - A specific routing system, with the possibility of overwriting

# The goal

> The goal of GV is to create web services or web sites 
> as fast and robust as possible, following the 
> convention over configuration technique, Hexagonal architecture patterns and market standards.

### Tech
Basically for the web part, all requests are redirected to the index.php inside the public folder, from 
there the app will start. 
On the other hand there's a bunch of shell scripts to manage the applications / create resources, populate tables, etc.

GV Use:
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [PHP7](http://php.net/)

optional but highly recommended:
- [Redis](https://redis.io/)

GV also use a number of open source projects to work properly. Among the architecture, you will find:

* [Doctrine's ORM](http://www.doctrine-project.org/) - Perfect ORM for the project!
* [Twig templating](https://twig.sensiolabs.org/) - The flexible, fast, and secure
template engine for PHP
* [Redis cache](https://redis.io/) - With [PRedis](https://github.com/nrk/predis) client.
* [PHP Unit](https://phpunit.de/) - Unit tests suite.
* [Monolog](https://github.com/Seldaek/monolog) - Logging system
* [PhpMailer](https://github.com/PHPMailer/PHPMailer) - Mail class

And of course GV itself is open source with a [public repository](https://github.com/veraguido/gv) on GitHub.

### Installation
GV requires:
docker and docker compose.

Install the dependencies running composer install in the root of the project

```sh
composer install
```

Provision your docker containers at the root of the project:
```sh
docker-compose build
```

From now on to start your services, use:
```sh
docker-compose up
```

Navigate through your browser to localhost:8089 and check if the gv message is present. Enjoy :)



### How it works
GV is an Hexagonal architecture framework done with convention over configuration as a mind set.
This mean that you need to put the correct files with the correct names in the correct place. 

The controller: Create a new User class that will extend from the GvController class.
The Model: Create a new User model inside the Models directory that you will later use, extend it from the GvModel class.
The view: Per each action that you consider that needs a view, you will need to create a /Views/{controller_name}/{action_name}.twig.html

For more information use the documentation.

### Shell commands
Use docker exec -it gv_app_1 /bin/bash to enter your container, from there, use shell/{command} and follow the steps.

### TODO list
- Documentation
- cron management

### Manual installation (only if you don't want to use docker)
Remember to configure your web server to Allow override in case of Apache2
```sh
/etc/apache2/sites-available/000-default.conf
```

And add the following tag to your virtual host:

```xml
<Directory /var/www/gv/> <!-- USE YOUR PATH TO THE PROJECT'S ROOT FOLDER -->
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Order allow,deny
    allow from all
</Directory>
```

Enable the rewrite module:
```sh
sudo a2enmod rewrite
```
Edit your php.ini to let apache2 handle sessions through redis: 

session.save_handler = redis

session.save_path = "tcp://localhost:6379"

configure your config.yml file inside the config folder.

