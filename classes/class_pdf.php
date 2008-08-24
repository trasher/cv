<?php
require('fpdf.php');
class ArticlePdf extends FPDF{
	var $widths;

	function FirstPage($heads, $url){
		$this->SetTitle(utf8_decode($heads["titre"]));
		$this->SetAuthor(utf8_decode($heads["auteur"]));
		$this->SetCreator('fpdf');
		$this->AddPage();
		$this->y = 100;
		$this->ScreenShot("templates/original/logo.png");
		$this->SetTextColor(255,153,0);
		$this->SetFont('gillb','',16);
		$this->Cell(0,10,utf8_decode($heads["titre"]),0,1,'C');
		$this->SetTextColor(0,0,0);
		$this->SetFont('gillb','',12);
		$this->Cell(0,10,"Par ".utf8_decode($heads["auteur"]).", ".utf8_decode($heads["date"]),0,1,'C');
		$this->SetFont('gillb','',10);
		$this->MultiCell(0,5,utf8_decode($heads["descriptif"]),0,'J');
		$this->SetFont('gill','',10);
		$this->Cell(0,5,utf8_decode("La version originale de cet article est disponible à l'adresse ").$url,0,1,'C',0,$url);
		$this->ln(50);
		$this->SetFont('courier','',8);
		$this->MultiCell(0,5,"Verbatim copying and distribution of this entire article is permitted in any medium, provided this notice is preserved.",0,'C');
		$this->MultiCell(0,5,utf8_decode("La reproduction exacte et la distribution intégrale de cet article est permise sur n'importe quel support d'archivage, pourvu que cette notice soit préservée."),0,'C');
	}

