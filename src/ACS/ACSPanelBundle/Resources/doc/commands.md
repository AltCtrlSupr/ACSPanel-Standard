# ACSPanel commands

##  acl-manager:update-entity "ACS/ACSPanelBundle/Entity/Domain"


```php
php app/console acl-manager:update-entity ACS\\ACSPanelBundle\\Entity\\Domain
```

Generate all acl entries based on entity passed. It takes user relationships and fill acl
database with the correct acl entries.

## acs:add-service-to-user "service" "username"

Adds permission to user to use a service.

```php
php app/console acs:add-service-to-user "service" "username"
```
