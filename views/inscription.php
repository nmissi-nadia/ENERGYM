<?php
require_once '../classes/Utilisateur.php';
require_once '../config/db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['name']);
    $prenom = htmlspecialchars($_POST['fi_name']);
    $email = htmlspecialchars($_POST['email']);
    $motDePasse = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $telephone = htmlspecialchars($_POST['phone']);

    try {
        
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs (nom, prenom, mail, mot_de_passe, telephone, rolee) 
            VALUES (:nom, :prenom, :email, :motDePasse, :telephone, 'membre')
        ");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'motDePasse' => $motDePasse,
            'telephone' => $telephone
        ]);

        header("Location: ./autmembre.php");
    } catch (PDOException $e) {
        die("Erreur lors de l'inscription : " . $e->getMessage());
    }
}
?>
