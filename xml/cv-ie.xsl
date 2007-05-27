<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/TR/WD-xsl">
<xsl:template match="/">
	<html>
		<head>
			<title>X-TND ...::: Johan C. - Curriculum Vitae :::...</title>
			<meta name="Author" content="Johan CWIKLINSKI"/>
			<meta name="keywords" content="curriculum, vitae, johan, cwiklinski, programmeur, webmaster, webmestre, java, php, linux, redhat, red, hat, reseau, réseau, réseaux, resaux, windows, lan, securite, sécurité, logiciel, open, source, programmation, trasher"/>
			<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
			<link rel="shortcut icon" href="http://x-tnd.be/favicon.ico"/>
			<link type="text/css" rel="stylesheet"  href="templates/cv.css"/>
		</head>
	<body>
	<xsl:apply-templates select="cv"/>
	</body>
	</html>
</xsl:template>


	<xsl:template match="cv">
		<h1>CURRICULUM VITAE</h1>
		<table class="cv">
			<xsl:apply-templates select="coordonnees"/>
		</table>
		<table class="cv">
			<xsl:apply-templates select="titre"/>
		</table>
		<div class="bottom">
			<a href="../xml/cv.xml">Source XML</a> - <a href="../xml/cv-xslt.xsl">Source XSL</a>
		</div>
	</xsl:template>

	<xsl:template match="coordonnees">
		<tr>
			<td style="width:50%;">
				<strong><xsl:value-of select="nom"/> <xsl:value-of select="prenom"/></strong>
				<br/><xsl:value-of select="adresse"/>
				<br/><strong><xsl:value-of select="cp"/> <xsl:value-of select="ville"/></strong>
			</td>
			<td style="text-align:right;">
				<xsl:value-of select="tel"/>
				<br/>
				<xsl:element name = "a">
					<xsl:attribute name="href">
						<xsl:value-of select="url" />
					</xsl:attribute>
					<xsl:value-of select="url" />
				</xsl:element> - <xsl:element name = "a">
					<xsl:attribute name="href">mailto:<xsl:value-of select="mail" /></xsl:attribute>
					<xsl:value-of select="mail" />
				</xsl:element>
				<br/>
				<xsl:value-of select="comment"/>
			</td>
		</tr>
	</xsl:template>

	<xsl:template match="titre">
		<tr><th colspan="2"><xsl:value-of select="@nom"/></th></tr>
		<xsl:apply-templates select="ligne"/>
	</xsl:template>

	<xsl:template match="ligne">
		<tr valign="top">
			<td style="width:20%;"><strong><xsl:value-of select="annee"/></strong></td>
			<td>
				<!-- traitement des retours ligne -->
				<xsl:call-template name="cr2br">
					<xsl:with-param name="text" select="descriptif"/>
				</xsl:call-template>
				<xsl:if test="ulink">
					<xsl:apply-templates select="ulink"/>
				</xsl:if>

			</td>
		</tr>
	</xsl:template>

	<xsl:template match="ulink">
		<a>
			<xsl:attribute name="href"><xsl:value-of select="@url"/></xsl:attribute>
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
