<?php
ini_set("session.use_trans_sid", "0");
session_start();
include('classes/class_acronyms.php');
$acronyms = new Acronyms();
function getAcronym($acronym){
	global $acronyms;
	return $acronyms->get($acronym);
}

if ( !isset($_GET["css"]) || trim($_GET["css"]) == "" ) {
    $_SESSION["css"] = "ulysses";
} else {
    $_SESSION["css"]=$_GET["css"];
}
$fichier="xml/descriptif.xml";

$ng = new domDocument;
$ng->load($fichier);
if (!$ng->relaxNGValidate('xml/descriptif.rng')) {
	echo "<p>Le document XML n'a pas &eacute;t&eacute; valid&eacute; selon le sch&eacute;ma associ&eacute;. Veuillez vous r&eacute;f&eacute;rer aux erreurs ci dessus.</p>";
}

$xsl = new DomDocument();
if ( isset($_SESSION['css']) && $_SESSION['css'] == 'ulysses' ) {
    $xsl->load("xml/descriptif-ulysses.xsl");
} else {
    $xsl->load("xml/descriptif.xsl");
}
$inputdom = new DomDocument();
$inputdom->load($fichier);

$proc = new XsltProcessor();
$xsl = $proc->importStylesheet($xsl);
$proc->setParameter('', 'ip', $_SERVER["REMOTE_ADDR"]);
$proc->setParameter('', 'css', $_SESSION["css"]);
$proc->registerPhpFunctions();
print $proc->transformToXml($inputdom);
?>
