 
define(['jquery','jquery/ui'], function($) {
    
    "use strict";
    
    $.widget('doyenhub.initAllBinding', {
        
        _create: function () {
            window.activeStorePickup = 0;
            jQuery(document).on('click', '.btn-toggle-methods', function(event) {
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


            });
            jQuery(document).on('click', '#btn-next-link', function(event) {
                jQuery('#shipping-method-buttons-container').find('button.continue').trigger('click');
            });
        } 
    }); 
    
    return $.doyenhub.initAllBinding;
});
