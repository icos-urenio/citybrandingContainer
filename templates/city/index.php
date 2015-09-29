<?php

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$user            = JFactory::getUser();
$this->language  = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = $app->getTemplate(true)->params;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->get('sitename');
$sitetitle = $this->params->get('sitetitle');
$sitedescription = $this->params->get('sitedescription');
$sitelandingpage = $this->params->get('sitelandingpage');
$sitebuttonspecialtitle = $this->params->get('sitebuttonspecialtitle');
$sitebuttonspeciallink = $this->params->get('sitebuttonspeciallink');
$sitearrow = $this->params->get('sitearrow');
?>

<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/ie/html5shiv.js'; ?>"></script><![endif]-->
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/css/main.css';?>" />
		<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/css/ie8.css';?>" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/css/ie9.css';?>" /><![endif]-->
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/css/custom.css';?>" />
		<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/jquery.min.js';?>"></script>
		<jdoc:include type="head" />
	</head>
	<body <?php echo $sitelandingpage ? 'class="landing"' : ''  ?>>

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header" <?php echo $sitelandingpage ? 'class="alt"' : ''  ?>>
						<?php if ($this->countModules('language')) : ?>
							<jdoc:include type="modules" name="language" style="" />
						<?php endif; ?>
						<h1><a href="index.php"><?php echo $sitetitle; ?></a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>Menu</span></a>
									<?php if ($this->countModules('menu')) : ?>							
									<div id="menu">
										<jdoc:include type="modules" name="menu" style="" />
									</div>
									<?php endif; ?>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Banner -->
				<?php if($sitelandingpage) : ?>
					<section id="banner">
						<div class="inner">
							<h2><?php echo $sitetitle; ?></h2>
							<p><?php echo $sitedescription; ?></p>
							<ul class="actions">
								<li><a href="<?php echo $sitebuttonspeciallink; ?>" class="button special"><?php echo $sitebuttonspecialtitle; ?></a></li>
							</ul>
						</div>
						<a href="#one" class="more scrolly"><?php echo $sitearrow; ?></a>
					</section>
					<div id="one"></div>
				<?php endif; ?>

				<?php if ($this->countModules('aside')) : ?>
					<aside id="cb" class="style5">
						<jdoc:include type="modules" name="aside" style="no" />					
					</aside>
				<?php endif; ?>
				
				<?php if ($this->countModules('superheader')) : ?>
					<jdoc:include type="modules" name="superheader" style="superheader" />
				<?php endif; ?>

				<?php if ($this->countModules('top')) : ?>
					
					<div id="top" class="wrapper style9 <?php echo $this->countModules('aside') ? 'alt' : ''  ?>">	
						<jdoc:include type="modules" name="top" style="no" />
					</div>
				<?php endif; ?>

				<?php if(!$sitelandingpage) : ?>
					
					<article id="main" class="<?php echo $this->countModules('aside') ? ' alt ' : ''  ?>
											  <?php echo $this->countModules('top') ? ' has-top ' : ''  ?>	
											 ">
						<section class="wrapper style9">	
							<div class="inner <?php echo $this->countModules('aside') ? 'alt' : ''  ?>">
								<jdoc:include type="message" />
								<jdoc:include type="component" />
							</div>
						</section>
					</article>

				<?php else : ?>
					
					<jdoc:include type="message" />
					<section id="main-content" class="wrapper alt style2">	
						<jdoc:include type="component" />
					</section> <!-- main-content -->
				
				<?php endif; ?>

				<!-- Bottom -->	
				<?php if ($this->countModules('bottom')) : ?>
					<jdoc:include type="modules" name="bottom" style="wrapper" />
				<?php endif; ?>

				<!-- CTA (Call To Action) -->
				<?php if ($this->countModules('cta')) : ?>							
					<jdoc:include type="modules" name="cta" style="cta" />
				<?php endif; ?>

				<!-- Footer -->
				<?php if ($this->countModules('footer')) : ?>							
					<footer id="footer" <?php echo $this->countModules('aside') ? 'class="alt"' : ''  ?>>
						<jdoc:include type="modules" name="footer" style="no" />
					</footer>
				<?php endif; ?>

			</div>

		<!-- Scripts -->
			
			<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/jquery.scrollex.min.js';?>"></script>
			<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/jquery.scrolly.min.js';?>"></script>
			<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/skel.min.js';?>"></script>
			<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/util.js';?>"></script>
			<!--[if lte IE 8]><script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/ie/respond.min.js';?>"></script><![endif]-->
			<script src="<?php echo $this->baseurl . '/templates/' . $this->template . '/assets/js/main.js';?>"></script>

	</body>
</html>