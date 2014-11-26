<?php
class pack
{
	public $support;
	protected $long;
	protected $larg;
	protected $haut;
	protected $poids;
	protected $qtmax;
	public function __construct($gamme, $long_piece, $larg_piece, $haut_piece, $poids_piece, $uv, $antiUV, $protCor, $fragile, $svLong, $svLarg, $svHaut){
		global $bdd;
		$req = $bdd->prepare('SELECT CODE_SUPPORT, CODE_GAMME, CODIFICATION, LONG_UTIL, LARG_UTIL, HAUT_UTIL, LONG_ENCOMB, LARG_ENCOMB, HAUT_ENCOMB, POIDS, CHARGE_MAX FROM pack_support WHERE CODE_GAMME = :CODE_GAMME ORDER BY LONG_UTIL*LARG_UTIL');
		$req->execute(array('CODE_GAMME' => $gamme));
		echo '<ul>';
		$test=true;
		while ($donnees = $req->fetch() AND $test){
			if ( $gamme=='SAC' AND $gamme==$donnees['CODE_GAMME'] AND (($antiUV==1 AND substr($donnees['CODIFICATION'], -1)=='u') OR ($antiUV==0 AND substr($donnees['CODIFICATION'], -1)!='u'))){
				$nbpiece = $this->emballage(array($long_piece, $larg_piece, $haut_piece, $donnees['LONG_UTIL'], $donnees['LARG_UTIL'], $donnees['HAUT_UTIL'], $svLong, $svLarg, $svHaut, $uv, $donnees['CODIFICATION'])).'<br>';
				echo $donnees['CODE_SUPPORT'].':'.$nbpiece;
				if ( $nbpiece>=$uv ){
					$test=false;
					$this->support=$donnees['CODE_SUPPORT'];
					$this->long=$donnees['LONG_ENCOMB'];
					$this->larg=$donnees['LARG_ENCOMB'];
					$this->haut=$donnees['HAUT_ENCOMB'];
					$this->poids=$donnees['POIDS'];
					$this->qtmax=$nbpiece;
					echo '<li>' . $nbpiece .'<br>';
				}
			}
		}
		echo '</ul>';
		$req->closeCursor();
	}
	public function rangement($caractere_piece)
	{
		if($caractere_piece[8]==1)
		{
			$nb_long=(int)($caractere_piece[3]/$caractere_piece[0]);
			$nb_larg=(int)($caractere_piece[4]/$caractere_piece[1]);
			$nb_haut=(int)($caractere_piece[5]/$caractere_piece[2]);
			$nbpieces=$nb_long*$nb_larg*$nb_haut;
			$rest_long=(int)($caractere_piece[3]-($caractere_piece[0]*$nb_long)); $rest_long = ($rest_long > 0) ? $rest_long : 0;			
			$rest_larg=(int)($caractere_piece[4]-($caractere_piece[1]*$nb_larg)); $rest_larg = ($rest_larg > 0) ? $rest_larg : 0;			
			$rest_haut=(int)($caractere_piece[5]-($caractere_piece[2]*$nb_haut)); $rest_haut = ($rest_haut > 0) ? $rest_haut : 0;
		}else{
			$nbpieces=0;
		}
		if($nbpieces > 0){
			for ($i = 0; $i <= 2; $i++){
				
				$temp = $caractere_piece[$i%3];
				$caractere_piece[$i%3]=$caractere_piece[($i+1)%3];
				$caractere_piece[($i+1)%3]=$caractere_piece[($i+2)%3];
				$caractere_piece[($i+2)%3]=$temp;
				$temp2 = $caractere_piece[($i%3)+6];
				$caractere_piece[($i%3)+6]=$caractere_piece[(($i+1)%3)+6];
				$caractere_piece[(($i+1)%3)+6]=$caractere_piece[(($i+2)%3)+6];
				$caractere_piece[(($i+2)%3)+6]=$temp2;
				//echo $caractere_piece[0].$caractere_piece[1].$caractere_piece[2];
					//On appelle notre fonction
				$nb_complement[$i][0]=$this->rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $rest_long, $caractere_piece[4], $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][0]+=$this->rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][0]+=$this->rangement(array($caractere_piece[0], $caractere_piece[1], $caractere_piece[2], $caractere_piece[3]-$rest_long, $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				
							
				$nb_complement[$i][1]=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4], $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][1]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][1]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
							
				
				$nb_complement[$i][2]=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][2]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][2]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
							
				$nb_complement[$i][3]=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4], $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][3]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3]-$rest_long, $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][3]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
									
				
				$nb_complement[$i][4]=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][4]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][4]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4], $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				
				$nb_complement[$i][5]=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $rest_long, $caractere_piece[4]-$rest_larg, $caractere_piece[5]-$rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][5]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $rest_larg, $caractere_piece[5], $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
				$nb_complement[$i][5]+=$this->rangement(array($caractere_piece[2], $caractere_piece[1], $caractere_piece[0], $caractere_piece[3], $caractere_piece[4]-$rest_larg, $rest_haut, $caractere_piece[6], $caractere_piece[7], $caractere_piece[8]));
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
			//echo $nb_long.'x'.$nb_larg.'x'.$nb_haut.'='.$nbpieces.' '.$caractere_piece[8].'</br>';
		}
		return $nbpieces;
	}
	public function emballage($dim_piece){
		for ($k = 0; $k <= 2; $k++){
			$temp = $dim_piece[$k%3];
			$dim_piece[$k%3]=$dim_piece[($k+1)%3];
			$dim_piece[($k+1)%3]=$dim_piece[($k+2)%3];
			$dim_piece[($k+2)%3]=$temp;
			$temp2 = $dim_piece[($k%3)+6];
			$dim_piece[($k%3)+6]=$dim_piece[(($k+1)%3)+6];
			$dim_piece[(($k+1)%3)+6]=$dim_piece[(($k+2)%3)+6];
			$dim_piece[(($k+2)%3)+6]=$temp2;
			$nombre_de_lignes = 1;
			while ($nombre_de_lignes <= $dim_piece[9]){
				if (substr($dim_piece[10], 0, 1)=='s'){
					$LONG_UTIL = ($dim_piece[3] - ($dim_piece[2] * $nombre_de_lignes));
					$LARG_UTIL = ($dim_piece[4] - ($dim_piece[2] * $nombre_de_lignes));
					$HAUT_UTIL = $dim_piece[2] * $nombre_de_lignes;
				}else{
					$LONG_UTIL = $dim_piece[0];
					$LARG_UTIL = ($dim_piece[4] - ($dim_piece[2] * $nombre_de_lignes));
					$HAUT_UTIL = $dim_piece[2] * $nombre_de_lignes;						
				}
				if ($LONG_UTIL>0 AND $LARG_UTIL>0 AND $HAUT_UTIL>0){
					$nb[$k+$nombre_de_lignes]=$this->rangement(array($dim_piece[0], $dim_piece[1], $dim_piece[2], $LONG_UTIL, $LARG_UTIL, $HAUT_UTIL, $dim_piece[6], $dim_piece[7], $dim_piece[8]));
					//echo 'uv:'.$dim_piece[9].' nbpiece: '.max($nb).' nbl:'. $nombre_de_lignes . ':'.$dim_piece[3] .'x'. $dim_piece[4] .'x'. $dim_piece[5].'x'.$LONG_UTIL.'x'.$LARG_UTIL.'x'.$HAUT_UTIL.'</br>';
				}
				$nombre_de_lignes++;
			}
			
		}
	return max($nb);
	}
}