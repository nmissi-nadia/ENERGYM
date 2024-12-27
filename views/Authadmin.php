<?php
session_start();
require_once '../config/db_connect.php';
require_once '../classes/Utilisateur.php';

// Méthode 1
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $email = htmlspecialchars($_POST['email']);
//     $motDePasse = $_POST['password'];
//     $role = $_POST['role'];

//     try {
       
//         $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE mail = :mail AND rolee = :rolee");
//         $stmt->execute(['mail' => $email, 'rolee' => $role]);
//         $user = $stmt->fetch(PDO::FETCH_ASSOC);

//         if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            
//             if ($role === 'membre') {
//                 $_SESSION['utilisateur'] = new Membre(
//                     $user['id_user'],
//                     $user['nom'],
//                     $user['prenom'],
//                     $user['mail'],
//                     $user['telephone'],
//                     $user['mot_de_passe']
//                 );
//                 header("Location: ./membre/dashboard.php");
//             } elseif ($role === 'admin') {
//                 $_SESSION['utilisateur'] = new Admin(
//                     $user['id_user'],
//                     $user['nom'],
//                     $user['prenom'],
//                     $user['mail'],
//                     $user['telephone'],
//                     $user['mot_de_passe']
//                 );
//                 header("Location: ../admin/dashboard.php");
//             }
//             exit;
//         } else {
//             $message = "Identifiants incorrects.";
//         }
//     } catch (PDOException $e) {
//         die("Erreur lors de la connexion : " . $e->getMessage());
//     }
// }
// Méthode 2
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupération des données du formulaire
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  

  try {
     
      $utilisateur = Utilisateur::authentifier($email, $password, $pdo);
      if ($utilisateur) {
          if ($utilisateur instanceof Membre) {
            //    session_start();
              $_SESSION['id_user'] = $utilisateur->getId();
              $_SESSION['nom'] = $utilisateur->getNom();
              $_SESSION['role'] = 'membre';

              header("Location: ./membre/dashboard.php");
              exit();
          } else if ($utilisateur instanceof Admin) {
              $_SESSION['id_user'] = $utilisateur->getId();
              $_SESSION['nom'] = $utilisateur->getNom();
              $_SESSION['role'] = 'admin';

              header("Location: ./admin/dashboard.php");
              exit();
          }
      } else {
          
          $erreur = "Email ou mot de passe incorrect.";
      }
  } catch (Exception $e) {
      // Gestion des erreurs
      $erreur = "Une erreur est survenue : " . $e->getMessage();
  }
}
?>