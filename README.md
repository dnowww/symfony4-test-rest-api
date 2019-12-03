Symfony 4 Test Rest Api

### To run:

- composer install
- fill '.env' with your DB settings (Postgres preferrable)
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load

##### Integer timestamp fields changed to datetime for compatibility with gedmo/doctrine-extensions package.