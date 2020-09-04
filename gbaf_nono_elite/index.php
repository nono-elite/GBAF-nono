<?php



session_start();
require('controller/intranet.php');

if (isset($_GET['action'])) {
    if (!isset($_SESSION['username'])) {
        if ($_GET['action'] == 'connexion') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                connexion($_POST['username'], $_POST['pass']);
            } else {
                pageConnexion();
            }
        } elseif ($_GET['action'] == 'register') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                register(
                    $_POST['lastname'],
                    $_POST['firstname'],
                    $_POST['username'],
                    $_POST['pass'],
                    $_POST['checkpass'],
                    $_POST['question'],
                    $_POST['answer']
                );
            } else {
                pageRegister();
            }
        } elseif ($_GET['action'] == 'forgetpass') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['change_pass_form'] == 'username') {
                    forgetPassUsername($_POST['username']);
                } elseif ($_POST['change_pass_form'] == 'question') {
                    forgetPassQuestion($_POST['answer']);
                } elseif ($_POST['change_pass_form'] == 'newpass') {
                    forgetPassNew($_POST['username'], $_POST['newpass'], $_POST['checkpass']);
                }
            } else {
                pageForgetpass();
            }
        }
    } else {
        if ($_GET['action'] == 'home') {
            pageHome();
        } elseif ($_GET['action'] == 'actor') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['actor_post'] == 'comment') {
                    addCommentActor($_POST['comment']);
                } elseif ($_POST['actor_post'] == 'vote') {
                    addVoteActor($_POST['up'], $_POST['down']);
                }
            } else {
                pageActor();
            }
        } elseif ($_GET['action'] == 'logout') {
            logout();
        } elseif ($_GET['action'] == 'account') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['account_form'] == 'username') {
                    changeUsername($_POST['username']);
                } elseif ($_POST['account_form'] == 'password') {
                    changePass($_POST['newpass'], $_POST['checkpass']);
                } elseif ($_POST['account_form'] == 'lastname') {
                    changeLastname($_POST['lastname']);
                } elseif ($_POST['account_form'] == 'firstname') {
                    changeFirstname($_POST['firstname']);
                } elseif ($_POST['account_form'] == 'questionAnswer') {
                    changeQuestionAnswer($_POST['question'], $_POST['answer']);
                }
            } else {
                pageAccount();
            }
        }
    }
} else {
    if (!isset($_SESSION['username'])) {
        pageConnexion();
    } else {
        pageHome();
    }
}
