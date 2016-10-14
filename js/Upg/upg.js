var UpgMagentoJs = Class.create();

UpgMagentoJs.prototype =
{
    initialize: function()
    {
        this.preparePaymentObserver();
    },

    getPaymentIframe: function(arguments)
    {
        console.log('Getting payment iframe');
        var UpgMagentoJsImplementationObject = this;
        var args = arguments;
        var req = new Ajax.Request(UPG_PAYMENT_CONFIG.BASE_URL+'paymentmodule/payment', {
            method: 'post',
            onSuccess: function(transport)
            {
                if(transport.responseJSON.hasOwnProperty("error")){
                    alert(transport.responseJSON.errorMsg);
                }else {
                    console.log(transport.responseJSON);
                    var fancyboxConfig = {
                        closeBtn: false,
                        closeClick: false,
                        modal: true,
                        padding: 0,
                        type: 'iframe',
                        href: transport.responseJSON.iframeUrl,
                        afterClose: function () {
                            return UpgMagentoJsImplementationObject.iframeCloseHandler(arguments);
                        }
                    };
                    if (args.fancybox.is_mobile) {
                        fancyboxConfig.fitToView = true;
                        fancyboxConfig.autoSize = false;
                        fancyboxConfig.width = UpgMagentoJsImplementationObject.viewportWidth();
                        fancyboxConfig.height = UpgMagentoJsImplementationObject.viewportHeight();
                    }
                    else {
                        if (args.fancybox.fit_to_width_desktop) {
                            fancyboxConfig.fitToView = true;
                        }
                        else {
                            fancyboxConfig.fitToView = false;
                            fancyboxConfig.autoSize = false;
                            fancyboxConfig.width = args.fancybox.width + args.fancybox.units;
                            fancyboxConfig.height = args.fancybox.height + args.fancybox.units;
                        }
                    }
                    jQuery.fancybox(fancyboxConfig);
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
    },
    
    viewportWidth: function () {
        return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
    },
    
    viewportHeight: function () {
        return Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    }
};
