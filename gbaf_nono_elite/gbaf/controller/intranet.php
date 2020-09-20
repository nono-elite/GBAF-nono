<?php

$GLOBALS['img_path'] = DIRECTORY_SEPARATOR . 'gbaf' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;

require_once('model/intranet.php');

function pageConnexion()
{
    include('view/intranet/login.php');
}

function connexion($usernamePost, $passPost)
{
    $username = htmlspecialchars($usernamePost);
    $pass = htmlspecialchars($passPost);
    $user = getUser($username);
    $isPasswordCorrect = password_verify($pass, $user['password']);
    if ($isPasswordCorrect) {
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php?action=home');
        exit();
    } else {
        $errorMsg = "Vérifiez vos identifiants";
    }
    include('view/intranet/login.php');
}

function logout()
{
    session_start();
    $_SESSION = array();
    session_destroy();
    header('location: index.php');
    exit();
}

function pageRegister()
{
    include('view/intranet/register.php');
}

function register($lastnamePost, $firstnamePost, $usernamePost, $passPost, $checkpassPost, $questionPost, $answerPost)
{
    $lastname = htmlspecialchars($lastnamePost);
    $firstname = htmlspecialchars($firstnamePost);
    $username = htmlspecialchars($usernamePost);
    $pass = htmlspecialchars($passPost);
    $checkpass = htmlspecialchars($checkpassPost);
    $question = htmlspecialchars($questionPost);
    $answer = htmlspecialchars($answerPost);
    $errors = 0;
    $errorsMsg = [];
    if (empty($lastname) OR strlen($lastname) > 30) {
        $errors++;
        $errorsMsg['lastname'] = 'Le nom est vide ou est trop long';
    }
    if (empty($firstname) OR strlen($firstname) > 30) {
        $errors++;
        $errorsMsg['firstname'] = 'Le prénom est vide ou est trop long';
    }
    $user = getUser($username);
    if (!empty($user)) {
        $errors++;
        $errorsMsg['username'] = 'ce pseudo est déjà pris veuillez en saisir un autre';
    }
    if (empty($username) OR strlen($username) > 20 OR strlen($username) < 4) {
        $errors++;
        $errorsMsg['username'] = 'Le nom d\'utilisateur est vide ou est trop long ou trop court';
    }
    if (empty($pass) OR strlen($pass) > 20 OR strlen($pass) < 4) {
        $errors++;
        $errorsMsg['pass'] = 'Le mot de passe est vide ou est trop long ou trop court';
    }
    if ($pass != $checkpass) {
        $errors++;
        $errorsMsg['diffPass'] = 'Le mot de passe n\'est pas identique';
    }
    if (empty($question) OR strlen($question) > 150) {
        $errors++;
        $errorsMsg['question'] = 'La question est vide ou est trop longue';
    }
    if (empty($answer) OR strlen($answer) > 50) {
        $errors++;
        $errorsMsg['answer'] = 'La réponse est vide ou est trop longue';
    }
    if ($errors === 0) {
            $pass_hache = password_hash($pass, PASSWORD_DEFAULT);
            createNewMember($lastname, $firstname, $username, $pass_hache, $question, $answer);
            header('Location: index.php');
            exit();
    }
    include('view/intranet/register.php');
}

function pageForgetpass()
{
    include('view/intranet/forgetpass.php');
}

function forgetPassUsername($usernamePost)
{
    $username = htmlspecialchars($usernamePost);
    $userExist = getUser($username);
    $question = $userExist['question'];
    if (empty($userExist)) {
        $errorUsername = 'Utilisateur inconnu';
    }
    include('view/intranet/forgetpass.php');
}

function forgetPassQuestion($answerPost)
{
    $answer = htmlspecialchars($_POST['answer']);
    $username = $_POST['username'];
    $answerExist = getAnswer($answer, $username);
    if (empty($answerExist)) {
        $errorAnswer = 'Réponse incorrecte';
        $userExist = getUser($username);
        $question = $userExist['question'];
    }
    include('view/intranet/forgetpass.php');
}

