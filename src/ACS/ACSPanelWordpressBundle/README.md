ACSPanelWordpressBundle
=======================

Support to admin a Wordpress farm for the ACSPanel.
This solution explores the concept of [Wordpress Multitenancy][1] to create a Wordpress farm using the same Wordpress core files to serve how many blogs you wish, each Blog has their own content folder and mysql database.

How it works
------------

At service level it works like the common Apache2 service for the [ACSPanel][2]. The only difference is the folder structure that is serie of symbolic links to the wordpress core files and a set of settings needed to make everything works as expected.
The Bundle itself create a new table in database to create relationship between one hosting and a database user. From here the wordpress farm knows the connection parameters to connect to the created database and populate a new Wordpress blog.

[1]: http://jason.pureconcepts.net/2012/08/wordpress-multitenancy/
[2]: https://github.com/AltCtrlSupr/acspanel
