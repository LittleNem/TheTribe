# The tribe game

Environnement utilisé
* [Ubuntu](https://ubuntu.com/download): Version 20.04.3 LTS
* [Docker](https://docs.docker.com/get-started/): Version 20.10.12
* [Docker-compose](https://docs.docker.com/compose/install/): Version 1.29.2
* [Make](https://linuxhint.com/install-make-ubuntu/): Version 4.2.1

## Lancement du projet
```
$ git clone git@github.com:LittleNem/TheTribe.git test
$ cd test
$ make build  #permet de générer le projet
$ make run    #permet de lancer le projet
$ make data   #permet de générer les datas et le jwt
```
Pour accéder au front, aller sur [localhost:3001](http://localhost:3001). Mais avant de naviguer dessus, aller sur [localhost/api](https://localhost/api) et accepter d'y accéder malgrès le "risque" dû à l'absence de certificat SSL valable.
![certificat](https://user-images.githubusercontent.com/6422825/158181994-5c5286a0-6982-46e6-be31-34adaf74b50f.gif)

Dans les données auto-générées, il y a des identifiants qui sont toujours fixes, pour que vous puissiez tester : <br/>
**email** : thetribe@test.fr <br/>
**password** : 123456789 <br/>
La branche à utiliser est la master. <br/>


## Technologies utilisées
**Symfony** pour la technologie principale back<br/>
**Api platform** un framework permettant de générer facilement une API<br/>
**ReactJS** pour le front<br/>
**Postgresql** pour la base de données<br/>
**Docker** pour la création de container et l'homogénisation de l'environnement

## Ce qui a été fait
* Connexion / déconnexion
* Liste des personnages
* Suppression du personnage
* Edition (du nom) du personnage
* Lancement d'un combat
* Règles du choix de l'adversaire
* Logique du combat
* Historique du combat lancé
* Règle à appliquer aux personnages selon le statut (gagné / perdu)

## Ce qui n'a pas été fait
* Inscription
* Distribution des points de skills dans la page du personnage
* Afficher la liste des combats pour un personnage
* Tests (fonctionnels, unitaires...)

## Ce que j'aurai voulu faire
* Ce qui n'a pas été fait
* Lors d'un lancement de combat, si le personnage sélectionné perd, l'enlever dynamiquement de la liste déroulante (il disparaît bien lors du rafraîchissement de la page)
* Ajouter une sécurité, vérifiant que l'adversaire attribué a bien des points d'attaque supérieur au point de défense du personnage OU des points de défense inférieur aux points d'attaque du personnage. (Auquel cas, une boucle infinie se créée) 
* Régler le soucis de SSL

## Note supplémentaires
A part docker (vaguement), je n'avais jamais touché aux technologies que j'ai choisi. 
Je ne me voyais pas utiliser Prestashop, la technologie que j'utilise depuis 3 ans, pour ce projet. Ni cakePHP, où je ne suis plus à jour. Quitte à me mettre à niveau, j'ai préféré prendre des technologies plus utilisées et adaptées. Je me suis aidée de formations en ligne pour la structure, sur laquelle j'ai retranscris le projet selon les besoins. J'en ai profité pour apprendre à faire une API, que je n'ai, jusqu'à aujourd'hui, que consommé. J'aurais aimé avoir eu le temps d'apprendre à mettre en place les outils assurant la réduction de dettes techniques (tests, CI...) mais j'ai dû me concentrer sur le fait de livrer quelque chose d'exploitable, utilisants déjà d'autres technologies que je ne connaissais que peu.

## Bilan
Je me suis bien amusée sur ce projet, je n'ai jamais eu un test technique aussi intense. 
Je me suis un peu mise en difficultée avec des technologies que je ne maîtrisais pas, du coup j'ai compensé en prenant plus de temps. Mais ça me confirme d'autant plus que j'aime le dev (J'avoue que ça m'a quand même bien épuisée, du coup je me suis prévue un gros week-end vacances :D). 
J'ai mis en place un docker pour faciliter le lancement du projet, je ne me suis pas attardée sur les configurations comme le devops n'est pas ce que je maîtrise le mieux et que ça n'était pas une priorité.
Merci pour ce beau projet en tant que test technique ! 

