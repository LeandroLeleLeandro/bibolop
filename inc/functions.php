<?php
  require_once 'db/connexionDb.php';

  $db = UserDbConnection();

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

  //Function servant a inscrire des nouveaux posts dans la base
  function ajouterPost($commentaire)
  {
    global $db;

    $insertmbr = $db->prepare("INSERT INTO post(commentaire) VALUES(?)");
    $insertmbr->execute(array($commentaire));
    return $db->lastInsertId();
    
  }

  //Function servant a ajouter des nouvelles images
  function ajouterMedia($nomMedia,$typeMedia, $idPost)
  {
    global $db;

    $insertmbr = $db->prepare("INSERT INTO media(idPost, nomFichierMedia, typeMedia) VALUES(?, ?, ?)");
    $insertmbr->execute(array($idPost, $nomMedia, $typeMedia));
  }

  // Fonction servant a resize une image.
  function resize_image($filename) 
  {
    $source = imagecreatefromjpeg($filename);
    list($width, $height) = getimagesize($filename);

    $newwidth = $width/5;
    $newheight = $height/5;

    $destination = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    imagejpeg($destination, "output.jpg", 100);
}

