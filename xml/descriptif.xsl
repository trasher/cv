<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">
<xsl:output method="xml" doctype-public="-//W3C//DTD XHTML 1.1//EN" doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd" encoding="UTF-8" indent="yes" omit-xml-declaration="yes"  />

<xsl:param name="space" select="' '"/>
<xsl:variable name="from"> '!@#$%^*(),:;.?/\[]{}|=+-_*"&amp;&gt;&lt;&#171;&#187;</xsl:variable>
<xsl:variable name="replace">__________________________________</xsl:variable>
<xsl:param name="ip"/>
<xsl:param name="css"/>

<xsl:template match="/">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
		<head>
			<title>...::: Johan Cwiklinski - Curriculum Vitae :::...</title>
			<meta name="Author" content="Johan CWIKLINSKI"/>
			<meta name="description" content="Johan Cwiklinski : Curriculum Vitae et présentation des réalisations et savoir-faire"/>
			<meta name="keywords" content="johan, cwiklinski, curriculum vitae, réalisation de sites web, programmation, webmaster, webmestre, java, php, linux, redhat, réseau, logiciels open source, trasher"/>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
			<!-- dublin core metas -->
			<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/"/>
			<meta name="DC.Publisher" content="Johan Cwiklinski - X-TnD"/>
			<meta name="DC.Language" scheme="RFC3066" content="fr"/>
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
		</head>
		<body>
			<div id="content">
				<div id="header">
					<xsl:if test="$css='black'">
					<object type="application/x-shockwave-flash" data="images/trashyland.swf" width="300" height="130">
						<param name="movie" value="images/trashyland.swf" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#000000" />
						<img src="images/trashyland.jpg" alt="TrashyLand - Partie personelle de Trasher"/>
					</object>
					</xsl:if>
					<xsl:value-of select="$space"/>
				</div>
				<ul id="styleswitcher">
					<li>Sélectionnez un style : </li>
					<li><a href="?css=blue" title="Style 'Bleu' (par défaut)">blue</a></li>
					<li><a href="?css=black" title="Style 'Noir' (ancien)">black</a></li>
					<li><a href="?css=nostyle" title="Pas de style. Affiche le XHTML sans appliquer de style">no style</a></li>
				</ul>
				<xsl:call-template name="summary"></xsl:call-template>
				<xsl:apply-templates select="//presentation" />
				<xsl:apply-templates select="descriptif" />
			</div>
			<div id="w3c">
				<ul>
					<li><a href="http://www.spreadfirefox.com/?q=affiliates&amp;id=116935&amp;t=85"><img alt="Get Firefox!" title="Get Firefox !" src="http://sfx-images.mozilla.org/affiliates/Buttons/80x15/firefox_80x15.png"/></a><span>&#160;;</span></li>
					<li><a href="http://www.spreadfirefox.com/?q=affiliates&amp;id=116935&amp;t=179"><img alt="Get Thunderbird!" title="Get Thunderbird !" src="http://sfx-images.mozilla.org/affiliates/thunderbird/thunderbird_blog2.png"/></a><span>.</span></li>
				</ul>
				<ul>
					<li>
						<a href="http://www.php.net" title="Réalisé en PHP5">
							<img src="http://ulysses.fr/icons/php5-power-grey.png" alt="Powered by PHP5"/>
						</a>
						<span>&#160;;</span>
					</li>
					<li>
						<a href="http://validator.w3.org/check/referer" title="Validation XHTML 1.1">
							<img alt="Valide XHTML 1.1" src="http://ulysses.fr/icons/w3c-xhtml1.1-grey.png" />
						</a>
						<span>&#160;;</span>
					</li>
					<li>
						<a href="http://jigsaw.w3.org/css-validator/check/referer" title="Validation CSS 2.0">
							<img alt="Valide CSS 2.0" src="http://ulysses.fr/icons/w3c-css2.0-grey.png" />
						</a>
						<span>&#160;;</span>
					</li>
					<li>
						<a href="http://www.w3.org/WAI/WCAG1AA-Conformance" title="Exlications sur la conformité de niveau Double-A">
							<img src="http://ulysses.fr/icons/w3c-wai-aa-grey.png" alt="Level Double-A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0" />
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
			<xsl:value-of select="$space"/><span>(<xsl:value-of select="@tech"/>)</span>
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
