<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/project/easy-modal/
* v 0.9.0.6
*/
header("content-type: application/x-javascript");
require('../../../../wp-load.php');
$opt = get_option('easy_modalOptions');
?>
eM_overlayId = "<?php echo $opt['overlayId']?>";
eM_overlayColor = "<?php echo $opt['overlayColor']?>";
eM_opacity = "<?php echo $opt['opacity']?>";
eM_overlayClose = "<?php echo $opt['overlayClose']?>";
eM_containerId = "<?php echo $opt['containerId']?>";
eM_autoResize = "<?php echo $opt['autoResize']?>";
eM_autoPosition = "<?php echo $opt['autoPosition']?>";
eM_positionX = "<?php echo $opt['positionX']?>";
eM_positionY = "<?php echo $opt['positionY']?>";
eM_minHeight = "<?php echo $opt['minHeight']?>";
eM_maxHeight = "<?php echo $opt['maxHeight']?>";
eM_minWidth = "<?php echo $opt['minWidth']?>";
eM_maxWidth = "<?php echo $opt['maxWidth']?>";
eM_cf7form = "<?php echo $opt['cf7form']?>";
jQuery(function ($) {
	var contact = {
		message: null,
		init: function () {
			$('.eModal').click(function (e) {
				e.preventDefault();
				// load the contact form using ajax
				$.get("<?php echo get_bloginfo('wpurl')?>/wp-content/plugins/easy-modal/content/content.php", function(data){
					// create a modal dialog with the data
					$(data).modal({
						closeHTML: "<a href='#' title='Close' class='modal-close'></a>",
						position: ["15%",],
						overlayId: eM_overlayId,
						overlayCss: {backgroundColor:eM_overlayColor},
						opacity : eM_opacity,
						overlayClose: eM_overlayClose,
						containerId: eM_containerId,
						autoResize: eM_autoResize,
						autoPosition: eM_autoPosition,
						position: [eM_positionX, eM_positionY],
						minHeight: eM_minHeight,
						maxHeight: eM_maxHeight,
						minWidth: eM_minWidth,
						maxWidth: eM_maxWidth,
						onOpen: contact.open,
						onShow: contact.show,
						onClose: contact.close
					});
				});
			});
		},
		open: function (dialog) {
			// add padding to the buttons in firefox/mozilla
			if ($.browser.mozilla) {
				$('#eM-container .contact-button').css({
					'padding-bottom': '2px'
				});
			}
			// input field font size
			if ($.browser.safari) {
				$('#eM-container .contact-input').css({
					'font-size': '.9em'
				});
			}
			// dynamically determine height
			var h = 280;
			if ($('#eM-subject').length) {
				h += 26;
			}
			if ($('#eM-cc').length) {
				h += 22;
			}
			var title = $('#eM-container .contact-title').html();
			$('#eM-container .contact-title').html('Loading...');
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {
						$('#eM-container .contact-content').animate({
							height: h
						}, function () {
							$('#eM-container .contact-title').html(title);
							$('#eM-container form').fadeIn(200, function () {
								$('#eM-container #eM-name').focus();
								$('#eM-container .contact-cc').click(function () {
									var cc = $('#eM-container #eM-cc');
									cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
								});
								// fix png's for IE 6
								if ($.browser.msie && $.browser.version < 7) {
									$('#eM-container .contact-button').each(function () {
										if ($(this).css('backgroundImage').match(/^url[("']+(.*\.png)[)"']+$/i)) {
											var src = RegExp.$1;
											$(this).css({
												backgroundImage: 'none',
												filter: 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="' +  src + '", sizingMethod="crop")'
											});
										}
									});
								}
							});
						});
					});
				});
			});
		},
		show: function (dialog) {
			if(eM_cf7form == true){
				$('div.wpcf7 > form').ajaxForm({
					beforeSubmit: function(formData, jqForm, options) {
						jqForm.wpcf7ClearResponseOutput();
						jqForm.find('img.ajax-loader').css({ visibility: 'visible' });
						return true;
					},
					beforeSerialize: function(jqForm, options) {
						jqForm.find('.wpcf7-use-title-as-watermark.watermark').each(function(i, n) {
							$(n).val('');	
						});
						return true;
					},
					data: { '_wpcf7_is_ajax_call': 1 },
					dataType: 'json',
					success: function(data) {
						var ro = $(data.into).find('div.wpcf7-response-output');
						$(data.into).wpcf7ClearResponseOutput();
						if (data.invalids) {
							$.each(data.invalids, function(i, n) {
								$(data.into).find(n.into).wpcf7NotValidTip(n.message);
							});
							ro.addClass('wpcf7-validation-errors');
						}
						if (data.captcha)
							$(data.into).wpcf7RefillCaptcha(data.captcha);
						if (data.quiz)
							$(data.into).wpcf7RefillQuiz(data.quiz);
						if (1 == data.spam)
							ro.addClass('wpcf7-spam-blocked');
						if (1 == data.mailSent) {
							$(data.into).find('form').resetForm().clearForm();
							ro.addClass('wpcf7-mail-sent-ok');
							if (data.onSentOk)
								$.each(data.onSentOk, function(i, n) { eval(n) });
						} else {
							ro.addClass('wpcf7-mail-sent-ng');
						}
						if (data.onSubmit)
							$.each(data.onSubmit, function(i, n) { eval(n) });
						$(data.into).find('.wpcf7-use-title-as-watermark.watermark').each(function(i, n) {
							$(n).val($(n).attr('title'));
						});
						ro.append(data.message).slideDown('fast');
					}
				});
			}
		},
		close: function (dialog) {
			$('#eM-container').fadeOut();
			$('#eM-container .eM-content').animate({
				height: 40
			}, function () {
				dialog.data.fadeOut(200, function () {
					dialog.container.fadeOut(200, function () {
						dialog.overlay.fadeOut(200, function () {
							$.modal.close();
						});
					});
				});
			});
		}
	};
	contact.init();
});