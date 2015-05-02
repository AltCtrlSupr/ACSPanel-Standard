ACSPanel (ACS Control Server Panel)
===================================

![Travis-ci](https://travis-ci.org/AltCtrlSupr/ACSPanel-Standard.svg)

Welcome to ACSPanel - The server administration tool written in Symfony2

This document contains information on how to download, install, and start
using ACSPanel.

Features:
---------

  - Multiserver: ACSPanel can be decentralized, you can have how many servers as you wish for each service. Each server will know what has to do.
  - Role based user system: ACSPanel has 4 basic roles, superadmin, admin, reseller and final user. Each one with its different available actions based on its permissions and assigned plans.
  - Plan system: ACSPanel works with custom Plans, you can create your different Plans to manage your resources.
  - Logged actions: Each change in the database is logged in database. You can know what did anyone and also check the changes and do rollback to the entity (Comming soon...). Thanks to [StofDoctrineExtensionsBundle][16] and [DoctrineExtensions][17]
  - Themeable. Thanks to [LiipThemeBundle][18], (GUI Designers needed)
  - Mobile front-end (Comming soon...):
  - Wordpress farm: See [PanelWordpressBundle][19]
  - Multilanguage: Each user can select the prefered language. (Translators needed)

How it works:
-------------

ACSPanel is just a Front-end for a custom Database with all the information related with your services. All the services ask to the panel database what configuration has to load.

Services supported (At the moment):
  - DNS: [PowerDNS][20]
  - Web: [Apache2][21], [Apache2 webproxy][22]
  - Database: [MySQL][23]
  - FTP: [ProFTPd][24], [PureFTPd][25]

1) Installing ACSPanel
----------------------

When it comes to installing the ACSPanel, you have the
following options.

### Download from GIT repository

To install ACSPanel from git repository, you have to clone the project with
the next command and execute the following commands

    cd /server_root_directory/

    git clone git@github.com:AltCtrlSupr/ACSPanel-Standard.git acspanel

    curl -sS https://getcomposer.org/installer | php

    php composer.phar install


2) Checking your System Configuration
-------------------------------------

Before starting using ACSPanel you should make sure that your local system is properly
configured.

Execute the `check.php` script from the command line:

    php app/check.php

The panel needs the next requeriments To work right:

    php5-curl

3) Permissions
--------------

To avoid permissions issues after executing console commands you should do the next

    mkdir app/cache && mkdir app/logs (TODO: check this to be created with the initial files...)
    sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

4) Setting up ACSPanel
--------------------

You should create your own config_dev.yml, as the parameters you can take config_dev.yml.sample as example.

    cp app/config/config_dev.yml.sample app/config/config_dev.yml


To install all the dependencies you have to execute composer.phar command.

    php composer.phar install

Then you are ready to create the acspanel basic schema executing the next command:

    php app/console doctrine:schema:create

You can load some basic fixtures doing next, like basic groups and admin to start using the panel:

    php app/console doctrine:fixtures:load

The basic fixtures, adds the superadmin user to let you start to work with the panel. Its default password is 1234. The acspanel will redirect you to password change screen where you should change the password.

You should install the assets as well:

    php app/console assets:install --symlink

(Optional) Install additional ACSPanel bundles:

Coming soon.

Congratulations! You're now ready to use ACSPanel.

5) Setting up Apache2
--------------------

    <VirtualHost *:80>
        DocumentRoot /home/user/www/acspanel/web
        <Directory /home/user/www/acspanel/web/>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>
    </VirtualHost>

Ensure you have rewrite mode enabled

6) Getting started with ACSPanel
-------------------------------

Changing the panel view parameters:

    edit app/config/config.yml // Set variables from twig globals, you can change the default date format, the panel name and breadcumb separation character

7) Setting up third party programs
-------------------------------

To set up roundcube to be able to change the passwords with the password plugin you must use the next query:

    $rcmail_config['password_query'] = 'UPDATE mail_mailbox mb INNER JOIN mail_domain md ON mb.mail_domain_id = md.id INNER JOIN domain d ON md.domain_id = d.id SET mb.password=%p WHERE mb.username=%l AND mb.password=%o AND d.domain=%d';


