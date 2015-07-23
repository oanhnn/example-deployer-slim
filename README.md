Introduction
------------

[![Join the chat at https://gitter.im/oanhnn/deployer-example](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/oanhnn/deployer-example?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Example for use [Deployer v3](http://deployer.org) to deployment PHP project.

See [http://deployer.org](http://deployer.org) for more information and documentation about Deployer.

Requirements
------------
* PHP 5.4.0 and up.

That's all!

You can install [ssh2 extension](http://php.net/manual/en/book.ssh2.php) to speedup deployment process and enable [sockets](http://php.net/manual/en/book.sockets.php) for parallel deployment.


Installation
------------
Clone with `git` and `composer install`
```shell
$ git clone git@github.com:oanhnn/deployer-example.git <target-directory>
$ cd <target-directory>
$ composer install
```
or using [`composer`](http://getcomposer.org)
```shell
$ composer create-project oanhnn/deployer-example <target-directory>
```

Usage
-------------
> In this example using forward agent feature, to run it, please [enable `ssh` forward agent](https://github.com/oanhnn/deployer-example/blob/master/docs/enable-feature-ssh-forward-agent.md) the first.
> If using ssh2 extension, please require package `"herzult/php-ssh": "~1.0"` and add line `set('ssh_type', 'ext-ssh2');` to `deploy.php` file before deployment.

Customize `stage/dev.php` or make a copy and write your own stages.

First deployment:  
```shell
$ bin/dep deploy:configure <stage>
$ bin/dep deploy <stage>
```

Next deployments:
```shell
$ bin/dep deploy <stage>
```

Using options `-vvv` for debug
```shell
$ bin/dep deploy <stage> -vvv
```

Contributing
------------
All code contributions must go through a pull request and approved by a core developer before being merged.
This is to ensure proper review of all the code.

Fork the project, create a feature branch, and send a pull request.

To ensure a consistent code base, you should make sure the code follows
the [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md).

If you would like to help take a look at the [list of issues](https://github.com/oanhnn/deployer-example/issues).

License
-------
Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
