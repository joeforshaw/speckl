#!/bin/sh

if [ -z "$1" ]
then
    docker-compose run --rm test /home/bin/speckl
else
    docker-compose run --rm test /home/bin/speckl $@
fi