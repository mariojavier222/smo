// JavaScript Document

	// JQUERY: Plugin "autoSumbit"
	(function($) {
		$.fn.autoSubmit = function(options) {
			return $.each(this, function() {
				// VARIABLES: Input-specific
				var input = $(this);
				var column = input.attr('name');
	
				// VARIABLES: Form-specific
				var form = input.parents('form');
				var method = form.attr('method');
				var action = form.attr('action');

				// VARIABLES: Where to update in database
				var where_val = form.find('#where').val();
				var where_col = form.find('#where').attr('name');
				var where_val2 = form.find('#where2').val();
				var where_col2 = form.find('#where2').attr('name');
				var OPe_Ord_ID = where_val;
				var OPe_Per_ID = where_val2;
	
				// ONBLUR: Dynamic value send through Ajax
				input.bind('blur', function(event) {
					// Get latest value
					var value = input.val();
					// AJAX: Send values
					$.ajax({
						url: action,
						type: method,
						data: {
							val: value,
							col: column,
							w_col: where_col,
							w_val: where_val,
							OPe_Ord_ID: OPe_Ord_ID,
							OPe_Per_ID: OPe_Per_ID						
						},
						cache: false,
						timeout: 10000,
						success: function(data) {
							// Alert if update failed
							if (data) {
								alert(data);
							}
							// Load output into a P
							else {
								var j = form.find('#j').val();
								$('#notice' + j).text('Actualizado');
								$('#notice' + j).fadeOut().fadeIn();
							}
						}
					});
					// Prevent normal submission of form
					return false;
				})
			});
		}
	})(jQuery);
	// JQUERY: Run .autoSubmit() on all INPUT fields within form
	$(function(){
		
		$("form[id^='ajax-form'] INPUT").autoSubmit();
		//$('#ajax-form INPUT').autoSubmit();
	});
