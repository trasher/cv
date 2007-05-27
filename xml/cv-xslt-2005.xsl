<?xml version="1.0" encoding="ISO-8859-1" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="ISO-8859-1" indent="yes" omit-xml-declaration="yes"/>
<xsl:template match="/">
	<html>
		<head>
			<title>X-TND ...::: Johan Cwiklinski. - Curriculum Vitae :::...</title>
			<meta name="Author" content="Johan CWIKLINSKI"/>
			<meta name="keywords" content="curriculum, vitae, johan, cwiklinski, programmeur, webmaster, webmestre, java, php, linux, redhat, red, hat, reseau, r�eau, r�eaux, resaux, windows, lan, securite, s�urit� logiciel, open, source, programmation, trasher"/>
			<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
			<link rel="shortcut icon" href="http://x-tnd.be/favicon.ico"/>
			<link type="text/css" rel="stylesheet"  href="templates/cv.css"/>
		</head>
	<body>
	<xsl:apply-templates select="cv"/>
	</body>
	</html>
</xsl:template>


<xsl:param name="space" select="' '"/>
	<xsl:template match="cv">
		<div class="content">
			<h1>CURRICULUM VITAE</h1>
			<xsl:apply-templates select="coordonnees"/>
			<div class="cv">
				<xsl:apply-templates select="titre"/>
			</div>
			<div class="bottom">
				<a href="xml/cv-utf8.xml">Source XML</a> - <a href="xml/cv-xslt.xsl">Source XSL</a>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="coordonnees">
		<div class="left">
			<strong><xsl:value-of select="nom"/><xsl:value-of select="$space"/><xsl:value-of select="prenom"/></strong>
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

	<xsl:template match="titre">
		<h2><xsl:value-of select="@nom"/></h2>
		<xsl:apply-templates select="ligne"/>
	</xsl:template>

	<xsl:template match="ligne">
		<div class="left"><strong><xsl:value-of select="annee"/></strong></div>
		<!-- traitement des retours ligne -->
		<div class="right">
			<xsl:call-template name="cr2br">
				<xsl:with-param name="text" select="descriptif"/>
			</xsl:call-template>
			<xsl:if test="ulink">
				<xsl:apply-templates select="ulink"/>
			</xsl:if>
		</div>
	</xsl:template>

	<xsl:template match="ulink">
		<xsl:value-of select="$space"/>
		<a>
			<xsl:attribute name="href">xml/<xsl:value-of select="@url"/></xsl:attribute>
			<xsl:value-of select="@txt"/>
		</a>
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
