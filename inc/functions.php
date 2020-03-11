<?php
require_once 'db/connexionDb.php';

$db = UserDbConnection();
setlocale(LC_ALL, "fr_FR.utf8", 'fra');

//Start la session si elle ne l'est pas déja
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

//Function servant a verifier si on est log ou non
function isLogged()
{
  return isset($_SESSION['connect']);
}

// Change le format de la date
function changeDateFormat($date)
{
  return utf8_encode(strftime("%A %d %B %Y &agrave; %Hh%M", strtotime($date)));
}

//Function servant a inscrire des nouveaux posts dans la base
function ajouterPost($commentaire)
{
  global $db;

  try 
  {
    $db->beginTransaction();
    $insertPost = $db->prepare("INSERT INTO post(commentaire) VALUES(:commentaire)");
    $insertPost->bindParam(":commentaire",$commentaire,PDO::PARAM_STR);
    $insertPost->execute();
    $lastId = $db->lastInsertId();
    $db->commit();
    return $lastId;
  } 
  catch (Exception $e) 
  {
    $db->rollBack();
    echo "Erreur : " . $e->getMessage();
  }
 
}

//Function servant a ajouter des nouvelles images
function ajouterMedia($nomMedia, $typeMedia, $idPost)
{
  global $db;

  $insertMedia = $db->prepare("INSERT INTO media(idPost, nomFichierMedia, typeMedia) VALUES(:idPost, :nomMedia, :typeMedia)");
  $insertMedia->bindParam(":idPost",$idPost,PDO::PARAM_INT);
  $insertMedia->bindParam(":nomMedia",$nomMedia,PDO::PARAM_STR);
  $insertMedia->bindParam(":typeMedia",$typeMedia,PDO::PARAM_STR);
  $insertMedia->execute();  
}

// Fonction supprimant un media
function supprimerMedia($idMedia)
{
  global $db;

  $supprimerMedia = $db->prepare("DELETE FROM media WHERE idMedia = :idMedia");
  $supprimerMedia->bindParam(":idMedia",$idMedia,PDO::PARAM_INT);

  $supprimerMedia->execute();  
}
// fonction supprimant un post
function supprimerPost($idPost)
{
  global $db;

  $supprimerPost = $db->prepare("DELETE FROM post WHERE idPost = :idPost");
  $supprimerPost->bindParam(":idPost",$idPost,PDO::PARAM_INT);

  $supprimerPost->execute();  
}

// Verifie si l'extension est bonne
function verifyExtension($img)
{

  $a = mime_content_type($img);
  switch ($a) 
  {
    case 'video/mp4':
      return True;
      break;
    case 'image/png':
      return True;
    break;
    case 'image/gif':
      return True;
    break;
    case 'image/jpg':
      return True;
    break;
    default:
    unlink($img);
      return False;
      break;
  }

}

function isResizable($img)
{
 
  $a = mime_content_type($img);
  switch ($a) 
  {
    case 'video/mp4':
      return False;
      break;
    case 'image/png':
      return True;
    break;
    case 'image/gif':
      return False;
    break;
    case 'image/jpg':
      return True;
    break;
    default:
      return False;
      break;
  }
}

// Fonction servant a resize une image.
function resizePics($newWidth = 200, $originalFile)
{
  $info = getimagesize($originalFile);
  $mime = $info['mime'];

  switch ($mime) {
    case 'image/jpeg':
      $image_create_func = 'imagecreatefromjpeg';
      $image_save_func = 'imagejpeg';
      $new_image_ext = 'jpg';
      break;

    case 'image/png':
      $image_create_func = 'imagecreatefrompng';
      $image_save_func = 'imagepng';
      $new_image_ext = 'png';
      break;

    case 'image/gif':
      $image_create_func = 'imagecreatefromgif';
      $image_save_func = 'imagegif';
      $new_image_ext = 'gif';
      break;

    default:
      return "<p class='text-danger'>FICHIER INVALIDE : $originalFile <br></p>";
  }

  $img = $image_create_func($originalFile);
  list($width, $height) = getimagesize($originalFile);
  $newHeight = ($height / $width) * $newWidth;
  $tmp = imagecreatetruecolor($newWidth, $newHeight);

  if ($new_image_ext == "gif" or $new_image_ext == "png") 
  {
    imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
    imagealphablending($tmp, false);
    imagesavealpha($tmp, true);
  }

  imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);


  $image_save_func($tmp,$originalFile);
}

