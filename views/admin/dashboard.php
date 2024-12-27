<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Utilisateur.php';

// Vérification si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ./autmembre.php");
    exit();
}
// Création de l'objet Admin
$admin = new Admin($_SESSION['id_user'], $_SESSION['nom'], '', '', '', '');

// Récupération des données nécessaires
$membres = Admin::consulterMembres($pdo);
$reservations = Admin::consulterReservations($pdo);
$activites = Admin::consulterActivites($pdo);

// Gestion des actions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifierReservation'])) {
        $message = $admin->modifierReservation($_POST['id_reservation'], $_POST['statut'], $pdo);
    } elseif (isset($_POST['ajouterActivite'])) {
        $message = $admin->ajouterActivite($_POST['nom_activite'], $_POST['description'], $_POST['capacite'], $_POST['date_debut'], $_POST['date_fin'], $pdo);
    } elseif (isset($_POST['modifierActivite'])) {
        $message = $admin->modifierActivite($_POST['id_activite'], $_POST['nom_activite'], $_POST['description'], $_POST['capacite'], $_POST['date_debut'], $_POST['date_fin'], $pdo);
    } elseif (isset($_POST['supprimerActivite'])) {
        $message = $admin->supprimerActivite($_POST['id_activite'], $pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Administrateur</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>

    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Membres inscrits -->
    <h2>Membres Inscrits</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <td><?php echo htmlspecialchars($membre['nom']); ?></td>
                    <td><?php echo htmlspecialchars($membre['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($membre['mail']); ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="id_membre" value="<?php echo $membre['id_user']; ?>">
                            <button type="submit" name="supprimer" id="supprimerActivite" style="background-color: red; color: white;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Réservations des membres -->
    <h2>Réservations des Membres</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Activité</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['nom']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['nom_Activité']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['statut']); ?></td>
                    <td><?php echo htmlspecialchars($reservation['date_reservation']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                            <select name="statut">
                                <option value="Confirmée" <?php echo $reservation['statut'] === 'Confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                <option value="Annulée" <?php echo $reservation['statut'] === 'Annulée' ? 'selected' : ''; ?>>Annulée</option>
                            </select>
                            <button type="submit" name="modifierReservation">Modifier</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Gestion des activités -->
    <h2>Liste des Activités</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Capacité</th>
                <th>Date Début</th>
                <th>Date Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activites as $activite): ?>
                <tr>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['id_Activite']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['nom_Activité']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['description']); ?></td>
                    <td class='px-4 py-2'><?php echo htmlspecialchars($activite['capacite']); ?></td>
                    <td class='px-4 py-2'><?php echo $activite['date_debut']; ?></td>
                    <td class='px-4 py-2'><?php echo $activite['date_fin']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gestion des Activités</h2>

    <h3>Ajouter une activité</h3>
    <form method="POST">
        <input type="text" name="nom_activite" placeholder="Nom de l'activité" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="capacite" placeholder="Capacité" required>
        <input type="date" name="date_debut" required>
        <input type="date" name="date_fin" required>
        <button type="submit" name="ajouterActivite">Ajouter</button>
    </form>

    <h3>Modifier/Supprimer une activité</h3>
    <form method="POST">
        <input type="number" name="id_activite" placeholder="ID de l'activité" required>
        <input type="text" name="nom_activite" placeholder="Nom de l'activité">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="capacite" placeholder="Capacité">
        <input type="date" name="date_debut">
        <input type="date" name="date_fin">
        <button type="submit" name="modifierActivite">Modifier</button>
    </form>
    <form method="POST">
        <input type="number" name="id_activite" placeholder="ID de l'activité" required>
        <button type="submit" name="supprimerActivite" style="background-color: #e74c3c;">Supprimer</button>
    </form>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>