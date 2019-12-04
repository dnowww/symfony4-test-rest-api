Symfony 4 Test Rest Api

### To run:

- composer install
- fill '.env' with your DB (Postgres) and REDIS settings (use your own servers or https://laradock.io/ instead)
- php bin/console doctrine:database:create
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load

### To use:
- get Postman
  - https://www.getpostman.com/
- import collection from repository: 
  - `Test Rest DNovikov.postman_collection.json`
- import environments: 
  - `TEST REST LOCAL.postman_environment.json`
  - `TEST REST REMOTE.postman_environment.json`
- use!

### Comments:
##### Integer timestamp fields changed to datetime for compatibility with gedmo/doctrine-extensions package.
