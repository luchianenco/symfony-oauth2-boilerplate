#!/usr/bin/env bash

#stop potentionally running app
docker-compose stop

##build and launch containers
docker-compose build
docker-compose up -d

##log into the container
docker exec -it --user www-data symfony-oauth2-app bash
docker-compose stop