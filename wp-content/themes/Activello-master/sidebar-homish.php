</div>
<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
  <div class="inner">
    <?php do_action( 'before_sidebar' ); ?>

    <h3></h3>
    <aside id="lates_comments" class="widget">
      <h3 class="widget-title"><?php echo "Последни коментари"; ?></h3>
    <?php
      $number=5; // number of recent comments desired
      $comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_approved = '1' ORDER BY comment_date_gmt DESC LIMIT $number");
    ?>
    <ul>
      <?php
      if ( $comments ) : foreach ( (array) $comments as $comment) :
      echo  '<li class="recentcomments">' . sprintf(__('%1$s по %2$s'), get_comment_author_link(), '<a href="'. get_comment_link($comment->comment_ID) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
      endforeach; endif; ?>
    </ul>

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


  </div>
<div>
