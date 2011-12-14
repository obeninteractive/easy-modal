(function ($){

	$.fn.emodal = function(options) {

		var defaults = {
			url: easymodal.ajaxurl,
			requestType: 'load',
			requestData: {},
			overlayClose: false,
			buttonClose: true,
			onLoad: function(){}
			
		};
		
		var options = $.extend({},defaults,options);
		function centerModal(animate){
			var top = ($(window).height() - $('#eModal-Container').outerHeight() ) / 2;
			var left = ($(window).width() - $('#eModal-Container').outerWidth() ) / 2;
			if(animate == true){
				$('#eModal-Container').animate({
					'top': top + $(document).scrollTop(),
					'left': left
				});
			} else {
				$('#eModal-Container').css({
					'top': top + $(document).scrollTop(),
					'left': left
				});
			}
		}
	
	
		var onLoad = function(){
			$(this).prepend(function(){
				if(options.buttonClose == true) return $('<a href="#close" id="close">x</a>').click(function(){
					$('#eModal-Container').fadeOut().remove();
					$('#eModal-Overlay').fadeOut().remove();
					return false;
				});
			});
			if(options.onLoad){
				options.onLoad();
			}
			var resizeTimer;
			$(window, this).resize(function(){
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(function(){
					centerModal(true);
				}, 100)
			});
			$(this).fadeIn();
			centerModal();
			
			if(options.cf7form == true)
			{
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
						if(1 == data.mailSent){
							$('#eModal-Container').fadeOut(4000,function(){$(this).remove();});
							$('#eModal-Overlay').fadeOut(2000, function(){$(this).remove();});
						}
					}
				});
			}
		}
		
		var openModal = function(e){
			$('<div id="eModal-Overlay"></div>').css({opacity:.3}).hide().appendTo('body').click(function(){
				if(options.overlayClose == true){
					$(this).next().fadeOut().remove();
					$(this).fadeOut().remove();
				}
			}).fadeIn();
			switch(options.requestType)
			{
				case 'load':
					$('<div id="eModal-Container"></div>').hide().load(options.url, options.requestData, onLoad).appendTo('body');
					break;
			}
		};
		$(this).click(function(e){
			e.stopPropagation();
			openModal();
			return false;
		})
		
		
		
	};

	$(document).ready(function(){
		
		$('.eModal').each(function(){
			
			var classes = $(this).attr("class").split(" ");
			
			for (var i = 0; i < classes.length; i++){
				
				if ( classes[i].substr(0,7) == "eModal-" ){
					
					var modalId = classes[i].split("-")[1];
					break;
					
				}
				
			}
			$(this).emodal(easymodal.settings[modalId]);
		})
		
		
		
		
		
	})
})(jQuery)