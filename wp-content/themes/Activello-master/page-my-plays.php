<?php get_header(); ?>

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


<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

$querystr = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts, {$wpdb->prefix}vk_user_play_status
    WHERE $wpdb->posts.ID = {$wpdb->prefix}vk_user_play_status.play_id
    AND {$wpdb->prefix}vk_user_play_status.user_id = " . $user_id . "
    AND {$wpdb->prefix}vk_user_play_status.status_value = 0";
 $pageposts = $wpdb->get_results($querystr, OBJECT);
?>

<div class="tab-content">

 <h3><?php echo 'Пиесите на ' . $current_user->display_name; ?></h3>
	<div id="plays0" class="tab-pane fade in active">

<?php if ($pageposts): ?>
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
	 <?php
		 $html = woocommerce_get_product_thumbnail( 'shop_catalog' );
		 $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
		 $img_src = $xpath->evaluate("string(//img/@src)");


		 ?>
		<div class="row">
			<div class="col-md-2">
				<a href="<?php echo the_permalink(); ?>" ><img class="media-object" src="<?php echo $img_src; ?>" width="95" height="95" alt="<?php the_title(); ?>"></a>
			</div>
			<div class="col-md-3">
				<h4><a href="<?php echo the_permalink(); ?>" ><?php echo get_the_title(); ?></a></h4>
			</div>
			<div class="col-md-2">
				<?php echo get_the_term_list( $id, 'product_cat', '<span class="glyphicon glyphicon-map-marker"> </span> ', ', ', '' ); ?></div>
			</div>
			<div class="col-md-1"><?php echo $average_rating; ?>
			</div>
			<hr>

 <?php endforeach; ?>
 <?php else : ?>
    <h2 class="center">Няма резултати</h2>
    <p class="center">Съжалявам, търсите нещо, което не е тук.</p>
    <?php include (TEMPLATEPATH . "/searchform.php"); ?>
 <?php endif; ?>
</div>


<?php
$querystr = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts, {$wpdb->prefix}vk_user_play_status
    WHERE $wpdb->posts.ID = {$wpdb->prefix}vk_user_play_status.play_id
    AND {$wpdb->prefix}vk_user_play_status.user_id = " . $user_id . "
    AND {$wpdb->prefix}vk_user_play_status.status_value = 1";
 $pageposts = $wpdb->get_results($querystr, OBJECT);
?>

<div id="plays1" class="tab-pane fade">
	<?php if ($pageposts): ?>
	 <?php global $post; ?>
	 <?php foreach ($pageposts as $post): ?>
		 <?php
			 $html = woocommerce_get_product_thumbnail( 'shop_catalog' );
			 $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
			 $img_src = $xpath->evaluate("string(//img/@src)");
			 ?>
			<div class="row">
				<div class="col-md-2">
					<a href="<?php echo the_permalink(); ?>" ><img class="img" src="<?php echo $img_src; ?>" width="95" height="95" alt="<?php the_title(); ?>"></a>
				</div>
				<div class="col-md-3">
					<h4><a href="<?php echo the_permalink(); ?>" ><?php echo get_the_title(); ?></a></h4>
				</div>
				<div class="col-md-3">
					<?php echo get_the_term_list( $id, 'product_cat', '<span class="glyphicon glyphicon-map-marker"> </span> ', ', ', '' ); ?></div>
				</div>
				<div class="col-md-1"><?php echo $average_rating; ?>
				</div>
				<hr>

	 <?php endforeach; ?>
	 <?php else : ?>
	    <h2 class="center">Няма резултати</h2>
	    <p class="center">Съжалявам, търсите нещо, което не е тук.</p>
	    <?php include (TEMPLATEPATH . "/searchform.php"); ?>
	 <?php endif; ?>
</div>

</div>

<?php	get_sidebar( 'navpills' ); ?>
<?php get_footer(); ?>
