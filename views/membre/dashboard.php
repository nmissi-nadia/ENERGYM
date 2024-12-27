<?php
session_start();
require_once '../../classes/Utilisateur.php';

require_once '../../config/db_connect.php'; 

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'membre') {
    header("Location: ../login.php");
    exit();
}

$membre = new Membre($_SESSION['id_user'], $_SESSION['nom'], '', '', '', '');

$activites = Membre::consulterActivites($pdo);

$reservations = $membre->afficherReservations($pdo);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reserver'])) {
        $message = $membre->reserverActivite($_POST['id_activite'], $pdo);
    } elseif (isset($_POST['annuler'])) {
        $message = $membre->annulerReservation($_POST['id_reservation'], $pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Membre</title>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>

    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Activités disponibles</h2>
    <ul>
        <?php foreach ($activites as $activite): ?>
            <li>
                <?php echo htmlspecialchars($activite['nom_Activité']); ?> - 
                <?php echo htmlspecialchars($activite['description']); ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="id_activite" value="<?php echo $activite['id_Activite']; ?>">
                    <button type="submit" name="reserver">Réserver</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Mes Réservations</h2>
    <ul>
        <?php foreach ($reservations as $reservation): ?>
            <li>
                <?php echo htmlspecialchars($reservation['nom_Activité']); ?> - 
                Statut : <?php echo htmlspecialchars($reservation['statut']); ?> - 
                Date : <?php echo htmlspecialchars($reservation['date_reservation']); ?>
                <?php if ($reservation['statut'] === 'Confirmée'): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                        <button type="submit" name="annuler">Annuler</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>
