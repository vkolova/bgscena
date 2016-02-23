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

<input id="address" name="city" type="hidden"/>
<?php
$city = 'София';
?>

<ol class="breadcrumb">
  <li><a href="#">Начало</a></li>
  <li><a href="#">Театри в София</a></li>
  <li class="active">Програма за <?php
	$value = (string)date("d/m/Y");
	$value = explode( '/', $value );
	echo $value[0] . ' ';
	switch ($value[1]) {
	case '01':
			echo 'януари';
			break;
	case '02':
			echo 'февруари';
			break;
	case '03':
			echo 'март';
			break;
	case '04':
			echo 'април';
			break;
	case '05':
			echo 'май';
			break;
	case '06':
			echo 'юни';
			break;
	case '07':
			echo 'юли';
			break;
	case '08':
			echo 'август';
			break;
	case '09':
			echo 'септември';
			break;
	case '10':
			echo 'октомври';
			break;
	case '11':
			echo 'ноември';
			break;
	case '12':
			echo 'декември';
			break;
	default:
			echo "Уупс! Нещо се обърка! :/ Май няма скорошни пиеси? <br/><br/>";
		}


	?> </li>
</ol>


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Днес</a></li>
    <li><a data-toggle="tab" href="#menu1">Утре</a></li>
</ul>

<div class="tab-content">
	<div id="home" class="tab-pane fade in active">
		<h3></h3>


<?php
$querystr = "
SELECT display_name, user_email
FROM $wpdb->users;
";

$users_info = $wpdb->get_results($querystr, OBJECT);
foreach (  $users_info as $user ) {
  $subject = "hello, " . $user->display_name;
  $content = "WordPress <b>knowledge<b>";
  wp_mail( $user->user_email, $subject, $content );
}




$today_date = (string)date("d/m/Y");
$querystr = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts, $wpdb->postmeta, $wpdb->terms, $wpdb->term_relationships
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
    AND $wpdb->postmeta.meta_key = '_vk_date_input'
    AND $wpdb->postmeta.meta_value LIKE " . "'%" . $today_date . "%'
		AND $wpdb->term_relationships.object_id = $wpdb->postmeta.post_id
		AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
		AND $wpdb->terms.name ='" . $city . "'";
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
	         <small class="text-muted">
						<?php
						$meta_time = get_post_meta( $post->ID, '_vk_time_input', true );
						if($meta_time)
							echo '<span class="glyphicon glyphicon-time"></span> ' . $meta_time . '<br/>';
						$taxonomyName = "product_cat";
						$parent_terms = wp_get_post_terms( $post->ID, $taxonomyName, array('parent' => 0, 'orderby' => 'slug', 'hide_empty' => false));
						echo '<span class="glyphicon glyphicon-map-marker"></span>';
						foreach ($parent_terms as $pterm) {
						    //Get the Child terms
						    $terms = wp_get_post_terms( $post->ID, $taxonomyName, array('parent' => $pterm->term_id, 'orderby' => 'slug', 'hide_empty' => false));
						    foreach ($terms as $term) {
						        echo '<a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a>';
						    }
						}
						?>
				 	</small>
					<hr/>
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
					FROM $wpdb->posts, $wpdb->postmeta, $wpdb->terms, $wpdb->term_relationships
					WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
					AND $wpdb->postmeta.meta_key = '_vk_date_input'
					AND $wpdb->postmeta.meta_value LIKE " . "'%" . $tomorrow_date . "%'
					AND $wpdb->term_relationships.object_id = $wpdb->postmeta.post_id
					AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
					AND $wpdb->terms.name'" . $city . "'";

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
				         <small class="text-muted">
									<?php
									$meta_time = get_post_meta( $post->ID, '_vk_time_input', true );
									if($meta_time)
										echo '<span class="glyphicon glyphicon-time"></span> ' . $meta_time . '<br/>';
									$taxonomyName = "product_cat";
									$parent_terms = wp_get_post_terms( $post->ID, $taxonomyName, array('parent' => 0, 'orderby' => 'slug', 'hide_empty' => false));
									echo '<span class="glyphicon glyphicon-map-marker"></span>';
									foreach ($parent_terms as $pterm) {
											//Get the Child terms
											$terms = get_terms($taxonomyName, array('parent' => $pterm->term_id, 'orderby' => 'slug', 'hide_empty' => false));
											foreach ($terms as $term) {
													echo '<a href="' . get_term_link( $term->name, $taxonomyName ) . '">' . $term->name . '</a>';
											}
									}
									?>
							 	</small>
								<hr/>
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



<?php get_sidebar( 'homish' ); ?>
<?php get_footer(); ?>
