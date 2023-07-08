(function() {
    const button = $('.field-cart_button');

    button.popover({
        html: true,
        sanitize: false,
        placement: 'bottom',
        title: 'Cart',
        //template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        trigger: 'manual',
        content: function () {
            let content = '<div>Cart';
            content += '</div>';
            return content;
        }
    });

    button.on('click', function() {
        $(this).popover('toggle');
        const icon = button.find('.bi');
        icon.toggleClass('bi-bag');
        icon.toggleClass('bi-x-lg');

        if (icon.hasClass('bi-bag')) {
            window.openedPopover = null;
        } else {
            if (window.openedPopover && window.openedPopover !== this) {
                $(window.openedPopover).trigger('click');
            }

            window.openedPopover = this;
        }
        return false;
    });
})();
