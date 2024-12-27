<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../classes/Utilisateur.php';

// Création de l'objet Admin
$admin = new Admin(13, 'elouah', '', '', '', '');

// Récupération des données nécessaires
$membres = Admin::consulterMembres($pdo);
$reservations = Admin::consulterReservations($pdo);
$activites = Admin:: AfficherListActivite($pdo);

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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>

<header class="bg-purple-500 w-[100%]">
<nav class="bg-purple-100 border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <img src="../../assets/images/energym.png" class="h-8 me-3 scale-[2.5]" alt="energym Logo" />
                <div class="flex items-center lg:order-2">
                    <a href="../logout.php" class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">Déconnexion</a>
                    <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="#" class="block py-2 pr-4 pl-3 text-gray-700  rounded bg-primary-700 lg:bg-transparent lg:hover:bg-transparen lg:text-primary-700 lg:p-0 dark:text-white " aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Activités</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Réservations</a>
                        </li>
                       
                    </ul>
                </div>
            </div>
    </nav>
    </header>
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
                        <form method="POST" class="formOne">
                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                          <div>
                              <select name="statut" class=>
                                  <option value="Confirmée" <?php echo $reservation['statut'] === 'Confirmée' ? 'selected' : ''; ?>>Confirmée</option>
                                  <option value="Annulée" <?php echo $reservation['statut'] === 'Annulée' ? 'selected' : ''; ?>>Annulée</option>
                              </select>
                          </div>
                           <div>
                             <button type="submit" name="modifierReservation" class="modifier">Modifier</button>
                           </div>
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

    <h3 class="ajouter">Ajouter une activité</h3>
    <form method="POST" class="titreAjouter">
        <input type="text" name="nom_activite" placeholder="Nom de l'activité" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="capacite" placeholder="Capacité" required>
        <input type="date" name="date_debut" required>
        <input type="date" name="date_fin" required>
        <button type="submit" name="ajouterActivite" class="bouttonAjouter">Ajouter</button>
    </form>

    <h3 class="ajouter">Modifier une activité</h3>
    <form method="POST" class="titreAjouter">
        <input type="number" name="id_activite" placeholder="ID de l'activité" required>
        <input type="text" name="nom_activite" placeholder="Nom de l'activité">
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="capacite" placeholder="Capacité">
        <input type="date" name="date_debut">
        <input type="date" name="date_fin">
        <button type="submit" name="modifierActivite" class="bouttonAjouter">Modifier</button>
    </form>
    
    <h3 class="ajouter">Supprimer une activité</h3>
    <form method="POST" class="titreAjouter">
        <input type="number" name="id_activite" placeholder="ID de l'activité" required>
        <button type="submit" name="supprimerActivite" class="bouttonAjouter">Supprimer</button>
    </form>

</body>
</html>
