<?php
//La route pour récupérer la liste de tous les utilisateurs
//Elle sera en méthode GET

//inclure les ressources
include './env.php';
include './utils/functions.php';
include './model/modelUtilisateur.php';


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
    // Méthode GET : donc autorisé
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
    if(!isset($data->id)){
        
        http_response_code(400);
        echo json_encode(["message" => "Veuillez remplir tous les champs obligatoires"]);
        return;
    }
    $id = normalize($data->id);
    try{
        //Création de l'objet de connexion PDO
        $pdo = connect($_ENV['dbhost'],$_ENV['dbname'],$_ENV['dblogin'],$_ENV['dbpassword']);
        
        //Appel du model pour récupérer les data utilisateurs
        $data = findByID($pdo, $id);
        if(!$data){
            http_response_code(404);
            echo(json_encode(['code' => 404, 'message' => 'aucun utilisateur pour cet id, ce mail ou ce pseudo']));
            exit;
        }
        //Envoyer le message de réponse
        http_response_code(201);
        echo(json_encode(['code'=>201,'data'=>$data]));
        exit;

    }catch(Exception $error){
        //Erreur avec la BDD, donc envoie d'une erreur 500 Internal Server Error
        http_response_code(500);
        echo(json_encode(['code' => 500, 'message' => 'Erreur interne du Serveur']));
        exit;
    }
    