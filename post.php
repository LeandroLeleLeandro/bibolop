<?php
/*
* 
*
*
*/

  $btnValider = filter_input(INPUT_POST,"btnValider");         // Détecte si le boutton valider a été cliqué
  $msg = "";
  $afficherPopUp = false;

  if ($btnValider) 
  {
    $countfiles = count($_FILES['photo']['name']);              // Nombres d'images recuperé par l'input files
    $dossier = 'img/upload/';                                   // Chemin ou seront uplaod les images
    $taille_maxi = 500000;                                      // Tailles max en octet
    $extensions = array('.png', '.gif', '.jpg', '.jpeg');       // Formats acceptés

    // Boucle qui va se repeter autant de fois que le nombre d'image envoyer dans l'input
    for($i=0;$i<$countfiles;$i++)
    {
      $filename = $_FILES['photo']['name'][$i];                 // Nom de l'image
      $extension = strrchr($filename, '.');                     // Extension de l'image
      $taille = filesize($_FILES['photo']['tmp_name'][$i]);     // Taille en octet de l'image
      $fichier = basename($filename);                           // Nom du fichier

      // Vérifie si l'extension est bonne.
      if(!in_array($extension, $extensions)) 
      {
          $erreur = "<p class='text-danger'>Le fichier $filename doit être de type png, gif, jpg, jpeg <br></p>";
      }

      // Vérifie si la taille de l'image n'est pas trop haute.
      if($taille>$taille_maxi)
      {
          $erreur = "<p class='text-danger'>Le fichier $filename est trop gros. <br></p>";
      }

      // Passer a la suite si il n'y a aucune erreurs.
      if(!isset($erreur))
      {
          //Remplacer tout les accents.
          $fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
          $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

          // Uploads les fichier si la fonction renvoie TRUE.
          if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $dossier . $fichier)) 
          {
            
              $msg .= "<p class='text-success'>L'upload de l'image $filename à été effectué avec succès ! <br></p>";
          }

          // Affiche les erreurs si elle renvoie FALSE.
          else 
          {
            
              $msg .= "<p class='text-danger'>Echec de l'upload ! pour : $filename <br></p>";
          }
      }

      // Afficher les erreurs si il y en a eu.
      else
      {
          $msg .= $erreur;  
          unset($erreur);
      }

    }
    $afficherPopUp = true;
  }
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Bibolop - POST</title>

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
                <input type="file" name="photo[]" class="custom-file-input"  id="myInput" aria-describedby="inputGroupFileAddon03" accept="image/*" multiple>
                <label class="custom-file-label" for="inputGroupFile03" data-label="Fichier">Choisez un fichier</label>
              </div>
            </div>
          </div>
        </form>    
      </div>   
    </div>

<!-- Pöopup -->
<div class="modal fade bd-example-modal-lg" id="popupInfos" tabindex="-1" role="dialog" aria-labelledby="popupInfos" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Informations :</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php
        if(isset($msg))
        {
          echo $msg;
        }
      ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Compris</button>
      </div>
    </div>
  </div>
</div>

  </body>
    <!-- Footer -->


    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="js/clean-blog.min.js"></script>

    <script>
    
      // Fonction servant a changer le texte de l'input file.
      $(document).ready(function() 
      {
        $('input[type="file"]').on("change", function() 
        {
          let filenames = [];
          let files = document.getElementById("myInput").files;

          // Affiche le nombre d'image envoyé si il y en a plus d'une
          if (files.length > 1) 
          {
            filenames.push("Nombre d'images : " + files.length);
          } 

          // Sinon affiche le nom de l'image envoyée.
          else 
          {
            for (let i in files) 
            {
              if (files.hasOwnProperty(i)) 
              {
                filenames.push(files[i].name);
              }
            }
          }
          $(this)
            .next(".custom-file-label")
            .html(filenames.join(","));
        });
      });

    </script>
    <?php if($afficherPopUp):?>
      <script> $('#popupInfos').modal('show');</script>
    <?php endif;?>

</html>
