<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php

require_once 'includes/cv.inc.php';

$fichier = 'xml/cv-utf8.xml';
if ( $session['lang'] === 'en' ) {
    $fichier="xml/cv-en.xml";
}
$xsl = new DomDocument();
$xsl->load("xml/cv-xslt.xsl");
$inputdom = new DomDocument();
$inputdom->load($fichier);

$proc = new XsltProcessor();
$xsl = $proc->importStylesheet($xsl);
print $proc->transformToXml($inputdom);

