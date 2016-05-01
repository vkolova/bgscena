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

  $frs = array();
  foreach ($result as $obj) {

    foreach ($friends as $frie) {
      if (in_array($obj->user_id, $frie)) {
       array_push($frs, $obj->user_id);
      }
    }

  }

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
        $to = $_POST['email'];
        $name = $_POST['name'];
        $subject = "Покана за театър";
        $message = $_POST['message'];

        $subject = $name . ' Ви покани да гледате "' . get_the_title( $post->ID ) . '" заедно' ;
        $content = '<h4>' . $name . ' Ви покани да гледате "' . get_the_title( $post->ID ) . '" заедно</h4>
        <p>' . $message . '</p>'  . get_post_field("post_content", $post->ID);

        date_default_timezone_set('Etc/UTC');
        require 'PHPMailer/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        //$mail->SMTPDebug = 2;                               // Enable verbose debug output
        $mail->Debugoutput = 'html';
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'makethemwild@gmail.com';                 // SMTP username
        $mail->Password = 'vampireacademy';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('makethemwild@gmail.com', 'bgscena');
        $mail->addAddress( $to );     // Add a recipient

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $post->$content . '</br>' . $content;

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Съобщението бе изпратено.';
        }

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
