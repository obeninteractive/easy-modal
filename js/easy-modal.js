(function($){$.toJSON=function(o)
{if(typeof(JSON)=='object'&&JSON.stringify)
return JSON.stringify(o);var type=typeof(o);if(o===null)
return"null";if(type=="undefined")
return undefined;if(type=="number"||type=="boolean")
return o+"";if(type=="string")
return $.quoteString(o);if(type=='object')
{if(typeof o.toJSON=="function")
return $.toJSON(o.toJSON());if(o.constructor===Date)
{var month=o.getUTCMonth()+1;if(month<10)month='0'+month;var day=o.getUTCDate();if(day<10)day='0'+day;var year=o.getUTCFullYear();var hours=o.getUTCHours();if(hours<10)hours='0'+hours;var minutes=o.getUTCMinutes();if(minutes<10)minutes='0'+minutes;var seconds=o.getUTCSeconds();if(seconds<10)seconds='0'+seconds;var milli=o.getUTCMilliseconds();if(milli<100)milli='0'+milli;if(milli<10)milli='0'+milli;return'"'+year+'-'+month+'-'+day+'T'+
hours+':'+minutes+':'+seconds+'.'+milli+'Z"';}
if(o.constructor===Array)
{var ret=[];for(var i=0;i<o.length;i++)
ret.push($.toJSON(o[i])||"null");return"["+ret.join(",")+"]";}
var pairs=[];for(var k in o){var name;var type=typeof k;if(type=="number")
name='"'+k+'"';else if(type=="string")
name=$.quoteString(k);else
continue;if(typeof o[k]=="function")
continue;var val=$.toJSON(o[k]);pairs.push(name+":"+val);}
return"{"+pairs.join(", ")+"}";}};$.evalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);return eval("("+src+")");};$.secureEvalJSON=function(src)
{if(typeof(JSON)=='object'&&JSON.parse)
return JSON.parse(src);var filtered=src;filtered=filtered.replace(/\\["\\\/bfnrtu]/g,'@');filtered=filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']');filtered=filtered.replace(/(?:^|:|,)(?:\s*\[)+/g,'');if(/^[\],:{}\s]*$/.test(filtered))
return eval("("+src+")");else
throw new SyntaxError("Error parsing JSON, source is not valid.");};$.quoteString=function(string)
{if(string.match(_escapeable))
{return'"'+string.replace(_escapeable,function(a)
{var c=_meta[a];if(typeof c==='string')return c;c=a.charCodeAt();return'\\u00'+Math.floor(c/16).toString(16)+(c%16).toString(16);})+'"';}
return'"'+string+'"';};var _escapeable=/["\\\x00-\x1f\x7f-\x9f]/g;var _meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'};})(jQuery);

(function ($){

	$.fn.emodal = function(options) {

		var defaults = {
			url: convertEntities(easymodal.ajaxurl),
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