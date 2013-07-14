<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/plugins/easy-modal/
*/
require( '../../../../../wp-load.php' );
global $EM;
$options = $EM->getModalSettings($_POST['modalId']);

if($_POST['modalId'] == 'Link'){?>
    <iframe src="<?php echo $_POST['url']?>" height="<?php echo $_POST['iframeHeight']?>" width="<?php echo $_POST['iframeWidth']?>" style="background:#fff"  >
        <p>Your browser does not support iframes.</p>
    </iframe><?php
} else {?>
    <h1 id='eModal-Title'><?php echo $options['title'] ?></h1>
	<?php echo do_shortcode($options['content']);
}?>