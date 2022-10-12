require(['jquery','slick'], function ($) {
    $(document).ready(function () {
    
		setInterval(function() {
			//store credit design
			$("hr").first().remove();
			
			$("div#checkout-shipping-method-load").hide();
			$("fieldset[data-index='prefix']").find("div.admin__field-control .admin__field.admin__field-option:first-child").hide();
			$("input[type='radio'][value='1']").attr('checked', true);
			$(".payment-option._collapsible.opc-payment-additional.credit").after("<hr>");
			jQuery('div[name="shippingAddress.prefix"]').addClass("name-prefix");	
			jQuery("div#checkout-shipping-method-load").hide();	
			//jQuery("div#checkout-shipping-method-load td#label_method_pickup_instore").parent("tr").hide();		// Mobile number velidation
			
			// var inputType = jQuery('input[name="telephone"]').attr('type');
			// if(inputType == 'text'){
			jQuery('input[name="telephone"]').attr('type','text');
			jQuery('input[name="telephone"]').attr('maxlength','9');
			jQuery('input[name="telephone"]').keypress(function(event){
				return /^[0-9]*$/.test(event.key)
			});
			$('div[name="shippingAddress.telephone"] input[name="telephone"]').keyup(function(event){
			 	console.log("Shipping");
                var d = jQuery("input[name='telephone']").val();
				if(d.length == 9) {
					if(d.charAt(0) == 5){
						console.log("Valid");
					   $("div[name='shippingAddress.telephone'] #error-msg01").hide();
					   $("button.action.proceed-payment.proceed-payment-main").prop('disabled', false);
					}else{
						$("div[name='shippingAddress.telephone'] #error-msg01").show();
						$("button.action.proceed-payment.proceed-payment-main").prop('disabled', true);
					}
				}
				else {
					$("div[name='shippingAddress.telephone'] #error-msg01").show();
                    $("button.action.proceed-payment.proceed-payment-main").prop('disabled', true);
				}
			});

			$('div[name="billingAddress.telephone"] input[name="telephone"]').keyup(function(event){
				console.log("Billing");
				var d = jQuery("input[name='telephone']").val();
				if(d.length == 9) {
					if(d.charAt(0) == 5){
						console.log("Vaild");
						$("div[name='billingAddress.telephone'] #error-msg01").hide();
						$("button.action.proceed-payment.proceed-payment-main").prop('disabled', false);
					}else{
						$("div[name='billingAddress.telephone'] #error-msg01").show();
						$("button.action.proceed-payment.proceed-payment-main").prop('disabled', true);
					}
				}
				else {
					$("div[name='billingAddress.telephone'] #error-msg01").show();
					$("button.action.proceed-payment.proceed-payment-main").prop('disabled', true);
				}
		   	});
			// }
			// jQuery('input[name="telephone"]').attr('maxlength','9');
			// jQuery("input[name='telephone']").keypress(function() {
			// if (jQuery(this).val().length == jQuery(this).attr("maxlength")) {
			// 		return false;
			// 	}
			// });

			// jQuery('input.storecredit.css-checkbox.filled').change(function(){
			//      if(jQuery(".store-credits.payment-option._collapsible.opc-payment-additional.credit._active")[0]){
			//     console.log("dsfgh");
			//     jQuery('input.storecredit.css-checkbox.filled').prop('checked', true);
			// } else {
			//    jQuery('input.storecredit.css-checkbox.filled').prop('checked', false);
			// }
			// });
			jQuery('.store-credits.payment-option._collapsible.opc-payment-additional.credit').click(function(){
			if(jQuery(".store-credits.payment-option._collapsible.opc-payment-additional.credit._active")[0]){
			    console.log("dsfgh");
			    jQuery('input.storecredit.css-checkbox.filled').prop('checked', true);
			} else {
			   jQuery('input.storecredit.css-checkbox.filled').prop('checked', false);
			}
			});
			// shipping method selection
			$("button.action.action-select-shipping").click(function(){
				$("#checkout-step-shipping_method").removeClass("hidden");
				$(".checkout-shipping-method").removeClass("hidden");
				$(".checkout-shipping-method").show();
				$("div#checkout-step-shipping_method").show();
				$("button.action.proceed-payment.proceed-payment-main.primary").show();
			});
			$("button.action.action-select-store-pickup").click(function(){
				$("#checkout-step-shipping_method").addClass("hidden");
				$(".checkout-shipping-method").addClass("hidden");
				$(".checkout-shipping-method").hide();
				$("div#checkout-step-shipping_method").hide();
				$("button.action.proceed-payment.proceed-payment-main.primary").hide();
			});
				//alert("pickup");
			if ($("button.action.action-select-store-pickup").hasClass("selected")) {
				$("#checkout-step-shipping_method").addClass("hidden");
				$(".checkout-shipping-method").addClass("hidden");
				$(".checkout-shipping-method").hide();
				$("button.action.proceed-payment.proceed-payment-main.primary").hide();
			}
			else{
				$("#checkout-step-shipping_method").removeClass("hidden");
				$(".checkout-shipping-method").removeClass("hidden");
				$(".checkout-shipping-method").show();
				$("button.action.proceed-payment.proceed-payment-main.primary").show();
			}
			if(window.location.hash == '#payment'){
				$("button.action.proceed-payment.proceed-payment-main.primary").hide();	   
			}
		//$("button.action.proceed-payment.proceed-payment-main.primary").hide();
		}, 5000);
	});
});