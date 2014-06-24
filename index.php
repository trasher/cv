<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Main index
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
 * @link      http://cv.ulysses.fr
 */

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

