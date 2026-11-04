<?php
//Récupère la liste des utilisateurs en BDD
function findAll($bdd){
    //ATTENTION : on ne sélectionne JAMAIS le mot de passe pour une lecture publique.
    //Diffuser les hashs par l'API, c'est les offrir au bruteforce hors ligne.
    $req = $bdd->prepare('SELECT u.id, u.nom, u.prenom, u.pseudo, u.email, u.dob FROM utilisateurs u');

    $req->execute();

    $data = $req->fetchAll(PDO::FETCH_OBJ);

    return $data;
}
function lireUtilisateursByPseudo($bdd, $pseudo){
    $req = $bdd->prepare('SELECT id, nom, prenom, pseudo, email, dob FROM utilisateurs WHERE pseudo = ?');
    $req->bindParam(1,$pseudo,PDO::PARAM_STR);
    $req->execute();
    $data = $req->fetchAll(PDO::FETCH_OBJ);

    return $data;
}
function lireUtilisateursByMail($bdd, $email){
    //Ici on garde le mdp : cette fonction servira AUSSI à la connexion (password_verify).
    //C'est un bon exemple : une même table se lit différemment selon l'usage.
    $req = $bdd->prepare('SELECT id, nom, prenom, pseudo, email, mdp, dob FROM utilisateurs WHERE email = ?');
    $req->bindParam(1,$email,PDO::PARAM_STR);
    $req->execute();
    $data = $req->fetchAll(PDO::FETCH_OBJ);

    return $data;
}
function enregistrerUtilisateur($bdd, $pseudo, $email, $mdp, $nom, $prenom, $dob){
    $req = $bdd->prepare('INSERT INTO utilisateurs (nom, prenom, pseudo, email, mdp, dob) VALUES (?,?,?,?,?,?);');

    $req->bindParam(1,$nom,PDO::PARAM_STR);
    $req->bindParam(2,$prenom,PDO::PARAM_STR);
    $req->bindParam(3,$pseudo,PDO::PARAM_STR);
    $req->bindParam(4,$email,PDO::PARAM_STR);
    $req->bindParam(5,$mdp,PDO::PARAM_STR);
    $req->bindParam(6,$dob,PDO::PARAM_STR);
    $req->execute();
}

function findByID($bdd,$id){
    //ATTENTION : on ne sélectionne JAMAIS le mot de passe pour une lecture publique.
    //Diffuser les hashs par l'API, c'est les offrir au bruteforce hors ligne.
    $req = $bdd->prepare('SELECT u.id, u.nom, u.prenom, u.pseudo, u.email, u.dob FROM utilisateurs u WHERE u.id=?');

    $req->bindParam(1,$id,PDO::PARAM_INT);
    $req->execute();

    $data = $req->fetch(PDO::FETCH_OBJ);

    return $data;
}

function updateByMail($bdd, $nom, $prenom, $pseudo, $newEmail, $dob, $email){
    //ATTENTION : on ne sélectionne JAMAIS le mot de passe pour une lecture publique.
    //Diffuser les hashs par l'API, c'est les offrir au bruteforce hors ligne.
    $req = $bdd->prepare('UPDATE utilisateurs SET nom=?, prenom=?, pseudo=?, email=?, dob=? WHERE email=?');

    $req->bindParam(1,$nom,PDO::PARAM_STR);
    $req->bindParam(2,$prenom,PDO::PARAM_STR);
    $req->bindParam(3,$pseudo,PDO::PARAM_STR);
    $req->bindParam(4,$newEmail,PDO::PARAM_STR);
    $req->bindParam(5,$dob,PDO::PARAM_STR);
    $req->bindParam(6,$email,PDO::PARAM_STR);
    $req->execute();
}

function deleteByPseudo($bdd, $pseudo){
    $req = $bdd->prepare('DELETE FROM utilisateurs WHERE pseudo=?');

    $req->bindParam(1,$pseudo,PDO::PARAM_STR);
    $req->execute();
}