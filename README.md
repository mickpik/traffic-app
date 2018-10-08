## SETUP
docker-compose build
docker-compose up

## TRAFFIC TO DATABASE
docker-compose exec php bin/console app:get:traffic

http://localhost:8080
