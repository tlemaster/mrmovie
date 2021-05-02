# Welcome to Mr. Movie
A small project that was originally created to help my father keep track of movies he's already seen and showcase a couple of my development skills. This site was created using a mix of technologies including Symfony and ReactJs. 

Thanks for stopping by!


Mr Movie uses the docker/compose config file set created by Martin Pham. Thank You Martin, details listed below.
# Symfony 5 docker containers

A Proof-of-concept of a running Symfony 5 application inside containers

```
git clone git@gitlab.com:martinpham/symfony-5-docker.git

cd symfony-5-docker

cd docker

docker-compose up
```

## Compose

### Database (MariaDB)

...

### PHP (PHP-FPM)

Composer is included

```
docker-compose run php-fpm composer 
```

To run fixtures

```
docker-compose run php-fpm bin/console doctrine:fixtures:load
```

### Webserver (Nginx)

...