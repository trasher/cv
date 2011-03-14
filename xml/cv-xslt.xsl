<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="UTF-8" indent="yes" omit-xml-declaration="yes"/>
    <xsl:param name="space" select="' '"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
            <head>
                <title>...::: Johan Cwiklinski - Curriculum Vitae :::...</title>
                <meta name="Author" content="Johan CWIKLINSKI"/>
                <meta name="keywords" content="administrateur système, développeur web, développeur, open source, webmaster, java, php, linux, fedora"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                <link rel="shortcut icon" href="http://ulysses.fr/favicon.jpg"/>
                <link type="text/css" rel="stylesheet"  href="templates/cv.css"/>
            </head>
            <body>
                <xsl:apply-templates select="cv"/>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="cv">
        <div class="content">
            <h1>
                <xsl:call-template name="cr2br">
                    <xsl:with-param name="text" select="@titre"/>
                </xsl:call-template>
            </h1>
            <xsl:apply-templates select="coordonnees"/>
            <div class="cv">
                <xsl:apply-templates select="competences" />
                <xsl:apply-templates select="titre"/>
            </div>
            <div class="bottom">
                <a href="xml/cv-utf8.xml">Source XML</a> - <a href="xml/cv-xslt.xsl">Source XSL</a>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="coordonnees">
        <div class="left">
            <strong><xsl:value-of select="prenom"/><xsl:value-of select="$space"/><xsl:value-of select="nom"/></strong>
            <br/><xsl:value-of select="adresse"/>
            <br/><strong><xsl:value-of select="cp"/><xsl:value-of select="$space"/><xsl:value-of select="ville"/></strong>
        </div>
        <div class="right">
            <xsl:call-template name="telephone" />
            <xsl:element name = "a">
                <xsl:attribute name="href">mailto:<xsl:value-of select="mail" /></xsl:attribute>
                <xsl:value-of select="mail" />
            </xsl:element>
            <br/>
            <xsl:call-template name="webadress" />
            <br/>
            <xsl:value-of select="comment"/>
        </div>
    </xsl:template>

    <xsl:template name="telephone">
        <xsl:for-each select="tel">
            <xsl:value-of select="."/> -
        </xsl:for-each>
    </xsl:template>

    <xsl:template name="webadress">
        <xsl:for-each select="url">
            <xsl:element name = "a">
                <xsl:attribute name="href">
                    <xsl:value-of select="." />
                </xsl:attribute>
                <xsl:value-of select="." />
            </xsl:element>
            <xsl:if test="position() &lt; count(//url)"><xsl:value-of select="$space"/>-<xsl:value-of select="$space"/></xsl:if>
        </xsl:for-each>
    </xsl:template>

    <xsl:template match="competences">
        <h2><xsl:value-of select="@nom"/></h2>
        <xsl:apply-templates select="competence" />
    </xsl:template>

    <xsl:template match="competence">
        <h3><xsl:value-of select="@nom"/></h3>
        <xsl:apply-templates select="ligne"/>
    </xsl:template>

    <xsl:template match="titre">
        <h2><xsl:value-of select="@nom"/></h2>
        <xsl:apply-templates select="ligne"/>
    </xsl:template>

    <xsl:template match="ligne">
        <xsl:choose>
            <xsl:when test="libelle">
                <xsl:if test="poste">
                    <div class="left big_block"><strong><xsl:value-of select="poste"/></strong></div>
                </xsl:if>
                <div>
                    <xsl:choose>
                        <xsl:when test="poste">
                            <xsl:attribute name="class">fright</xsl:attribute>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:attribute name="class">left</xsl:attribute>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:value-of select="annee"/>
                </div>
                <div>
                    <xsl:choose>
                        <xsl:when test="poste">
                            <xsl:attribute name="class">right big_left_margin</xsl:attribute>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:attribute name="class">right</xsl:attribute>
                        </xsl:otherwise>
                    </xsl:choose>
                    <xsl:apply-templates select="libelle"/>
                </div>
                <xsl:if test="descriptif">
                    <xsl:apply-templates select="descriptif"/>
                </xsl:if>
                <xsl:if test="poste">
                    <xsl:if test="position()!=last()">
                        <hr/>
                    </xsl:if>
                </xsl:if>
            </xsl:when>
            <xsl:otherwise>
                <div class="oneline">
                    <xsl:apply-templates/>
                </div>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="descriptif">
        <div class="descriptif">
            <ul>
                <xsl:for-each select="ligne">
                    <li><xsl:apply-templates/></li>
                </xsl:for-each>
            </ul>
        </div>
    </xsl:template>

    <xsl:template match="lien">
        <a class="normal">
            <xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
            <xsl:value-of select="."/>
        </a>
    </xsl:template>

    <xsl:template match="br">
        <br/>
    </xsl:template>

    <xsl:template match="em">
        <em><xsl:value-of select="." /></em>
    </xsl:template>

    <!-- remplacement des \n par des br -->
    <xsl:template name="cr2br">
        <xsl:param name="text" select="."/>
        <xsl:choose>
            <xsl:when test="contains($text, '\n')">
                <xsl:value-of select="substring-before($text, '\n')" disable-output-escaping="yes"/>
                <br/>
                <xsl:call-template name="cr2br">
                    <xsl:with-param name="text" select="substring-after($text,'\n')"/>
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="$text" disable-output-escaping="yes"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

</xsl:stylesheet>
