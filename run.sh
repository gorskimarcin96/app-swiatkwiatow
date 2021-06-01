cp .env.example .env
docker-compose up -d
docker exec -it swiatkwiatow-app composer install
docker exec -it swiatkwiatow-app php ./vendor/bin/phpunit
docker exec -it swiatkwiatow-app php bin/console app:download-rand-images-from-website https://sklep.swiatkwiatow.pl/ 3
