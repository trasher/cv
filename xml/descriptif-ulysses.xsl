<?xml version="1.0" encoding="UTF-8" ?>
<!--
Description XSLT, ulysses version

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
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">
<xsl:output method="xml" doctype-system="about:legacy-compat" encoding="UTF-8" indent="yes" omit-xml-declaration="yes"  />

    <xsl:param name="space" select="' '"/>
    <xsl:variable name="from"> '!@#$%^*(),:;.?/\[]{}|=+-_*"&amp;&gt;&lt;&#171;&#187;</xsl:variable>
    <xsl:variable name="replace">__________________________________</xsl:variable>
    <xsl:param name="ip"/>
    <xsl:param name="css"/>

    <xsl:template match="/">
        <html lang="{$lang}">
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
                <title>...::: Johan Cwiklinski - Curriculum Vitae :::...</title>
                <meta property="http://purl.org/dc/elements/1.1/title" content="Johan Cwiklinski - Curriculum Vitae" />
                <meta property="http://purl.org/dc/elements/1.1/publisher" content="Johan Cwiklinski"/>
                <meta property="http://purl.org/dc/elements/1.1/language" content="{$lang}"/>
                <meta property="http://purl.org/dc/elements/1.1/creator" content="Johan CWIKLINSKI"/>
                <meta name="description" content="Johan Cwiklinski : Curriculum Vitae et présentation des réalisations et savoir-faire"/>
                <meta name="keywords" content="johan, cwiklinski, curriculum vitae, réalisation de sites web, programmation, webmaster, webmestre, java, php, linux, redhat, réseau, logiciels open source, trasher"/>
                <meta name="author" content="Johan Cwiklinski" />
                <meta name="geo.placename" content="Bordeaux, Gironde, Aquitaine, France" />
                <meta property="http://purl.org/dc/elements/1.1/date http://purl.org/dc/terms/created" content="{/descriptif/@DCdate}"/>
                <meta property="http://purl.org/dc/elements/1.1/date http://purl.org/dc/terms/modified" content="{/descriptif/@DCdateModif}"/>
                <link rel="shortcut icon" href="http://ulysses.fr/favicon.jpg"/>
                <link rel="stylesheet" href="templates/{$css}.css" />
                <xsl:comment><![CDATA[[if IE]>
                    <link rel="stylesheet" href="templates/ulysses/styles-ie.css" />
                    <script src="templates/ulysses/html5-ie.js"></script>
                <![CDATA["/><![endif]]]></xsl:comment>
                <meta name="viewport" content="width=device-width" />
            </head>
            <body>
                <div class="content">
                    <xsl:comment><![CDATA[[if lte IE 7]>
                        <link rel="stylesheet" href="templates/ulysses/oldie.css" />
                        <div id="alert-oldie">
                            <p><strong>Attention ! </strong> Votre navigateur (Internet Explorer 6 ou 7) présente de sérieuses lacunes en terme de sécurité et de performances, dues à son obsolescence (il date de 2001).<br />En conséquence, ce site sera consultable mais de manière moins optimale qu'avec un navigateur récent (<a href="http://www.mozilla-europe.org/fr/firefox/">Firefox</a>, <a href="http://www.browserforthebetter.com/download.html">Internet Explorer 8</a>, <a href="http://www.google.com/chrome?hl=fr">Chrome</a>, <a href="http://www.apple.com/fr/safari/download/">Safari</a>,...)</p>
                        </div>
                    <![CDATA["/><![endif]]]></xsl:comment>

                    <header role="banner">
                        <h1><a href="http://cv.ulysses.fr">Johan Cwiklinski - Curriculum Vitae</a></h1>
                        <nav role="navigation">
                            <a href="http://ulysses.fr" title="{php:functionString('_T', 'Website home')}"><xsl:value-of select="php:functionString('_T', 'Home')"/></a>
                            <a href="http://ulysses.fr/projects.php" title="{php:functionString('_T', 'My projects (on ulysses.fr)')}"><xsl:value-of select="php:functionString('_T', 'Projects')"/></a>
                            <a href="http://x-tnd.be" title="{php:functionString('_T', 'Self-employment activity presentation')}"><xsl:value-of select="php:functionString('_T', 'Self-employment')"/></a>
                            <a href="http://blog.ulysses.fr" title="{php:functionString('_T', 'My blog')}">Blog</a>
                            <a href="http://cv.ulysses.fr" title="Curriculum Vitae" class="current">CV</a>
                            <a href="http://ulysses.fr/about.php" title="{php:functionString('_T', 'About (on ulysses.fr)')}">...</a>
                        </nav>
                        <aside id="language">
                            <ul>
                                <li>
                                    <xsl:value-of select="php:functionString('_T', 'Change language:')"/>
                                </li>
                                <li><a href="?lang=en">English</a></li>
                                <li><a href="?lang=fr">Français</a></li>
                            </ul>
                        </aside>
                    </header>

                    <xsl:call-template name="summary"></xsl:call-template>
                    <xsl:apply-templates select="descriptif/section[1]" />
                    <xsl:apply-templates select="//presentation" />
                    <xsl:apply-templates select="descriptif/section[not(position() = 1)]" />
                    <nav id="social">
                        <a title="{php:functionString('_T', 'My twitter account')}" href="http://twitter.com/johancwi">
                            <img alt="Twitter" src="templates/ulysses/twitter.png"/>
                        </a>
                        <a title="{php:functionString('_T', 'My LinkedIn profile')}" href="http://linkedin.com/in/johancwiklinski">
                            <img alt="Linked In" src="templates/ulysses/linkedin.png"/>
                        </a>
                        <a title="{php:functionString('_T', 'My Google+ page')}" href="https://plus.google.com/116251525337893141428">
                            <img alt="Google Plus" src="templates/ulysses/gplus.png"/>
                        </a>
                    </nav>
                    <footer>
                        <p>© 2014 <a href="http://cv.ulysses.fr">Johan Cwiklinski</a> <a rel="license" href="http://creativecommons.org/licenses/by-nd/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nd/3.0/80x15.png" /></a></p>
                    </footer>
                </div>
                <ul id="styleswitcher">
                    <li><xsl:value-of select="php:functionString('_T', 'Design:')"/></li>
                    <li><a href="?css=ulysses" title="{php:functionString('_T', 'Ulysses style (default)')}" class="current">ulysses</a></li>
                    <li><a href="?css=blue" title="{php:functionString('_T', 'Blue style (old)')}">blue</a></li>
                    <li><a href="?css=nostyle" title="{php:functionString('_T', 'No style: displays XHTML without style')}">no style</a></li>
                </ul>
            </body>
        </html>
    </xsl:template>

    <xsl:template name="summary">
        <aside>
            <h2>Plan de page</h2>
            <ul id="summary">
                <xsl:for-each select="//section">
                    <li>
                        <a>
                            <xsl:attribute name="href">#<xsl:value-of select="translate(@nom,$from, $replace)"/></xsl:attribute>
                            <xsl:value-of select="@nom" />
                        </a>
                        <xsl:call-template name="sectionSummary">
                            <xsl:with-param name="section" select="."/>
                        </xsl:call-template>
                    </li>
                </xsl:for-each>
            </ul>
        </aside>
    </xsl:template>

    <xsl:template name="sectionSummary">
        <xsl:param name="section"/>
        <ul>
            <xsl:for-each select="$section/element">
                <li>
                    <a>
                        <xsl:attribute name="href">#<xsl:value-of select="concat(translate(parent::section/@nom, $from, $replace), '_', translate(@nom,$from, $replace))"/></xsl:attribute>
                        <xsl:value-of select="@nom" />
                    </a>
                </li>
            </xsl:for-each>
        </ul>
    </xsl:template>

    <xsl:template match="presentation">
        <section class="mainsec">
            <xsl:apply-templates/>
        </section>
    </xsl:template>

    <xsl:template match="para">
        <xsl:if test="@titre">
            <xsl:choose>
                <xsl:when test="@titre = 'Qui suis-je ?' or @titre='Who am I?'">
                    <h2><xsl:value-of select="@titre"/></h2>
                </xsl:when>
                <xsl:otherwise>
                    <h3><xsl:value-of select="@titre"/></h3>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:if>
        <p><xsl:apply-templates/></p>
    </xsl:template>

    <xsl:template match="descriptif">
        <div id="descriptif">
            <xsl:apply-templates select="section"/>
        </div>
    </xsl:template>

    <xsl:template match="section">
        <section class="mainsec" id="{translate(@nom, $from, $replace)}">
            <h2>
                <xsl:if test="@commentaire">
                    <xsl:attribute name="title"><xsl:value-of select="@commentaire"/></xsl:attribute>
                </xsl:if>
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
            </h2>
            <xsl:apply-templates select="element"/>
        </section>
    </xsl:template>

    <xsl:template match="element">
        <h3 id="{concat(translate(parent::section/@nom, $from, $replace), '_', translate(@nom,$from, $replace))}">
            <a>
                <xsl:attribute name="href">
                    <xsl:value-of select="@url"/>
                </xsl:attribute>
                <xsl:value-of select="@nom"/>
            </a><xsl:value-of select="$space"/>
        </h3>
        <xsl:if test="not(@tech = '-')">
            <aside class="tech">
                <strong><xsl:text>Languages&#160;:</xsl:text></strong><xsl:value-of select="$space"/><xsl:value-of select="@tech"/>
            </aside>
        </xsl:if>
        <p>
            <xsl:apply-templates/>
        </p>
    </xsl:template>

    <xsl:template match="lien">
        <a>
            <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
            <xsl:value-of select="."/>
        </a>
    </xsl:template>

    <xsl:template match="acronyme">
        <abbr>
            <xsl:attribute name="title">
                <xsl:value-of select="php:functionString('getAcronym', .)"/>
            </xsl:attribute>
            <xsl:value-of select="."/>
        </abbr>
    </xsl:template>

</xsl:stylesheet>
