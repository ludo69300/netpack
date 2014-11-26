<!doctype html>
<!-- Généré par Outils de développement. Il est possible que ce ne soit pas une représentation précise du fichier source original -->
<HTML lang="fr">
	<HEAD>
		<meta charset="utf-8">
	</HEAD>
	<BODY>
	<?php 	
	$x=10;
	$y=20;
	$z=30;
	$caractere_piece = array($x, $y, $z, 31, 29, 23, true, true, true);

for ($i = 0; $i <= 2; $i++) 
			{	
				$temp = $caractere_piece[$i%3];
				$caractere_piece[$i%3]=$caractere_piece[($i+1)%3];
				$caractere_piece[($i+1)%3]=$caractere_piece[($i+2)%3];
				$caractere_piece[($i+2)%3] = $temp;
				echo $i%3 .'-'.$caractere_piece[$i%3].'</br>';
				echo ($i+1)%3 .'-'. $caractere_piece[($i+1)%3].'</br>';
				echo ($i+2)%3 .'-'. $caractere_piece[($i+2)%3].'</br></br>';

				echo 'complement :'.$nb_maxi=max($caractere_piece).'</br>';

			}
	?>
 	</BODY>
</HTML>  