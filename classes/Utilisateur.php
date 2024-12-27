<?php
require "../config/db_connect.php";
class Utilisateur {
    protected $id_user;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $telephone;
    protected $motDePasse;

    // Constructeur
    public function __construct($id, $nom,$prenom, $email,$telephpne, $motDePasse) {
        $this->id_user = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->motDePasse = $motDePasse;
    }

    // Getters
    public function getId() {
        return $this->id_user;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function gettelephone() {
        return $this->telephone;
    }

    // Setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    // Méthode pour afficher les informations
    public function afficherInformations() {
        return "ID: $this->id_user, Nom: $this->nom, Email: $this->email";
    }

    // Méthode d'authentification
    public static function authentifier($email, $motDePasse, $pdo) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($motDePasse, $user['motDePasse'])) {
                if ($user['role'] === 'membre') {
                    return new Membre(
                        $user['id_user'],
                        $user['nom'],
                        $user['prenom'],
                        $user['email'],
                        $user['telephone'],
                        $user['motDePasse']
                    );
                } elseif ($user['role'] === 'admin') {
                    return new Admin(
                        $user['id_user'],
                        $user['nom'],
                        $user['prenom'],
                        $user['email'],
                        $user['telephone'],
                        $user['motDePasse']
                    );
                }
            }
            return null; // Authentification échouée
        } catch (PDOException $e) {
            die("Erreur lors de l'authentification : " . $e->getMessage());
        }
    }
}

// cllass membre hérite d'utilisateur 
class Membre extends Utilisateur {
    private $reservations = [];

    // Ajout d une réservation
    public function ajouterReservation($reservation) {
        $this->reservations[] = $reservation;
    }
    // Annuler une réservation
    public function annulerReservation($reservationId) {
        foreach ($this->reservations as $index => $reservation) {
            if ($reservation['id'] === $reservationId) {
                unset($this->reservations[$index]);
                return true;
            }
        }
        return false;
    }

    // Afficher les réservations
    public function afficherReservations() {
        if (empty($this->reservations)) {
            return "Aucune réservation.";
        }

        $result = "Réservations:\n";
        foreach ($this->reservations as $reservation) {
            $result .= "- " . $reservation['details'] . "\n";
        }
        return $result;
    }
}


// ============================================================


// class Admin hérit d 'utilisateur
class Admin extends Utilisateur {
    
    // Méthode pour consulter la liste des membres inscrits
    public static function consulterMembres($pdo) {
        $stmt = $pdo->query("SELECT * FROM utilisateurs WHERE rolee = 'membre'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour consulter les réservations des membres
    public static function consulterReservations($pdo) {
        $stmt = $pdo->query("SELECT r.id_reservation, u.nom, u.prenom, a.nom_Activité, r.statut, r.date_reservation 
                              FROM reservations r
                              JOIN utilisateurs u ON r.idmembre = u.id_user
                              JOIN activite a ON r.idactivite = a.id_Activite");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour confirmer ou annuler une réservation
    public function modifierReservation($idReservation, $statut, $pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE reservations SET statut = :statut WHERE id_reservation = :idReservation");
            $stmt->execute([
                'idReservation' => $idReservation,
                'statut' => $statut,
            ]);
            return "Réservation modifiée avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de la modification de la réservation : " . $e->getMessage();
        }
    }

    // Méthode pour ajouter une nouvelle activité
    public function ajouterActivite($nomActivite, $description, $capacite, $dateDebut, $dateFin, $pdo) {
        try {
            $stmt = $pdo->prepare("INSERT INTO activite (nom_Activité, description, capacite, date_debut, date_fin) 
                                   VALUES (:nomActivite, :description, :capacite, :dateDebut, :dateFin)");
            $stmt->execute([
                'nomActivite' => $nomActivite,
                'description' => $description,
                'capacite' => $capacite,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
            ]);
            return "Activité ajoutée avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de l'ajout de l'activité : " . $e->getMessage();
        }
    }

    // Méthode pour modifier une activité
    public function modifierActivite($idActivite, $nomActivite, $description, $capacite, $dateDebut, $dateFin, $pdo) {
        try {
            $stmt = $pdo->prepare("UPDATE activite SET nom_Activité = :nomActivite, description = :description, capacite = :capacite, 
                                   date_debut = :dateDebut, date_fin = :dateFin WHERE id_Activite = :idActivite");
            $stmt->execute([
                'idActivite' => $idActivite,
                'nomActivite' => $nomActivite,
                'description' => $description,
                'capacite' => $capacite,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
            ]);
            return "Activité modifiée avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de la modification de l'activité : " . $e->getMessage();
        }
    }

    // Méthode pour supprimer une activité
    public function supprimerActivite($idActivite, $pdo) {
        try {
            $stmt = $pdo->prepare("DELETE FROM activite WHERE id_Activite = :idActivite");
            $stmt->execute(['idActivite' => $idActivite]);
            return "Activité supprimée avec succès.";
        } catch (PDOException $e) {
            return "Erreur lors de la suppression de l'activité : " . $e->getMessage();
        }
    }

    //Afficher la liste des activité 
    public function AfficherListActivite($pdo){
          $stmt = $pdo->query("SELECT * FROM activite");
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
          
    }
}
?>


