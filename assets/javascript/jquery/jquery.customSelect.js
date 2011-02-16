/************************************************ 
*  jQuery customSelect plugin                   *
*                                               *
*  Author: Ammon Casey		                    *
*  Website: http://www.brokenparadigmlabs.com	*
*  Twitter: @ammonkc							*
*  Date:   04.02.2010                           *
************************************************/

jQuery.fn.customSelect = function(options, selected_callback) {
	
	// define default settings
	var settings = {
		mouse_over: 'pointer',
		mouse_out:  'default',
		hide_select: true,
		selectWidth: 68,
		selectHeight: 23,
		padTop: 0,
		padBottom: 0,
		padRight: 24,
		padLeft: 8,		
		styledClass: 'selectbox',
		textColor: '#ffffff',
		fontSize: 12,
		bg_image: '/assets/images/selectbox_bg.png',
		bg_repeat: 'no-repeat',
		bg_color: 'rgb(34,34,34)',
		use_images: true
	};
    
	if(options) {
		jQuery.extend(settings, options);
	}
	
	// create the switch
	return this.each(function() { 
		var selectbox = jQuery(this);
		if (!jQuery(selectbox).is('select')) { return; }
		
		var styled;
		
		// Hide the checkbox
		if (settings.hide_select) {
		    selectbox.css({
		        'position':'relative',		        
		        'height': settings.selectHeight,
		        'width': settings.selectWidth + settings.padRight + settings.padLeft,
		        'border': '2px solid',
		        'opacity':0,
		        'z-index':5
		    });
		}
		
		// use images 
		if (settings.use_images) {
			bg_image = 'url(' + settings.bg_image + ')';		
		}else{
		    bg_image = 'none';
		}
		
		/**** make styled replacement ****/
		styled = jQuery('<span/>',{'class': settings.styledClass})
		                .css({
		                    'position': 'absolute',	                    	
	                    	'height': settings.selectHeight,
	                    	'width': settings.selectWidth,
	                    	'padding-top': settings.padTop,
	                    	'padding-bottom': settings.padBottom,
	                    	'padding-right': settings.padRight,
	                    	'padding-left': settings.padLeft,
	                    	'color': settings.textColor,
	                    	'background-color': settings.bg_color,
	                    	'background-image': bg_image,
	                    	'background-repeat': settings.bg_repeat,
	                    	'z-index': 4,
	                    	'overflow': 'hidden'
		                })
		                .text(selectbox.find('option[value="' + selectbox.val() + '"]').text());
		
		
		/* insert into placeholder */
		selectbox.wrap('<div class="selectBox-container"/>');
		selectbox.before(styled);
		
		var mySelectbox = selectbox.parent();
		
		jQuery(selectbox).change(function() {
		    var newVal = jQuery(this).find('option[value="'+jQuery(this).val()+'"]').text();
		    jQuery(this).siblings('span.'+settings.styledClass)
		                .text(newVal);
		    selected_callback();
		});

	});
	
};
