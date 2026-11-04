<?php
//La route pour récupérer la liste de tous les utilisateurs
//Elle sera en méthode GET

//inclure les ressources
include './env.php';
include './utils/functions.php';
include './model/modelUtilisateur.php';

function modification($host,$dbname,$login,$password){
    // I. Mise en place des header()
    //Controle de l'origin : Qui a le droit de demander service : ici TOUT LE MONDE
    header("Access-Control-Allow-Origin: *");

    // Format des données envoyées : Dans quelle langue je parle : ici en JSON, avec caractères encodés en UTF-8
    header("Content-Type: application/json; charset=UTF-8");

    // Méthodes autorisées : Quel est le but de la route : ici lire des données
    header("Access-Control-Allow-Methods: PUT, OPTIONS");

    // Entêtes autorisées pour le Client : qu'est-ce que le client a le droit de m'envoyer
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    // II. Contrôler la méthode de la requête HTTP
    // Je dois m'assurer que le Client accède à cette route grâce à la méthode PUT
    if($_SERVER['REQUEST_METHOD'] === "OPTIONS"){
        http_response_code(204);
            return;
    }

    if($_SERVER['REQUEST_METHOD'] != 'PUT'){
        http_response_code(405);
        echo json_encode(["message" => "La méthode n'est pas autorisée"]);
        return;
    }
    $data = json_decode(file_get_contents("php://input"));

    if(!is_object($data)){
        http_response_code(400);
        echo json_encode(["message" => "Corps de requête invalide ou absent"]);
        return;
    }
    
    if(!filter_var($data->email,FILTER_VALIDATE_EMAIL) || !filter_var($data->newEmail,FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(["message" => "Votre email n'est pas au bon format"]);
        return;
    }
    $pseudo = normalize($data->pseudo);
    $newEmail = strtolower(normalize($data->newEmail));
    $email = strtolower(normalize($data->email));
    $dob = normalize($data->dob);
    $nom = normalize($data->nom);
    $prenom = normalize($data->prenom);

    try{
        //Connexion à la BDD
        $bdd = connect($host,$dbname,$login,$password);

        
        $isEmailExist = lireUtilisateursByMail($bdd,$newEmail);
        if(!empty($isEmailExist)){
            //409 Conflict : la demande entre en conflit avec l'état actuel de la ressource
            http_response_code(409);
            echo json_encode(["message" => "Ce Pseudo et/ou cet Email est déjà pris"]);
            return;
        }

        //Enregistrement de l'utilisateur
        updateByMail($bdd, $nom, $prenom, $pseudo, $newEmail, $dob, $email);


        //Envoi de la réponse : 200 Created, car une nouvelle ressource a été créée
        http_response_code(200);
        echo json_encode(["message" => "Modification effectué avec succès"]);

        return;

    }catch(Exception $error){
        //Une API doit répondre en JSON MEME quand elle plante
        http_response_code(500);
        //On n'envoie jamais $error->getMessage() : cela exposerait la structure de la BDD
        echo json_encode(["message" => "Un problème est survenu lors de l'enregistrement"]);
        return;
    }

}
modification($_ENV['dbhost'],$_ENV['dbname'],$_ENV['dblogin'],$_ENV['dbpassword']);