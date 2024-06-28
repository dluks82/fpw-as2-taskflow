#!/bin/bash

if [ "$1" == "prod" ]; then
  # Parar e remover contêineres e redes para produção
  docker-compose down
  # Subir novos contêineres para produção
  docker-compose up -d
elif [ "$1" == "dev" ]; then
  # Parar e remover contêineres e redes para desenvolvimento
  docker-compose -f docker-compose.yml -f docker-compose.override.yml down
  # Subir novos contêineres para desenvolvimento
  docker-compose -f docker-compose.yml -f docker-compose.override.yml up -d
else
  echo "Usage: ./deploy.sh [prod|dev]"
  exit 1
fi
