<?xml version="1.0" encoding="UTF-8" ?>
<!--
Description XSLT

Copyright © 2007-2014 Johan Cwiklinski

This file is part of my curriculum vitae (http://cv.ulysses.fr).

This is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Galette. If not, see <http://www.gnu.org/licenses/>.

@author    Johan Cwiklinski <johan@x-tnd.be>
@copyright 2007-2014 Johan Cwiklinski
@license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
@link      http://cv.ulysses.fr
-->
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">
<xsl:output method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="UTF-8" indent="yes" omit-xml-declaration="yes"  />

<xsl:param name="space" select="' '"/>
<xsl:variable name="from"> '!@#$%^*(),:;.?/\[]{}|=+-_*"&amp;&gt;&lt;&#171;&#187;</xsl:variable>
<xsl:variable name="replace">__________________________________</xsl:variable>
<xsl:param name="ip"/>
<xsl:param name="css"/>

<xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}">
        <head>
            <title>...::: Johan Cwiklinski - Curriculum Vitae :::...</title>
            <meta name="Author" content="Johan CWIKLINSKI"/>
            <meta name="description" content="Johan Cwiklinski : Curriculum Vitae et présentation des réalisations et savoir-faire"/>
            <meta name="keywords" content="johan, cwiklinski, curriculum vitae, réalisation de sites web, programmation, webmaster, webmestre, java, php, linux, redhat, réseau, logiciels open source, trasher"/>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <!-- dublin core metas -->
            <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/"/>
            <meta name="DC.Publisher" content="Johan Cwiklinski - X-TnD"/>
            <meta name="DC.Language" scheme="RFC3066" content="{$lang}"/>
            <meta name="DC.Identifier" content="dublin_core"/>
            <meta name="DC.Creator" content="Johan CWIKLINSKI"/>
            <meta>
                <xsl:attribute name="name">DC.Date.created</xsl:attribute>
                <xsl:attribute name="scheme">W3CDTF</xsl:attribute>
                <xsl:attribute name="content"><xsl:value-of select="/descriptif/@DCdate"/></xsl:attribute>
            </meta>
            <meta>
                <xsl:attribute name="name">DC.Date.modified</xsl:attribute>
                <xsl:attribute name="scheme">W3CDTF</xsl:attribute>
                <xsl:attribute name="content"><xsl:value-of select="/descriptif/@DCdateModif"/></xsl:attribute>
            </meta>
            <link rel="shortcut icon" href="http://ulysses.fr/favicon.jpg"/>
            <xsl:if test="$css!='nostyle'">
                <style type="text/css">
                    @import 'templates/<xsl:value-of select="$css"/>.css';
                </style>
            </xsl:if>
            <meta name="viewport" content="width=device-width" />
        </head>
        <body>
            <div id="content">
                <div id="header"><xsl:value-of select="$space"/></div>
                <ul id="styleswitcher">
                    <li><xsl:value-of select="php:functionString('_T', 'Select a style:')"/></li>
                    <li><a href="?css=ulysses" title="{php:functionString('_T', 'Ulysses style (default)')}" class="current">ulysses</a></li>
                    <li><a href="?css=blue" title="{php:functionString('_T', 'Blue style (old)')}">blue</a></li>
                    <li><a href="?css=nostyle" title="{php:functionString('_T', 'No style: displays XHTML without style')}">no style</a></li>
                </ul>
                <xsl:call-template name="summary"></xsl:call-template>
                <xsl:apply-templates select="//presentation" />
                <xsl:apply-templates select="descriptif" />
            </div>
            <div id="w3c">
                <ul>
                    <li>
                        <a href="http://www.php.net" title="Powered by PHP5">
                            <img src="images/blue/php5-power.png" alt="PHP5"/>
                        </a>
                        <span>&#160;;</span>
                    </li>
                    <li>
                        <a href="http://validator.w3.org/check/referer" title="Valide XHTML 1.1">
                            <img alt="XHTML 1.1" src="images/blue/w3c-xhtml1.1.png" />
                        </a>
                        <span>&#160;;</span>
                    </li>
                    <li>
                        <a href="http://jigsaw.w3.org/css-validator/check/referer" title="Valide CSS 2.0">
                            <img alt="CSS 2.0" src="images/blue/w3c-css2.0.png" />
                        </a>
                        <span>&#160;;</span>
                    </li>
                    <li>
                        <a href="http://www.w3.org/WAI/WCAG1AA-Conformance" title="Level Double-A conformance, W3C-WAI Web Content Accessibility Guidelines 1.0">
                            <img src="images/blue/w3c-wai-aa.png" alt="WAI AA" />
                        </a>
                        <span>&#160;;</span>
                    </li>
                </ul>
            </div>
        </body>
    </html>
</xsl:template>

<xsl:template name="summary">
    <ol id="summary">
        <xsl:for-each select="//section">
            <li>
                <a>
                    <xsl:attribute name="href">#<xsl:value-of select="translate(@nom,$from, $replace)"/></xsl:attribute>
                    <xsl:value-of select="@nom" />
                </a>
            </li>
        </xsl:for-each>
    </ol>
</xsl:template>

<xsl:template match="presentation">
    <div id="presentation">
        <xsl:apply-templates/>
    </div>
</xsl:template>

<xsl:template match="para">
    <xsl:if test="@titre">
        <h1><xsl:value-of select="@titre"/></h1>
    </xsl:if>
    <p><xsl:apply-templates/></p>
</xsl:template>

<xsl:template match="descriptif">
    <div id="descriptif">
        <xsl:apply-templates select="section"/>
    </div>
</xsl:template>

<xsl:template match="section">
    <div>
        <xsl:attribute name="id"><xsl:value-of select="translate(@nom,$from, $replace)"/></xsl:attribute>
        <xsl:attribute name="class">parts</xsl:attribute>
        <h2>
            <xsl:choose>
                <xsl:when test="@lien">
                    <a>
                        <xsl:attribute name="href"><xsl:value-of select="@lien"/></xsl:attribute>
                        <xsl:value-of select="@nom"/>
                    </a>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="@nom"/>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:if test="@commentaire">
                <xsl:value-of select="$space"/><span>(<xsl:value-of select="@commentaire"/>)</span>
            </xsl:if>
        </h2>
        <xsl:apply-templates select="element"/>
    </div>
</xsl:template>

<xsl:template match="element">
    <p>
        <a>
            <xsl:attribute name="href">
                <xsl:value-of select="@url"/>
            </xsl:attribute>
            <xsl:value-of select="@nom"/>
            <xsl:if test="not(@tech = '-')">
                <xsl:value-of select="$space"/><span>(<xsl:value-of select="@tech"/>)</span>
            </xsl:if>
        </a><xsl:value-of select="$space"/>
        <xsl:apply-templates/>
    </p>
</xsl:template>

<xsl:template match="lien">
    <a class="normal">
        <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
        <xsl:value-of select="."/>
    </a>
</xsl:template>

<xsl:template match="acronyme">
    <acronym>
        <xsl:attribute name="title">
            <xsl:value-of select="php:functionString('getAcronym', .)"/>
        </xsl:attribute>
        <xsl:value-of select="."/>
    </acronym>
</xsl:template>

</xsl:stylesheet>
