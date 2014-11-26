<?php 
include_once('../modele/connexion_sql.php');
include_once('../modele/pack.class.php');

$gamme=(string)htmlspecialchars($_POST['gamme']);
$long_piece=(int)htmlspecialchars($_POST['long_piece']);
$larg_piece=(int)htmlspecialchars($_POST['larg_piece']); 
$haut_piece=(int)htmlspecialchars($_POST['haut_piece']); 
$poids_piece=(float)htmlspecialchars($_POST['poids_piece']); 
$uv=(int)htmlspecialchars($_POST['uv']);
if (isset($_POST['check_antiUV']))  {
	$check_antiUV=1;
} else {
	$check_antiUV=0;
}
if (isset($_POST['check_protCor'])){
	$check_protCor=1;
} else {
	$check_protCor=0;
}
if (isset($_POST['check_fragile'])){
	$check_fragile=1;
} else {
	$check_fragile=0;
}
if (isset($_POST['SVLong'])){
	$svLong=1;
} else {
	$svLong=0;
}
if (isset($_POST['SVLarg'])){
	$svLarg=1;
} else {
	$svLarg=0;
}
if (isset($_POST['SVHaut'])){
	$svHaut=1;
} else {
	$svHaut=0;
}
// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
if (isset($_FILES['monfichier']) AND $_FILES['monfichier']['error'] == 0)
{
        // Testons si le fichier n'est pas trop gros
        if ($_FILES['monfichier']['size'] <= 1000000)
        {
                // Testons si l'extension est autorisée
                $infosfichier = pathinfo($_FILES['monfichier']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees))
                {
                        // On peut valider le fichier et le stocker définitivement
                        move_uploaded_file($_FILES['monfichier']['tmp_name'], 'uploads/' . basename($_FILES['monfichier']['name']));
                        echo "L'envoi a bien été effectué !";
                }
        }
}
$pack = new Pack($_POST['gamme'], $_POST['long_piece'], $_POST['larg_piece'], $_POST['haut_piece'], $_POST['poids_piece'],$_POST['uv'], $check_antiUV, $check_protCor, $check_fragile, $svLong, $svLarg, $svHaut);
echo 'gamme :' . $pack->support . '</br>';

echo "gamme : $gamme<br>";
echo "Longeure : $long_piece<br>";
echo "Largeure : $larg_piece<br>";
echo "Hauteur : $haut_piece<br>";
echo "Poids : $poids_piece<br>";
echo "Anti UV : $check_antiUV<br>";
echo "Corosion : $check_protCor<br>";
echo "Fragile : $check_fragile<br>";
echo 'SVLong :' . $svLong . '<br>';
echo 'SVLarg :' . $svLarg . '<br>';
echo 'SVHaut :' . $svHaut . '<br>';



