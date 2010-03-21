<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">
<xsl:output method="xml" doctype-system="about:legacy-compat" encoding="UTF-8" indent="yes" omit-xml-declaration="yes"  />

    <xsl:param name="space" select="' '"/>
    <xsl:variable name="from"> '!@#$%^*(),:;.?/\[]{}|=+-_*"&amp;&gt;&lt;&#171;&#187;</xsl:variable>
    <xsl:variable name="replace">__________________________________</xsl:variable>
    <xsl:param name="ip"/>
    <xsl:param name="css"/>


    <xsl:template match="/">
        <html lang="fr">
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
                <title>...::: Johan Cwiklinski - Curriculum Vitae :::...</title>
                <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/"/>
                <meta name="DC.title" content="Site personnel de Johan Cwiklinski" />
                <meta name="DC.Publisher" content="Johan Cwiklinski"/>
                <meta name="DC.Language" content="fr"/>
                <meta name="DC.Identifier" content="dublin_core"/>
                <meta name="DC.Creator" content="Johan CWIKLINSKI"/>
                <meta name="description" content="Johan Cwiklinski : Curriculum Vitae et présentation des réalisations et savoir-faire"/>
                <meta name="keywords" content="johan, cwiklinski, curriculum vitae, réalisation de sites web, programmation, webmaster, webmestre, java, php, linux, redhat, réseau, logiciels open source, trasher"/>
                <meta name="author" content="Johan Cwiklinski" />
                <meta name="geo.placename" content="Bordeaux, Gironde, Aquitaine, France" />
                <meta name="DC.Date.created" content="{/descriptif/@DCdate}"/>
                <meta name="DC.Date.modified" content="{/descriptif/@DCdateModif}"/>
                <link rel="shortcut icon" href="http://ulysses.fr/favicon.jpg"/>
                <link rel="stylesheet" href="templates/{$css}.css" />
                <xsl:comment><![CDATA[[if IE]>
                    <link rel="stylesheet" href="templates/ulysses/styles-ie.css" />
                    <script src="templates/ulysses/html5-ie.js"></script>
                <![CDATA["/><![endif]]]></xsl:comment>
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
                            <a href="http://ulysses.fr" title="Accueil du site">Accueil</a>
                            <a href="http://blog.ulysses.fr" title="Mon blog">Blog</a>
                            <a href="http://cv.ulysses.fr" title="Mon curriculum Vitae" class="current">CV</a>
                            <a href="http://ulysses.fr/projects.php" title="Mes projets (sur ulysses.fr)">Projets</a>
                            <a href="http://zia.ulysses.fr" title="Le blog de ma petite fille">Zia</a>
                            <a href="http://ulysses.fr/about.php" title="À propos (sur ulysses.fr)">...</a>
                        </nav>
                    </header>

                    <xsl:call-template name="summary"></xsl:call-template>
                    <xsl:apply-templates select="descriptif/section[1]" mode="render" />
                    <xsl:apply-templates select="//presentation" />
                    <section class="mainsec">
                        <h2>Réalisations</h2>
                        <xsl:apply-templates select="descriptif/section" />
                    </section>
                    <nav id="social">
                        <a title="Mon compte twitter" href="http://twitter.com/johancwi">
                            <img alt="Twitter" src="templates/ulysses/twitter.png"/>
                        </a>
                        <a title="Mon profil Facebook" href="http://www.facebook.com/profile.php?id=753358560">
                            <img alt="Facebook" src="templates/ulysses/facebook.png"/>
                        </a>
                        <a title="Mon profil Linked In" href="http://www.linkedin.com/profile?viewProfile=&amp;key=8255601&amp;trk=tab_pro">
                            <img alt="Linked In" src="templates/ulysses/linkedin.png"/>
                        </a>
                    </nav>
                    <footer>
                        <p>© 2010 <a href="http://cv.ulysses.fr">Johan Cwiklinski</a> - <a href="http://www.amazon.fr/wishlist/18FZ3E3CX0WLR">Mes envies cadeaux</a> <a rel="license" href="http://creativecommons.org/licenses/by-nd/3.0/" title="Cette création est mise à disposition sous un contrat Creative Commons."><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nd/3.0/80x15.png" /></a></p>
                    </footer>
                </div>
                <ul id="styleswitcher">
                    <li>Thème : </li>
                    <li><a href="?css=ulysses" title="Style 'Ulysses' (par défaut)" class="current">ulysses</a></li>
                    <li><a href="?css=blue" title="Style 'Bleu' (ancien)">blue</a></li>
                    <li><a href="?css=black" title="Style 'Noir' (très ancien)">black</a></li>
                    <li><a href="?css=nostyle" title="Pas de style. Affiche le XHTML sans appliquer de style">no style</a></li>
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
                        <xsl:attribute name="href">#<xsl:value-of select="translate(@nom,$from, $replace)"/></xsl:attribute>
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
                <xsl:when test="@titre = 'Qui suis-je ?'">
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

    <xsl:template match="section[1]" mode="render">
        <section class="mainsec" id="{translate(@nom, $from, $replace)}">
            <h2>
                <xsl:call-template name="sectiontitle">
                    <xsl:with-param name="el" select="."/>
                    <xsl:with-param name="first" select="boolean('true')"/>
                </xsl:call-template>
            </h2>
            <xsl:apply-templates select="element" mode="render"/>
        </section>
    </xsl:template>


    <xsl:template match="section[1]"/>

    <xsl:template match="section">
        <div id="{translate(@nom,$from, $replace)}" class="parts">
        <h3>
            <xsl:call-template name="sectiontitle">
                <xsl:with-param name="el" select="."/>
            </xsl:call-template>
        </h3>
            <xsl:apply-templates select="element"/>
        </div>
    </xsl:template>

    <xsl:template name="sectiontitle">
        <xsl:param name="el"/>
        <xsl:choose>
            <xsl:when test="$el/@lien">
                <a>
                    <xsl:attribute name="href"><xsl:value-of select="$el/@lien"/></xsl:attribute>
                    <xsl:value-of select="$el/@nom"/>
                </a>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$el/@nom"/>
            </xsl:otherwise>
        </xsl:choose>
        <xsl:if test="$el/@commentaire">
            <xsl:value-of select="$space"/><span>(<xsl:value-of select="$el/@commentaire"/>)</span>
        </xsl:if>
    </xsl:template>

    <xsl:template match="element" mode="render">
        <h3>
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

    <xsl:template match="element">
        <h4>
            <a>
                <xsl:attribute name="href">
                    <xsl:value-of select="@url"/>
                </xsl:attribute>
                <xsl:value-of select="@nom"/>
            </a>
        </h4>
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
