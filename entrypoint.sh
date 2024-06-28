#!/bin/sh

# Executar Composer apenas no ambiente local
if [ "$APP_ENV" = "local" ]; then
  # Verificar se o composer.json está presente
  if [ -f "/var/www/html/composer.json" ]; then
    echo "composer.json encontrado, instalando dependências..."
    composer install --prefer-dist --no-progress --no-interaction
  else
    echo "composer.json não encontrado, pulando instalação de dependências..."
  fi
fi

# Iniciar o servidor Apache
apache2-foreground
