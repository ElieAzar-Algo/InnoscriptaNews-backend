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
