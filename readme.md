# Sqare Platform [![Build Status](https://travis-ci.com/systeady/square-platform.svg?branch=master)](https://travis-ci.com/systeady/square-platform)

This project was built with the [Laravel PHP Framework](https://laravel.com/docs/6.x) version 6.

It comes with [Laravel Homestead](https://laravel.com/docs/6.x/homestead) as a local development environment that has all the system requirements in order to run the project.

![screenshot_one](https://user-images.githubusercontent.com/29707399/66476097-6b271800-ea9d-11e9-8a48-cc9e8c94ae58.png)

## Software requirements:

There are several software packages that your system must have in order to get, install and run the project locally.

1. [Git](https://git-scm.com/)
2. [PHP](http://php.net/)
3. [Composer](https://getcomposer.org/)
4. [npm](https://www.npmjs.com/)
5. [VirtualBox](https://www.virtualbox.org/)
6. [Vagrant](https://www.vagrantup.com/)

## Getting Started

Add `192.168.10.10  homestead.test` to your `/etc/hosts` file.

Press the "Use this template" button at the top of the page in order to create a new repository from this template.

Then in your terminal:

```sh
$ git clone your-new-repo-from-this-template

$ cd your-repo-name

$ composer install

$ composer dev-env

$ composer dev-ide

$ php vendor/bin/homestead make

$ cd app/Modules/Core

$ npm install

$ npm run prod

$ cd ../../..

$ vagrant up

$ vagrant ssh

$ cd code

$ phpunit

$ php artisan module:migrate

$ php artisan module:seed
```

Now you can visit http://homestead.test/ in your web browser and you can login with:

<strong>E-Mail Address:</strong> super.admin@example.com

<strong>Password:</strong> password

You can learn more about making new modules from Caffeinated [Modules Guide](https://caffeinatedpackages.com/guide/packages/modules.html).

## Contributing

Please take a look at the [contribution guidelines](contributing.md).

## License

Square Platform is open-source software licensed under the [MIT license](license.md).
