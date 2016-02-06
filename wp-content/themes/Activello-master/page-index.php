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
    <?php
      // Checks if this is homepage to enable homepage widgets
      if ( is_front_page() ) :
        get_sidebar( 'home' );
      endif;
    ?>
	</div><!-- .entry-content -->
	</article><!-- #post-## -->
</div>


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Днес</a></li>
    <li><a data-toggle="tab" href="#menu1">Утре</a></li>
</ul>

<div class="tab-content">
	<div id="home" class="tab-pane fade in active">
		<h3></h3>
<?php
$today_date = (string)date("d/m/Y");
$querystr = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
    AND $wpdb->postmeta.meta_key = '_vk_date_input'
    AND $wpdb->postmeta.meta_value LIKE " . "'%" . $today_date . "%'";
echo $querystr;

 $pageposts = $wpdb->get_results($querystr, OBJECT);
?>


<?php $count = 1;
 if ($pageposts): ?>
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
	 <?php
		 $html = woocommerce_get_product_thumbnail( 'shop_catalog' );
		 $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
		 $img_src = $xpath->evaluate("string(//img/@src)");
	 ?>
	 <?php  if ($count == 1) {
	 			echo '<div class="row">';
	 		}
	 		?>
	 <div class="col-xs-6 col-md-4">
	       <a href="<?php the_permalink() ?>" ><img class="img-top" src="<?php echo $img_src; ?>" width="300" height="300" alt="<?php the_title(); ?>"></a>
	         <h4 class="title"><a href="<?php the_permalink() ?>" ><?php the_title(); ?></a></h4>
	         <p class="text"><?php echo substr($post->post_content, 0, 140); ?></p>
	         <p class="text"><small class="text-muted">
						<?php echo get_the_term_list( $id, 'product_cat', '<span class="map-icon">g</span>    ', ', ', '' ); ?>
				 	</small></p>
	</div>
	<?php  if ($count == 3) {
				 	echo '</div>';
					$count = 0;}
				$count++;
					?>
 <?php endforeach;

 if(count($pageposts) % 2 == 0 || count($pageposts) == 1) {
 	echo "</div>";
 }
  ?>
 <?php else : ?>
    <h2 class="center">Няма резултати</h2>
    <p class="center">Съжалявам, търсите нещо, което не е тук.</p>
    <?php include (TEMPLATEPATH . "/searchform.php"); ?>
 <?php endif; ?>
</div>


<!-- TOMORROW -->
<div id="menu1" class="tab-pane fade">
      <h3></h3>
			<?php
			$tomorrow_date = (string)date("d/m/Y", time()+86400);
			$querystr = "
			    SELECT $wpdb->posts.*
			    FROM $wpdb->posts, $wpdb->postmeta
			    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
			    AND $wpdb->postmeta.meta_key = '_vk_date_input'
			    AND $wpdb->postmeta.meta_value LIKE " . "'%" . $tomorrow_date . "%'";

			 $pageposts = $wpdb->get_results($querystr, OBJECT);
			?>
			<?php
			$count = 1;
			 if ($pageposts): ?>
			 <?php global $post; ?>
			 <?php foreach ($pageposts as $post): ?>
				 <?php
					 $html = woocommerce_get_product_thumbnail( 'shop_catalog' );
					 $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
					 $img_src = $xpath->evaluate("string(//img/@src)");
				 ?>
				 <?php  if ($count == 1) {
				 			echo '<div class="row">';
				 		}
				 		?>
				 <div class="col-xs-6 col-md-4">
				       <a href="<?php the_permalink() ?>" ><img class="img-top" src="<?php echo $img_src; ?>" width="300" height="300" alt="<?php the_title(); ?>"></А>
				         <h4 class="title"><a href="<?php the_permalink() ?>" ><?php the_title(); ?></a></h4>
				         <p class="text"><?php echo substr($post->post_content, 0, 140); ?></p>
				         <p class="text"><small class="text-muted">
									<?php echo get_the_term_list( $id, 'product_cat', '', ', ', '' ); ?>
							 	</small></p>
				</div>
				<?php  if ($count == 3) {
							 	echo '</div>';
								$count = 0;}
											$count++;
								?>
			 <?php endforeach;
			 if(count($pageposts) % 2 == 0 || count($pageposts) == 1) {
			 	echo "</div>";
			 }
 ?>
			 <?php else : ?>
			    <h2 class="center">Няма резултати</h2>
			    <p class="center">Съжалявам, търсите нещо, което не е тук.</p>
			    <?php include (TEMPLATEPATH . "/searchform.php"); ?>
			 <?php endif; ?>
			</div>
</div>



<?php get_sidebar(); ?>
<?php get_footer(); ?>
