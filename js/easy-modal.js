/*
 * Easy Modal
 * http://wizardinternetsolutions.com/project/easy-modal/
 * 
 * Copyright (c) 2011 Wizard Internet Solutions 
 * http://wizardinternetsolutions.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */
eM_plugin_url = convertEntities(eMSettings.plugin_url);



eM_overlayId = convertEntities(eMSettings.overlayId);
eM_overlayCss = convertEntities(eMSettings.overlayCss);
eM_opacity = convertEntities(eMSettings.opacity);

eM_containerId = convertEntities(eMSettings.containerId);
eM_minHeight = convertEntities(eMSettings.minHeight);
eM_maxHeight = convertEntities(eMSettings.maxHeight);
eM_minWidth = convertEntities(eMSettings.minWidth);
eM_maxWidth = convertEntities(eMSettings.maxWidth);


eM_cf7form = convertEntities(eMSettings.cf7form);


jQuery(function ($) {
	var contact = {
		message: null,
		init: function () {
			$('.eModal').click(function (e) {
				e.preventDefault();
				// load the contact form using ajax
				
				$.get("/wp-content/plugins/easy-modal/content/content.php?plugin_url=" + eM_plugin_url, function(data){
					// create a modal dialog with the data
					$(data).modal({
						closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
						position: ["15%",],
						overlayId: eM_overlayId,
						overlayCss: eM_overlayCss,
						opacity : eM_opacity,
						containerId: eM_containerId,
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
		},
	};

	contact.init();

});