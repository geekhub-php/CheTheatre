#!/bin/bash

env=${1:-dev}

bin/console doctrine:database:drop --force --env=$env
bin/console doctrine:database:create --env=$env
bin/console doctrine:database:import 2021-01-03-theatre-dump.sql --env=$env
bin/console doctrine:migrations:migrate --no-interaction --env=$env
