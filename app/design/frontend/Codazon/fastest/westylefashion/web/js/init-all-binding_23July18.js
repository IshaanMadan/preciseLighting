 
define(['jquery','jquery/ui'], function($) {
    
    "use strict";
    
    $.widget('doyenhub.initAllBinding', {
        
        _create: function () {
            window.activeStorePickup = 0;
            
            /*jQuery(document).on('click', '.btn-toggle-methods', function(event) {
                jQuery('.btn-toggle-methods').removeClass('active-method');
                jQuery(this).addClass('active-method');    
                var isStorePicukp = jQuery(this).hasClass('btn-storepickups-methods');
                if(isStorePicukp){
                    jQuery('#opc-shipping_method').hide();
                    jQuery('#opc-shipping_method_storepickup').show();
                    activeStorePickup = 1;
                }else{
                    jQuery('#opc-shipping_method_storepickup').hide();
                    jQuery('#opc-shipping_method').show();
                    activeStorePickup = 0;
                }


            });*/

            jQuery(document).on('click', '.btn-toggle-methods', function(event) {
                jQuery('.btn-toggle-methods').removeClass('active-method');
                jQuery(this).addClass('active-method');    
                var isStorePicukp = jQuery(this).hasClass('btn-storepickups-methods');
                var shippMethods = jQuery('#checkout-shipping-method-load').find('.table-checkout-shipping-method');
                jQuery('#opc-shipping_method').removeClass('active-store-pickup-method');
                if(shippMethods.length){
                    shippMethods.find('tr.row').each(function(index, el) {
                        jQuery(this).removeClass('hide-shiping-method');       
                    });
                    var isMethodClicked = 0;
                    shippMethods.find('.radio').each(function(index, el) {
                        var codeValue = jQuery(this).val();
                        //jQuery(this).closest('tr.row').removeClass('active-store-pickup-row');
                        if(isStorePicukp && codeValue.indexOf('storepickup') < 0){
                            jQuery(this).closest('tr.row').addClass('hide-shiping-method');    
                        }
                        if(!isStorePicukp && codeValue.indexOf('storepickup') >= 0){
                            jQuery(this).closest('tr.row').addClass('hide-shiping-method');    
                        }
                        if(!isMethodClicked && !isStorePicukp && codeValue.indexOf('storepickup') < 0){
                            jQuery(this).trigger('click');
                        }
                        if(!isMethodClicked && isStorePicukp && codeValue.indexOf('storepickup') >= 0){
                            jQuery(this).trigger('click');
                            jQuery(this).closest('tr.row').addClass('active-store-pickup-row');   
                        }

                    });
                    if(isStorePicukp){
                        jQuery('#onepage-checkout-shipping-method-additional-load').show();
                        //jQuery('.checkout-shipping-method').find('.step-title').first().hide();
                        //jQuery('#opc-shipping_method').addClass('active-store-pickup-method');
                        activeStorePickup = 1;
                    }else{
                        jQuery('#onepage-checkout-shipping-method-additional-load').hide();
                        //jQuery('.checkout-shipping-method').find('.step-title').first().show();
                        activeStorePickup = 0;
                    }
                }

            });

            var methodInterval = setInterval(function() {
                if(jQuery('#checkout-shipping-method-load').length){
                    var shippMethods = jQuery('#checkout-shipping-method-load').find('.table-checkout-shipping-method');
                    if(shippMethods.length){
                        shippMethods.find('.radio').each(function(index, el) {
                            var codeValue = jQuery(this).val();
                            if(codeValue.indexOf('storepickup') < 0){
                                jQuery('.btn-delivery-methods').trigger('click');
                                clearInterval(methodInterval);
                                return false; 
                            }
                        });
                    }
                }
            }, 100);


            jQuery(document).on('click', '#btn-next-link', function(event) {
                jQuery('#shipping-method-buttons-container').find('button.continue').trigger('click');
            });
            jQuery(document).on('focus', '.input-text', function(event) {
                var parentField = jQuery(this).closest('.field');
                if(parentField.length && !parentField.hasClass('active-field')){
                    parentField.addClass('active-field');
                }
            });
            jQuery(document).on('blur', '.input-text', function(event) {
                var parentField = jQuery(this).closest('.field');
                if(parentField.length){
                    parentField.removeClass('active-field');
                }
            });
            jQuery(document).on('change', '.store_list_radio', function(event) {
                jQuery('.store_list_select').val(jQuery(this).val());
            });
        } 
    }); 
    
    return $.doyenhub.initAllBinding;
});
