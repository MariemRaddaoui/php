<?php 
$nom=ucfirst($_POST["nom"]);
$prenom=ucfirst($_POST["prenom"]);
$nombre=$_POST["nombre"];
$adresse=$_POST["adresse"];
$type=ucfirst($_POST["type"]);
$avec_mayonnaise= isset($_POST["mayonnaise"]);
$avec_salade=isset($_POST["salade"]);
$avec_harissa=isset($_POST["harissa"]);
$prix_total=$nombre*4;
$remise=0;
$prix_final=$prix_total;
if($nombre>10){
    $remise=$prix_total*0.1;
    $prix_final=$prix_total-$remise;
}
?>


<html>
<body>

    <h2 style="text-align:center">Votre commande:</h2>
    <table style=" margin-left: auto; margin-right: auto;" border="1" margin="auto" cellpadding="15" cellspacing="3" width="50%">
		<tbody>
        <tr>
			<th >Nom</th>
			<td style="text-align:center"><?=$nom?></td>
		</tr>
		<tr>
        <th >Prenom</th>
			<td style="text-align:center"><?=$prenom?></td>
		</tr>
		<tr>
        <th >No. de sandwichs</th>
			<td style="text-align:center"><?=$nombre?></td>
		</tr>
		<tr>
        <th >Type</th>
			<td style="text-align:center"><?=$type?></td>
		</tr>
        <tr>
            <th >Ingrediants à ajouter</th>
			<td style="text-align:center"><?php
            if ($avec_mayonnaise==FALSE && $avec_harissa==FALSE && $avec_salade==FALSE){
                echo "Rien";
            }
            else if ($avec_harissa==TRUE){
                echo " Harissa\n";
            }
            if($avec_mayonnaise==TRUE){
                echo " Mayonnaise\n";
            }
            if($avec_salade==TRUE){
                echo " Salade\n";
            }
            ?></td>
		</tr>
        <tr>
        <th >Prix Total</th>
			<td style="text-align:center"><?="${prix_total} DT"?></td>
		</tr>
        <?php 
        if ($nombre>10){
            echo "<tr>
            <th >Remise</th>
			<td style=\"text-align:center\">${remise} DT</td>
		</tr>";
        echo "<tr>
        <th >Prix Final après Remise</th>
        <td style=\"text-align:center\">${prix_final} DT</td>
    </tr>";
        }?>
        </tbody>
	</table>
</body>
</html>


