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
    <header class="">
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
                            <a href="#" class="block py-2 pr-4 pl-3 text-white rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 dark:text-white" aria-current="page">Home</a>
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
    <h1 class="justify-self-center text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-indigo-500 via-purple-500 to-pink-500 mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?></h1>
    </header>
    
    <main>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl dark:text-white">Activités disponibles</h2>
        <table class="w-[90vw] bg-white  justify-self-center my-10">
            <thead class="bg-gradient-to-r to-emerald-600 from-indigo-500 via-purple-500 to-pink-500">
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
                        <button class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-lg h-10" type="submit" name="reserver">Réserver</button>
                    </form></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl dark:text-white">Mes Réservations</h2>

        <table class="w-[90vw] bg-white justify-self-center my-10">
            <thead class="bg-gradient-to-r to-emerald-600 from-indigo-500 via-purple-500 to-pink-500">
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
                                <button class="bg-gradient-to-r to-emerald-600 from-indigo-500 via-purple-500 to-pink-500" type="submit" name="annuler">Annuler</button>
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
