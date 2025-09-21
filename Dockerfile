# Utiliser une image PHP officielle
FROM php:8.2-cli

# Installer curl (nécessaire pour Cohere API)
RUN apt-get update && apt-get install -y curl unzip git

# Copier tous les fichiers dans le container
WORKDIR /app
COPY . .

# Exposer le port
EXPOSE 10000

# Lancer le serveur PHP
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
