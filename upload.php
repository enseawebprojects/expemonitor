<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
 <head>
<meta charset="utf-8">
<title> Ensea project </title>


<?php
$type=0;
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

	else{
	     echo "il y a un problème";
	    }

 
switch ($_POST['choix']) {
	case "choix1":
		$type=1;
		break;
	case "choix2":
		$type=2;
		break;
	case "choix3":
		$type=3;
		break;
	case "choix4":
		$type=4;
		break;
	default:
		# code...
		break;
	}

if($type==1)
{ 
	echo"<style>

	body {
	  font: 10px sans-serif;
	}

	.axis path,
	.axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}

	.x.axis path {
	  display: none;
	}

	.line {
	  fill: none;
	  stroke: steelblue;
	  stroke-width: 1.5px;
	}

	</style>";}

elseif($type==2)
{
	echo " <style>
	.arc text {
	  font: 10px sans-serif;
	  text-anchor: middle;
	}

	.arc path {
	  stroke: #fff;
	}

	</style>";}	

?>


	
  </head>

  <body>

<p>Bonjour !</p>
 <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script type ="text/javascript">
	
var graphic_type = '<?php echo $type; ?>' ;
if(graphic_type==1)
{	

		var margin = {top: 20, right: 20, bottom: 30, left: 50},
		    width = 960 - margin.left - margin.right,
		    height = 500 - margin.top - margin.bottom;

		var formatDate = d3.time.format("%d-%b-%y");

		var x = d3.time.scale()
		    .range([0, width]);

		var y = d3.scale.linear()
		    .range([height, 0]);

		var xAxis = d3.svg.axis()
		    .scale(x)
		    .orient("bottom");

		var yAxis = d3.svg.axis()
		    .scale(y)
		    .orient("left");

		var line = d3.svg.line()
		    .x(function(d) { return x(d.date); })
		    .y(function(d) { return y(d.close); });

		var svg = d3.select("body").append("svg")
		    .attr("width", width + margin.left + margin.right)
		    .attr("height", height + margin.top + margin.bottom)
		  .append("g")
		    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

		d3.tsv("upload/data2.tsv", type, function(error, data) {
		  if (error) throw error;

		  x.domain(d3.extent(data, function(d) { return d.date; }));
		  y.domain(d3.extent(data, function(d) { return d.close; }));

		  svg.append("g")
		      .attr("class", "x axis")
		      .attr("transform", "translate(0," + height + ")")
		      .call(xAxis);

		  svg.append("g")
		      .attr("class", "y axis")
		      .call(yAxis)
		    .append("text")
		      .attr("transform", "rotate(-90)")
		      .attr("y", 6)
		      .attr("dy", ".71em")
		      .style("text-anchor", "end")
		      .text("Price ($)");

		  svg.append("path")
		      .datum(data)
		      .attr("class", "line")
		      .attr("d", line);
		});


		function type(d) {
		  d.date = formatDate.parse(d.date);
		  d.close = +d.close;
		  return d;
		}
}
else if(graphic_type==2)
{

	var width = 960,
    height = 500,
    radius = Math.min(width, height) / 2;

	var color = d3.scale.ordinal()
	    .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

	var arc = d3.svg.arc()
	    .outerRadius(radius - 10)
	    .innerRadius(0);

	var labelArc = d3.svg.arc()
	    .outerRadius(radius - 40)
	    .innerRadius(radius - 40);

	var pie = d3.layout.pie()
	    .sort(null)
	    .value(function(d) { return d.population; });

	var svg = d3.select("body").append("svg")
	    .attr("width", width)
	    .attr("height", height)
	  .append("g")
	    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

	d3.csv("upload/camembert.csv", type, function(error, data) {
	  if (error) throw error;

	  var g = svg.selectAll(".arc")
	      .data(pie(data))
	    .enter().append("g")
	      .attr("class", "arc");

	  g.append("path")
	      .attr("d", arc)
	      .style("fill", function(d) { return color(d.data.age); });

	  g.append("text")
	      .attr("transform", function(d) { return "translate(" + labelArc.centroid(d) + ")"; })
	      .attr("dy", ".35em")
	      .text(function(d) { return d.data.age; });
		});

		function type(d) {
		  d.population = +d.population;
		  return d;
		}
}

 </script> 

<p>Si tu veux changer de prénom, <a href="projet.html">clique ici</a> pour revenir à la page projet.html</p>

  </body>
</html>
