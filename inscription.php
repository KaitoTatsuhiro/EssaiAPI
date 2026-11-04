<?php
//La route pour récupérer la liste de tous les utilisateurs
//Elle sera en méthode GET

//inclure les ressources
include './env.php';
include './utils/functions.php';
include './model/modelUtilisateur.php';

function inscrireUtilisateurs($host,$dbname,$login,$password){
    // I. Mise en place des header()
    //Controle de l'origin : Qui a le droit de demander service : ici TOUT LE MONDE
    header("Access-Control-Allow-Origin: *");

    // Format des données envoyées : Dans quelle langue je parle : ici en JSON, avec caractères encodés en UTF-8
    header("Content-Type: application/json; charset=UTF-8");

    // Méthodes autorisées : Quel est le but de la route : ici lire des données
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    // Entêtes autorisées pour le Client : qu'est-ce que le client a le droit de m'envoyer
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


    // II. Contrôler la méthode de la requête HTTP
    // Je dois m'assurer que le Client accède à cette route grâce à la méthode GET
    if($_SERVER['REQUEST_METHOD'] === "OPTIONS"){
        http_response_code(204);
            return;
    }

    if($_SERVER['REQUEST_METHOD'] != 'POST'){
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
    if(!isset($data->pseudo) || empty($data->pseudo)
        || !isset($data->email) || empty($data->email)
        || !isset($data->mdp) || empty($data->mdp)){
        
        http_response_code(400);
        echo json_encode(["message" => "Veuillez remplir tous les champs obligatoires"]);
        return;
    }
    if(!filter_var($data->email,FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        echo json_encode(["message" => "Votre email n'est pas au bon format"]);
        return;
    }

    if(strlen($data->mdp) < 8){
        http_response_code(400);
        echo json_encode(["message" => "Le mot de passe doit contenir au moins 8 caractères"]);
        return;
    }
    $pseudo = normalize($data->pseudo);
    $email = strtolower(normalize($data->email));
    //Les champs suivants n'étant pas obligatoires, s'ils existent on les normalise, sinon NULL
    $nom = isset($data->nom) ? normalize($data->nom) : NULL;
    $prenom = isset($data->prenom) ? normalize($data->prenom) : NULL;
    $dob = isset($data->dob) ? normalize($data->dob) : NULL;

    //Hashage du mot de passe, à partir de la donnée BRUTE
    $mdp = password_hash($data->mdp,PASSWORD_DEFAULT);

    try{
        //Connexion à la BDD
        $bdd = connect($host,$dbname,$login,$password);

        //Vérification de l'existence du pseudo et de l'email
        $isPseudoExist = lireUtilisateursByPseudo($bdd,$pseudo);
        $isEmailExist = lireUtilisateursByMail($bdd,$email);
        if(!empty($isPseudoExist) || !empty($isEmailExist)){
            //409 Conflict : la demande entre en conflit avec l'état actuel de la ressource
            http_response_code(409);
            echo json_encode(["message" => "Ce Pseudo et/ou cet Email est déjà pris"]);
            return;
        }

        //Enregistrement de l'utilisateur
        enregistrerUtilisateur($bdd,$pseudo,$email,$mdp,$nom,$prenom,$dob);


        //Envoi de la réponse : 201 Created, car une nouvelle ressource a été créée
        http_response_code(201);
        echo json_encode(["message" => "Enregistrement effectué avec succès"]);

        return;

    }catch(Exception $error){
        //Une API doit répondre en JSON MEME quand elle plante
        http_response_code(500);
        //On n'envoie jamais $error->getMessage() : cela exposerait la structure de la BDD
        echo json_encode(["message" => "Un problème est survenu lors de l'enregistrement"]);
        return;
    }

}

inscrireUtilisateurs($_ENV['dbhost'],$_ENV['dbname'],$_ENV['dblogin'],$_ENV['dbpassword']);

    
