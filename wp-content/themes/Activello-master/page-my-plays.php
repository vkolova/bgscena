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

		 $ratings = $wpdb->get_var( $wpdb->prepare("
			 SELECT SUM(meta_value) FROM $wpdb->commentmeta
			 LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			 WHERE meta_key = 'rating'
			 AND comment_post_ID = %d
			 AND comment_approved = '1'
			 AND meta_value > 0
		 ", $post->ID ) );

		 $counts = array();

		 $raw_counts = $wpdb->get_results( $wpdb->prepare("
			 SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			 LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			 WHERE meta_key = 'rating'
			 AND comment_post_ID = %d
			 AND comment_approved = '1'
			 AND meta_value > 0
			 GROUP BY meta_value
		 ", $post->ID ) );
		 //print_r( $raw_counts);

		 foreach ( $raw_counts as $count ) {
			 $counts[ $count->meta_value ] = $count->meta_value_count;
		 }

		 $count = count($counts);
			if ($count) {
				$average_rating = number_format( $ratings / $count, 2, '.', '' );
			} else {
				$average_rating = '0.00';
			}
		 ?>
		<div class="row">
			<div class="col-md-2">
				<a href="<?php echo the_permalink(); ?>" ><img class="media-object" src="<?php echo $img_src; ?>" width="95" height="95" alt="<?php the_title(); ?>"></a>
			</div>
			<div class="col-md-2">
				<h3><a href="<?php echo the_permalink(); ?>" ><?php echo get_the_title(); ?></a></h3>
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

			 $ratings = $wpdb->get_var( $wpdb->prepare("
				 SELECT SUM(meta_value) FROM $wpdb->commentmeta
				 LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				 WHERE meta_key = 'rating'
				 AND comment_post_ID = %d
				 AND comment_approved = '1'
				 AND meta_value > 0
			 ", $post->ID ) );

			 $counts = array();

			 $raw_counts = $wpdb->get_results( $wpdb->prepare("
				 SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
				 LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				 WHERE meta_key = 'rating'
				 AND comment_post_ID = %d
				 AND comment_approved = '1'
				 AND meta_value > 0
				 GROUP BY meta_value
			 ", $post->ID ) );
			 //print_r( $raw_counts);

			 foreach ( $raw_counts as $count ) {
				 $counts[ $count->meta_value ] = $count->meta_value_count;
			 }

			 $count = count($counts);
				if ($count) {
					$average_rating = number_format( $ratings / $count, 2, '.', '' );
				} else {
					$average_rating = '0.00';
				}
			 ?>
			<div class="row">
				<div class="col-md-2">
					<a href="<?php echo the_permalink(); ?>" ><img class="img" src="<?php echo $img_src; ?>" width="95" height="95" alt="<?php the_title(); ?>"></a>
				</div>
				<div class="col-md-2">
					<h3><a href="<?php echo the_permalink(); ?>" ><?php echo get_the_title(); ?></a></h3>
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

</div>

<?php	get_sidebar( 'navpills' ); ?>
<?php get_footer(); ?>
