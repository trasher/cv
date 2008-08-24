<?php
class HttpErrors{
	var $err_codes;
	var $lang;
	var $file;
	var $datas_rep = "i18n/";
	
	function __construct($language){
		$this->lang = $language;
		$this->file = $this->datas_rep.$this->lang."/http-codes.xml";
		$this->err_codes = simplexml_load_file($this->file);
	}
	
	function GetHeaders($code){
		$error = $this->err_codes->xpath("//code[@number='".$code."']");
		return "X-TnD - ".$code." - ".ucwords(utf8_decode($error[0]["title"]));
	}
	
	function GetError($code){
		$error = $this->err_codes->xpath("//code[@number='".$code."']");
		$parent = $this->err_codes->xpath("//code[@number='".$code."']/ancestor::*");
		echo $parent[0]["type"]["name"];
		$common = new Commons($_SESSION["xtnd"]["lang"]);
		echo "<h1 class=\"first_title\">".ucwords(utf8_decode($error[0]["title"]))."<br/>".utf8_decode($error[0])."</h1>\n";
		echo "<p class=\"center\"><a href=\"http://www.x-tnd.be\"><img alt=\"X-TnD Logo\" src=\"http://www.x-tnd.be/templates/original/logo.png\" /></a></p>\n";
		echo "<p class=\"center\">".$common->GetError("https")."</p>\n";
	}
}
?>