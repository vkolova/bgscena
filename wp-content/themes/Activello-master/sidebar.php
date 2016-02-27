<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package activello
 */
?>
</div>

	<?php
        $show_sidebar = true;
        if( is_singular() && ( get_post_meta($post->ID, 'site_layout', true) ) ){
           if( get_post_meta($post->ID, 'site_layout', true) == 'no-sidebar' || get_post_meta($post->ID, 'site_layout', true) == 'full-width' ) {
               $show_sidebar = false;
           }
        }
        elseif( get_theme_mod( 'activello_sidebar_position' ) == "no-sidebar" ||  get_theme_mod( 'activello_sidebar_position' ) == "full-width" ) {
            $show_sidebar = false;
        } ?>
        <?php if( $show_sidebar ): ?>
	<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
		<div class="inner">
			<?php do_action( 'before_sidebar' ); ?>
			<?php if ( ! dynamic_sidebar( 'homish' ) ) : ?>

			<?php	wp_enqueue_script('vk-js-ui');
				wp_enqueue_script('vk-calendar');
				?>
				<h3></h3>

				<aside id="my_calendar" class="widget">
						<div id="calendar"></div>
				</aside>

				<h3></h3>
				<aside id="lates_comments" class="widget">
						<h3 class="widget-title"><?php echo "Последни коментари"; ?></h3>
				<?php
						$number = 5; // number of recent comments desired
						$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $number");
				?>
				<ul>
						<?php
						if ( $comments ) : foreach ( (array) $comments as $comment) :
						echo  '<li class="recentcomments">' . sprintf(__('%1$s по %2$s'), get_comment_author_link(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
						endforeach; endif; ?>
				</ul>

				<h3></h3>

								<aside id="digest" class="widget">
				<!-- Begin MailChimp Signup Form -->

						<h3 class="widget-title">Абонирайте се за нашия бюлетин</h3>
			<link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet" type="text/css">
			<style type="text/css">	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }</style>
			<div id="mc_embed_signup">
			<form action="//byethost13.us12.list-manage.com/subscribe/post?u=5553e50301d77b7c5d47aa287&amp;id=257f5d9ce8" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<div id="mc_embed_signup_scroll">
			<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="мейл адрес" required>
				<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_5553e50301d77b7c5d47aa287_257f5d9ce8" tabindex="-1" value=""></div>
				<div class="clear"><input type="submit" value="Запис" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</div>
			</form>
			</div>

			<!--End mc_embed_signup-->
			</aside>

				<h3></h3>

				<aside id="archives" class="widget">
						<h3 class="widget-title"><?php esc_html_e( 'Archives', 'activello' ); ?></h3>
						<ul>
								<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
						</ul>
				</aside>

				<aside id="meta" class="widget">
						<h3 class="widget-title"><?php esc_html_e( 'Meta', 'activello' ); ?></h3>
						<ul>
								<?php wp_register(); ?>
								<li><?php wp_loginout(); ?></li>
								<?php wp_meta(); ?>
						</ul>
				</aside>



			<?php endif; // end sidebar widget area ?>
		</div>
	</div><!-- #secondary -->
	<?php endif; ?>
