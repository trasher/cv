<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PDF version
 *
 * PHP version 5
 *
 * Copyright © 2007-2014 Johan Cwiklinski
 *
 * This file is part of my curriculum vitae (http://cv.ulysses.fr).
 *
 * This is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Main
 * @package   CV
 *
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2007-2014 Johan Cwiklinski
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://cv.ulysses.fr
 */

require_once 'includes/cv.inc.php';
require_once 'includes/tcpdf/tcpdf.php';

/**
 * CV Specific PDF class
 *
 * @category  Main
 * @name      PDF
 * @package   CV
 * @abstract  Class for expanding TCPDF.
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2007-2014 Johan Cwiklinski
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @link      http://cv.ulysses.fr
 */
class PDF extends TCPDF
{
    const TXT_SIZE = 8;         //size for texts
    const CV_TITLE_SIZE = 13;   //size for CV title
    const MAIN_TITLE_SIZE = 12; //size for "main" titles
    const TITLE_SIZE = 11;      //size for subtitles
    const COORDS_SIZE = 10;     //size for coordinates block
    const STD_LINE_H = 6;       //standard line heigth

    public $cptPostes;
    private $_borders = 0;      //show borders for debug

    private $_line = array(
        'width' => 0.3,
        'cap' => 'butt',
        'join' => 'miter',
        'dash' => '0',
        'phase' => 10,
        'color' => array(0, 0, 0)
    );
    private $_dashed_line = array(
        'width' => 0.1,
        'cap' => 'butt',
        'join' => 'miter',
        'dash' => '1',
        'phase' => 10,
        'color' => array(0, 0, 0)
    );


    /**
     * Line breaks
     *
     * @param string $orig Original text
     *
     * @return string
     */
    function CodeBreaks($orig)
    {
        $array = explode("\\n", $orig);
        $data = $array[0];
        for ( $i = 1; $i < count($array); $i++ ) {
            $data .= "\n" . $array[$i];
        }
        return $data;
    }

    function Coordonnees($arrCoords, $titrecv)
    {
        $topy = $this->getY();
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('dejavusansb', '', self::COORDS_SIZE);
        $this->Cell(0, self::STD_LINE_H, $arrCoords->prenom . ' ' . $arrCoords->nom, $this->_borders, 1, 'L');
        $this->SetFont('dejavusans', '', self::COORDS_SIZE);
        $this->Cell(0, self::STD_LINE_H, $arrCoords->adresse, $this->_borders, 1, 'L');
        $this->Cell(0, self::STD_LINE_H, $arrCoords->cp . ' ' . $arrCoords->ville, $this->_borders, 1, 'L');
        $xval = $this->GetX();
        $yval = $this->GetY();
        $this->Image('images/phone.jpg', $xval, $yval, 5);
        $this->Cell(5, self::STD_LINE_H, '', 0, 0, '');
        $cpte = 0;
        $tels = null;
        foreach ( $arrCoords->tel as $tel ) {
            if ( $cpte > 0 ) {
                $tels .= ' / ';
            }
            $tels .= $tel;
            $cpte++;
        }
        $this->Cell(0, 5, $tels, $this->_borders, 1, 'L');
        $this->SetFont('dejavusans', '', self::TXT_SIZE);
        foreach ( $arrCoords->url as $url ) {
            $lenght = $this->GetStringWidth($url) + 2;
            $this->Cell($lenght, self::STD_LINE_H, $url, $this->_borders, 0, 'L', 0, $url);
            $this->Cell(3, self::STD_LINE_H, '-', 0, 0, 'C');
        }
        $this->Cell(0, self::STD_LINE_H, $arrCoords->mail, $this->_borders, 1, 'L', 0, 'mailto:' . $arrCoords->mail);
        $this->Cell(0, self::STD_LINE_H, $arrCoords->comment, $this->_borders, 1, 'L');
        $bottomy = $this->getY();
        $this->Titre($titrecv, $topy);
        $this->setY($bottomy);
    }

    function Titre($titre, $y)
    {
        $this->setY($y);
        $this->setX(190 - 70);
        $this->SetFont('dejavusansb', '', self::CV_TITLE_SIZE);
        $this->SetTextColor(0, 0, 255);
        $this->MultiCell(70, self::STD_LINE_H, $this->CodeBreaks($titre), $this->_borders, 'R');
        $this->Line(190 - 70, $this->getY(), 190, $this->getY());
    }

    function Competences($competences)
    {
        $this->ln(5); //some blank space before
        $this->SetFont('dejavusansb', '', self::MAIN_TITLE_SIZE);
        $this->SetTextColor(0, 0, 255);
        $this->Cell(0, 8, $competences['nom'], $this->_borders, 1, 'L');
        foreach ( $competences->competence as $competence ) {
            $this->SetFont('dejavusansb', '', self::TITLE_SIZE);
            $this->SetTextColor(0, 0, 255);
            $this->Cell(0, 8, $competence['nom'], $this->_borders, 1, 'C');
            $this->SetTextColor(0, 0, 0);
            $this->parseNode($competence);
        }
    }

    /**
     * Parse a node looking for subnodes
     *
     * @param SimpleXMLObject $node          The simple xml node to parse
     * @param integer         $typo_size_var Text size
     *
     * @return void
     */
    function parseNode($node, $typo_size_var = 0)
    {
        $newnode = dom_import_simplexml($node);
        foreach ( $newnode->childNodes as $child ) {
            if ( $child->hasChildNodes() ) {
                switch ( $child->nodeName ){
                case 'em':
                    $this->SetFont('dejavusansi', '', self::TXT_SIZE + $typo_size_var);
                    $this->Write(self::STD_LINE_H, $child->textContent);
                    $this->SetFont('dejavusans', '', self::TXT_SIZE + $typo_size_var);
                    break;
                case 'lien':
                    /** FIXME: link seems to be KO :'( */
                    $this->Write(self::STD_LINE_H, $child->textContent, $child->attributes->getNamedItem('url')->value);
                    break;
                case 'br':
                    $this->ln();
                    break;
                case 'ligne':
                    $this->parseNode($child);
                    $this->ln();
                    break;
                }
            } else {
                if ( trim($child->textContent) != '' ) {
                    $this->SetFont('dejavusans', '', self::TXT_SIZE + $typo_size_var);
                    $this->Write(self::STD_LINE_H, $child->textContent);
                }
            }
        }
    }

    function drawDescription($descriptif)
    {
        $this->Line($this->getX(), $this->getY(), $this->getX() + 190 , $this->getY(), $this->_dashed_line);
        $this->SetFont('dejavusans', '', self::TXT_SIZE -1);
        foreach ( $descriptif->ligne as $l ) {
            $this->setX($this->getX() + 10);
            $this->parseNode($l, -1);
            $this->ln();
        }
    }

    function TraitementPdf($cv)
    {
        $posteW = 72;
        $dateW = 25;
        $libelleW = 93;

        foreach ( $cv->titre as $titre ) {
            $this->SetFont('dejavusansb', '', self::MAIN_TITLE_SIZE);
            $this->SetTextColor(0, 0, 255);
            $this->ln(3);
            $this->Cell(0, 8, $titre['nom'], $this->_borders, 1, 'L');
            $yend = null;
            $xorig = $this->getX();
            $count = 0;
            foreach ( $titre->ligne as $line ) {
                if ( $line['break'] == 'true' ) {
                    $this->AddPage();
                    $yend = 0 + PDF_MARGIN_TOP;
                    $this->setY($yend);
                }
                $x = $xorig;
                $yorig = $this->getY();
                $y = ($this->getY()>$yend) ? $this->getY() : $yend;
                $this->SetFont('dejavusansb', '', self::TXT_SIZE);
                $this->SetTextColor(0, 0, 0);
                if ( isset($line->poste) ) {
                    if ( $count > 0 ) {
                        $this->Line($xorig, $this->getY(), $xorig+190, $this->getY(), $this->_line);
                    }
                    $count++;
                    $y += 2;
                    $this->setY($y);
                    $this->MultiCell($posteW, self::STD_LINE_H, $line->poste, $this->_borders, 'L');
                    $x += $posteW;
                    $yend = $this->getY();
                    $this->SetFont('dejavusans', '', self::TXT_SIZE);
                    $this->setY($y);
                    $this->setX($x + $libelleW);
                    $this->Cell($dateW, self::STD_LINE_H, $line->annee, $this->_borders, 0, 'R');
                    $this->setX($x);
                    $this->MultiCell($libelleW, self::STD_LINE_H, $this->CodeBreaks($line->libelle), $this->_borders, 'L');
                    $this->setY(($this->getY() > $yend) ? $this->getY() +1 : $yend + 1);
                    $this->drawDescription($line->descriptif);
                } else {
                    $count++;
                    $this->setY($y);
                    $x += 60;
                    $yend = $this->getY();
                    $this->SetFont('dejavusans', '', self::TXT_SIZE);
                    $this->setY($y);
                    $this->setX($xorig);
                    $this->Cell(40, self::STD_LINE_H, $line->annee, $this->_borders, 0, 'L');
                    $this->setX($xorig + 40);
                    $this->parseNode($line->libelle);
                    $this->ln();
                    $y = ($this->getY()>$yend) ? $this->getY() : $yend;
                }
            }
        }
    }
}

$filename = 'xml/cv-utf8.xml';
if ( $session['lang'] === 'en' ) {
    $filename = 'xml/cv-en.xml';
}
$cv = simplexml_load_file($filename);
$arrayCoords = $cv->xpath('//coordonnees');
$competences = $cv->xpath('//competences');
$postes = $cv->xpath("//poste");

/* CREATION DU PDF */
$pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8');
$pdf->cptPostes = count($postes);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($cv->coordonnees->prenom . ' ' . $cv->coordonnees->nom);
$pdf->SetTitle($cv->coordonnees->prenom . ' ' . $cv->coordonnees->nom . ' - Curriculum Vitae');
$pdf->SetSubject($cv->coordonnees->prenom . ' ' . $cv->coordonnees->nom . ' | ' . str_replace('\n', ' - ', $cv['titre']));

// No headers and footers
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->setFooterMargin(0);
$pdf->setHeaderMargin(0);

// Show full page
$pdf->SetDisplayMode('fullpage');

$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(0, 0, 0);
$pdf->Coordonnees($arrayCoords[0], $cv['titre']);

$pdf->Competences($competences[0]);
$pdf->TraitementPdf($cv);
$pdf->Output('cv_johan_cwiklinski.pdf', 'I');

