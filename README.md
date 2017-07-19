# GV

GV is a PHP framework focused on usability, fastness and reliableness. 

  - Redis cache
  - A validation strategy
  - Http handling
  - PHP Unit
  - Monolog
  - Only one configuration file with only a few REALLY needed lines
  - A specific routing system, with the posibility of overwrite

# The goal

> The goal of GV is to create web services or web sites 
> as fast and robust as possible, following the 
> convention over configuration technique plus market standards.

### Tech
Basically all requests are redirected to the index.php inside the public folder, from there the app will start.

gv uses a number of open source projects to work properly. Among the architecture, you will find:

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
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [Redis](https://redis.io/)
- [PHP7](http://php.net/)

Install the dependencies running composer install in the root of the project

```sh
composer install
```

Remember to configure your web server to Allow override in case of Apache2
```sh
/etc/apache2/apache2.conf
```
And change the "AllowOverride None" to "AllowOverride All" for the /var/www directory.
Enable the rewrite module:
```sh
sudo a2enmod rewrite
```

configure your config.yml file inside the config folder.

### How it works
GV is a MVC framework done with convention over configuration as a mind set.
This mean that you need to put the correct files with the correct names in the correct place. 

For instance, lets imagine that we want to manage a new entity, Users, this will consist of:
- A controller that will manage the actions through /user/{action}
- A Model that will have specific fields (username, password, status)
- If needed, a view that will render each action

The controller: Create a new User class that will extend from the GController class.
The Model: Create a new User model inside the Models directory that you will later use.
The view: Per each action that you consider that needs a view, you will need to create a /Views/{controller_name}/{action_name}.twig.html

For more information use the documentation.

### Docker
TODO
