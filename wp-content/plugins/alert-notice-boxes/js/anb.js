jQuery(document).ready(function($){
	function getCookie(cname) {
	    var name = cname + "=";
	    var decodedCookie = decodeURIComponent(document.cookie);
	    var ca = decodedCookie.split(';');
	    for(var i = 0; i <ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	$('[data-anb-id]').each(function (index, value){
		var anb_id = $(this).data("anb-id");
		var anb_show = true;

		var anb_close_cookie_name = "anb_close_" + anb_id;
		var anb_close_cookie = getCookie(anb_close_cookie_name);
		var anb_close_days = $(this).children().data("anb-close-button");

		if (anb_close_days == 0) {
			if (anb_close_cookie) {
				document.cookie = anb_close_cookie_name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			}
		} else {
			if (anb_close_cookie) {
				$(this).parent().remove();
				var anb_show = false;
			}
		}

		var anb_limitation_name = "anb_limitation_" + anb_id;
		var anb_limitation_cookie = getCookie(anb_limitation_name);
		var anb_limitations_times = $(this).data("anb-limitations-times");
		var anb_limitations_days = $(this).data("anb-limitations-days");
		if (anb_limitations_times == 0) {
			if (anb_limitation_cookie) {
				document.cookie = anb_limitation_name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
			}
		} else {
			if (anb_limitation_cookie) {
				if (anb_limitation_cookie > 1) {
					var anb_current_limitation_times = anb_limitation_cookie -1;
					var d = new Date();
					var n = d.setTime(d.getTime() + (anb_limitations_days*24*60*60*1000));
					document.cookie = anb_limitation_name + "=" + anb_current_limitation_times + "; expires=" + d.toUTCString();
				} else {
					$(this).parent().remove();
					var anb_show = false;
				}

			} else {
				var d = new Date();
				var n = d.setTime(d.getTime() + (anb_limitations_days*24*60*60*1000));
				document.cookie = anb_limitation_name + "=" + anb_limitations_times + "; expires=" + d.toUTCString();
			}
		}

		if (anb_show) {
			var anb_delay = $(this).data("anb-delay");
			if (anb_delay > 0) {
				setTimeout(function(){
					$('#anb-id-'+anb_id).removeClass('delay');
				}, anb_delay*1000);
			}
			var anb_show_time = ($(this).data("anb-show-time") + anb_delay)*1000;
			if (anb_show_time > 0) {
				var anb_animation_out_class = $(this).data("anb-animation-out-class");
				var anb_animation_out_speed = $(this).data("anb-animation-out-speed")*1000;
				setTimeout(function(){
					$('#anb-id-'+anb_id).addClass(anb_animation_out_class);
					setTimeout(function(){
						$('#anb-id-'+anb_id).parent().fadeOut( 400, function() {
							$(this).remove();
						});
					}, anb_animation_out_speed);
				}, anb_show_time);
			}
		}

	});

	$('body').on("click", "[data-anb-close-button]", function(e){
		var anb_id = $(this).parent().data("anb-id");
		var anb_close_days = $(this).data("anb-close-button");
		if (anb_close_days > 0) {
			var anb_close_status = true;
			var d = new Date();
			var n = d.setTime(d.getTime() + (anb_close_days*24*60*60*1000));
			document.cookie = "anb_close_" + anb_id + "=" + anb_close_status + "; expires=" + d.toUTCString() ;
		}

		var anb_animation_out_class = $(this).parent().data("anb-animation-out-class");
		var anb_animation_out_speed = $(this).parent().data("anb-animation-out-speed")*1000;
		$(this).parent().addClass(anb_animation_out_class);
		setTimeout(function(){
			$('#anb-id-'+anb_id).parent().fadeOut( 400, function() {
				$(this).remove();
			});
		}, anb_animation_out_speed);
	});

	$(".anb-close").click(function(e){
		e.preventDefault();
		$(this).parent().children("[data-anb-close-button]").trigger('click');
	});

});
