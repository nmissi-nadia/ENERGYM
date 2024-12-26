<?php
session_start();
require_once '../classes/Utilisateur.php';
require_once '../config/db_connect.php';
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
              session_start();
              $_SESSION['id_user'] = $utilisateur->getId();
              $_SESSION['nom'] = $utilisateur->getNom();
              $_SESSION['role'] = 'membre';

              header("Location: ./membre/dashboard.php");
              exit();
          } elseif ($utilisateur instanceof Admin) {
              session_start();
              $_SESSION['id_user'] = $utilisateur->getId();
              $_SESSION['nom'] = $utilisateur->getNom();
              $_SESSION['role'] = 'admin';

              header("Location: /admin/dashboard.php");
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

<!DOCTYPE html>
<html>
<head>
    <title>AUthentification ENERGYM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/userlogin.css">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="inscription.php" id="form1" method="POST">
                <h1>Créer un compte</h1>
                <span>Utilisez votre email pour vous inscrire</span>
                <input type="text" name="name" placeholder="Nom" id="name" required>
                <input type="text" name="fi_name" placeholder="Prénom" id="fi_name" required>
                <input type="email" name="email" placeholder="Email" id="email" required>
                <input type="password" name="password" placeholder="Mot de passe" id="password" required>
                <input type="text" name="phone" placeholder="Numéro de téléphone" id="phone" required>
                <button id="button1">Inscrire</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="" id="form2" method="POST">
                <h1 id="head">Se connecter</h1>
                <span>Utilisez votre compte</span>
                <input type="email" name="email" placeholder="Email" id="email-log" required>
                <input type="password" name="password" placeholder="Mot de passe" id="password-log" required>
                <select name="role" style="background-color: #ffffff; width: 60px;" id="role">
                    <option value="admin">Admin</option>
                    <option value="membre">Membre</option>
                </select>
                <button id="button2">Connecter</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Bon Retour!</h1>
                    <p>Pour rester en contact avec nous, veuillez vous connecter avec vos informations personnelles</p>
                    <button class="ghost" id="signIn">Se connecter</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Bonjour !</h1>
                    <p>Entrez vos coordonnées et commencez à réserver avec nous</p>
                    <button class="ghost" id="signUp">Inscrire</button>
                    <a href="avocat/avocatreg.php">
                        <h3 class="rounded-[20px] border-[1px] border-[solid] border-[#20a87e] bg-[#20a87e] text-[#FFFFFF] text-[12px] font-bold px-[45px] py-[12px] tracking-[1px] uppercase [transition:transform_80ms_ease-in]">Inscription d'un avocat</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script type="text/javascript" src="../assets/js/userlogin.js"></script>
