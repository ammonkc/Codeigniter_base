$(document).ready(function() {
    if ( $("form").length ) {
        html5form.init();
    }
});

/****  Utility Func  ****/
var html5form = function() {
    return {
        initSlider: function() {
        	$('input[type=range]').each(function() {
        		var $input = $(this);
        		var $slider = $('<div id="' + $input.attr('id') + '" class="' + $input.attr('class') + '"></div>');
        		var step = $input.attr('step');
        
        		$input.after($slider).hide();
        
        		$slider.slider({
        			min: $input.attr('min'),
        			max: $input.attr('max'),
        			step: $input.attr('step'),
        			change: function(e, ui) {
        				$(this).val(ui.value);
        			}
        		});
        	});
        },
        initSpinner: function() {
        	$('input[type=number]').each(function() {
        		var $input = $(this);
        		$input.spinner({
        			min: $input.attr('min'),
        			max: $input.attr('max'),
        			step: $input.attr('step')
        		});
        	});
        },
        initDatepicker: function() {
        	$('input[type=date]').each(function() {
        		var $input = $(this);
        		$input.datepicker({
        			minDate: $input.attr('min'),
        			maxDate: $input.attr('max'),
        			dateFormat: 'yy-mm-dd'
        		});
        	});
        },
        initColorpicker: function() {
        	$('input[type=color]').each(function() {
        		var $input = $(this);
        		$input.ColorPicker({
        			onSubmit: function(hsb, hex, rgb, el) {
        				$(el).val(hex);
        				$(el).ColorPickerHide();
        			}
        		});
        	});
        },
        initPlaceholder: function() {
        	$('input[placeholder]').placehold();
        },
        init: function(params) {
            if( !Modernizr.inputtypes.range ){
            	$(document).ready( html5form.initSlider );
            };
            if(!Modernizr.inputtypes.number){
            	$(document).ready(html5form.initSpinner);
            };
            if(!Modernizr.inputtypes.date){
            	$(document).ready(html5form.initDatepicker);
            };
            if(!Modernizr.inputtypes.color){
            	$(document).ready(html5form.initColorpicker);
            };
            if(!Modernizr.input.placeholder){
            	$(document).ready(html5form.initPlaceholder);
            };
            return this;
        }
    }
}();