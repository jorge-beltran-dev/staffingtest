# staffingtest
Staff Rota Shift test

- Installation

Run:
composer update
cp .env.example .env
sudo chmod -R 777 storage/
sudo chmod -R 777 bootstrap/cache
php artisan key:generate

Configure the database connection at .env

Run:
php artisan migrate

Access the homepage of the application for viewing the rota

- Notes

Staff names are randomly generated with faker each time the page loads.
Assumed day numbers where 0 Monday to 6 Sunday.
Assumed the working hours past 00:00 had to be assigned to the day the shift started.
A known bug is when a shifts starts after 00:00 the shift and the calculations for premium minutes are wrong, 
but this case is not present on the example data. Could be fixed with proper managment of shop opening and closing times.

- Tests

Run:
phpunit

Running the tests requires php-sqlite installed
