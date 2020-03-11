<?php
/*
* 
*
*
*/
  require_once 'inc/functions.php';

  $btnValider = filter_input(INPUT_POST,"btnValider");                            // Détecte si le boutton valider a été cliqué.
  $description = filter_input(INPUT_POST,"description",FILTER_SANITIZE_STRING);   // Récupère la valeur du textArea.
  $afficherPopUp = false;                                                         // Cache la popup d'information.
  $erreur = [];
  $msg = "";

  // Lance les test a l'appuie du boutton.
  if ($btnValider) 
  {
    // Filtrage des champs.
    $description = filter_input(INPUT_POST,"description",FILTER_SANITIZE_STRING);

    // Vérifications pour le mot de passe.
    if(!$description || $description == "")
    {
        $erreur["description"] = "<p class='text-danger'>Veuillez rentrer une description. <br></p>";
        $msg .= $erreur["description"];
    } 

    // Vérifie si au moins une image a été envoyée au formulaire.
    $testFiles = $_FILES['photo']['name'];
    if ($testFiles[0] == "") 
    {
      $erreur["imageVide"] = "<p class='text-danger'>Vous devez envoyer au moines une image. <br></p>";
      $msg .= $erreur["imageVide"];
    }

    // Si il n'y a aucune erreur, passe a la suite en executant le code ci-dessous.
    if(count($erreur) == 0)
    {
      $countfiles = count($_FILES['photo']['name']);              // Nombres d'images recuperé par l'input files
      $dossier = 'img/upload/';                                   // Chemin ou seront uplaod les images
      $taille_maxi = 3000000;                                     // Tailles max en octet
      $extensions = array('.png', '.gif', '.jpg', '.jpeg','.mp4');// Formats acceptés
      $idPost = ajouterPost($description);
      echo "pas de pb";
      echo $idPost;
      echo $description;
      // Boucle qui va se repeter autant de fois que le nombre d'image envoyer dans l'input
      for($i=0;$i<$countfiles;$i++)
      {
        $filename = $_FILES['photo']['name'][$i];                 // Nom de l'image
        $extension = strrchr($filename, '.');                     // Extension de l'image
        $taille = filesize($_FILES['photo']['tmp_name'][$i]);     // Taille en octet de l'image 


        $arr = explode(".", $filename, 2);
        $nomFichierSansLeType = $arr[0]  . $idPost;               // nom de l'image sans le type
        $nomFichierSansLeType = preg_replace('/\s+/', '', $nomFichierSansLeType);             // Enlever les espaces
        $fichier = $nomFichierSansLeType . $extension;            // Nom du fichier

        // Vérifie si l'extension est bonne.
        if(!in_array($extension, $extensions)) 
        {
            $erreurImg = "<p class='text-danger'>Le fichier $filename doit être de type png, gif, jpg, jpeg <br></p>";
            $msg .= $erreurImg;
        }

        // Vérifie si la taille de l'image n'est pas trop haute.
        if($taille>$taille_maxi)
        {
            $erreurImg = "<p class='text-danger'>Le fichier $filename est trop gros. <br></p>";
            $msg .= $erreurImg;
        }

        // Passer a la suite si il n'y a aucune erreurs.
        if(!isset($erreurImg))
        {
            //Remplacer tout les accents.
            $fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
            $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
            // Uploads les fichier si la fonction renvoie TRUE.
            if(move_uploaded_file($_FILES['photo']['tmp_name'][$i], $dossier . $fichier)) 
            {         
                $msg .= "<p class='text-success'>L'upload de l'image $filename à été effectué avec succès ! <br></p>";
                ajouterMedia($nomFichierSansLeType,$extension,$idPost);

                if (isResizable(__DIR__."\\img\\upload\\".$nomFichierSansLeType.$extension) == true) 
                {
                  $msg .= resizePics(380,__DIR__."\\img\\upload\\".$nomFichierSansLeType.$extension);
                }                 
            }

            // Affiche les erreurs si elle renvoie FALSE.
            else 
            {      
                $msg .= "<p class='text-danger'>Echec de l'upload ! pour : $filename <br></p>";
            }
            if (verifyExtension("img/upload/".$nomFichierSansLeType.$extension) == false)
            {
              supprimerPost($idPost);
              supprimerMedia($idPost);
            }
        }

        // Supprime les erreurs d'upload si il y en a eu et passe a la prochaine image.
        else
        {
            unset($erreurImg);
        }

      }
    }

    // Affiche un popup d'information a la fin des tests.
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
    <?php
      include "inc/navbar/navbarBase.php";
    ?>   

    <!-- Formulaire -->
    <div class="container mt-5">
      <div class="card text-light" style="background-color: #fbfbfb">
        <form action="" method="post" enctype="multipart/form-data">
          <div class="card-header" style="background-color: #FAA275">
            <h4>Ajouter un post</h4>
          </div>
          <div class="card-body">
            <textarea name="description" id="textAreaImg" cols="30" rows="7" class="form-control"></textarea>
          </div>
          <div class="card-footer" style="background-color: #f0f0f0">
            <div class="input-group mb-2 mt-2">
              <div class="input-group-prepend">
                <input type="submit" class="btn btn-bouton1" value="Envoyez" name="btnValider">
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

    <!-- Popup d'information -->
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
