(function ($) {
	"use strict";
	if (typeof ($.fn.lc_switch) != 'undefined') { return false; }

	$.fn.lc_switch = function (on_text, off_text, on_color, off_color, on_box_shadow_color, off_box_shadow_color) {

		// destruct
		$.fn.lcs_destroy = function () {
			$(this).each(function () {
				var $wrap = $(this).parents('.lcs_wrap');

				$wrap.children().not('input').remove();
				$(this).unwrap();
			});

			return true;
		};


		// set to ON
		$.fn.lcs_on = function () {
			$(this).each(function () {
				var $wrap = $(this).parents('.lcs_wrap'),
					$input = $wrap.find('input');

				if ($wrap.find('.lcs_on').length) return true;

				$input.prop('checked', true);
				$input.trigger('lcs-on').trigger('lcs-statuschange');

				$wrap.find('.lcs_switch').removeClass('lcs_off').addClass('lcs_on')
					.css({
						'background': on_color || '',
						'box-shadow': on_box_shadow_color ? `0 0 2px ${on_box_shadow_color} inset` : ''
					});
			});

			return true;
		};


		// set to OFF
		$.fn.lcs_off = function () {
			$(this).each(function () {
				var $wrap = $(this).parents('.lcs_wrap'),
					$input = $wrap.find('input');

				if (!$wrap.find('.lcs_on').length) return true;

				$input.prop('checked', false);
				$input.trigger('lcs-off').trigger('lcs-statuschange');

				$wrap.find('.lcs_switch').removeClass('lcs_on').addClass('lcs_off')
					.css({
						'background': off_color || '',
						'box-shadow': off_box_shadow_color ? `0 0 2px ${off_box_shadow_color} inset` : ''
					});
			});

			return true;
		};


		// toggle status
		$.fn.lcs_toggle = function () {
			$(this).each(function () {
				if ($(this).hasClass('lcs_radio_switch')) {
					return true;
				}

				($(this).is(':checked')) ? $(this).lcs_off() : $(this).lcs_on();
			});

			return true;
		};


		// construct
		return this.each(function () {

			if (!$(this).parent().hasClass('lcs_wrap')) {
				var ckd_on_txt = (typeof (on_text) == 'undefined') ? 'ONLINE' : on_text,
					ckd_off_txt = (typeof (off_text) == 'undefined') ? 'OFFLINE' : off_text;

				var on_label = (ckd_on_txt) ? '<div class="lcs_label lcs_label_on">' + ckd_on_txt + '</div>' : '',
					off_label = (ckd_off_txt) ? '<div class="lcs_label lcs_label_off">' + ckd_off_txt + '</div>' : '';

				var disabled = ($(this).is(':disabled')) ? true : false,
					active = ($(this).is(':checked')) ? true : false;

				var status_classes = '';
				status_classes += (active) ? ' lcs_on' : ' lcs_off';
				if (disabled) {
					status_classes += ' lcs_disabled';
				}

				// wrap and append
				const structure = `
					<div class="lcs_switch ${status_classes}" 
						 style="background-color: ${active ? on_color : off_color}; 
								box-shadow: ${active ? `0px 0px 2px ${on_box_shadow_color} inset` : `0px 0px 2px ${off_box_shadow_color} inset`}">
					<div class="lcs_cursor"></div> ${on_label}${off_label} </div>
				`;

				if ($('[name="is_broadcast"]').val() == "2" || document.location.origin + "/messenger" == window.location.href) {
					if ($(this).is(':input') && ($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio')) {

						$(this).wrap('<div class="lcs_wrap"></div>');
						$(this).parent().append(structure);

						$(this).parent().find('.lcs_switch').addClass('lcs_' + $(this).attr('type') + '_switch');
					}
				}
			}
		});
	};

	// handlers
	$(document).ready(function () {
		$(document).on('click tap', '.lcs_switch:not(.lcs_disabled)', function (e) {
			if ($(this).hasClass('lcs_on')) {
				if (!$(this).hasClass('lcs_radio_switch')) {
					$(this).lcs_off();
				}
			} else {
				$(this).lcs_on();
			}
		});

		$(document).on('change', '.lcs_wrap input', function () {
			($(this).is(':checked')) ? $(this).lcs_on() : $(this).lcs_off();
		});
	});
})(jQuery);
