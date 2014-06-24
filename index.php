<?php

require_once 'includes/cv.inc.php';
require_once 'classes/class_acronyms.php';
$acronyms = new Acronyms();

/**
 * Retrieve an acronym from XSL
 *
 * @param string $acronym The acronym
 *
 * @return string
 */
function getAcronym($acronym)
{
    global $acronyms;
    return $acronyms->get($acronym);
}

/**
 * Translate a string from XSL
 *
 * @param string $str Original string
 *
 * @return string
 */
function _T($str)
{
    global $lang;
    return $lang[$str];
}

if ( !isset($_GET['css']) || trim($_GET['css']) == '' ) {
    $session['css'] = 'ulysses';
} else {
    $session['css']=$_GET['css'];
}

$fichier = 'xml/descriptif.xml';
if ( $session['lang'] === 'en' ) {
    $fichier = 'xml/descriptif-en.xml';
}

$ng = new domDocument;
$ng->load($fichier);
if (!$ng->relaxNGValidate('xml/descriptif.rng')) {
    echo '<p>' . $lang['Xml document is not valid against associated schema. Please refer to above errors.'] . '</p>';
}

$xsl = new DomDocument();
if ( isset($session['css']) && $session['css'] == 'ulysses' ) {
    $xsl->load('xml/descriptif-ulysses.xsl');
} else {
    $xsl->load('xml/descriptif.xsl');
}
$inputdom = new DomDocument();
$inputdom->load($fichier);

$proc = new XsltProcessor();
$xsl = $proc->importStylesheet($xsl);
$proc->setParameter('', 'ip', $_SERVER['REMOTE_ADDR']);
$proc->setParameter('', 'css', $session['css']);
$proc->setParameter('', 'lang', $session['lang']);
$proc->registerPhpFunctions();
print $proc->transformToXml($inputdom);

