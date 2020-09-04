<?php // Model = requêtes SQL

function getUser($username)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT id, lastname, firstname, username, password, question, answer FROM gbaf_member WHERE username = ?');
    $req->execute(array($username));
    $user = $req->fetch();
    return $user;
}

function getActors()
{
    require('db/connexion.php');
    $listActor = $db->query('SELECT * FROM gbaf_actor');
    $actors = $listActor->fetchAll();
    return $actors;
}

function getAnswer($answer, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT id, lastname, firstname, username, password, question, answer FROM gbaf_member WHERE answer = ? AND username = ?');
    $req->execute(array($answer, $username));
    $answer = $req->fetch();
    return $answer;
}

function getActor($idActor)
{
    require('db/connexion.php');
    $actorInfo = $db->prepare('SELECT * FROM gbaf_actor WHERE id = ?');
    $actorInfo->execute(array($idActor));
    $actor = $actorInfo->fetch();
    return $actor;
}

function createNewMember($lastname, $firstname, $username, $pass_hache, $question, $answer)
{
    require('db/connexion.php');
    $req = $db->prepare('INSERT INTO gbaf_member(lastname, firstname, username, password, question, answer) VALUES(:lastname, :firstname, :username, :password, :question, :answer)');
    $req->execute(array(
        'lastname'=> $lastname,
        'firstname' => $firstname,
        'username' => $username,
        'password' => $pass_hache,
        'question' => $question,
        'answer' => $answer
    ));
}

function updatePass($pass_hache, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('UPDATE gbaf_member SET password = ? WHERE username = ?');
    $req->execute(array($pass_hache, $username));
}

function updateUsername($newUsername, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('UPDATE gbaf_member SET username = ? WHERE username = ?');
    $req->execute(array($newUsername, $username));
}

function updateLastname($newLastname, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('UPDATE gbaf_member SET lastname = ? WHERE username = ?');
    $req->execute(array($newLastname, $username));
}

function updateFirstname($newFirstname, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('UPDATE gbaf_member SET firstname = ? WHERE username = ?');
    $req->execute(array($newFirstname, $username));
}

function updateQuestionAnswer($newQuestion, $newAnswer, $username)
{
    require('db/connexion.php');
    $req = $db->prepare('UPDATE gbaf_member SET question = ?, answer = ? WHERE username = ?');
    $req->execute(array($newQuestion, $newAnswer, $username));
}

function getComment($id_actor)
{
    require('db/connexion.php');
    $listComment = $db->prepare('SELECT gbaf_comment.comment, DATE_FORMAT(date_add, \'%d/%m/%Y à %Hh%imin%ss\') AS date_add_fr, gbaf_member.firstname, gbaf_member.id FROM gbaf_comment
        LEFT JOIN gbaf_member ON gbaf_comment.member_id = gbaf_member.id
        WHERE gbaf_comment.actor_id = ? ORDER BY gbaf_comment.date_add DESC');
    $listComment->execute(array($id_actor));
    return $listComment;
}

function getVoteByActor($îd_actor)
{
    require('db/connexion.php');
    $listVotes = $db->prepare('SELECT * FROM gbaf_vote WHERE actor_id= ?');
    $listVotes->execute(array($îd_actor));
    return $listVotes;
}

function getLikeByActor($id_actor)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT COUNT(gbaf_vote.vote) AS nb_vote FROM gbaf_vote WHERE actor_id = ? AND vote = 1');
    $req->execute(array($id_actor));
    $like = $req->fetch();
    return $like;
}

function getDislikeByActor($id_actor)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT COUNT(gbaf_vote.vote) AS nb_vote FROM gbaf_vote WHERE actor_id = ? AND vote = 0');
    $req->execute(array($id_actor));
    $dislike = $req->fetch();
    return $dislike;
}

function getLikes()
{
    require('db/connexion.php');
    $listLikes = $db->query('SELECT COUNT(*) AS nb_likes, actor_id FROM gbaf_vote WHERE vote = 1 GROUP BY actor_id');
    $likes = $listLikes->fetchAll();
    return $likes;
}

function getDislikes()
{
    require('db/connexion.php');
    $listDislikes = $db->query('SELECT COUNT(*) AS nb_dislikes, actor_id FROM gbaf_vote WHERE vote = 0 GROUP BY actor_id');
    $dislikes = $listDislikes->fetchAll();
    return $dislikes;
}

function getCommentExist($id_actor, $id_member)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT gbaf_comment.comment FROM gbaf_comment
        LEFT JOIN gbaf_member ON gbaf_comment.member_id = gbaf_member.id
        WHERE gbaf_comment.actor_id = ? AND gbaf_comment.member_id = ?');
    $req->execute(array($id_actor, $id_member));
    $commentExist = $req->fetch();
    return $commentExist;
}

function getVoteExist($id_actor, $id_member)
{
    require('db/connexion.php');
    $req = $db->prepare('SELECT gbaf_vote.vote FROM gbaf_vote
        LEFT JOIN gbaf_member ON gbaf_vote.member_id = gbaf_member.id
        WHERE gbaf_vote.actor_id = ? AND gbaf_vote.member_id = ?');
    $req->execute(array($id_actor, $id_member));
    $voteExist = $req->fetch();
    return $voteExist;
}

function insertComment($comment, $idActor, $idMember)
{
    require('db/connexion.php');
    $req = $db->prepare('INSERT INTO gbaf_comment(comment, date_add, actor_id, member_id) VALUES(:comment, NOW(), :actor_id, :member_id)');
    $req->execute(array(
        'comment' => $comment,
        'actor_id' => $idActor,
        'member_id' => $idMember
    ));
}

function insertLike($vote, $idActor, $idMember)
{
    require('db/connexion.php');
    $req = $db->prepare('INSERT INTO gbaf_vote(vote, actor_id, member_id) VALUES(:vote, :actor_id, :member_id)');
    $req->execute(array(
        'vote' => $vote,
        'actor_id' => $idActor,
        'member_id' => $idMember
    ));
}
