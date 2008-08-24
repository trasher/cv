<?php
class MonoFile{
	var $monofile;
	var $lang;
	var $file;
	var $datas_rep = "i18n/";
	
	function __construct($language){
		$this->lang = $language;
	}
		
	function GetCategories($ref){
		global $server_path;
		$file = $ref["ident"].".xml";
		$this->file =  $this->datas_rep.$this->lang."/".$file;
		if(!file_exists($this->file)){
			//traduction non disponible
			$this->file=$this->datas_rep."fr_FR/".$file;
			$notraduc = true;
		}

		$this->monofile = simplexml_load_file($this->file);
		echo "\n\t\t\t\t<ul class=\"level2\">\n";
		foreach($this->monofile->cat as $cat){
			$nomId = utf8_decode(str_replace(" ", "_", $cat["nom"]));
			echo "\t\t\t\t\t<li><a href=\"".$server_path.$ref["url"]."#".$nomId."\">";
			echo utf8_decode($cat["nom"]);
			if($notraduc){echo " (fr)";}
			echo "</a></li>\n";
		}
		echo "\t\t\t\t</ul>\n\t\t\t";
	}

	function buildPlan($file){
		$this->file =  $this->datas_rep.$this->lang."/".$file;
		if(!file_exists($this->file)){
			//traduction non disponible
			$this->file=$this->datas_rep."fr_FR/".$file;
			$notraduc = true;
		}

		$this->monofile = simplexml_load_file($this->file);
		echo "<ul>\n";
		foreach($this->monofile->cat as $cat){
			$nomId = utf8_decode(str_replace(" ", "_", $cat["nom"]));
			echo "\t<li><a href=\"".$ref["url"]."#".$nomId."\">";
			echo utf8_decode($cat["nom"]);
			if($notraduc){echo " (fr)";}
			echo "</a></li>\n";
		}
		echo "</ul>";
	}
	
	function GetFile($file){
		global $commons;
		$this->file =  $this->datas_rep.$this->lang."/".$file;
		if(!file_exists($this->file)){
			//traduction non disponible
			echo "<p class=\"traduc\">".$commons->GetValue("traduction")."</p>";
			$this->file=$this->datas_rep."fr_FR/".$file;
		}
		$this->NGValidate($this->file);
		$xsl = new DomDocument();
		$xsl->load("templates/monofile.xsl");
		$inputdom = new DomDocument();
		$inputdom->load($this->file);
		
		$proc = new XsltProcessor();
		$xsl = $proc->importStylesheet($xsl);
		$param = explode('.xml', $file);
		$proc->setParameter('', 'category', $param[0]);
		$proc->setParameter('', 'top', $commons->GetValue("back2top"));
		print $proc->transformToXml($inputdom);
	}
	
	function NGValidate($file){
		global $commons;
		$ng = new domDocument;
		$ng->load($file);
		if (!$ng->relaxNGValidate('templates/monofile.rng')) {
			echo $commons->GetValue("ngvalidate");
		}
	}
}
?>