8) Updating the panel (DO BACKUPS FIRST!!)
------------------------------------------

Get the latest version of the code

    git pull

Install the latest version of the dependencies

    ./composer.phar install

Update the database

    php app/console doctrine:schema:update --force

And install the assets

    php app/console assets:install --symlink


9) Setting up the Docker container
----------------------------------

You will need docker-compose install to build the container. So if you haven't yet follow those [instructions](https://docs.docker.com/compose/install/)

Then you can build the container running next command

```
docker-compose up
```


10) Setting up services to automatic apply panel settings
--------------------------------------------------------

Apache: Copy the script tools/acspanel-srv-apache2-reboot to each Apache2 server and give execution permission to the script. Change the panel database access details and add to crontab.
Create a file in /etc/cron.d/ folder with the next contents:

	* *     * * *   root    /usr/local/sbin/acspanel-srv-apache2-reboot

Change the route to your script location



What's inside?
---------------

Symfony2 comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][12] - Adds support for Assetic, an asset processing
    library

  * [**JMSSecurityExtraBundle**][13] - Allows security to be added via
    annotations

  * [**JMSDiExtraBundle**][14] - Adds more powerful dependency injection
    features

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][15] (in dev/test env) - Adds code generation
    capabilities

  * **AcmeDemoBundle** (in dev/test env) - A demo bundle with some example
    code

And ACSPanel adds the following Bundles:

  * [**FOSUserBundle**][26] - Adds the user authentication and administration.

  * [**StofDoctrineExtensionsBundle**][27] - Adds loggable support to entities.

  * [**KnpMenuBundle**][28] - Adds the menu generation system.

  * [**LiipThemeBundle**][29] - Adds the themes support.

  * [**CraueFormFlowBundle**][29] - Used in add hosting form flow.

  * [**GregwarFormBundle**][30]

  * [**KnpPaginatorBundle**][31]

  * [**DoctrineFixturesBundle**][32]

Enjoy!

[1]:  http://symfony.com/doc/2.1/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://symfony.com/download
[4]:  http://symfony.com/doc/2.1/quick_tour/the_big_picture.html
[5]:  http://symfony.com/doc/2.1/index.html
[6]:  http://symfony.com/doc/2.1/bundles/SensioFrameworkExtraBundle/index.html
[7]:  http://symfony.com/doc/2.1/book/doctrine.html
[8]:  http://symfony.com/doc/2.1/book/templating.html
[9]:  http://symfony.com/doc/2.1/book/security.html
[10]: http://symfony.com/doc/2.1/cookbook/email.html
[11]: http://symfony.com/doc/2.1/cookbook/logging/monolog.html
[12]: http://symfony.com/doc/2.1/cookbook/assetic/asset_management.html
[13]: http://jmsyst.com/bundles/JMSSecurityExtraBundle/master
[14]: http://jmsyst.com/bundles/JMSDiExtraBundle/master
[15]: http://symfony.com/doc/2.1/bundles/SensioGeneratorBundle/index.html
[16]: https://github.com/stof/StofDoctrineExtensionsBundle
[17]: https://github.com/l3pp4rd/DoctrineExtensions
[18]: https://github.com/liip/LiipThemeBundle
[19]: https://github.com/AltCtrlSupr/PanelWordpressBundle
[20]: https://github.com/AltCtrlSupr/acspanel-deb/
[21]: https://github.com/AltCtrlSupr/acspanel-deb/
[22]: https://github.com/AltCtrlSupr/acspanel-deb/
[23]: https://github.com/AltCtrlSupr/acspanel-deb/
[24]: https://github.com/AltCtrlSupr/acspanel-deb/
[25]: https://github.com/AltCtrlSupr/acspanel-deb/
[26]: https://github.com/FriendsOfSymfony/FOSUserBundle
[27]: https://github.com/stof/StofDoctrineExtensionsBundle
[28]: https://github.com/KnpLabs/KnpMenuBundle
[29]: https://github.com/liip/LiipThemeBundle
[30]: https://github.com/craue/CraueFormFlowBundle
[31]: https://github.com/Gregwar/FormBundle
[32]: https://github.com/KnpLabs/KnpPaginatorBundle
[33]: https://github.com/doctrine/DoctrineFixturesBundle
