<?php
/**
 * activello functions and definitions
 *
 * @package activello
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 697; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function activello_content_width() {
  if ( is_page_template( 'page-fullwidth.php' ) ) {
    global $content_width;
    $content_width = 1008; /* pixels */
  }
}
add_action( 'template_redirect', 'activello_content_width' );

if ( ! function_exists( 'activello_main_content_bootstrap_classes' ) ) :
/**
 * Add Bootstrap classes to the main-content-area wrapper.
 */
function activello_main_content_bootstrap_classes() {
	if ( is_page_template( 'page-fullwidth.php' ) ) {
		return 'col-sm-12 col-md-12';
	}
	return 'col-sm-12 col-md-8';
}
endif; // activello_main_content_bootstrap_classes

if ( ! function_exists( 'activello_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function activello_setup() {

  /*
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   */
  load_theme_textdomain( 'activello', get_template_directory() . '/languages' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  /**
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
   */
  add_theme_support( 'post-thumbnails' );

  add_image_size( 'activello-featured', 1170, 550, true );
  add_image_size( 'activello-slider', 1920, 550, true );
  add_image_size( 'activello-thumbnail', 330, 220, true );
  add_image_size( 'activello-medium', 640, 480, true );

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus( array(
    'primary'      => esc_html__( 'Primary Menu', 'activello' )
  ) );

  // Enable support for Post Formats.
  add_theme_support( 'post-formats', array(
		'video',
		'audio',
	) );

  // Setup the WordPress core custom background feature.
  add_theme_support( 'custom-background', apply_filters( 'activello_custom_background_args', array(
    'default-color' => 'FFFFFF',
    'default-image' => '',
  ) ) );

  // Enable support for HTML5 markup.
  add_theme_support( 'html5', array(
    'comment-list',
    'search-form',
    'comment-form',
    'gallery',
    'caption',
  ) );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );

}
endif; // activello_setup
add_action( 'after_setup_theme', 'activello_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function activello_widgets_init() {
  register_sidebar( array(
    'name'          => esc_html__( 'Sidebar', 'activello' ),
    'id'            => 'sidebar-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ));

  register_widget( 'activello_social_widget' );
  register_widget( 'activello_recent_posts' );
  register_widget( 'activello_categories' );
  register_widget( 'activello_instagram_widget' );
}
add_action( 'widgets_init', 'activello_widgets_init' );


/* --------------------------------------------------------------
       Theme Widgets
-------------------------------------------------------------- */
require_once(get_template_directory() . '/inc/widgets/widget-categories.php');
require_once(get_template_directory() . '/inc/widgets/widget-social.php');
require_once(get_template_directory() . '/inc/widgets/widget-recent-posts.php');
require_once(get_template_directory() . '/inc/widgets/widget-instagram.php');

/**
 * This function removes inline styles set by WordPress gallery.
 */
function activello_remove_gallery_css( $css ) {
  return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

add_filter( 'gallery_style', 'activello_remove_gallery_css' );

/**
 * Enqueue scripts and styles.
 */
function activello_scripts() {

	wp_register_script( 'vk-js-ui', get_template_directory_uri() . '/inc/js/jquery-ui.js');
	wp_register_script( 'vk-calendar', get_template_directory_uri() . '/inc/js/calendar.js');

  // Add Bootstrap default CSS
  wp_enqueue_style( 'activello-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css' );

  // Add Font Awesome stylesheet
  wp_enqueue_style( 'activello-icons', get_template_directory_uri().'/inc/css/font-awesome.min.css' );
	wp_enqueue_style( 'vk-styles', get_template_directory_uri().'/inc/css/vk-css.css' );

  // Add Google Fonts
  wp_enqueue_style( 'activello-fonts', '//fonts.googleapis.com/css?family=Lora:400,400italic,700,700italic|Montserrat:400,700|Maven+Pro:400,700');

  // Add slider CSS only if is front page ans slider is enabled
  if( ( is_home() || is_front_page() ) && get_theme_mod('activello_featured_hide') == 1 ) {
    wp_enqueue_style( 'flexslider-css', get_template_directory_uri().'/inc/css/flexslider.css' );
  }

  // Add main theme stylesheet
  wp_enqueue_style( 'activello-style', get_stylesheet_uri() );

  // Add Modernizr for better HTML5 and CSS3 support
  wp_enqueue_script('activello-modernizr', get_template_directory_uri().'/inc/js/modernizr.min.js', array('jquery') );

  // Add Bootstrap default JS
  wp_enqueue_script('activello-bootstrapjs', get_template_directory_uri().'/inc/js/bootstrap.min.js', array('jquery') );

  // Add slider JS only if is front page ans slider is enabled
  if( ( is_home() || is_front_page() ) && get_theme_mod('activello_featured_hide') == 1 ) {
    wp_register_script( 'flexslider-js', get_template_directory_uri() . '/inc/js/flexslider.min.js', array('jquery'), '20140222', true );
  }

  // Main theme related functions
  wp_enqueue_script( 'activello-functions', get_template_directory_uri() . '/inc/js/functions.min.js', array('jquery') );

  // This one is for accessibility
  wp_enqueue_script( 'activello-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.js', array(), '20140222', true );

  // Add instafeed/instagram
  if( is_active_widget( false, false, 'activello-instagram', true ) ){
    wp_enqueue_script('activello-instafeedjs', get_template_directory_uri().'/inc/js/instafeed.min.js', array('jquery') );
  }

  // Threaded comments
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'activello_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';

/**
 * Load custom metabox
 */
require get_template_directory() . '/inc/metaboxes.php';

/**
 * Social Nav Menu
 */
require get_template_directory() . '/inc/socialnav.php';

/* Globals */
global $site_layout, $header_show;
$site_layout = array('pull-right' =>  esc_html__('Left Sidebar','activello'), 'side-right' => esc_html__('Right Sidebar','activello'), 'no-sidebar' => esc_html__('No Sidebar','activello'),'full-width' => esc_html__('Full Width', 'activello'));
$header_show = array(
                        'logo-only' => __('Logo Only', 'travelify'),
                        'logo-text' => __('Logo + Tagline', 'travelify'),
                        'title-only' => __('Title Only', 'travelify'),
                        'title-text' => __('Title + Tagline', 'travelify')
                      );

/* Get Single Post Category */
function get_single_category($post_id){

    if( !$post_id )
        return '';

    $post_categories = wp_get_post_categories( $post_id );

    if( !empty( $post_categories ) ){
        return wp_list_categories('echo=0&title_li=&show_count=0&include='.$post_categories[0]);
    }
    return '';
}

// Change what's hidden by default
add_filter('default_hidden_meta_boxes', 'be_hidden_meta_boxes', 10, 2);
function be_hidden_meta_boxes($hidden, $screen) {
    if ( 'post' == $screen->base || 'page' == $screen->base ) {
        // removed 'postexcerpt',
        $hidden = array(
            'slugdiv',
            'trackbacksdiv',
            'postcustom',
            'commentstatusdiv',
            'commentsdiv',
            'authordiv',
            'revisionsdiv'
        );
    }
    return $hidden;
}

if ( ! function_exists( 'activello_woo_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function activello_woo_setup() {
	/*
	 * Enable support for WooCemmerce.
	*/
	add_theme_support( 'woocommerce' );

}
endif; // activello_woo_setup
add_action( 'after_setup_theme', 'activello_woo_setup' );


function vk_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'vk_search_join' );

function vk_search_where( $where ) {
    global $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'vk_search_where' );


function vk_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'vk_search_distinct' );


function vk_add_custom_types( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'nav_menu_item', 'product'
		));
	  return $query;
	}
}
add_filter( 'pre_get_posts', 'vk_add_custom_types' );


function vk_display_plays( $date, $city) {
	global $wpdb;
	$querystr = "
	    SELECT $wpdb->posts.*
	    FROM $wpdb->posts, $wpdb->postmeta, $wpdb->terms, $wpdb->term_relationships
	    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
	    AND $wpdb->postmeta.meta_key = '_vk_date_input'
	    AND $wpdb->postmeta.meta_value LIKE '%" . $date . "%'
					AND $wpdb->term_relationships.object_id = $wpdb->postmeta.post_id
					AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
					AND $wpdb->terms.name ='" . $city . "'";

			 $pageposts = $wpdb->get_results($querystr, OBJECT);
				?>
		</br>
	<?php
	$count = 1;
	 if ($pageposts): ?>
	 <?php global $post;?>
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
     <a href="<?php the_permalink() ?>" >
						<img class="img-top" src="<?php echo $img_src; ?>" width="300" height="300" alt="<?php the_title(); ?>">
					</a>
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
					$count++; ?>
	 <?php endforeach;
	 if(count($pageposts) % 2 == 0 || count($pageposts) == 1) {
	 	echo "</div>";
	 }
	  ?>
	 <?php else : ?>
	    <h2 class="center">Няма резултати</h2>
	    <p class="center">Съжалявам, търсите нещо, което не е тук.</p>
	    <?php include (TEMPLATEPATH . "/searchform.php"); ?>
	 <?php endif;
}

function vk_process_date( $value ) {
	$date = str_replace('/', '-', $value);
	$time = strtotime( $date );
	$timestamp = date( $time);
	$dw = date( "w", $timestamp);
	$day_of_week = array( 'неделя', 'понеделник', 'вторник', 'сряда', 'четвъртък', 'петък', 'събота');
	echo $day_of_week[ $dw ] . ', ';
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
			echo "Упс! Нещо се обърка!<br/><br/>";
	}

}

function vk_latin_cyrillic( $textlat ) {
	if ($textlat = 'Sofia') {
		return 'София';
	} else {
		$cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у',
		'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
		'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я' );
		$lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
		'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya', 'A','B','V','G','D','E','Zh',
		'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
		'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya');
		$textlat = str_replace($lat, $cyr, $textlat);
		return $textlat;
	}
}
