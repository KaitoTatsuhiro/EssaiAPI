# Essai API
Documentation


INFORMATION D UN UTILISATEUR
http://localhost/Exercices/Exercice1/information.php
Méthode : POST
Données : envoyées dans le body au format JSON
•	id : l'id de l’utilisateur. Donnée obligatoire. Au format Int. Est unique dans la BDD.
Exemple de données envoyées :
{
    "id":"2"
}

Liste des Réponses :
code 201 : Created
Le serveur renvoie un message de confirmation d’enregistrement via le JSON suivant :
{
    "code": 201,
    "data": {
        "id": 2,
        "nom": "Miura",
        "prenom": "Kentaro",
        "pseudo": "Guts",
        "email": "griffith@gmail.com",
        "dob": "1966-07-11"
    }
}

code 405 : Method Not Allowed
Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "La méthode n'est pas autorisée"
}
code 400 : Bad Request
Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Corps de requête invalide ou absent"
}

code 400 : Bad Request
Les champs obligatoire ne sont pas remplis. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Veuillez remplir tous les champs obligatoires"
}
code 404 : Bad Request
Manque l'utilisateur avec cet id. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "aucun utilisateur pour cet id"
}

code 500 : Internal Server Error
L’enregistrement n’a pas pu aboutir. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Un problème est survenu lors de l’enregistrement"
}

modification.php

suppression.php
