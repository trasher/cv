<?php
class Headers{
	private $file;
	
	function __construct($file){
		//TODO gestion de l'erreur si le fichier spécifié n'existe pas
		$this->file = $file;
	}
		
	function loadPageHeaders(){
		$keys = array();
		if(file_exists($this->file) && is_file($this->file)){
			$fichier = simplexml_load_file($this->file);
			$keys = $this->getCommons($fichier);
		}
		global $commons, $pageName;
		$keys["title"] = $commons->GetHeaders($pageName[0], 'titre');
		if(trim($keys["title"])=="") $keys["title"] = $commons->GetHeaders('index', 'titre');
		$keys["keywords"] = $commons->GetHeaders($pageName[0], 'keywords');
		if(trim($keys["keywords"])=="") $keys["keywords"] = $commons->GetHeaders('index', 'keywords');
		$keys["description"] = $commons->GetHeaders($pageName[0], 'description');
		if(trim($keys["description"])=="") $keys["description"] = $commons->GetHeaders('index', 'description');
		$keys["author"] = "Johan Cwiklinski";
		return $keys;
	}

	function loadArticleHeaders(){
		$fichier = simplexml_load_file($this->file);
		$keys = array();
		$keys = $this->getCommons($fichier);
		$keys["title"] = utf8_decode($fichier["titre"]);
		$keys["keywords"] = utf8_decode($fichier["keywords"]);
		$keys["description"] = utf8_decode($fichier["descriptif"]);
		$keys["author"] = utf8_decode($fichier["auteur"]);
		return $keys;
	}

	private function getCommons($xml){
		$array = array();
		$array["DCdate"] = $xml["DCdate"];
		if($xml["DCdateModif"])
			$array["DCdateModif"] = $xml["DCdateModif"];
		if($xml["DCsubject"])
			$array["DCsubject"] = utf8_decode($xml["DCsubject"]);
		return $array;
	}
}
?>