<?php
class Menu{
	var $menu;
	var $lang;
	var $file;
	var $menucount;
	var $datas_rep = "i18n/";
	
	function __construct($language){
		$this->lang = $language;
		$this->file = $this->datas_rep.$this->lang."/menu.xml";
		$this->menu = simplexml_load_file($this->file);
	}

	function GetTopItems(){
		global $server_path;
		$menu_en_cours = $this->menu->xpath("//position[@id='top']/item");
		echo "\t<div id=\"menu\">\n";
		echo "\t\t<ul class=\"level1\">\n";
		foreach($menu_en_cours as $menuitem){
			echo "\t\t\t<li";
			if($menuitem["withsubs"]=="yes") echo " class=\"submenu\"";
			echo ">";
			echo "<a href=\"".$server_path.$menuitem["url"]."\" title=\"".utf8_decode($menuitem["title"])."\"";
			if(isset($menuitem["accesskey"])) echo " accesskey=\"".$menuitem["accesskey"]."\"";
			echo ">";
			echo utf8_decode($menuitem["nom"]);
			//echo " <span class=\"descript\">".utf8_decode($menuitem["title"])."</span>\n";
			echo "</a>";
			$this->SubMenus($menuitem);
			echo "</li>\n";
		}
		echo "\t\t</ul>\n";
		echo "\t</div>\n";
	}

	function SubMenus($parent_menu){
		global $server_path;
		$varDir = dir($this->datas_rep);
		while($dir=$varDir->read()){
			if($dir==$parent_menu["ident"]){
				$varSubDir = dir($this->datas_rep.$dir);
				echo "\n\t\t\t\t<ul class=\"level2\">\n";
				while($subDir=$varSubDir->read()){
					if($subDir!="." && $subDir!=".."){
						$cats = new Commons($this->lang);
						//conformité WCAG
						$title = utf8_decode($parent_menu["nom"]." ".utf8_encode($cats->GetCategory($subDir)));
						$href = $parent_menu["url"]."#".str_replace(" ", "_", $cats->GetCategory($subDir));
						echo "\t\t\t\t\t<li class=\"submenu\"><a href=\"".$server_path.$href."\" title=\"$title\">";
						echo $cats->GetCategory($subDir);
						echo "</a>";
						$this->GetFiles($dir, $subDir, $server_path.$parent_menu["url"]);
						echo "\t\t\t\t\t</li>\n";
					}
				}
				echo "\t\t\t\t</ul>\n\t\t\t";
			}
		}
		$varDir->close();
		if(file_exists($this->datas_rep.$this->lang."/".$parent_menu["ident"].".xml")){
			$cats = new MonoFile($this->lang);
			$cats->GetCategories($parent_menu);
		}elseif(file_exists($this->datas_rep."fr_FR/".$parent_menu["ident"].".xml")){
			$cats = new MonoFile($this->lang);
			$cats->GetCategories($parent_menu);
		}

	}
	
	private function GetFiles($ref_dir, $ref_sub_dir, $par_url){
		$varFile = dir($this->datas_rep.$ref_dir."/".$ref_sub_dir);
		echo "\n\t\t\t\t\t\t<ul class=\"level3\">\n";
		while($cur_file=$varFile->read()){
			if($cur_file!='.' && $cur_file!='..' && !is_dir($cur_file)){
				$arrayTmp=explode(".",$cur_file); //si pas de langue, suppression du .xml
				$arrayLangs=explode("-",$arrayTmp[0]); //une seule ligne si plusieurs langues
				if($arrayLangs[1]==$this->lang){
					$this->GetXmlFileDescr($this->datas_rep.$ref_dir."/".$ref_sub_dir."/".$cur_file, $ref_sub_dir, $arrayLangs[0], $par_url, true);
				}
				if(!file_exists($this->datas_rep.$ref_dir."/".$ref_sub_dir."/".$arrayLangs[0]."-".$this->lang.".xml")){
					//dispo seulement en fr
					$this->GetXmlFileDescr($this->datas_rep.$ref_dir."/".$ref_sub_dir."/".$cur_file, $ref_sub_dir, $arrayLangs[0], $par_url, false);
				}
			}
		}
		echo "\t\t\t\t\t\t</ul>\n";
		$varFile->close();
	}
	
	function GetXmlFileDescr($xml_file, $category, $artId, $par_url, $traduced){
		$xsl = new DomDocument();
		$xsl->load("templates/files-menu.xsl");
		$inputdom = new DomDocument();
		$inputdom->load($xml_file);
		
		$proc = new XsltProcessor();
		
		$proc->setParameter('', 'url', $par_url);
		$proc->setParameter('', 'category', $category);
		$proc->setParameter('', 'artId', $artId);
		if(!$traduced){$tvalue="(fr)";}else{$tvalue="";}
		$proc->setParameter('', 'traduced', $tvalue);
		$xsl = $proc->importStylesheet($xsl);
		echo "\t\t\t\t\t\t\t";
		print $proc->transformToXml($inputdom);
	}
	
	function GetBottomMenu(){
		$bottom_menu = $this->menu->xpath("//position[@id='bottom']/item");
		echo "\t\t<ul>\n";
		foreach($bottom_menu as $b_menu){
			echo "\t\t\t<li>";
			echo "<a href=\"".$b_menu["url"]."\" title=\"".utf8_decode($b_menu["title"])."\" id=\"".$b_menu["ident"]."\"";
			if(isset($b_menu["accesskey"])) echo " accesskey=\"".$b_menu["accesskey"]."\"";
			echo ">";
			echo utf8_decode($b_menu["nom"])."</a>";
			echo "</li>\n";
		}
		echo "\t\t</ul>\n";
	}
}
?>