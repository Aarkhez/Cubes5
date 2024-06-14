<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use \Core\View;

/**
 * Contact controller
 */
class Contact extends \Core\Controller
{
    /**
     * Traite la soumission du formulaire de contact
     * @return void
     */
    public function submitAction()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Récupération des données du formulaire
            $nom = $_POST["nom"];
            $prenom = $_POST["prenom"];
            $email = $_POST["email"];
            $message = $_POST["message"];

            // Corps du message
            $message_body = "Nom: $nom $prenom<br>";
            $message_body .= "Email: $email<br>";
            $message_body .= "Message: $message<br>";

            // Créer une nouvelle instance de PHPMailer
            $mail = new PHPMailer();

            // Configurer le mode de débogage (facultatif, pour le développement)
            // Commentez cette ligne pour désactiver le mode de débogage en production
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // Configurer le serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par l'adresse du serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'takus05@gmail.com'; // Remplacez par votre adresse e-mail
            $mail->Password = 'ihigczapmebcfdgz'; // Remplacez par votre mot de passe e-mail
            $mail->SMTPSecure = 'ssl'; // Utiliser PHPMailer::ENCRYPTION_SMTPS pour SSL, PHPMailer::ENCRYPTION_STARTTLS pour TLS
            $mail->Port = 465; // Le port SMTP à utiliser (25, 587, 465 pour SSL)

            // Configurer le destinataire
            $mail->addAddress('takus05@gmail.com', '');

            // Encodage et type de contenu
            $mail->CharSet = 'UTF-8';
            $mail->ContentType = 'text/html';

            // Configurer le contenu de l'e-mail
            $mail->isHTML(true); // Indiquer si le contenu est au format HTML (true) ou texte brut (false)
            $mail->Subject = 'Formulaire de contact (CUBES5) de ' . $nom  . ' ' . $prenom;
            $mail->Body = $message_body;

            // Envoyer l'e-mail
            if ($mail->send()) {
                header('Location: product/contact?success=1'); // Redirection en cas de succès
                exit;
            } else {
                header('Location: product/contact?success=0'); // Redirection en cas d'échec
                exit;
            }
        }
    }
}
