#!/bin/bash

env=${1:-dev}

bin/console doctrine:database:drop --force --env=$env
bin/console doctrine:database:create --env=$env
bin/console doctrine:database:import 2021-10-20-chetheatre_prod.sql --env=$env
bin/console doctrine:migrations:migrate --no-interaction --env=$env
