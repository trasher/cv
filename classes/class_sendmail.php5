<?php
class SendMail{
	var $lang;
	
	function __construct($language){
		$this->lang = $language;
	}
	
	function mail_attachement($to,$sujet,$message,$typemime,$nom,$reply,$from, $typeContrib){
		if($typemime!="" && $nom!=""){
			$limite = "_parties_".md5(uniqid (rand()));
			
			$mail_mime  = "Date: ".date("l j F Y, G:i")."\n";
			$mail_mime .= "MIME-Version: 1.0\n";
			$mail_mime .= "Content-Type: multipart/mixed;\n";
			$mail_mime .= "   boundary=\"----=$limite\"\n\n";
			
			//Le message en texte simple pour les navigateurs qui n'acceptent pas le HTML
			$texte = "This is a multi-part message in MIME format.\n";
			$texte .= "Ceci est un message est au format MIME.\n";
			$texte .= "------=$limite\n";
			$texte .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
			$texte .= "Content-Transfer-Encoding: 7bit\n\n";
			$texte .= "Type(s) de contribution:";
			for($i=0;$i<count($typeContrib);$i++){
				$texte .= $typeContrib[$i]."---";
			}
			$texte .= "\n\nMessage:\n";
			$texte .= $message;
			$texte .= "\n\n";
			
			//le fichier
			$attachement  = "------=$limite\n";
			$attachement .= "Content-Type:\"$typemime\"; name=\"$nom\"\n";
			if($typemime=="text/plain"){
				$attachement .= "Content-Transfer-Encoding: utf8\n";
			}else{
				$attachement .= "Content-Transfer-Encoding: base64\n";
	
			}
			$attachement .= "Content-Disposition: attachment; filename=\"$nom\"\n\n";
			$fd = fopen( "../upload/".$nom, "r" );
			$contenu = fread( $fd, filesize( "../upload/".$nom ) );
			fclose( $fd );
			if($typemime=="text/plain"){
				$attachement .=  utf8_encode($contenu);
			}else{
				$attachement .=  chunk_split(base64_encode($contenu));
			}
		$attachement .= "\n\n\n------=$limite\n";
		return mail($to, $sujet, $texte.$attachement, "Reply-to: $reply\nFrom: $from\n".$mail_mime);
		}else{
			return mail($to, $sujet, $message,"From:$from".$mail_mime);
		}
	}
	
	function UploadAttachment($repertoireDestination){
		if($_FILES["userfile"]["tmp_name"]!=""){
			if (is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
				if (rename($_FILES["userfile"]["tmp_name"], $repertoireDestination."/".$_FILES["userfile"]["name"])) {
					
				} else {
					echo "<h1 class=\"error\">Problème de transfert !</h1>";
				}          
			} else {
				echo "<h1 class=\"error\">Erreur : Le fichier n'a pas été uploadé</h1>";
			}
		}	
	}
	
	function Recapitule($from){
		require_once('class_commons.php5');
		$commons = new Commons($this->lang);
		echo "<table align=\"center\">";
		echo "<tr>";
		echo "<th colspan=\"2\" align=\"center\">".$commons->GetValue("recap")."</td>";
		echo "</tr>\n";
		echo "<tr>";
		echo "<th width=\"250px\">".$commons->GetValue("expediteur")."</th>";
		echo "<td>".$_POST["mailexp"]."</td>";
		echo "</tr>\n";
		echo "<tr>";
		echo "<th>".$commons->GetValue("destinataire")."</th>";
		echo "<td>".$_POST["dest"]."".$_POST["maildest"]."</td>";
		echo "</tr>\n";
		if($from=="contribution"){
			echo "<tr>";
			echo "<th>".$commons->GetValue("contribution")."</th>";
			echo "<td>";
			for($i=0;$i<count($_POST["contribution"]);$i++){
				echo $_POST["contribution"][$i]."---";
			}
			echo "</td>";
			echo "</tr>\n";
		}
		echo "<th>".$commons->GetValue("objet")."</th>";
		echo "<td>".$_POST["objet"]."</td>";
		echo "</tr>\n";
		echo "<tr>";
		echo "<th>".$commons->GetValue("message")."</th>";
		echo "<td>".$_POST["message"]."</td>";
		echo "</tr>\n";
		if($from=="contribution" && $_FILES["userfile"]["name"]!=""){
			echo "<tr>\n";
			echo "<th>".$commons->GetValue("fichierJoint")."</th>";
			echo "<td>".$_FILES["userfile"]["name"]."</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}
?>