<?php
require('../../classes/fpdf.php');

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
	//Pied de page
	function Footer(){
		$this->SetY(-20);
		$this->SetFont('gill','',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(0,10,utf8_decode('Réalisation Johan CWIKLINSKI - 2005 - PHP/XML/FPDF'),0,0,'C',0,"http://www.x-tnd.be");
	}
	function Coordonnees($arrCoords){
		$this->SetTextColor(0,0,0);
		$this->SetFont('gillb','',13);
		$this->Cell(0,6,utf8_decode($arrCoords->nom." ".$arrCoords->prenom),0,1,'L');
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
		//$this->Cell(0,6,utf8_decode($arrCoords->tel),0,1,'L');
		$this->SetFont('gill','',11);
		foreach($arrCoords->url as $url){
			$lenght = $this->GetStringWidth($url)+2;
			$this->Cell($lenght,6,$url,0,0,'L',0,utf8_decode($url));
			$this->Cell(3,6,"-",0,0,'C');
		}
		//$this->Cell(50,6,$arrCoords->url,0,0,'L',0,utf8_decode($arrCoords->url));
		$this->Cell(0,6,$arrCoords->mail,0,1,'L',0,"mailto:".$arrCoords->mail);
		$this->Cell(0,6,utf8_decode($arrCoords->comment),0,1,'L');
	}

	function TraitementPdf($cv){
		foreach($cv->titre as $titre){
			$this->SetFont('gillb','',16);
			$this->SetTextColor(0,0,255);
			$this->Cell(0,10,utf8_decode($titre["nom"]),0,1,'L');
			foreach($titre->ligne as $line){
				$this->SetFont('gillb','',11);
				$this->SetTextColor(0,0,0);
				$this->Cell(40,5,utf8_decode($line->annee),0,0,"left");
				$this->SetFont('gill','',11);
				$this->MultiCell(140,5,utf8_decode($this->CodeBreaks($line->descriptif)),'J');
			}
		}
	}
}
$cv = simplexml_load_file("xml/cv-utf8.xml");
$arrayCoords = $cv->xpath("//coordonnees");
/* CREATION DU PDF */
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->Open();
$pdf->SetMargins(10, 10 , 20);
$pdf->AddFont('gill','','gill.php');
$pdf->AddFont('gillb','','gillb.php');
$pdf->AddPage();
$pdf->SetFont('gill','',11);
$pdf->SetTextColor(0,0,0);
$pdf->Coordonnees($arrayCoords[0]);
//$pdf->Ln();
$pdf->TraitementPdf($cv);
$pdf->Output();
?>
