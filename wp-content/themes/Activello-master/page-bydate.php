<?php
/**
 * The template for displaying theathres.
 */

get_header(); ?>

<?php the_post_thumbnail( 'activello-featured', array( 'class' => 'single-featured' )); ?>

<div class="post-inner-content">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'activello' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	</article><!-- #post-## -->
</div>

<input type="hidden" id="address" name="city" value="" />
<?php
	// require_once('geoplugin.class.php');
	//
	// $geoplugin = new geoPlugin();
	// $geoplugin->locate();
	// $city = $geoplugin->city;
	$city = "София";
?>

<ol class="breadcrumb">
  <li><a href="#">Начало</a></li>
  <li><a href="#">Театри в София<?php
	// echo $_POST['city'];
	 ?></a></li>
  <li class="active">
		<?php
			$value = (string)date("d/m/Y");
			vk_process_date( $value );
		?></li>
</ol>


<ul class="nav nav-tabs">
    <li><a data-toggle="tab" href="#home">Днес</a></li>
    <li><a data-toggle="tab" href="#tomorrow">Утре</a></li>
				<li class="active"><a data-toggle="tab" href="#bydate"><?php $value = $_GET['date'];
				vk_process_date( $value ); ?></a></li>
</ul>

<div class="tab-content">
	<div id="home" class="tab-pane fade">
		<?php
			$today_date = (string)date("d/m/Y");
			vk_display_plays( $today_date, $city );
		?>
	</div>


	<!-- TOMORROW -->
	<div id="tomorrow" class="tab-pane fade">
		<?php
			$tomorrow_date = (string)date("d/m/Y", time()+86400);
			vk_display_plays( $tomorrow_date, $city );
		?>
	</div>

	<div id="bydate" class="tab-pane fade in active">
		<?php
			$s_date = $_GET['date'];
			vk_display_plays( $s_date, $city );
		?>
	</div>
</div>



<?php get_sidebar( 'homish' ); ?>
<?php get_footer(); ?>
