# imes-laravel

##### IMES backend by Laravel 8.0

###### Stages of deploy for development:

1. Run the `composer install` command
2. Run the `npm install` command
3. Create **.env** file in the root directory of the project
4. Copy all rules form the **.env.example** file and paste to the **.env**
5. Customize the `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` fields in the **.env**
6. Generate APP secret key by `php artisan key:generate`
7. Generate JWT secret key by `php artisan jwt:secret`
8. Run the migrations by `php artisan migrate`
9. Build the frontend by `npm run dev`

###### Login data:

* Email: _imes.pro@ukrlogika.com_ 
* Password: _secret00_

###### IDE helper:

1. `php artisan ide-helper:generate`
2. `php artisan ide-helper:meta`
3. `php artisan ide-helper:models`
