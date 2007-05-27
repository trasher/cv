<?php
class Commons{
	var $commons;
	var $lang;
	var $file;
	var $datas_rep = "i18n/";
	
	function __construct($language){
		$this->lang = $language;
		$this->file = $this->datas_rep.$this->lang."/commun.xml";
		$this->commons = simplexml_load_file($this->file);
	}
	
	function GetHeaders($page, $elt){
		$header = $this->commons->xpath("//page[@ref='".$page."']");
		if(trim(utf8_decode($header[0]->$elt))!=""){
			return utf8_decode($header[0]->$elt);
		}else { return false;}
	}
	
	function GetValue($ref){
		return utf8_decode($this->commons->$ref);
	}
	
	function GetCategory($cat){
		$category = $this->commons->xpath("//categorie[@titre='".$cat."']");
		return utf8_decode($category[0]);
	}
	
	function GetError($err){
		$error = $this->commons->xpath("//error[@titre='".$err."']");
		return utf8_decode($error[0]);
	}
}
?>