function getAllPost()
{
  global $db;
  $requser = $db->query('SELECT idPost,commentaire,creationDate FROM post ORDER BY modificationDate desc');
  $postInfo = $requser->fetchAll();
  return $postInfo;
}

function getNbMediaByPost($idPost)
{
  global $db;

  $reqUserInfo = $db->prepare("SELECT COUNT(post.idPost) FROM post,media WHERE media.idPost = post.idPost and post.idPost = :id");
  $reqUserInfo->bindParam(":id",$idPost,PDO::PARAM_INT);
  $reqUserInfo->execute();
  $dataUser = $reqUserInfo->fetch();

  return $dataUser[0];
}

function getMediaByPost($idPost)
{
  global $db;

  $reqMedia = $db->prepare("SELECT nomFichierMedia,typeMedia,creationDate FROM `media` WHERE idPost = :id");
  $reqMedia->bindParam(":id",$idPost,PDO::PARAM_INT);
  $reqMedia->execute();
  $medias = $reqMedia->fetchAll();

  return $medias;
}

function showPosts()
{
  $postDatas = getAllPost();
  $html = "<div class='card-columns  m-5'>";

  foreach ($postDatas as $post) 
  {
    $medias = getMediaByPost($post["idPost"]);
    $nbMedias = getNbMediaByPost($post["idPost"]);

    if ($nbMedias >= 2) 
    {
      $html .= "<div class='shadow card p-4'>";
      $html .= "<div data-interval='false' id='carousel".$post["idPost"]."' class='carousel slide'>";
      $html .= "<ol class='carousel-indicators'>";

      for ($i = 0; $i < $nbMedias; $i++) 
      {
        if ($i == 0) 
        {
          $html .= "<li data-target='#carouselExampleIndicators' data-slide-to='$i' class='active'></li>";
        } 
        else 
        {
          $html .= "<li data-target='#carouselExampleIndicators' data-slide-to='$i'></li>";
        }
      }

      $html .= "</ol>";
      $html .= "<div class='carousel-inner'>";
          
      $tracteur = True;

      foreach ($medias as $m) 
      {
        if ($tracteur) 
        {
          $html .= "<div class='carousel-item active'>";
          $html .= "<img class='d-block w-100 img-responsive' src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "'>";
          $html .= "<hr></div>";
          $tracteur = false;
        } 
        else 
        {
          $html .= "<div class='carousel-item'>";
          $html .= "<img class='d-block w-100' src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "'>";
          $html .= "<hr></div>";
        }
      }

      $html .= "</div>";
      $html .= '
      <a class="carousel-control-prev" href="#carousel'.$post["idPost"].'" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carousel'.$post["idPost"].'" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>';
      $html .= "</div>";
    }
    else
    {
      foreach ($medias as $m) 
      {
        switch ($m["typeMedia"]) 
        {
          case '.png':
            $html .= "<div class='shadow card p-4'>";
            $html .= "<img class='d-block w-100' src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "'>";
            $html .= "<hr>";
            break;
          case '.jpg':
            $html .= "<div class='shadow card p-4'>";
            $html .= "<img class='d-block w-100' src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "'>";
            $html .= "<hr>";
            break;
          case '.gif':
            $html .= "<div class='shadow card p-4'>";
            $html .= "<img class='d-block w-100' src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "'>";
            $html .= "<hr>";
            break;
          case '.mp4':
            $html .= "<div class='shadow card p-4'>";
            $html .= "<video autoplay loop width='100%'>";
            $html .= "<source src='img/upload/" . $m["nomFichierMedia"] . $m["typeMedia"] . "' alt='" . $m["nomFichierMedia"] . "' type='video/mp4'>";
            $html .= "</video>";
            $html .= "<hr>";
              break;
          default:
            # code...
            break;
        }
      
      }
    }
    
    $html .= "<div class='card-body'>";
    $html .= "<h5 class='card-title mt-0'>".$post["commentaire"]."</h5>";
    $html .= "<p class='card-text mt-0'>".changeDateFormat($post["creationDate"])."</p>";
    $html .= '<hr>';
    $html .= '<a href="deletePost.php?id='.$post["idPost"].'"><button type="button" class="btn btn-danger btn-lg float-right"><i class="fas fa-trash"></i></button></a>';
    $html .= '<a href="deletePost.php?id='.$post["idPost"].'"><button type="button" class="btn btn-info btn-lg float-right mr-3"><i class="fas fa-edit"></i></button></a>';
    $html .= "</div></div>";
  }
  $html .= "</div>";
  return $html;
}
