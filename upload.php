<!DOCTYPE html>
<html>
  <head>
<meta charset="utf-8">
	
	<title> Ensea project </title>
	<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
	<script> 
	var data=data_recu;
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
