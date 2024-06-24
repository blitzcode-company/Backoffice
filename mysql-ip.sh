#!/bin/bash

MYSQL_CONTAINER=$(docker ps -q --filter name=mysql-backoffice)

if [[ -z "$MYSQL_CONTAINER" ]]; then
  echo "Error: Contenedor MySQL no encontrado."
  exit 1
fi

MYSQL_IP=$(docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $MYSQL_CONTAINER)

sed -i "s/^DB_HOST=.*$/DB_HOST=$MYSQL_IP/" .env

echo "Dirección IP de MySQL almacenada en .env: $MYSQL_IP"
