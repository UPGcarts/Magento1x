var UpgMagentoJs = Class.create();

UpgMagentoJs.prototype =
{
    initialize: function()
    {
        this.preparePaymentObserver();
    },

    getPaymentIframe: function()
    {
        console.log('Getting payment iframe');
        var UpgMagentoJsImplementationObject = this;
        var req = new Ajax.Request(UPG_PAYMENT_CONFIG.BASE_URL+'paymentmodule/payment', {
            method: 'post',
            onSuccess: function(transport)
            {
                if(transport.responseJSON.hasOwnProperty("error")){
                    alert(transport.responseJSON.errorMsg);
                }else {
                    console.log(transport.responseJSON);
                    jQuery.fancybox({
                        closeBtn: false,
                        closeClick: false,
                        modal: true,
                        padding: 0,
                        type: 'iframe',
                        href: transport.responseJSON.iframeUrl,
                        afterClose: function () {
                            return UpgMagentoJsImplementationObject.iframeCloseHandler(arguments);
                        }
                    });
                }
            }
        });
    },

    preparePaymentObserver: function() {
        return false;
    },

    setPaymentCallback: function(callback, paymentObj) {
        this.callbackObj = paymentObj;
        this.callbackFunction = callback;
    },

    iframeCloseHandler: function()
    {
        console.log("Payment iFrame closed");
        //Were payments entered correctly?

        //Yes, continue

        //No, go back to the Payment step
        return this.callbackFunction.apply(this.callbackObj, arguments);
    }
};
