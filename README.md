# alexia-weather-station [Fr]

Projet BTS SN


Le but de ce projet est d'afficher sur une page web les données reçues par un capteur de température branché en USB sur un **Raspberry Pi**. Celui-ci envoit la température environ toutes les **0.5s**.


Fonctionnalités
-

- Affichage web **responsive**.
- Accès sécurisé avec un compte.
- Affichage de la dernière température enregistée.
- Graphique affichant les 10 dernières températures relevées (**sur mobile, limité aux 5 dernières**).
- Contient une **API** qui permet d'accéder aux données enregistrées (***endpoint***: '**/api.php**').
- Supporte **MySQL** & **SQLite** pour la base de données gràce à l'orm **Medoo**.
- Alerte via notification **Push** grâce à l'API Pushbullet ou email.
Possibilité de modifier l'interval de rafraichissement du **daemon**, de la témpératures minimale ou maximale (pour le système d'alerte) depuis l'interface web.

Installation
-
Tout d'abord il vous faut **PHP 7.2** (la version ou j'ai developpé ce projet), **Composer** (gestionnaire de dépendances PHP) installé globalement (***%PATH%***) ainsi que les extensions suivantes installées et activées dans votre **php.ini**.

- php-mysql
- php-sqlite
- php-pdo

Après, il faut se rendre dans le dossier ou ce trouve notre application, soit ici **alexia-weather-station** et lancer les deux commandes suivantes: `composer install` et après `php bin/alexia.php --install`.

- **Remarque**: Si vous voulez installer des données de test, il suffit de rajouter le flag `--load-fixtures` soit comme ceci: `php bin/alexia.php --install --load-fixtures`.

Un fois l'installation il nous faut démarrer notre serveur web.
Ici j'utilise celui de PHP directement, mais vous pouvez utiliser Apache ou Nginx...

Pour lancer notre serveur avec PHP, on lance alors: `php -S localhost:8080 -t public`.

Pourquoi le flag `-t` car notre applicatin web se situe dans le dossier public

Le serveur est donc accessible en local sur le port 8080.

Afin de remplir notre base de données, il faut maintenant lancer notre daemon qui lui s'occupera de récupérer la témpérature relevée par le capteur: `php bin/daemon.php`.

***Note***: n'oubliez pas de modifier la variable **$handle** (ligne 18) du fichier ***daemon.php*** en fonction de votre cas.


À savoir:
-

- La méthode que j'ai choisi dans le **daemon** pour enregistrer les températures est peut-être un peu grossière, mais sur le coup j'avais pas vraiment d'idée.


- Au niveau du système d'alarme, j'ai choisi d'envoyer un mail à tous les utilisateurs inscrits en base de données en partant du principent que tout les utilisateurs sont **admin**. Pour les notifications Push, on utilise un seul compte pour tout le monde.

ToDo
-

- [ ] Rédiger un `readme.md` en anglais.
- [ ] Remplacer les messages en français par de l'anglais ou faire un système de traduction...


Pourquoi j'ai utilisé PHP ?
-
Il existe d'autres langages plus adaptés, cependant j'ai choisi ce langage car j'utilise PHP casiment tout le temps, de plus c'était un petit challenge pour moi.