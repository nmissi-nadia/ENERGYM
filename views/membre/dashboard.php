<?php
session_start();
require_once  '../../classes/Utilisateur.php';

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['reserver']) || isset($_POST['annuler']))) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rejoignez notre salle de sport et atteignez vos objectifs de fitness. Profitez d'équipements modernes, de cours variés et d'un accompagnement personnalisé pour une expérience sportive unique.">
    <meta name="keywords" content="salle de sport, fitness, musculation, cours collectifs, entraînement personnalisé, équipement sportif, remise en forme, bien-être, coaching sportif, objectifs de fitness">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ENERGYM</title>
    <title>Tableau de bord - Membre</title>
</head>
<body>
    <header class="flex justify-between mx-10">
    <h1 >Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>
    <a href="../logout.php">Se déconnecter</a>
    </header>
    
    <main>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h2>Activités disponibles</h2>
        <table class="w-[90vw] bg-white  justify-self-center my-10">
            <thead>
            <tr class="bg-gray-200 text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                    <th class="py-2">ID d'Activité</th>
                    <th>Nom de l'Activité</th>
                    <th>Description</th>
                    <th>Capacité</th>
                    <th>Date début</th>
                    <th>Date Fin</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($activites as $activite): ?>
                <tr class='border-t'>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['id_Activite']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['nom_Activité']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['description']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['capacite']); ?></td>
                    <td class='px-4 py-2'><?php echo $activite['date_debut']; ?></td>
                    <td class='px-4 py-2'><?php echo $activite['date_fin']; ?></td>
                    <td class='px-4 py-2'><form method="POST" style="display: inline;">
                        <input type="hidden" name="id_activite" value="<?php echo $activite['id_Activite']; ?>">
                        <button type="submit" name="reserver">Réserver</button>
                    </form></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Mes Réservations</h2>

        <table class="w-[90vw] bg-white justify-self-center my-10">
            <thead>
            <tr class="bg-gray-200 text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                    <th class="py-2">ID de Réservation</th>
                    <th>Nom de l'Activité</th>
                    <th>Statut</th>
                    <th>Date Réservation</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr class='border-t'>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($reservation['id_reservation']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($reservation['nom_Activité']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($reservation['statut']); ?> </td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($reservation['date_reservation']); ?></td>
                    <td class='px-4 py-2'>
                        <?php if ($reservation['statut'] === 'Confirmée'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                <button type="submit" name="annuler">Annuler</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </main>
    
    
</body>
</html>
