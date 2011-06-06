<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/project/easy-modal/
* v 0.9.0.4
*/
require( '../../../../wp-load.php' );
global $eM;
$options = $eM->getAdminOptions();
?>
<div class='eM-content'>
    <h1 class='eM-title'><?php echo $options['title'] ?></h1>
    <?php echo do_shortcode($options['content']) ?>
</div>