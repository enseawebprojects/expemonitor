<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
 <head>
<meta charset="utf-8">
<title> Ensea project </title>


<?php
$type=0;
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
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
	default:
		# code...
		break;
	}

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
		                	if($type==1){$file='data.tsv'; }
		                	elseif($type==2 || $type==3){$file='data.csv'; }
		           
		                        move_uploaded_file($_FILES['data_recu']['tmp_name'], 'upload/' . $file);
		                        
		                }
		        }


      
		}

	else{
	     echo "il y a un problème";
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

elseif($type==3)
{
	echo " <style>

.bar rect {
  fill: steelblue;
}

.bar text {
  fill: #fff;
  font: 10px sans-serif;
}

</style>";}	


?>


	
  </head>

  <body>
<style>
h1{
font-size: 20px;
color:rgb(206,103,0);
}
</style>
<h1> voici le résultat :</h1>

 <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 <script src="//d3js.org/d3.v4.0.0-alpha.45.min.js"></script>
 <script type ="text/javascript">
	
var graphic_type = '<?php echo $type; ?>' ;
var data='<?php echo $file; ?>' ;
if(graphic_type==1) // Line chart
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

d3.tsv("upload/data.tsv", type, function(error, data) {
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
else if(graphic_type==2) // PIE CHART
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

	d3.csv("upload/data.csv", type, function(error, data) {
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

else if(graphic_type==3) // HISTOGRAM
{

var parseDate = d3.timeParse("%m/%d/%Y %H:%M:%S %p"),
    formatCount = d3.format(",.0f");

var margin = {top: 10, right: 30, bottom: 30, left: 30},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var x = d3.scaleTime()
    .domain([new Date(2015, 0, 1), new Date(2016, 0, 1)])
    .rangeRound([0, width]);

var y = d3.scaleLinear()
    .range([height, 0]);

var histogram = d3.histogram()
    .value(function(d) { return d.date; })
    .domain(x.domain())
    .thresholds(x.ticks(d3.timeWeek));

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.append("g")
    .attr("class", "axis axis--x")
    .attr("transform", "translate(0," + height + ")")
    .call(d3.axisBottom(x));

d3.csv("upload/data.csv", type, function(error, data) {
  if (error) throw error;

  var bins = histogram(data);

  y.domain([0, d3.max(bins, function(d) { return d.length; })]);

  var bar = svg.selectAll(".bar")
      .data(bins)
    .enter().append("g")
      .attr("class", "bar")
      .attr("transform", function(d) { return "translate(" + x(d.x0) + "," + y(d.length) + ")"; });

  bar.append("rect")
      .attr("x", 1)
      .attr("width", function(d) { return x(d.x1) - x(d.x0) - 1; })
      .attr("height", function(d) { return height - y(d.length); });

  bar.append("text")
      .attr("dy", ".75em")
      .attr("y", 6)
      .attr("x", function(d) { return (x(d.x1) - x(d.x0)) / 2; })
      .attr("text-anchor", "middle")
      .text(function(d) { return formatCount(d.length); });
});

function type(d) {
  d.date = parseDate(d.date);
  return d;
}
}

 </script> 

<h1>  pour revenir à la page d'accueil <a href="projet.html">cliquez ici </a></h1>

  </body>
</html>
