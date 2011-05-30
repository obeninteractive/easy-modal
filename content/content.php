<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/project/easy-modal/
*/

    require( '../../../../wp-load.php' );
	global $eM;
	$options = $eM->getAdminOptions();
	
?>
<div>
	<div class='eM-content'>
		<h1 class='eM-title'><?php echo $options['title'] ?></h1>

		<?php echo do_shortcode($options['content']) ?>
	</div>
</div>
<?php if(strstr($options['content'],'[contact-form')!= NULL){ ?>
<?php } ?>