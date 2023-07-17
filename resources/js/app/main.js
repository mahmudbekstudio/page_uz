import './search_button';
import './account_button';
import './cart_button';

window.openedPopover = null;
parent.window.activeBlock = null;

$(document)
    .on('click', '.template-block-border', function (e) {
        const activeClassName = 'template-block-border-active';
        const templateBlock = $(this).first();

        if (!templateBlock.hasClass(activeClassName) && parent.window.activeBlock && parent.window.activeBlock.id) {
            $('#' + parent.window.activeBlock.id).removeClass(activeClassName);
        }

        parent.window.iframeClick(e);

        if (templateBlock.hasClass(activeClassName)) {
            templateBlock.removeClass(activeClassName);
        } else {
            templateBlock.addClass(activeClassName);
        }
    });

/*const popup = $('#website-main-popup');
window.getWebsitePopup = function(x, y, width) {
    popup.css('left', x);
    popup.css('top', y);
    popup.css('width', width);
    return popup;
}*/
