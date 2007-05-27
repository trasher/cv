<?php
class Niouzes{
	var $niouze;
	//var $lang;
	var $file;
	//var $datas_rep = "i18n/";
	
	function __construct($file){
		$this->file = $file;
		$this->niouze = simplexml_load_file($this->file);
		//$this->lang = $lang;
	}

	
	function GetResumedNews(){
		global $commons;
		$resumed_news = $this->niouze->log;

		echo "\t<div id=\"niouzes\">\n";
		echo "\t\t<ul title=\"".$commons->getValue("lastNewsToolTip")."\">\n";
		echo "\t\t\t<li class=\"titre\"><a href=\"changelog.php5\">".$commons->getValue("lastNews")."</a></li>\n";
		$count=0;
		foreach($resumed_news as $news_head){
			$count++;
			$toreplace= array("'", " ", "\"", "\\", "/", ".");
			$url = "changelog.php5#".str_replace($toreplace,"_",utf8_decode($news_head["detail"]));
			echo "\t\t\t<li class=\"entete\"><a href=\"".$url."\">".utf8_decode($news_head["date"])." (".utf8_decode($news_head["auteur"]).")</a></li>\n";
			echo "\t\t\t<li>".utf8_decode($news_head["detail"])."</li>\n";
			if($count>=5){break;}
		}
		echo "\t\t</ul>\n\t</div>\n";
	}
	
	function GetNews(){
		echo $this->NGValidate();

		$xsl = new DomDocument();
		$xsl->load("templates/changelog.xsl");
		$inputdom = new DomDocument();
		$inputdom->load($this->file);
			
		$proc = new XsltProcessor();
		$xsl = $proc->importStylesheet($xsl);
		print $proc->transformToXml($inputdom);
	}
	
	function NGValidate(){
		global $commons;
		$ng = new domDocument;
		$ng->load($this->file);
		if (!$ng->relaxNGValidate('templates/changelog.rng')) {
			return $commons->GetValue("ngvalidate");
		}
	}
}
?>