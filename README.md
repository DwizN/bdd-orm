# bdd-orm Antoine OFFROY
Création ORM M1 I2L

Le fichier de configuration est Mysql.php

Le nom de la base de données est bdaa il faudra la créer.

Avant de lancer le programme, exécuter le fichier version.sql avant d'exécuter run.php  
!SINON IL Y'AURA DES ERREURS POUR LE UPDATE!

Toutes les commandes se lancent à partir de run.php
Les commandes sont mises en commentaire.

Pour le update, j'ai décidé de créer une table version contenant le champs de la table, la version ainsi que le nombre de champs. 

Pour vérifier si je dois update je vérifie la valeur de la version de mon JSON avec la version contenue dans ma table version. si la version diffère alors j'update. 

Dans ce que j'ai rendu on peut : Ajouter un champ, Supprimer un champ, et update les types et propriétés, mais pas en même temps. on peut ajouter autant de champs qu'on veut, supprimer de champs autant qu'on veut et update autant de fields qu'on veut, ça marche mais pas en même temps. (Sinon risque éventuel de conflits). 

Pour l'ajout et la suppression je compare mon nombre de champs fields contenus dans mon JSON avec le nombre de champs que j'ai sauvegardé en base. 

Pour chaque mise à jour la ligne correspondant à la table (ici post) est mise à jour. 

Pour que les changements fonctionnent il faut absolument que la version diffère de la version contenue dans la table version.

( Il semble qu'il y'ait un problème d'update au niveau SQL pour la primary key, il faut lors des updates supprimer PRIMARY KEY du champs ID, primary key sera toujours présent même si PRIMARY KEY n'est pas spécifiée. 




