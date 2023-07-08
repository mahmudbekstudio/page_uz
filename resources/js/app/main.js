import './search_button';
import './account_button';
import './cart_button';

window.openedPopover = null;

$(document)
    .on('click', '.template-block-border', function (e) {
        parent.window.iframeClick(e);
    })
    .on('mouseover', '.template-block-border', function (e) {
        parent.window.iframeMouseOver(e);
    })
    .on('mouseleave', '.template-block-border', function (e) {
        parent.window.iframeMouseLeave(e);
    });

/*const popup = $('#website-main-popup');
window.getWebsitePopup = function(x, y, width) {
    popup.css('left', x);
    popup.css('top', y);
    popup.css('width', width);
    return popup;
}*/
