sudo cp .docker/nginx.sample.conf .docker/nginx.conf
sudo cp .docker/supervisord.sample.conf .docker/supervisord.conf
docker-compose up -d
cat db.sql | docker-compose exec -T db mysql -u root -pROOT pimcore
docker-compose exec php composer install
docker-compose exec php bin/console pimcore:deployment:classes-rebuild
sudo chown -R www-data:www-data var/* public/*
docker-compose exec php bin/console assets:install public
docker-compose exec php bin/console app:create-admin-user
sudo rm -rf var/admin/*
sudo chmod -R 777 var/* public/*
docker-compose exec node npm install
docker-compose exec node npm run build