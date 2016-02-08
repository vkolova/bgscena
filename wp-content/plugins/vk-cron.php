<?php

$querystr = "
SELECT display_name, user_email
FROM $wpdb->users;
";

$users_info = $wpdb->get_results($querystr, OBJECT);
/*
$to = "bgscena@bgscena.byethost13.com";
$subject = "hello, ";
$content = "WordPress <b>knowledge<b>";
wp_mail( $to, $subject, $content );
*/

foreach (  $users_info as $user ) {
  $subject = "hello, " . $user->display_name;
  $content = "WordPress <b>knowledge<b>";
  wp_mail( $user->user_email, $subject, $content );
}



?>
