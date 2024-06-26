# Devoon Frameworkp PHP STRUCTURE MVC

>Documentation

[devoon](https://doc-devoon.onrender.com)

# Installation App devoon
```bash
git clone https://github.com/Fredericra/devoon-php-framework.git
```
or
```bash
composer install devoon
```
## Start application
```bash
 php devoon serve 
````

#
```bash
php devoon serve:port
```
### ex : php devoon serve:8080

# database connection 

## refreshing database
```bash
php devoon db:refresh
```

## remote the database table
```bash	
php devoon db:update
```


## MVC METHODS --------------------------------

### Create controller instance
```bash 
php devoon controller:create NewController --gen
```

or


```bash 
php devoon controller:create NewController --gen
```


### Create model instance
> Model 
```bash 
php devoon model:create NewModels --table:tablename--permission:true or false
```
or

```bash 
php devoon controller:create NewController
```



### View

> View --------------------------------


```bash 
php devoon view:create NewViewName
```

### middleware --------------------------------


```bash 
php devoon middleware:create MiddlewareFile --name:middlewareName
```

- [*] Robust and fast users utility functions 
- [*] Security


devoon@copyright 2024