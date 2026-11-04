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
L’un des champs obligatoires n’est pas rempli. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Veuillez remplir tous les champs obligatoires"
}

code 400 : Bad Request
L’email rentré n’est pas au bon format. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Votre email n’est pas au bon format"
}
code 400 : Bad Request
Le mot de passe fait moins de 8 caractères. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Le mot de passe doit contenir au moins 8 caractères"
}


code 409 : Conflict
Le pseudo ou l’email sont déjà utilisés par un autre utilisateur enregistré. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Ce Pseudo et/ou cet Email est déjà pris"
}

code 500 : Internal Server Error
L’enregistrement n’a pas pu aboutir. Le serveur renvoie un message d’erreur via le JSON suivant :
{
    "message": "Un problème est survenu lors de l’enregistrement"
}

modification.php

suppression.php