	function Footer(){
		$this->SetY(-15);
		$this->SetFont('gill','',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(0,10,utf8_decode('Réalisation X-TnD - 2006 - PHP5/XML/FPDF'),0,0,'C',0,"http://www.x-tnd.be");
	}

	function SetWidths($w){
		//Tableau des largeurs de colonnes
		$this->widths=$w;
	}

	function Row($data){
		//Calcule la hauteur de la ligne
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Effectue un saut de page si nécessaire
		$this->CheckPageBreak($h);
		//Dessine les cellules
		for($i=0;$i<count($data);$i++){
			$w=$this->widths[$i];
			//Sauve la position courante
			$x=$this->GetX();
			$y=$this->GetY();
			//Imprime le texte
			$this->MultiCell($w,5,$data[$i],0,'L');
			//Repositionne à droite
			$this->SetXY($x+$w,$y);
		}
		//Va à la ligne
		$this->Ln($h);
	}
	
	function SectionTitle($titre){
		$this->ln(7);
		$this->SetTextColor(255,153,0);
		$this->SetFont('gillb','',14);
		$this->SetFillColor(0,0,0);
		$this->SetDrawColor(0,0,0);
		$this->Cell(0,5,utf8_decode($titre),1,1,'J',1);
		$this->Cell(0,2,"",0,1);
		$this->SetFont('gill','',11);
	}
	
	function ChapterTitle($titre){
		$this->ln(5);
		$this->SetTextColor(255,153,0);
		$this->SetFont('gillb','',13);
		$this->SetFillColor(0,0,0);
		$this->SetDrawColor(0,0,0);
		$this->Cell(10,2,"",0,0);
		$this->Cell(0,5,utf8_decode($titre),1,1,'J',1);
		$this->Cell(0,2,"",0,1);
		$this->SetFont('gill','',11);
	}
	
	function ParaContent($content){
		$this->SetTextColor(0,0,0);
		$this->SetFont('gill','',11);
		$this->Write(5,utf8_decode($content));
	}

	function ListContent(){
		$this->SetTextColor(0,0,0);
		$this->Cell(20,5," - ",0,0, 'R');
	}

	function WriteLink($content, $url){
		$this->SetTextColor(50,0,255);
		$this->Write(5,utf8_decode($content),$url);
		$this->SetTextColor(0,0,0);
	}

	function Code($content){
		$this->SetFont('courier','',9);
		$this->SetFillColor(217,217,217);
		$this->SetDrawColor(255,153,0);
		//$this->ln();
		$this->MultiCell(0,5,utf8_decode($content),1,'L',1);
		$this->SetTextColor(0,0,0);
		$this->ln(2);
	}
	
	function TextBreak(){
		$this->ln();
	}
	
	function ScreenShot($path){
		list($width, $height, $type, $attr) = getimagesize($path);
		$w = $width/2.8346;
		$h = $height/2.8346;
		if($w>180){
			$h = $h * 180/$w;
			$w = 180;
		}
		$this->CheckPageBreak($h);
		$x = (210-$w)/2;
		$this->Image($path,$x,$this->GetY(),$w, $h);
		$this->y += $h;
	}
	
	function CheckPageBreak($h){
		//Si la hauteur h provoque un débordement, saut de page manuel
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt){
		//Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb){
			$c=$s[$i];
			if($c=="\n"){
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax){
				if($sep==-1){
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	var $outlines=array();
	var $OutlineRoot;
	
	function Bookmark($txt,$level=0,$y=0){
		if($y==-1)
			$y=$this->GetY();
		$this->outlines[]=array('t'=>utf8_decode($txt),'l'=>$level,'y'=>$y,'p'=>$this->PageNo());
	}
	
	function _putbookmarks(){
		$nb=count($this->outlines);
		if($nb==0)
			return;
		$lru=array();
		$level=0;
		foreach($this->outlines as $i=>$o){
			if($o['l']>0){
			$parent=$lru[$o['l']-1];
			//Set parent and last pointers
			$this->outlines[$i]['parent']=$parent;
			$this->outlines[$parent]['last']=$i;
			if($o['l']>$level){
				//Level increasing: set first pointer
				$this->outlines[$parent]['first']=$i;
			}
			}else
				$this->outlines[$i]['parent']=$nb;
			if($o['l']<=$level and $i>0){
				//Set prev and next pointers
				$prev=$lru[$o['l']];
				$this->outlines[$prev]['next']=$i;
				$this->outlines[$i]['prev']=$prev;
			}
			$lru[$o['l']]=$i;
			$level=$o['l'];
		}
		//Outline items
		$n=$this->n+1;
		foreach($this->outlines as $i=>$o){
			$this->_newobj();
			$this->_out('<</Title '.$this->_textstring($o['t']));
			$this->_out('/Parent '.($n+$o['parent']).' 0 R');
			if(isset($o['prev']))
			$this->_out('/Prev '.($n+$o['prev']).' 0 R');
			if(isset($o['next']))
			$this->_out('/Next '.($n+$o['next']).' 0 R');
			if(isset($o['first']))
			$this->_out('/First '.($n+$o['first']).' 0 R');
			if(isset($o['last']))
			$this->_out('/Last '.($n+$o['last']).' 0 R');
			$this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]',1+2*$o['p'],($this->h-$o['y'])*$this->k));
			$this->_out('/Count 0>>');
			$this->_out('endobj');
		}
		//Outline root
		$this->_newobj();
		$this->OutlineRoot=$this->n;
		$this->_out('<</Type /Outlines /First '.$n.' 0 R');
		$this->_out('/Last '.($n+$lru[0]).' 0 R>>');
		$this->_out('endobj');
	}
	
	function _putresources(){
		parent::_putresources();
		$this->_putbookmarks();
	}
	
	function _putcatalog(){
		parent::_putcatalog();
		if(count($this->outlines)>0){
			$this->_out('/Outlines '.$this->OutlineRoot.' 0 R');
			$this->_out('/PageMode /UseOutlines');
		}
	}
	
	var $_toc=array();

	function TOC_Entry($txt,$level=0) {
		$this->_toc[]=array('t'=>$txt,'l'=>$level,'p'=>$this->PageNo());
	}
	
	function insertTOC($label) {
		$this->Ln(2);
		$this->SetFont('gillb','',20);
		$this->Cell(0,5,$label,0,1,'C');
		$this->Ln(10);
	
		foreach($this->_toc as $t) {
	
		//Offset
		$level=$t['l'];
		if($level>0)
			$this->Cell($level*8);
		$weight='';
		if($level==0){
			$this->SetFont('gillb','',12);
		}else{
			$this->SetFont('gill','',10);
		}
		$str=$t['t'];
		$strsize=$this->GetStringWidth($str);
		$this->Cell($strsize+2,$this->FontSize+2,utf8_decode($str));
	
		//Filling dots
		$this->SetFont('gill','',10);
		$PageCellSize=$this->GetStringWidth($t['p'])+2;
		$w=$this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-($strsize+2);
		$nb=$w/$this->GetStringWidth('.');
		$dots=str_repeat('.',$nb);
		$this->Cell($w,$this->FontSize+2,$dots,0,0,'R');
	
		//Page number
		$this->Cell($PageCellSize,$this->FontSize+2,$t['p'],0,1,'R');
		}
	}
}
?>