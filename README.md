# daysounds project
Projet IESA Multimedia

Concept Owner : @edouardthieffry

cloner le projet

> composer install/update

config de la database

vérifiez que vous utilisez bien php 7

dans le dossier app/config/parameters.yml (pour mac)

parameters:

    database_host: 127.0.0.1
    
    database_port:  8889
    
    database_name: daysounds
    
    database_user: root
    
    database_password: root
    
    mailer_transport: smtp
    
    mailer_host: 127.0.0.1
    
    mailer_user: null+
    
    mailer_password: null
    
    secret: ThisTokenIsNotSoSecretChangeIt


lancer le server
> php bin/console server:run

creer la database
> php bin/console doctrine:database:create
> php bin/console doctrine:schema:update --force

remplir la database
> php bin/console doctrine:fixtures:load

generer les assets
> php bin/console assets:install

se connecter a l'adresse 127.0.0.1:port   port->port configurer dans mamp pour mac et wamp64 pour windows

login as username :username 1 / password : root

enjoy !
