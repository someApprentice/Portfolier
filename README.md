# Portfolier

Service for creation  afinance Portfolio and combinate it with a Stocks
Service allow you to observe your Portfolio quotations for the last two years and calculate profit rate for the last year

Implemented via:
* Symfony
* Doctrine
* PostgreSQL
* Guzzle
* PHPUnit
* Twig
* Bootstrap

## Requirements
* PHP7.1 or above
* PostgreSQL
* Composer

## Installation
1. `git clone https://github.com/someApprentice/Portfolier.git`
1. `composer install`
1. change `DATABASE_URL` environment property in your `.env` file
1. `php bin/console doctrine:database:create`
1. `php bin/console doctrine:migrations:migrate`

### Adding a custom finance source

1. Implement `Portfolier\Source\SourceInterface`, `Portfolier\Factory\AbstractFactory` and `Portfolier\Entity\Quotations\AbstractQuotations` classes
1. Tag your new `SourceInterface` instance with the `source` tag in `config/services.yaml` file

Followed classes will be automaticly added to your service

### Troubleshooting
With a PostgreSQL version 10 may be a huge bug with creating migrations or just checking the schema difference

```
 [Doctrine\DBAL\Exception\InvalidFieldNameException]                                                        
  An exception occurred while executing 'SELECT min_value, increment_by FROM ...:  
  SQLSTATE[42703]: Undefined column: 7 ERROR:  column "min_value" does not exist                             
  LINE 1: SELECT min_value, increment_by FROM ...                                    
                 ^
  [Doctrine\DBAL\Driver\PDOException]                                             
  SQLSTATE[42703]: Undefined column: 7 ERROR:  column "min_value" does not exist  
  LINE 1: SELECT min_value, increment_by FROM ...         
                 ^
  [PDOException]                                                                  
  SQLSTATE[42703]: Undefined column: 7 ERROR:  column "min_value" does not exist  
  LINE 1: SELECT min_value, increment_by FROM ...         
```

Solution: https://github.com/doctrine/dbal/issues/2868#issuecomment-332119007

## Testing
1. Override the value of the DATABASE_URL env var in the phpunit.xml.dist to use a different database for your tests
1. `"vendor/bin/simple-phpunit"`

## Time spent for each stage

GMT+3

13.02.2018<br />
11:01 - 13:15, 14:16 - 16:25, 18:39 - 20:04  Gathering information<br />

14.02.2018<br />
10:39 - 11:44 Enviroment creation<br />
12:30 - 15:39, 16:27 - 17:24, 18:38 - 22:37 Authorization Service creation<br />

15.02.2018<br />
11:56 - 12:28, 14:27 - 15:32 Completing the Authorization Service<br />
16:26 - 20:30, 22:00 - 23:45 Portfolier Service creation<br />

16.02.2018<br />
13:07 - 16:00, 16:44 - 19:09 Completing the Portfolier Service<br />
20:37 - 22:10 Creating an architecture for grabbing data from a multiple Sources<br />
22:15 - 24:32 Implemeting a GoogleFinance Source<br />

17.02.2018<br />
9:16 - 12:48 Completing the GoogleFinance Source<br />
15:26 - 17:19, 18:12 - 19:37, 20:20 - 23:18 Creation of the methods for calculation quotations of all Portfolio Stocks and calculation profit rate<br />

18.02.2018<br />
15:44 - 17:32 Writing tests for the GoogleFinance Source<br />
17:32 - 19:01 Implemeting an YahooFinance Source<br />

19.02.2018<br />
00:35 Writing tests for the YahooFinance Source<br />
15:34 - 20:42 Creation of a SourceCollection<br />
22:39 - 2:01 Creating more handsome view
