<?php
require "../../config/db_connect.php";
class Utilisateur {
    protected $id_user;
    protected $nom;
    protected $prenom;
    protected $email;
    protected $telephone;
    protected $motDePasse;

    // Constructeur
    public function __construct($id, $nom,$prenom, $email,$telephone, $motDePasse) {
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
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE mail = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
                if ($user['rolee'] === 'membre') {
                    return new Membre(
                        $user['id_user'],
                        $user['nom'],
                        $user['prenom'],
                        $user['email'],
                        $user['telephone'],
                        $user['motDePasse']
                    );
                } elseif ($user['rolee'] === 'admin') {
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



        // Méthode pour consulter les activités disponibles
        public static function consulterActivites($pdo) {
            $stmt = $pdo->query("SELECT * FROM activite WHERE disponibilite = 1");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Méthode pour réserver une activité
        public function reserverActivite($idActivite, $pdo) {
            try {
                $stmt = $pdo->prepare("INSERT INTO reservations (idmembre, idactivite) VALUES (:idmembre, :idactivite)");
                $stmt->execute([
                    'idmembre' => $this->id_user,
                    'idactivite' => $idActivite,
                ]);
                return "Réservation effectuée avec succès.";
            } catch (PDOException $e) {
                return "Erreur lors de la réservation : " . $e->getMessage();
            }
        }

        // Méthode pour annuler une réservation
        public function annulerReservation($idReservation, $pdo) {
            try {
                $stmt = $pdo->prepare("UPDATE reservations SET statut = 'Annulée' WHERE id_reservation = :idReservation AND idmembre = :idmembre");
                $stmt->execute([
                    'idReservation' => $idReservation,
                    'idmembre' => $this->id_user,
                ]);
                return "Réservation annulée avec succès.";
            } catch (PDOException $e) {
                return "Erreur lors de l'annulation : " . $e->getMessage();
            }
        }

        // Méthode pour afficher le récapitulatif des réservations
        public function afficherReservations($pdo) {
            $stmt = $pdo->prepare("SELECT r.id_reservation, a.nom_Activité, r.statut, r.date_reservation 
                                FROM reservations r 
                                JOIN activite a ON r.idactivite = a.id_Activite 
                                WHERE r.idmembre = :idmembre");
            $stmt->execute(['idmembre' => $this->id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
}
// class Admin hérit d 'utilisateur
class Admin extends Utilisateur {
    
    public function confirmerReservation($reservationId) {
        
        return "La réservation avec l'ID $reservationId a été confirmée.";
    }

    // Annuler une réservation
    public function annulerReservationAdmin($reservationId) {
        
        return "La réservation avec l'ID $reservationId a été annulée par l'administrateur.";
    }

    // Ajouter une activité
    public function ajouterActivite($activite) {
        
        return "Activité '$activite' ajoutée avec succès.";
    }
}
?>


