Ajax.Responders.register({
    onComplete: function(ajax, response) {
        if(ajax.url.indexOf("saveShippingMethod") > -1) {
            if($$('.no-display #p_method_payco').length == 1) {
                triggerEventPrototype($$('#payment-buttons-container button')[0], 'click');
            }
        }
    }
});

function triggerEventPrototype(element, eventName) {
    // safari, webkit, gecko
    if (document.createEvent) {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(eventName, true, true);

        return element.dispatchEvent(evt);
    }

    // Internet Explorer
    if (element.fireEvent) {
        return element.fireEvent('on' + eventName);
    }
}