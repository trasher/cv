<?php
class Acronyms{
	var $file;
	var $datas_rep = "xml/";
	var $commons;
	
	function __construct(){
		$this->file = $this->datas_rep."/acronyms.xml";
		$this->commons = simplexml_load_file($this->file);
	}
	
	/*function GetHeaders($page, $elt){
		$header = $this->commons->xpath("//page[@ref='".$page."']");
		if(trim(utf8_decode($header[0]->$elt))!=""){
			return utf8_decode($header[0]->$elt);
		}else { return false;}
	}*/
	
	function get($ref){
		$value = $this->commons->xpath("//acronym[@name='".$ref."']");
		return utf8_decode($value[0]);
	}
	
	/*function GetCategory($cat){
		$category = $this->commons->xpath("//categorie[@titre='".$cat."']");
		return utf8_decode($category[0]);
	}
	
	function GetError($err){
		$error = $this->commons->xpath("//error[@titre='".$err."']");
		return utf8_decode($error[0]);
	}*/
}
?>
