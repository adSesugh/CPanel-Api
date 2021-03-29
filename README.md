# Laravel CPanel API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/keensoen/cpanel-api.svg?style=flat-square)](https://packagist.org/packages/keensoen/cpanel-api)
[![Build Status](https://img.shields.io/travis/keensoen/cpanel-api/master.svg?style=flat-square)](https://travis-ci.org/keensoen/cpanel-api)
[![Quality Score](https://img.shields.io/scrutinizer/g/keensoen/cpanel-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/keensoen/cpanel-api)
[![Total Downloads](https://img.shields.io/packagist/dt/keensoen/cpanel-api.svg?style=flat-square)](https://packagist.org/packages/keensoen/cpanel-api)


> Laravel Package for CPanel UAPI  
  
Please consider starring the project to show your :heart: and support.  

> This laravel package allows you to connect and manage you CPanel based hosting using CPanel UAPI. 

Some practical usages are:
- Create database, sub domains, emails accounts etc
- Create database users
- Set privileges on database of any user
- List all email account of the specified domain
- Check email account size
- Increase email quota when need arises
- Delete email account when necessary

Learn more about at [CPanel UAPI](https://documentation.cpanel.net/display/DD/Guide+to+cPanel+API+2)

## Installation 

#### Step 1) Install the Package
Use following composer command to install the package
```bash  
composer require keensoen/cpanel-api 
```
or
Add `keensoen/cpanel-api` as a requirement to `composer.json`:

```
{
    ...
    "require": {
        ...
        "keensoen/cpanel-api": "^1.0"
    },
}
```

Update composer:

```
$ composer update
```

#### Step 2) Publish Configurations
Run following command:
```
php artisan vendor:publish --provider="Keensoen\CPanelApi\CPanelApiServiceProvider"
```
#### Step 3) Set CPanel details in `.env`
```
CPANEL_DOMAIN= 
CPANEL_PORT=
CPANEL_API_TOKEN=
CPANEL_USERNAME=
```
or

```php
$cpanel = new CPanel($cpanel_domain=null, $cpanel_api_token=null, $cpanel_username=null, $protocol='https', $port=2083);
```

To generate `CPANEL_API_TOKEN`, login to the `CPanel >> SECURITY >> Manage API Tokens >> Create`.

## Usages & available methods 
Make sure you import:
```php
use Keensoen\CPanelApi\CPanel;
```

#### To Get List of All Email Account in the CPanel

```php
$cpanel = new CPanel();  
$response = $cpanel->getEmailAccounts();
```

#### Create Email Account
Your password must be eight character above and should contain alphanumeric and symbols.
For example 

```php
$cpanel = new Cpanel()
$username = 'john.dansy';
$password = 'ideaHeals@#12';
$response = $cpanel->createEmailAccount($username, $password);
``` 

#### To Delete Email Account
You will have to pass a full email address to be able to delete the account.

```php
$cpanel = new Cpanel()
$response = $cpanel->deleteEmailAccount('john.dansy@example.com');
``` 

#### To Get Email Account Disk Usage
You will have to pass a full email address of which you want to get disk usage.

```php
$cpanel = new Cpanel()
$response = $cpanel->getDiskUsage('john.dansy@example.com');
``` 

#### To Increase Email Account Quota
You will have to pass a full email address of which you want to get disk usage.

```php
$cpanel = new Cpanel()
$email = 'john.dansy@example.com';
$quota = 1024;
$response = $cpanel->increaseQuota($email,$quota);
``` 

#### To Create Database
Database name should be prefixed with cpanel username `cpanelusername_databasename`

If your CPanel username is `surf` then your database name 
| should be `surf_website`.

```php
$cpanel = new CPanel();
$response = $cpanel->createDatabase('cpanelusername_databasename');
```
Find More Details at [CPanel UAPI - Mysql::create_database](https://documentation.cpanel.net/display/DD/UAPI+Functions+-+Mysql::create_database)

#### To Delete Database

```php
$cpanel = new CPanel();  
$response = $cpanel->deleteDatabase('cpanelusername_databasename');
```

[CPanel UAPI - Mysql::delete_database](https://documentation.cpanel.net/display/DD/UAPI+Functions+-+Mysql%3A%3Adelete_database)

#### To Get List of All Databases in the CPanel

```php
$cpanel = new CPanel();  
$response = $cpanel->listDatabases();
```
#### To Create Database User

```php
$cpanel = new CPanel();  
$response = $cpanel->createDatabaseUser($username, $password);
```
#### To Delete Database User

```php
$cpanel = new CPanel();  
$response = $cpanel->deleteDatabaseUser($username);
```

#### To Give All Privileges to a Database User On a Database

```php
$cpanel = new CPanel();  
$response = $cpanel->setAllPrivilegesOnDatabase($database_user, $database_name);
```

#### Using CPanel UAPI Methods
You can also call all the method available at  [CPanel UAPI](https://documentation.cpanel.net/display/DD/Guide+to+UAPI) using following method:
```php
$cpanel = new CPanel();  
$response = $cpanel->callUAPI($Module, $function, $parameters);
```
for example if you want to add new `ftp` account, documetation is available at [CPanel UAPI - Ftp::add_ftp](https://documentation.cpanel.net/display/DD/UAPI+Functions+-+Ftp%3A%3Aadd_ftp) then use the method as represented below:
```php
$cpanel = new CPanel();  
$Module = 'Ftp';
$function = 'add_ftp';
$parameters_array = [
'user'=>'ftp_username',
'pass'=>'ftp_password', //make sure you use strong password
'quota'=>'42',
];
$response = $cpanel->callUAPI($Module, $function, $parameters_array);
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email sesughagbadu@yahoo.com instead of using the issue tracker.

## Credits

- [Agbadu Daniel S.](https://github.com/keensoen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.