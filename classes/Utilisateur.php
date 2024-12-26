<?php
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


