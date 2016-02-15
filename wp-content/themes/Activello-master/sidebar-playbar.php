</div>
<?php
$user_id = get_current_user_id();
$post_id = $wp_query->post->ID;

function get_friends_who_list($user_id, $play_id, $friends, $value) {
  global $wpdb;
  $querystr = "
  SELECT user_id
  FROM {$wpdb->prefix}vk_user_play_status
  WHERE play_id = $play_id
  AND user_id != $user_id
  AND status_value = $value
  ";
  $result = $wpdb->get_results($querystr, OBJECT_K);
  // echo $querystr . "<br/><br/>";
  // echo "<br/><br/>+++";
  // print_r($result);

//  $friends_list = array_merge($friends, $result);
  $frs = array();
  foreach ($result as $obj) {
    // if(($obj->friend_user_id == $user_id) && ($obj->initiator_user_id != $obj->user_id)) {
    //   array_push($friends, $obj->initiator_user_id);
    // } else {
    //  array_push($friends, $obj->friend_user_id);
    // }
    // echo "--";
    // print_r($friends);
    //
    // echo "--";
    foreach ($friends as $frie) {
      if (in_array($obj->user_id, $frie)) {
       array_push($frs, $obj->user_id);
      }
    }

  }
  // echo "....";
  // print_r($frs);
  return array_unique($frs);

}

?>
<div id="secondary" class="widget-area col-sm-12 col-md-4" role="complementary">
  <div class="inner">
    <?php do_action( 'before_sidebar' );
    wp_enqueue_script('vk-js-ui');
    wp_enqueue_script('vk-calendar');
    ?>
    <h3></h3>
    <aside id="invitation" class="widget">
      <h3 class="widget-title">Покани приятел</h3>
      <form action="" method="post">
      От:<br/><input type="text" name="name"><br>
      До:<br/><input type="text" name="email"><br>
      Съобщение:<br/><textarea rows="5" name="message" cols="30"></textarea><br/><br/>
      <input type="submit" name="submit" value="Изпрати">
      </form>

      <?php
      if(isset($_POST['submit'])){
        $to = $_POST['email']; // this is your Email address
        $from = $user_info->email; // this is the sender's Email address
        $name = $_POST['name'];
        $subject = "Покана за театър";
        $message = $name . "\n\n" . $_POST['message'];

        $headers = "От:" . $from;
        wp_mail($to, $subject, $message, $headers);

        $subject = "Покана за театър от " . $name;
        $content = "WordPress <b>knowledge<b>";
        wp_mail( $to, $subject, $content );
        echo "Съобщението е изпратено";
        }
      ?>
    </aside>


    <?php
    $querystr = "
    SELECT friend_user_id, initiator_user_id
      FROM {$wpdb->prefix}bp_friends
      WHERE friend_user_id = $user_id OR initiator_user_id = $user_id
    ";
    $friends_list = $wpdb->get_results($querystr, ARRAY_N);
    // echo "</br><br/>" . $querystr . "-----";
    //  echo "--::";
    //  print_r($friends_list);

    $friends_watched = get_friends_who_list($user_id, $post_id, $friends_list, 1);
    $friends_want_to = get_friends_who_list($user_id, $post_id, $friends_list, 0);

    if ($friends_watched) {
      echo '<aside id="friends-who-watched" class="widget">';
      echo '<h3 class="widget-title">Приятели, които са гледали пиесата</h3>';
      foreach ($friends_watched as $id) {
        $html = get_avatar($id, 50);
        $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
        $img_src = $xpath->evaluate("string(//img/@src)");
        $user_info = get_userdata($id);
        echo '<img title="' . $user_info->display_name . '" src="' . $img_src . '" /> ' ;
      }
      echo '</aside>';
    }

    if($friends_want_to) {
      echo '<aside id="friends-who-want-to" class="widget">';
      echo '<h3 class="widget-title">Приятели, които искат да гледат пиесата</h3>';
      foreach ($friends_want_to as $id) {
        $html = get_avatar($id, 50);
        $xpath = new DOMXPath(@DOMDocument::loadHTML($html));
        $img_src = $xpath->evaluate("string(//img/@src)");
        $user_info = get_userdata($id);
        echo '<img title="' . $user_info->display_name . '" src="' . $img_src . '" /> ' ;
      }
      echo '</aside>';
    }
    ?>

    <aside id="my_calendar" class="widget">
      <div id="calendar"></div>
    </aside>




  </div>
<div>
