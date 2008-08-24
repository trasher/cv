<?php
class Articles{
	var $cats;
	var $lang;
	var $file;
	var $datas_rep = "i18n/";
	var $dest;
	
	function __construct($dest, $language){
		$this->lang = $language;
		$this->dest = $dest;
		$this->directory = $this->datas_rep.$this->dest."/";
	}
		
	function GetHeaders(){
		$varDir = dir($this->directory);
		$this->cats = new Commons($this->lang);
		echo "<div class=\"center\"><img src=\"images/".$this->dest.".png\" width=\"48\" height=\"48\" alt=\"logo_category\" />";
		echo "<h1 class=\"first_title\">";
		echo $this->cats->GetValue($this->dest);
		echo "</h1></div>";
		while($dir=$varDir->read()){
			if($dir!="." && $dir!=".."){
				echo "<h1 id=\"".str_replace(" ", "_", $this->cats->GetCategory($dir))."\"><a class=\"toplink hidden\" href=\"#hautpage\">".utf8_decode($this->cats->GetValue("back2top"))."</a>&nbsp;".$this->cats->GetCategory($dir)."</h1>\n";
				echo "<table width=\"100%\" summary=\"".$this->cats->GetValue($this->dest)." ".$this->cats->GetCategory($dir)."\">\n";
				$this->GetFiles($dir);
				echo "</table>\n";
			}
		}
	}

	function buildPlan(){
		$varDir = dir($this->directory);
		$this->cats = new Commons($this->lang);
		echo "<ul>\n";
		while($dir=$varDir->read()){
			if($dir!="." && $dir!=".."){
				echo "<li>\n";
				echo $this->cats->GetCategory($dir);
				$varSubDir = dir($this->directory.$dir);
				echo "<ul>\n";
				while($subdir=$varSubDir->read()){
					if($subdir!="." && $subdir!=".." && $subdir!="images"){
						$proceed=false;
						$filelang=explode('.xml',$subdir);
						$filelang=explode('-',$filelang[0]);
						
						$path = $this->directory.$dir."/".$subdir;
						if(isset($filelang[1]) && $filelang[1]==$this->lang){
							$proceed=true;
							$language = "";
						}elseif(isset($filelang[1]) && !$proceed && $filelang[1]=="fr_FR"){
							if(!file_exists($this->directory.$dir."/".$filelang[0]."-".$this->lang.".xml")){
								$path = $this->directory.$rep."/".$filelang[0]."-fr_FR.xml";
								$proceed = true;
								$language = " (fr)";
							}
						}

						if($proceed){
							$file = simplexml_load_file($this->directory.$dir."/".$subdir);
							$title = $file->xpath("//page");
							$path = $this->dest.".php?cat=".$dir."&amp;art=".$filelang[0];
							echo "<li><a href=\"$path\">".utf8_decode($title[0]["titre"]).$language."</a></li>";
						}
					}
				}
				echo "</ul>\n</li>";
			}
		}
		echo "</ul>\n";
	}
	
	function GetFiles($rep){
		$varDir = dir($this->directory.$rep);
		//echo "<table width=\"100%\" summary=\"".$this->cats->GetValue($this->dest)." ".$this->cats->GetCategory($rep)."\">\n";
		while($dir=$varDir->read()){
			if($dir!="." && $dir!=".." && $dir!="images"){
				$proceed=false;
				$filelang=explode('.xml',$dir);
				$filelang=explode('-',$filelang[0]);
				
				$path = $this->directory.$rep."/".$dir;
				if(isset($filelang[1]) && $filelang[1]==$this->lang){
					$proceed=true;
					$language = "";
				}elseif(isset($filelang[1]) && !$proceed && $filelang[1]=="fr_FR"){
					if(!file_exists($this->directory.$rep."/".$filelang[0]."-".$this->lang.".xml")){
						$path = $this->directory.$rep."/".$filelang[0]."-fr_FR.xml";
						$proceed = true;
						$language = "(fr)";
					}
				}
				if($proceed){
					$xsl = new DomDocument();
					$xsl->load("templates/articles-headers.xsl");
					$inputdom = new DomDocument();
					$inputdom->load($path);
					$proc = new XsltProcessor();
					$xsl = $proc->importStylesheet($xsl);
					$proc->setParameter('', 'langs', $this->getLangs($rep, $filelang[0]));
					$proc->setParameter('', 'category', $rep);
					$proc->setParameter('', 'catname', utf8_encode($this->cats->GetValue('categories')." ".$this->cats->GetCategory($rep)));
					$proc->setParameter('', 'dest', $this->dest);
					$proc->setParameter('', 'art', $filelang[0]);
					$proc->setParameter('', 'language', $language);
					$proc->registerPhpFunctions();
					print $proc->transformToXml($inputdom);
				}
			}
		}
		//echo "</table>\n";
	}
	
	function getLangs($directory, $idArticle){
		$langs="";
		$varFileLang = dir($this->directory."/".$directory);
		while($file=$varFileLang->read()){
			if($file!="." && $file!=".."){
				$filename = explode('.xml', $file);
				$lang = explode('-', $filename[0]);
				if($lang[0]==$idArticle){
					$langues[] = $lang[1];
				}
			}
		}
		for($i=0;$i<count($langues)-1;$i++){
			$langs.=$langues[$i].",";
		}
		$langs.=$langues[count($langues)-1];
		return $langs;
	}
	
	
	/*function GetPageHeaders($cat, $art){
		$filepath = $this->directory.$cat."/".$art.'-'.$this->lang.".xml";
		if(!file_exists($filepath)){
			//traduction non disponible
			$filepath=$this->directory.$cat."/".$art."-fr_FR.xml";
		}
		$file = simplexml_load_file($filepath);
		$keys["title"] = utf8_decode($file["titre"]);
		$keys["keywords"] = utf8_decode($file["keywords"]);
		$keys["description"] = utf8_decode($file["descriptif"]);
		$keys["author"] = utf8_decode($file["auteur"]);
		$keys["DCdate"] = $file["DCdate"];
		$keys["DCdateModif"] = $file["DCdateModif"];
		$keys["DCsubject"] = $file["DCsubject"];
		return $keys;
	}*/
}
?>
