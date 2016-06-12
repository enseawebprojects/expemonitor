<!DOCTYPE html>
<html>
  <head>
<meta charset="utf-8">
	
	<title> Ensea project </title>
	<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

	<?php
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
if (isset($_FILES['data_recu']) AND $_FILES['data_recu']['error'] == 0)
{
        // Testons si le fichier n'est pas trop gros
        if ($_FILES['data_recu']['size'] <= 1000000)
        {
                // Testons si l'extension est autorisée
                $infosfichier = pathinfo($_FILES['data_recu']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('csv','tsv');
                if (in_array($extension_upload, $extensions_autorisees))
                {
                        // On peut valider le fichier et le stocker définitivement
                        move_uploaded_file($_FILES['data_recu']['tmp_name'], 'upload/' . basename($_FILES['data_recu']['name']));
                        echo "L'envoi a bien été effectué !";
                }
        }
}
?>
	
	<script> 
	var data=basename($_FILES['data_recu']['name']);
	function load_data(){
		d3.csv("data.csv",function(data){
		console.log(data[0]);
		});
	}
		    
  </script> 
  </head>
  <body>

<p>Bonjour !</p>

<p>Je sais comment tu t'appelles, hé hé. Tu t'appelles <?php echo $_POST['data']; ?> !</p>

<p>Si tu veux changer de prénom, <a href="projet.html">clique ici</a> pour revenir à la page projet.html</p>
</form>
  </body>
</html>
