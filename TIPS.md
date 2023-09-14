# Laravel Start Commands

php artisan migrate
php artisan run

# Docker Commands
docker build -t innoscripta-news-app .
docker run -d -p 8000:8000 --name innoscripta-news-container innoscripta-news-app

# Run Schedule and CRON jobs
to test the schedule every minute you can run the following command:
php artisan schedule:work

to test the schedule with cron job every five minutes use the following cron job:
* * * * * cd < here the path of the project > && php artisan schedule:run >> /dev/null 2>&1
NOTE comment out the everySixHours schedules and uncomment the everyFiveMinutes schedules

the compatible CRON job with everySixHours Schedules is below:
0 */6 * * * cd < here the path of the project > && php artisan schedule:run >> /dev/null 2>&1


# Authentication using Sanctum


# check mysql in container
mysql -u elie -p -h mysql -P 3306

# reduce docker compose build time
COMPOSER_DISABLE_NETWORK=1 composer install

# check workers
docker-compose logs -f worker
OR 
docker-compose -f docker-compose-app.yml logs  worker

# build and run the 2 docker-compose files
docker-compose -f docker-compose-mysql.yml up -d --build
docker-compose -f docker-compose-app.yml up -d --build