function forgetPassNew($usernamePost, $newpassPost, $checkpassPost)
{
    $username = $usernamePost;
    $newpass = htmlspecialchars($newpassPost);
    $checkpass = htmlspecialchars($checkpassPost);
    if (empty($newpass) OR strlen($newpass) > 20 OR strlen($newpass) < 4) {
        $errorPass = 'Le mot de passe est vide ou est trop long ou trop court';
    } else {
        if ($newpass != $checkpass) {
            $diffPass = 'Le mot de passe n\'est pas identique';
        } else {
            $pass_hache = password_hash($newpass, PASSWORD_DEFAULT);
            updatePass($pass_hache, $username);
            header('Location: index.php');
            exit();
        }
    }
    include('view/intranet/forgetpass.php');
}

function pageHome()
{
    $actors = getActors();
    $likes = getLikes();
    $dislikes = getDislikes();
    $likesByActor = [];
    foreach ($likes as $like) {
        $likesByActor += array($like['gbaf_actor_id'] => $like['nb_likes']);
    }
    $dislikesByActor = [];
    foreach ($dislikes as $dislike) {
        $dislikesByActor += array($dislike['gbaf_actor_id'] => $dislike['nb_dislikes']);
    }
    include('view/intranet/home.php');
}

function pageAccount()
{
    include('view/intranet/account.php');
}

function changeUsername($usernamePost)
{
    $newUsername = htmlspecialchars($usernamePost);
    $testUsername = getUser($newUsername);
    if (!empty($testUsername)) {
        $errorUsername = 'c\'est votre pseudo actuel ou ce pseudo est déjà pris veuillez en saisir un autre';
    } elseif (empty($newUsername) OR strlen($newUsername) > 20 OR strlen($newUsername) < 4) {
        $errorUsername = 'Le nom d\'utilisateur est vide ou est trop long ou trop court';
    } else {
        updateUsername($newUsername, $_SESSION['username']);
        $_SESSION['username'] = $newUsername;
        $confirmUsername = 'Votre nom d\'utilisateur a bien été changé';
    }
    include('view/intranet/account.php');
}

function changePass($newpassPost, $checkpassPost)
{
    $newpass = htmlspecialchars($newpassPost);
    $checkpass = htmlspecialchars($checkpassPost);
    if (empty($newpass) OR strlen($newpass) > 20 OR strlen($newpass) < 4) {
        $errorPass = 'Le mot de passe est vide ou est trop long ou trop court';
    } elseif ($newpass != $checkpass) {
        $diffPass = "Vos mots de passe saisis ne sont pas identiques, Réessayez";
    } else {
        $pass_hache = password_hash($newpass, PASSWORD_DEFAULT);
        updatePass($pass_hache, $_SESSION['username']);
        $confirmPassword = 'Votre mot de passe a bien été changé';
    }
    include('view/intranet/account.php');
}

function changeLastname($lastnamePost)
{
    $newLastname = htmlspecialchars($lastnamePost);
    if (empty($newLastname) OR strlen($newLastname) > 30) {
        $errorLastname = 'Le nom est vide ou est trop long';
    } else {
        updateLastname($newLastname, $_SESSION['username']);
        $_SESSION['lastname'] = $newLastname;
        $confirmLastname = 'Votre nom a bien été changé';
    }
    include('view/intranet/account.php');
}

function changeFirstname($firstnamePost)
{
    $newFirstname = htmlspecialchars($firstnamePost);
    if (empty($newFirstname) OR strlen($newFirstname) > 30) {
        $errorFirstname = 'Le prénom est vide ou est trop long';
    } else {
        updateFirstname($newFirstname, $_SESSION['username']);
        $_SESSION['firstname'] = $newFirstname;
        $confirmFirstname = 'Votre prénom a bien été changé';
    }
    include('view/intranet/account.php');
}

function changeQuestionAnswer($questionPost, $answerPost)
{
    $newQuestion = htmlspecialchars($questionPost);
    $newAnswer = htmlspecialchars($answerPost);
    if (empty($newQuestion) OR strlen($newQuestion) > 150) {
        $errorQuestion = 'La question est vide ou est trop longue';
    } elseif (empty($newAnswer) OR strlen($newAnswer) > 50) {
        $errorAnswer = 'La réponse est vide ou est trop longue';
    } else {
        updateQuestionAnswer($newQuestion, $newAnswer, $_SESSION['username']);
        $confirmQuestionAnswer = 'Vos question/réponse ont bien été changées';
        $user = getUser($_SESSION['username']);
    }
    include('view/intranet/account.php');
}

