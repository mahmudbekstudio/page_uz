import searchClass from '../../admin/component/website-render/fields/class/search';
$(function() {
    const button = $('.field-search_button');

    button.popover({
        html: true,
        sanitize: false,
        placement: 'bottom',
        title: 'Search',
        //template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        trigger: 'manual',
        content: function () {
            const search = new searchClass();
            return search.html;
            /*let content = '<form><div class="input-group">';
            content += '<input type="text" name="s" class="form-control form-control-sm" placeholder="Search">';
            content += '<div class="input-group-append">';
            content += '<button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search" /></button>';
            content += '</div>';
            content += '</div></form>';
            return content;*/
        }
    });

    button.on('click', function() {
        $(this).popover('toggle');
        const icon = $(this).find('.bi');
        icon.toggleClass('bi-search');
        icon.toggleClass('bi-x-lg');

        if (icon.hasClass('bi-search')) {
            //closed
            window.openedPopover = null;
        } else {
            //opened
            if (window.openedPopover && window.openedPopover !== this) {
                $(window.openedPopover).trigger('click');
            }

            window.openedPopover = this;
        }
        return false;
    });
});
