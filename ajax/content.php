<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/project/easy-modal/
*/
require( '../../../../wp-load.php' );
global $eModal;
$options = $eModal->getAdminOptions($_POST['modalId']);

?>
<h1 class='eM-title'><?php echo $options['title'] ?></h1>
<?php echo do_shortcode($options['content']) ?>
