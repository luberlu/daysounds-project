# daysounds project
Projet IESA Multimedia

Concept Owner : @edouardthieffry

Installation de Symfony : 

> Pour nous

Configuration de son serveur en local 

Installer la dernière version de MAMP

Configurer le port MYSQL en 8889

Ajouter la dernière version de PHP de l’installation de MAMP comme exécutable par défaut

sudo nano ~/.bash_profile

export PATH = /Applications/MAMP/bin/php/php7.0.8/bin:$PATH

Enregistrer

php -v pour s’assurer que tout à fonctionné.

____________________________________________________________

Se placer dans le répertoire propre à tous nos projets web
git clone https://github.com/luberlu/daysounds.git

Installer composer
$ curl -sS https://getcomposer.org/installer | php

Ajouter composer comme commande globale à son système
(https://getcomposer.org/doc/00-intro.md#globally)
sudo mv composer.phar /usr/local/bin/composer
php ./bin/console server:start



> Pour les externes

git clone https://github.com/luberlu/daysounds.git

Installer composer
$ curl -sS https://getcomposer.org/installer | php

Ajouter composer comme commande globale à son système
(https://getcomposer.org/doc/00-intro.md#globally)
sudo mv composer.phar /usr/local/bin/composer

cloner le projet

> composer update

config de la database

dans le dossier app/config/parameters.yml

parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: daysounds
    database_user: root
    database_password: root
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: ThisTokenIsNotSoSecretChangeIt

lancer le server
> php bin/console server:start

creer la database
> php bin/console doctrine:database:create
> php bin/console doctrine:schema:update --force

remplir la database
> php bin/console doctrine:fixtures:load

generer les assets
> php bin/console assets:install

se connecter a l'adresse 127.0.0.1:port   port->port configurer dans mamp pour mac et wamp64 pour windows