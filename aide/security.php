<?php
  require_once 'connexionDb.php';

  $bdd = connectDB();
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //Start la session si elle ne l'est pas déja
  if (session_status() == PHP_SESSION_NONE)
  {
    session_start();
  }

  //Function servant a verifier si on est log ou non
  function isLogged()
  {
    return isset($_SESSION['connect']);
  }

  //Function servant a verifier les perms des utilisateurs
  function isAllowed($perm)
  {
    return isset($_SESSION["droits"]) && array_key_exists($perm, $_SESSION["droits"]);
  }

  //Function servant a inscrire des nouveaux membres dans la base
  function inscrireMembre($pseudo,$mail,$mdp,$avatar,$grade)
  {
    global $bdd;

    $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, email, password, avatar, grade, dateInscription) VALUES(?, ?, ?, ?, ?, NOW())");
    $insertmbr->execute(array($pseudo, $mail, $mdp, $avatar, $grade));
  }

  //Function qui verifie si quelqun a déja ce pseudo.
  function verifierPseudo($pseudo)
  {
    global $bdd;

    $reqpseudo = $bdd->prepare("SELECT pseudo FROM membres WHERE pseudo = ?");
    $reqpseudo->execute(array($pseudo));
    $pseudoexist = $reqpseudo->rowCount();

    if($pseudoexist == 0){
      return true;
    } else {
      return false;
    }
  }

  //Function qui insert un msg.
  function insererMsg($pseudo,$message)
  {
    global $bdd;

    $req = $bdd->prepare('INSERT INTO tchat (pseudo, message, dt_HeureEnvoie) VALUES(?, ?, NOW())');
    $req->execute(array($pseudo, $message));
  }

  //Function qui met un user a connecté
  function setConnected($id)
  {
    global $bdd;

    $insertConnected = $bdd->prepare("UPDATE membres SET  connected = ? WHERE idPseudo = ?");
    $insertConnected->execute(array("true",$id));;
  }

  //Function qui vérifie si un utilisateur existe
  function verifierUser($pseudo,$mdp)
  {
    global $bdd;

    $requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ? AND password = ?");
    $requser->execute(array($pseudo, $mdp));
    $userexist = $requser->rowCount();

    if($userexist == 1){
      return true;
    }
    else {
      return false;
    }
  }

  //Function qui nous donnes les infos d'un compte
  function getInfos($pseudo)
  {
    global $bdd;

    $requser = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
    $requser->execute(array($pseudo));
    $userinfo = $requser->fetch();
    return $userinfo;
  }

  function getInfosByID($id)
  {
    global $bdd;

    $requser = $bdd->prepare('SELECT * FROM membres WHERE idPseudo = ?');
    $requser->execute(array($id));
    $userinfo = $requser->fetch();
    return $userinfo;
  }

  //Function qui change la date du dernier msg a maintenant.
  function updateLastMsg($id)
  {
    global $bdd;
    $insertLastMsg = $bdd->prepare("UPDATE membres SET  lastMsg = NOW() WHERE idPseudo = ?");
    $insertLastMsg->execute(array($id));
    $insertDateLimite = $bdd->prepare("UPDATE membres SET  dateLimite = ? WHERE idPseudo = ?");
    $insertDateLimite->execute(array(date("Y/m/d H:i:s", strtotime("+10 minutes")),$id));
  }

  //Function qui retourne tout les messages du tchat.
  function getAllMsg()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM tchat ORDER BY IdMessage ASC');
    return $reponse;
  }

  //Function qui retourne les informations des users connecter.
  function getUserConnecter()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM membres WHERE connected LIKE "true"');
    return $reponse;
  }

  //Function qui retourne les user classé par leur nombre de msgs.
  function getAllUserByMsg()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM membres ORDER BY nbMessage DESC LIMIT 15');
    return $reponse;
  }

  //Function qui retourne les avatars des users qui en ont.
  function getUserAvatar()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT avatar,pseudo FROM membres WHERE avatar not like "NoPics.jpg"');
    return $reponse;
  }

  //Function qui met l'utilisateur entré en paramètre a déconnecter
  function setUserDisconected($id)
  {
    global $bdd;
    $insertConnecter = $bdd->prepare("UPDATE membres SET connected = ? WHERE idPseudo = ?");
    $insertConnecter->execute(array("false",$id));;
  }

  //Function qui met l'utilisateur entré en paramètre a connecter
  function setUserConnected($id)
  {
    global $bdd;
    $insertConnecter = $bdd->prepare("UPDATE membres SET connected = ? WHERE idPseudo = ?");
    $insertConnecter->execute(array("true",$id));;
  }

  //Function qui retourne la liste des emoji disponible.
  function getListeEmoji()
  {
    global $bdd;
    $reponse = $bdd->query('SELECT * FROM emoji ORDER BY LENGTH(NomEmoji) DESC');
    return $reponse;
  }

  //Function qui va effacer les msg du chat
  function clearChat()
  {
    global $bdd;
    $req = $bdd->query('truncate tchat');
  }

  function updateNbMsg($id)
  {
    global $bdd;
    $reqNbMsg = $bdd->prepare('SELECT nbMessage FROM membres WHERE idPseudo = ?');
    $reqNbMsg->execute(array($id));
    $reqFini = $reqNbMsg->fetch();
    $nombreMsg = $reqFini["nbMessage"];
    $nombreMsg = $nombreMsg + 1;
    $insertNbMessage = $bdd->prepare("UPDATE membres SET  nbMessage = ? WHERE idPseudo = ?");
    $insertNbMessage->execute(array($nombreMsg,$id));
  }

  //Function qui va supprimer l'emoji envoyer en paramètre.
  function deleteEmoji($id)
  {
    global $bdd;
    $req = $bdd->prepare('DELETE FROM emoji WHERE idEmoji = ?');
    $req->execute(array($id));
  }

  //Function qui va supprimer l'user envoyer en paramètre.
  function deleteUser($id)
  {
    global $bdd;
    $req = $bdd->prepare('DELETE FROM membres WHERE idPseudo = ?');
    $req->execute(array($id));
  }

  //Function qui va supprimer le message envoyer en paramètre.
  function deleteMessage($id)
  {
    global $bdd;
    $req = $bdd->prepare('DELETE FROM tchat WHERE IdMessage = ?');
    $req->execute(array($id));
  }

  //Function qui va changer le grade de l'utilisateur entre admin et user.
  function updateGrade($grade,$id)
  {
    global $bdd;
    if ($grade == "user")
    {
      $changeGrade = $bdd->prepare("UPDATE membres SET grade = ? WHERE idPseudo = ?");
      $changeGrade->execute(array("admin",$id));
    }
    if ($grade == "admin")
    {
      $changeGrade = $bdd->prepare("UPDATE membres SET grade = ? WHERE idPseudo = ?");
      $changeGrade->execute(array("user",$id));
    }
  }

  //Function qui va retourner la liste des messages triés par leur id
  function getMsgOrderById()
  {
    global $bdd;
    $messages = $bdd->query('SELECT * FROM tchat ORDER BY IdMessage');
    return $messages;
  }

  //Function qui va retourner la liste des membres triés par leur grade
  function getMembreOrderByGrade()
  {
    global $bdd;
    $membres = $bdd->query('SELECT * FROM membres ORDER BY grade, idPseudo');
    return $membres;
  }

  //Function qui va inserer l'emoji entrer en paramètre.
  function insererEmoji($appelageEmoji,$nomEmoji)
  {
    global $bdd;
    $insertEmoj = $bdd->prepare("INSERT INTO emoji(nomEmoji, urlEmoji) VALUES(?, ?)");
    $insertEmoj->execute(array($appelageEmoji, $nomEmoji));
  }

  //Function qui va update le pseudo.
  function updatePseudo()
  {
    global $bdd;
    $req = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE idPseudo = ?");
    $req->execute(array($pseudo, $id));
    $changePseudo = $bdd->prepare("UPDATE tchat SET pseudo = ? WHERE pseudo = ?");
    $changePseudo->execute(array($pseudo, $id));
  }

  //Function qui va update l'email.
  function updateEmail($email,$id)
  {
    global $bdd;
    $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE idPseudo = ?");
    $insertmail->execute(array($email,$id));
  }

  //Function  qui va update l'avatar
  function updateAvatar($avatar,$id)
  {
    global $bdd;
    $updateavatar = $bdd->prepare('UPDATE membres SET avatar = ? WHERE idPseudo = ?');
    $updateavatar->execute(array($avatar,$id));
  }

  //Function  qui va update le password de l'user
  function updatePassword($pwd,$id)
  {
    global $bdd;
    $req = $bdd->prepare('UPDATE membres SET password = ? WHERE idPseudo = ?');
    $req->execute(array($pwd,$id));
  }






















//
