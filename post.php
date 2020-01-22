<?php

$btnValider = filter_input(INPUT_POST,"btnValider");

if ($btnValider) 
{
  $dossier = 'img/upload/';
  $fichier = basename($_FILES['photo']['name']);
  $taille_maxi = 100000;
  $taille = filesize($_FILES['photo']['tmp_name']);
  $extensions = array('.png', '.gif', '.jpg', '.jpeg');
  $extension = strrchr($_FILES['photo']['name'], '.'); 

  //Début des vérifications de sécurité...
  if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
  {
      $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
  }
  if($taille>$taille_maxi)
  {
      $erreur = 'Le fichier est trop gros...';
  }
  if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
  {
      //On formate le nom du fichier ici...
      $fichier = strtr($fichier, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
      $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
      if(move_uploaded_file($_FILES['photo']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
      {
            echo 'Upload effectué avec succès !';
      }
      else //Sinon (la fonction renvoie FALSE).
      {
            echo 'Echec de l\'upload !';
      }
  }
  else
  {
      echo $erreur;
  }
}


?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Bibolop - Accueil</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Mon CSS a moi -->
    <link rel="stylesheet" href="css/style.css">
  </head>

  <body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: beige;">
    <div class="container">
      <div class="collapse navbar-collapse">
        <div class="navbar-nav mr-auto">
          <a class="navbar-brand" href="#">Bibolop</a>
        </div>
        <ul class="navbar-nav mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php"><i class ="fas fa-home"></i> Accueil</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="post.php"><i class="fas fa-plus"></i> Post</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

    <!-- Main Content -->
    <div class="container mt-5">
      <div class="card">
        <form action="" method="post" enctype="multipart/form-data">
        
          <div class="card-header">
            Ajouter un poste
          </div>
          <div class="card-body">
            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
          </div>
          <div class="card-footer">
          <div class="input-group mb-2 mt-2">
              <div class="input-group-prepend">
                <input type="submit" class="btn btn-success" value="Envoyez" name="btnValider">
              </div>
              
              <div class="custom-file">
                <input type="file" name="photo" class="custom-file-input"  id="myInput" aria-describedby="inputGroupFileAddon03" multiple>
                <label class="custom-file-label" for="inputGroupFile03" data-label="Fichier">Choisez un fichier</label>
              </div>
            </div>
          </div>

          
          </div>
        </form>   
      </div>
    </div>

    <!-- Footer -->
 

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/clean-blog.min.js"></script>

    <script>
      document.querySelector('.custom-file-input').addEventListener('change',function(e){
        var fileName = document.getElementById("myInput").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
      })
    </script>
   
  </body>
</html>
