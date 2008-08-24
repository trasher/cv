<?php
class Languages{
 	var $langs;
	var $file;
	var $datas_rep = "i18n/";
	var $language;
	
	function __construct(){
		ini_set("session.use_trans_sid", "0");
		session_start();
		
		$this->file = $this->datas_rep."/traductions.xml";
		$this->langs = simplexml_load_file($this->file);
		if(!isset($_SESSION["xtnd"]["lang"]) || trim($_SESSION["xtnd"]["lang"])==""){
			$_SESSION["xtnd"]["lang"]='fr_FR';
			$this->language = 'fr_FR';
		}else{
			if(isset($_GET["lang"])){
				$current = $this->langs->xpath("//lang[@id='".$_GET["lang"]."']");
				if(isset($current[0])){$change=$_GET["lang"];}else{$change='fr_FR';}
				$_SESSION["xtnd"]["lang"]=$change;
			}
			$this->language = $_SESSION["xtnd"]["lang"];
		}
	}

	function GetFlags(){
		$common = new Commons($_SESSION["xtnd"]["lang"]);
		echo "\t<div id=\"drapos\">\n\t\t<ul>\n";
		$gets = getsWithoutLang();
		foreach($this->langs->lang as $lang){
			$href = $_SERVER["PHP_SELF"]."?lang=".$lang["id"];
			if($gets) $href.= "&amp;".$gets;
			echo "\t\t\t<li><a class=\"hidden ".$lang["id"]."\" href=\"$href\" title=\"".utf8_decode($lang->longname)."\">";
			echo utf8_decode($lang->longname);
			echo "</a></li>\n";
		}
		echo "\t\t</ul>\n\t</div>";
	}

	function GetLanguages(){
		foreach($this->langs->lang as $lang){
			$langs .= $lang["short"];
			if($lang->position()!=last()){
				$langs.=", ";
			}
		}
		return $langs;
	}

	function GetShortName(){
		$shortname = $this->langs->xpath("//shortname[../@id='".$this->language."']");
		return $shortname[0];
	}
}
?>