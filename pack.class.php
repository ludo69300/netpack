<?php
include_once('modele/connexion_sql.php');
class pack
{
	protected $long;
	protected $larg;
	protected $haut;
	protected $poids;
	protected $qtmax;
	public function __construct($gamme, $long_piece, $larg_piece, $haut_piece, $poids_piece, $uv, $antiUV, $protCor, $fragile)
	{
		global $bdd;
		$req = $bdd->prepare('SELECT RT FROM support_pack WHERE CODE_GAMME = :CODE_GAMME AND LONG_UTIL >= :LONG_UTIL AND LARG_UTIL >= :LARG_UTIL AND HAUT_UTIL >= :HAUT_UTIL ORDER BY LONG_UTIL*LARG_UTIL*HAUT_UTIL LIMIT 0, 1');
		$req->execute(array('CODE_GAMME' => $gamme,'LONG_UTIL' => $long_piece,'LARG_UTIL' => $larg_piece,'HAUT_UTIL' => $haut_piece));
		echo '<ul>';
		while ($donnees = $req->fetch())
		{
			echo '<li>' . $donnees['RT'] . '</li>';
		}
		echo '</ul>';
		$req->closeCursor();
		$req = $bdd->prepare('SELECT CODE_SUPPORT, LONG_UTIL, LARG_UTIL FROM support_pack WHERE CODE_GAMME = :CODE_GAMME ORDER BY LONG_UTIL*LARG_UTIL');
		$req->execute(array('CODE_GAMME' => $gamme));
		echo '<ul>';
		$test=true;
		while ($donnees = $req->fetch() AND $test)
		{
		$nombre_de_lignes = 1;
		while ($nombre_de_lignes <= $uv)
		{
			$nbpiece = $nombre_de_lignes * (int)($donnees['LONG_UTIL'] / ($long_piece + ($haut_piece*$nombre_de_lignes)))*(int)($donnees['LARG_UTIL'] / ($larg_piece + ($haut_piece*$nombre_de_lignes)));
			$nbpiecelarg= $nombre_de_lignes * (int)($donnees['LARG_UTIL'] / ($long_piece + ($haut_piece * $nombre_de_lignes))) * (int)($donnees['LONG_UTIL'] / ($larg_piece + ($haut_piece * $nombre_de_lignes)));
			if ($nbpiece>=$uv OR $nbpiecelarg>=$uv)
			{
				$test=false;
				echo '<li>' . $nombre_de_lignes . ' '  . $donnees['CODE_SUPPORT'] .' nbpièce1 :'. $nbpiece .'  nbpièce2 :'. $nbpiecelarg . '</li>';
			}
			$nombre_de_lignes++;
		}
		}
		echo '</ul>';
		$req->closeCursor();
	}
	public function emballage($dim_piece)
	{
		function rangement($caractere_piece)
		{
			
			$nb_long=(int)($caractere_piece[3]/$caractere_piece[0]);
			$nb_larg=(int)($caractere_piece[4]/$caractere_piece[1]);
			$nb_haut=(int)($caractere_piece[5]/$caractere_piece[2]);
			$nbpieces=$nb_long*$nb_larg*$nb_haut;
			$rest_long=(int)($caractere_piece[3]-($caractere_piece[0]*$nb_long)); $rest_long = ($rest_long > 0) ? $rest_long : 0;			
			$rest_larg=(int)($caractere_piece[4]-($caractere_piece[1]*$nb_larg)); $rest_larg = ($rest_larg > 0) ? $rest_larg : 0;			
			$rest_haut=(int)($caractere_piece[5]-($caractere_piece[2]*$nb_haut)); $rest_haut = ($rest_haut > 0) ? $rest_haut : 0;
			
			//echo $nb_long.'x'.$nb_larg.'x'.$nb_haut.'='.$nbpieces.'</br>';
			if($nbpieces > 0)
			{
				for ($i = 0; $i <= 2; $i++) 
				{
					
					$temp = $caractere_piece[$i%3];
					$caractere_piece[$i%3]=$caractere_piece[($i+1)%3];
					$caractere_piece[($i+1)%3]=$caractere_piece[($i+2)%3];
					$caractere_piece[($i+2)%3]=$temp;

						//On appelle notre fonction
					$nb_complement[$i][0]=rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $rest_long, $caractere_piece[4], $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][0]+=rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][0]+=rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $caractere_piece[3]-$rest_long, $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					
								
					$nb_complement[$i][1]=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4], $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][1]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][1]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
								
					
					$nb_complement[$i][2]=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][2]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][2]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
								
					$nb_complement[$i][3]=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4], $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][3]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][3]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
										
					
					$nb_complement[$i][4]=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][4]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][4]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					
					$nb_complement[$i][5]=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][5]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
					$nb_complement[$i][5]+=rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				}
				for ($j = 0; $j < 6; $j++) 
				{
					for ($i = 0; $i <= 2; $i++) 
					{
						$nb_maxi[$i]=$nb_complement[$i][$j];
					}	
					$nb_maxi_cas[$j]=max($nb_maxi);
				}
				$nbpieces+=max($nb_maxi_cas);
				return $nbpieces;
			}
			else
			{
				return 0;
			}
		}
		for ($k = 0; $k <= 2; $k++) 
		{
		
			$temp = $dim_piece[$k%3];
			$dim_piece[$k%3]=$dim_piece[($k+1)%3];
			$dim_piece[($k+1)%3]=$dim_piece[($k+2)%3];
			$dim_piece[($k+2)%3]=$temp;
			$nb[$k]=rangement(array($dim_piece[0], $dim_piece[1], $dim_piece[2], $dim_piece[3], $dim_piece[4], $dim_piece[5], $dim_piece[6], $dim_piece[7], $dim_piece[8]));
		}
	return max($nb);
	}
}