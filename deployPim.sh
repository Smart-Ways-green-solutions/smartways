docker-compose exec php composer install
docker-compose exec php bin/console ca:cl
docker-compose exec php bin/console pi:ca:cl
docker-compose exec php bin/console pim:dep:cla -c -vv
sudo chown -R www-data:www-data var/* public/*
docker-compose exec php bin/console assets:install public
sudo rm -rf var/admin/*
sudo chmod -R 777 var/* public/*
docker-compose exec node npm install
docker-compose exec node npm run build