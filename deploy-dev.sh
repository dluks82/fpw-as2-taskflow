#!/bin/bash

# Parar e remover contêineres e redes
docker-compose -f docker-compose.yaml -f docker-compose.override.yaml down

# Subir novos contêineres
docker-compose -f docker-compose.yaml -f docker-compose.override.yaml up -d
