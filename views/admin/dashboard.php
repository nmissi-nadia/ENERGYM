<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Utilisateur.php';



// Création de l'objet Admin
$admin = new Admin($_SESSION['id_user'], $_SESSION['nom'], '', '', '', '');

// Récupération des membres et des réservations
$membres = Admin::consulterMembres($pdo);
$reservations = Admin::consulterReservations($pdo);

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

<!-- =============== -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Administrateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        form {
            display: inline;
        }
        select, input[type="text"], input[type="number"], input[type="date"], textarea, button {
            margin: 5px 0;
            padding: 8px;
            font-size: 14px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>

    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Membres Inscrits</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <td><?php echo htmlspecialchars($membre['nom']); ?></td>
                    <td><?php echo htmlspecialchars($membre['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($membre['mail']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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
        <button type="submit" name="supprimerActivite">Supprimer</button>
    </form>

    <a href="logout.php">Se déconnecter</a>
</body>
</html>
