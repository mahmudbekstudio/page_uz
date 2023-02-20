## Page Uz Platform ##

### Installation ###

* `git clone <repository> projectname`
* `cd projectname`
* `composer install`
* `npm install`
* `create .env file`
* `php artisan key:generate`
* `php artisan storage:link`
* `Create a database and inform *.env*`
* `php artisan migrate --seed` to create and populate tables
* `php artisan jwt:secret` This will update your .env file with something like JWT_SECRET=foobar
* `npm run dev` generate dev version of frontend
* `php artisan serve` to start the app on http://localhost:8000/

### Deploy ###

* `cd <project path>`
* `git pull origin master`
* `composer install`
* `npm install`
* `php artisan view:clear`
* `php artisan config:clear`
* `php artisan route:clear`
* `composer dumpautoload -o`
* `php artisan optimize --force`
* `php artisan config:cache`
* `php artisan route:cache`
* `php artisan migrate --seed` to create and populate tables
* `npm run prod` generate dev version of frontend
