<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package activello
 */
?><!doctype html>
	<!--[if !IE]>
	<html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 7 ]>
	<html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 8 ]>
	<html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 9 ]>
	<html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
	<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
						<div class="navbar-header">
							<button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<?php activello_header_menu(); // main navigation ?>

						<div class="nav-search">
							<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
								<input type="text" name="s" placeholder="<?php echo esc_attr_x( 'Search and hit enter...', 'search placeholder', 'activello' ); ?>">
								<button type="submit" class="header-search-icon" name="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'activello' ); ?>"><i class="fa fa-search"></i></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</nav><!-- .site-navigation -->

		<?php
		$show_logo = true;
		$show_title = false;
		$show_tagline = true;
		$logo = get_theme_mod('header_logo', '');
		$tagline = ( get_bloginfo( 'description' ) ) ? get_bloginfo( 'description' ) : '';
		$header_show = get_theme_mod('header_show', 'logo-text');
		
		if( $header_show == 'logo-only' ){
			$show_tagline = false;
		}
		elseif( $header_show == 'title-only' ){
			$show_tagline = $show_logo = false;
		}
		elseif( $header_show == 'title-text' ){
			$show_logo = false;
			$show_title = true;
		}?>

		<div class="container">
			<div id="logo">
				<span class="site-name"><a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php 
					if( $show_logo && $logo ) { 
                                            echo wp_get_attachment_image($logo, 'full');
					}
					elseif( $show_title ) { 
						bloginfo( 'name' );
					}
					else{
						bloginfo( 'name' );
					} ?>
					</a>
				</span><!-- end of .site-name -->
				
				<?php if( $show_tagline && get_bloginfo( 'description' ) != "" ) : ?>
					<div class="tagline"><?php bloginfo( 'description' ); ?></div>
				<?php endif; ?>
			</div><!-- end of #logo -->
			
			<?php if( ! is_front_page() || ! is_home() ) : ?>
			<div id="line"></div>
			<?php endif; ?>
		</div>
		
	</header><!-- #masthead -->

	
	<div id="content" class="site-content">

		<div class="top-section">
			<?php activello_featured_slider(); ?>
		</div>

		<div class="container main-content-area">
		
			<?php if( is_single() && has_category() ) : ?>
			<div class="cat-title">
				<?php echo get_the_category_list(); ?>
			</div>		
			<?php endif; ?>
                        <?php
                            global $post;                            
                            if( is_singular() && get_post_meta($post->ID, 'site_layout', true) ){
                                $layout_class = get_post_meta($post->ID, 'site_layout', true);
                            }
                            else{
                                $layout_class = get_theme_mod( 'activello_sidebar_position' );
                            }?>
                    
			<div class="row">
				<div class="main-content-inner <?php echo activello_main_content_bootstrap_classes(); ?> <?php echo $layout_class; ?>">