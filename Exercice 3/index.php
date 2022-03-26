<?php
class Sondage
{
	public $Question = "" ;
	public $Choix = array() ;
	public $DejaVote = false;
	public $ListeIp = array() ;
	public $Resultats = array() ;

	public $FichierIp ;
	public $FichierResultats ;

	public function __construct ( $Question, $Choix, $FichierIp, $FichierResultats )
	{
		$this->Question = $Question ; 
		$this->Choix = $Choix ;
		$this->FichierIp = $FichierIp ;
		$this->FichierResultats = $FichierResultats ;
		
		if(!file_exists($FichierIp) OR !file_exists($FichierResultats)) {
			touch($FichierIp) ;
			touch($FichierResultats) ;
		}
		
		if(isset($_COOKIE['vote']) OR $this->VerifierIp($_SERVER["REMOTE_ADDR"]) === false) {
			$this->DejaVote = true ;
		}
	}
	
	private function VerifierIp ($ip)
	{
		$this->ListeIp = explode(";", file_get_contents($this->FichierIp, NULL, NULL, 16));
		if(!empty($this->ListeIp) AND in_array($ip, $this->ListeIp)){
			return false ;
		}
		return true ;
	}
	public function AjoutVote ($NumVote)
	{
		if($this->DejaVote === true) {
			return false ;
		}
		$this->Resultats = explode(";", file_get_contents($this->FichierResultats, NULL, NULL, 16));
		if(!isset($this->Resultats[0]{0})) {
			$Nb = sizeof($this->Choix) ;
			for($n=0;$n<$Nb;$n++) $this->Resultats[$n] = '0';
		}
		$this->Resultats[$NumVote]++ ;
		
		$this->ListeIp[] = $_SERVER["REMOTE_ADDR"] ;
		
		if( !file_put_contents($this->FichierResultats, '<?php exit(); ?>'.implode(";", $this->Resultats) ) 
		OR !file_put_contents($this->FichierIp, '<?php exit(); ?>'.implode(";", $this->ListeIp) ) ) {
			return false ;
		}
		$this->DejaVote = true ;
		setcookie('vote', true, time()+2*60) ; 
		return true;
	}
	public function AfficherVote ()
	{
		
		echo '<div style="border:1px solid black;padding:0 10px;width:450px;"><p<b>'.$this->Question.'</b></p><table style="margin-left:10px;">' ;
		if($this->DejaVote === true) {
            if(isset($_POST['choix'])) {
                $vote=$this->Choix[intval($_POST['choix'])];
            }else if(isset($_COOKIE['vote'])){
                $vote=$this->Choix[intval($_COOKIE['vote'])];
            } 
            if(isset($vote)){
			echo "<p>Vous avez déjà voté! Votre vote est: ".$vote."</p>";}
            else {
                echo '<p>Vous avez votez mais suite à l\'expiration de la cookie, on ne peut plus vous afficher votre réponse.</a></p>';
            }
		}
		else {
			echo '<p><a href="#form" >Retourner au formulaire de vote</a></p>';
		}
		echo '</div>' ;
	}
	public function AfficherFormulaire ()
	{
		echo '<div style="border:1px solid black;padding:0 10px;width:450px;"><form method="post" id="form"><p><b>'.$this->Question.'</b></p><table style="margin-left:10px;">' ;
		$disable = '';
		if($this->DejaVote === true) {
			$disable = ' disabled="disabled"';
			echo '<p>Vous avez déjà voté !</p>' ;
		}
		foreach($this->Choix as $id=>$choix)
		{
			echo '<tr><td><input type="radio" name="choix" value="'.$id.'"'.$disable.' /></td><td><u>'.$choix.'</u></td></tr>' ;
		}
		echo '</table><p><input type="submit" value="Votez!" '.$disable.'/></p></form></div><script>
        if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>' ;	
	}

}

$sondage = new Sondage('Comment vous trouvez le cours du PHP ?', array('Bon', 'Moyen', 'Mauvais'), 'ip.php', 'resultats.php') ;

if( isset($_POST['choix']) ){
$sondage->AjoutVote(intval($_POST['choix']));

}
echo '<html><body>' ;
if($sondage->DejaVote === true OR isset($_GET['resultats'])){
    $sondage->AfficherVote() ;
}
else {
$sondage->AfficherFormulaire();
}