function pageActor()
{
    $member = getUser($_SESSION['username']);
    $commentExist = getCommentExist($_GET['gbaf_id_actor'],$member['id']);
    $voteExist = getVoteExist($_GET['gbaf_id_actor'],$member['id']);
    $actor = getActor($_GET['gbaf_id_actor']);
    $like = getLikeByActor($_GET['gbaf_id_actor']);
    $dislike = getDislikeByActor($_GET['gbaf_id_actor']);
    $listComment = getComment($_GET['gbaf_id_actor']);
    $comments = $listComment->fetchAll();
    $listVotes = getVoteByActor($_GET['gbaf_id_actor']);
    $votes = $listVotes->fetchAll();
    $votesByActor = [];
    foreach ($votes as $vote) {
        $votesByActor += array($vote['gbaf_member_id'] => $vote['vote']);
    }
    include('view/intranet/actor.php');
}

function addCommentActor($commentPost)
{
    $member = getUser($_SESSION['username']);
    $commentExist = getCommentExist($_GET['gbaf_id_actor'],$member['id']);
    $comment = htmlspecialchars($commentPost);
    $idActor = $_GET['gbaf_id_actor'];
    $errors = 0;
    if(!empty($commentExist)) {
        $errors++;
        $errorComment = "Vous avez déjà écrit un commentaire ici";
    }
    if (empty($comment) OR strlen($comment) > 500) {
        $errors++;
        $errorComment = "vide ou trop long";
    }
    if ($errors === 0) {
        insertComment($comment, $idActor, $member['id']);
        $commentExist = getCommentExist($_GET['gbaf_id_actor'],$member['id']);
    }
    $member = getUser($_SESSION['username']);
    $commentExist = getCommentExist($_GET['gbaf_id_actor'],$member['id']);
    $voteExist = getVoteExist($_GET['gbaf_id_actor'],$member['id']);
    $actor = getActor($_GET['gbaf_id_actor']);
    $like = getLikeByActor($_GET['gbaf_id_actor']);
    $dislike = getDislikeByActor($_GET['gbaf_id_actor']);
    $listComment = getComment($_GET['gbaf_id_actor']);
    $comments = $listComment->fetchAll();
    $listVotes = getVoteByActor($_GET['gbaf_id_actor']);
    $votes = $listVotes->fetchAll();
    $votesByActor = [];
    foreach ($votes as $vote) {
        $votesByActor += array($vote['gbaf_member_id'] => $vote['vote']);
    }
    include('view/intranet/actor.php');
}

function addVoteActor($upPost, $downPost)
{
    $member = getUser($_SESSION['username']);
    $voteExist = getVoteExist($_GET['gbaf_id_actor'],$member['id']);
    $like = $upPost;
    $dislike = $downPost;
    $idActor = $_GET['gbaf_id_actor'];
    if (isset($like)) {
        $vote = 1;
    }
    if (isset($dislike)) {
        $vote = 0;
    }
    insertLike($vote, $idActor, $member['id']);
    $voteExist = getVoteExist($_GET['gbaf_id_actor'],$member['id']);
    $member = getUser($_SESSION['username']);
    $commentExist = getCommentExist($_GET['gbaf_id_actor'],$member['id']);
    $voteExist = getVoteExist($_GET['gbaf_id_actor'],$member['id']);
    $actor = getActor($_GET['gbaf_id_actor']);
    $like = getLikeByActor($_GET['gbaf_id_actor']);
    $dislike = getDislikeByActor($_GET['gbaf_id_actor']);
    $listComment = getComment($_GET['gbaf_id_actor']);
    $comments = $listComment->fetchAll();
    $listVotes = getVoteByActor($_GET['gbaf_id_actor']);
    $votes = $listVotes->fetchAll();
    $votesByActor = [];
    foreach ($votes as $vote) {
        $votesByActor += array($vote['gbaf_member_id'] => $vote['vote']);
    }
    include('view/intranet/actor.php');
}
