<?php
require('../../classes/fpdf.php');
global $cptPostes;

class PDF extends FPDF{
var $widths;

	function CodeBreaks($orig){
		$array=explode("\\n", $orig);
		$data=$array[0];
		for($i=1;$i<count($array);$i++){
			$data.="\n".$array[$i];
		}
		return $data;
	}

	function Coordonnees($arrCoords){
		$this->SetTextColor(0,0,0);
		$this->SetFont('gillb','',13);
		$this->Cell(0,6,utf8_decode($arrCoords->prenom." ".$arrCoords->nom),0,1,'L');
		$this->SetFont('gill','',13);
		$this->Cell(0,6,utf8_decode($arrCoords->adresse),0,1,'L');
		$this->SetFont('gillb','',13);
		$this->Cell(0,6,utf8_decode($arrCoords->cp." ".$arrCoords->ville),0,1,'L');
		$this->SetFont('gill','',13);
		$xval=$this->GetX();
		$yval=$this->GetY();
		$this->Image("../../images/phone.jpg",$xval,$yval,5);
		$this->Cell(5,6,'',0,0,'');
		$cpte = 0;
		foreach($arrCoords->tel as $tel){
			if($cpte > 0) $tels .= " / ";
			$tels .= $tel;
			$cpte++;
		}
		$this->Cell(0,6,utf8_decode($tels),0,1,'L');
		$this->SetFont('gill','',11);
		foreach($arrCoords->url as $url){
			$lenght = $this->GetStringWidth($url)+2;
			$this->Cell($lenght,6,$url,0,0,'L',0,utf8_decode($url));
			$this->Cell(3,6,"-",0,0,'C');
		}
		$this->Cell(0,6,$arrCoords->mail,0,1,'L',0,"mailto:".$arrCoords->mail);
		$this->Cell(0,6,utf8_decode($arrCoords->comment),0,1,'L');
	}

	function Competences($competences){
			$this->SetFont('gillb','',16);
			$this->SetTextColor(0,0,255);
			$this->Cell(0,10,utf8_decode($competences->attributes->getNamedItem("nom")->value),0,1,'L');
			foreach($competences->childNodes as $competence){
				if($competence->nodeType==1){
					$this->SetFont('gillb','',14);
					$this->SetTextColor(0,0,255);
					$this->Cell(0,10,utf8_decode($competence->attributes->getNamedItem('nom')->value),0,1,'C');
					$this->SetTextColor(0,0,0);
					$this->parseCompetences($competence);
				}
			}
	}

	function parseCompetences($node){
		foreach($node->childNodes as $child) {
			if($child->hasChildNodes()){
				if($child->nodeName=="em"){
					$this->SetFont('gilli','',11);
					$this->Write(5, utf8_decode($child->textContent));
					$this->SetFont('gill','',11);
				}elseif($child->nodeName=="ligne"){
					$this->parseCompetences($child);
					$this->ln();
				}
			}else{
				if(trim($child->textContent)!=""){
					$this->SetFont('gill','',11);
					$this->Write(5, utf8_decode($child->textContent));
				}
			}
		}
	}

	function TraitementPdf($cv){
		foreach($cv->titre as $titre){
			$this->SetFont('gillb','',16);
			$this->SetTextColor(0,0,255);
			$this->Cell(0,10,utf8_decode($titre["nom"]),0,1,'L');
			$yend;
			$xorig = $this->getX();
			$count = 0;
			foreach($titre->ligne as $line){
				//$x = $this->getX();
				$x = $xorig;
				$yorig = $this->getY();
				$y = ($this->getY()>$yend)?$this->getY():$yend;
				$this->SetFont('gillb','',11);
				$this->SetTextColor(0,0,0);
				if(isset($line->poste)){
					$count++;
					$y += 2;
					$this->setY($y);
					$this->MultiCell(60,5,utf8_decode($line->poste), 0, 'L');
					$x+=60;
					$yend = $this->getY();
					$this->SetFont('gill','',11);
					$this->setY($y);
					$this->setX($x+90);
					$this->Cell(40,5,utf8_decode($line->annee),0,0,'L');
					$this->setX($x);
					$this->MultiCell(90,5,utf8_decode($this->CodeBreaks($line->libelle)), 0,'J');
					$y = ($this->getY()>$yend)?$this->getY():$yend;
					$this->setLineWidth(0.1);
					$y +=1;
					if($count < $GLOBALS["cptPostes"])
						$this->Line($xorig, $y, $xorig+190, $y);
				}else{
					$count++;
					$this->setY($y);
					$x+=60;
					$yend = $this->getY();
					$this->SetFont('gill','',11);
					$this->setY($y);
					$this->setX($xorig);
					$this->Cell(40,5,utf8_decode($line->annee),0,0,'L');
					$this->setX($xorig+40);
					$this->MultiCell(150,5,utf8_decode($this->CodeBreaks($line->libelle)), 0,'J');
					$y = ($this->getY()>$yend)?$this->getY():$yend;
				}
			}
			$this->setY(($this->getY()>$yend)?$this->getY():$yend);
		}
	}
}
$dom = new DomDocument();
$dom->load("xml/cv-utf8-2006.xml");

$cv = simplexml_load_file("xml/cv-utf8-2006.xml");
$arrayCoords = $cv->xpath("//coordonnees");
$competences = $dom->getElementsByTagName('competences')->item(0);
$postes = $cv->xpath("//poste");
$GLOBALS["cptPostes"] = count($postes);

/* CREATION DU PDF */
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->Open();
$pdf->SetMargins(10, 10 , 20);
$pdf->AddFont('gill','','gill.php');
$pdf->AddFont('gillb','','gillb.php');
$pdf->AddFont('gilli','','gili.php');
$pdf->AddPage();
$pdf->SetFont('gill','',11);
$pdf->SetTextColor(0,0,0);
$pdf->Coordonnees($arrayCoords[0]);
$pdf->Competences($competences);
$pdf->TraitementPdf($cv);
$pdf->Output();
?>
