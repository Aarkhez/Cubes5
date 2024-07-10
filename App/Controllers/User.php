<?php
namespace App\Controllers;
use App\Config;
use App\Model\UserRegister;
use App\Models\Articles;
use App\Utility\Hash;
use App\Utility\Session;
use \Core\View;
use Exception;
use http\Env\Request;
use http\Exception\InvalidArgumentException;
/**
 * User controller
 */
class User extends \Core\Controller
{
    /**
     * Affiche la page de login
     */
    public function loginAction()
{
    if (isset($_POST['submit'])) {
        $f = $_POST;
        // TODO: Validation
        // Appel de la méthode login pour tenter la connexion
        $loggedIn = $this->login($f);
        if ($loggedIn) {
            // Redirection vers la page du compte si la connexion est réussie
            header('Location: /account');
            exit;
        } else {
            // Si la connexion échoue, afficher le formulaire de login avec les messages flash
            $flashMessages = Session::getFlashes();
            View::renderTemplate('User/login.html', ['flash' => $flashMessages]);
            exit;
        }
    }
    // Affichage initial du formulaire de login
    $flashMessages = Session::getFlashes();
    View::renderTemplate('User/login.html', ['flash' => $flashMessages]);
}
    /**
     * Page de création de compte
     */
    public function registerAction()
    {
        if(isset($_POST['submit'])){
            $f = $_POST;
            if ($f['password'] !== $f['password-check']) {
                // Gestion d'erreur côté utilisateur
                Session::addFlash('danger', 'Les mots de passe ne correspondent pas.');
                header('Location: /register');
                exit;
            }
            // Ajoutez ici la logique d'inscription et de connexion de l'utilisateur
            $user = $this->register($f);
            if($user){
                $this->login($f);
            header('Location: /account');
            exit;
            }else {
                header('Location: /register');
            exit;
            }
        }
        $flashMessages = Session::getFlashes();
        View::renderTemplate('User/register.html', ['flash' => $flashMessages]);
    }
    /**
     * Affiche la page du compte
     */
    public function accountAction()
    {
        $articles = Articles::getByUser($_SESSION['user']['id']);
        View::renderTemplate('User/account.html', [
            'articles' => $articles
        ]);
    }
    /*
     * Fonction privée pour enregister un utilisateur
     */
    private function register($data)
{
    try {
        // Vérifier si l'email existe déjà dans la base de données
        $existingUser = \App\Models\User::getByLogin($data['email']);
        if ($existingUser) {
            // Utilisateur avec cet email existe déjà, retourner un message flash
            Session::addFlash('danger', 'Un compte avec cet email existe déjà.');
            return null; // Ou gérer le cas d'erreur selon votre logique
        }
        // Générer un sel pour le hachage du mot de passe
        $salt = Hash::generateSalt(32);
        // Créer l'utilisateur dans la base de données
        $userID = \App\Models\User::createUser([
            "email" => $data['email'],
            "username" => $data['username'],
            "password" => Hash::generate($data['password'], $salt),
            "salt" => $salt
        ]);
        return $userID;
    } catch (Exception $ex) {
        // En cas d'erreur, ajouter un message flash avec l'erreur
        Session::addFlash('danger', 'Une erreur est survenue lors de l\'inscription.');
        // Log de l'erreur si nécessaire
        error_log('Erreur lors de l\'inscription : ' . $ex->getMessage());
        return null; // Ou gérer le cas d'erreur selon votre logique
    }
}
function login($data){
        try {
            if(!isset($data['email'])){
                throw new Exception('TODO');
            }
            $user = \App\Models\User::getByLogin($data['email']);
            if (!$user) {
                Session::addFlash('danger', 'L\'utilisateur n\'existe pas.');
                return null;
            }
            if ($user && (Hash::generate($data['password'], $user['salt']) !== $user['password'])) {
                Session::addFlash('danger', 'Mot de passe erroné.');
                return false;
            }
            // TODO: Create a remember me cookie if the user has selected the option
            // to remained logged in on the login form.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L86
            $_SESSION['user'] = array(
                'id' => $user['id'],
                'username' => $user['username'],
            );
            return $_SESSION['user'];
        } catch (Exception $ex) {
            Session::addFlash('danger', 'Une erreur est survenue lors de la connexion.');
            // Log de l'erreur si nécessaire
            error_log('Erreur lors de la connexion : ' . $ex->getMessage());
            return null;
        }
    }
    /**
     * Logout: Delete cookie and session. Returns true if everything is okay,
     * otherwise turns false.
     * @access public
     * @return boolean
     * @since 1.0.2
     */
    public function logoutAction() {
        /*
        if (isset($_COOKIE[$cookie])){
            // TODO: Delete the users remember me cookie if one has been stored.
            // https://github.com/andrewdyer/php-mvc-register-login/blob/development/www/app/Model/UserLogin.php#L148
        }*/
        // Destroy all data registered to the session.
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header ("Location: /");
        return true;
    }
}
