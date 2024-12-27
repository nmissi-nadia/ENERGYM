# ENERGYM
Modernisation d’un système de gestion pour une salle de sport V2

# Gestion de Salle de Sport V2 - Projet Web

## Description

Le projet **"Gestion de Salle de Sport V2"** est une application web permettant aux membres d'une salle de sport de :
- Consulter les activités disponibles.
- Réserver des activités.
- Gérer leurs réservations.

Du côté de l'administration, l'application permet :
- La gestion des activités (ajout, modification, suppression).
- La consultation de la liste des membres inscrits et de leurs réservations.
- La confirmation ou l'annulation des réservations.

Ce projet a été conçu pour améliorer l'organisation et l'interaction entre les membres et l'administration d'une salle de sport.

---

## Fonctionnalités

### Membres
- Créer un compte et se connecter.
- Consulter les activités disponibles avec leurs détails.
- Réserver une activité.
- Consulter et annuler leurs réservations.

### Administrateur
- Consulter la liste des membres inscrits et leurs réservations.
- Ajouter, modifier ou supprimer des activités.
- Confirmer ou annuler des réservations.

---

## Technologies Utilisées

### Frontend
- **HTML5** : Structure des pages web.
- **CSS3** : Mise en page et design des interfaces.
- **JavaScript** : Interactivité et validation des formulaires.

### Backend
- **PHP** : Gestion de la logique métier et des interactions serveur.
- **MySQL** : Base de données pour stocker les utilisateurs, activités et réservations.
- **Laragon** : Environnement de développement local.

---

## Installation

1. Clonez le dépôt GitHub :
   ```bash
   git clone https://github.com/nmissi-nadia/ENERGYM.git
   ```

2. Importez la base de données :
   - Rendez-vous dans phpMyAdmin.
   - Importez le fichier `Commande.sql` situé à la racine du projet.

3. Configurez la connexion à la base de données dans `config/db_connect.php` :
   ```php
   <?php
   $host = 'localhost';
   $db = 'salle_sportv2';
   $user = 'root';
   $pass = '';

   try {
       $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die("Erreur : " . $e->getMessage());
   }
   ?>
   ```

4. Démarrez le serveur local avec Laragon et accédez au projet via votre navigateur :
   ```
   http://localhost/ENERGYM
   ```

---

## Utilisation

### Membres
1. Inscrivez-vous sur la page d'inscription.
2. Connectez-vous pour accéder aux activités.
3. Réservez une activité et consultez vos réservations dans votre espace personnel.

### Administrateur
1. Connectez-vous avec un compte administrateur.
2. Accédez au tableau de bord pour gérer les membres, les réservations et les activités.
3. Ajoutez ou modifiez les activités via le formulaire dédié.

---

## Auteur
**NMISSI NADIA**

- Contact : nmissinadia@gmail.com

**ELOUAH FADWA**

- Contact : elouahfadwa@gmail.com

- Étudiantes en développement web à YouCode.


---

## Remerciements
Merci à l'équipe pédagogique de YouCode pour leur soutien et leurs précieux conseils tout au long du développement de ce projet.

---


