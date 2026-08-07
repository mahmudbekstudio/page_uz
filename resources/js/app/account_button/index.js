$(function() {
    const button = $('.field-account_button');

    function loginForm() {
        return '<form class="form-signin">\n' +
            '  <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>\n' +
            '  <label for="inputEmail" class="sr-only">Email address</label>\n' +
            '  <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>\n' +
            '  <label for="inputPassword" class="sr-only">Password</label>\n' +
            '  <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>\n' +
            '  <div class="checkbox mb-3">\n' +
            '    <label>\n' +
            '      <input type="checkbox" value="remember-me"> Remember me\n' +
            '    </label>\n' +
            '  </div>\n' +
            '  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>\n' +
            '</form>';
    }

    function registrationForm() {
        return '<form class="form-signin">\n' +
            '  <h1 class="h3 mb-3 font-weight-normal">Please Registration</h1>\n' +
            '  <label for="inputEmail" class="sr-only">Email address</label>\n' +
            '  <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>\n' +
            '  <label for="inputPassword" class="sr-only">Password</label>\n' +
            '  <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>\n' +
            '  <button class="btn btn-lg btn-primary btn-block" type="submit">Registration</button>\n' +
            '</form>';
    }

    button.popover({
        html: true,
        sanitize: false,
        placement: 'bottom',
        title: 'Account',
        //template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
        trigger: 'manual',
        content: function () {
            let content = '<nav>';
            content += '<div class="nav nav-tabs" id="nav-tab-field-account_button" role="tablist">';
            content += '<button class="nav-link active" id="nav-field-account_button-login-tab" data-toggle="tab" data-target="#nav-tab-field-account_button-login" type="button" role="tab" aria-controls="nav-tab-field-account_button-login" aria-selected="true">Login</button>';
            content += '<button class="nav-link" id="nav-field-account_button-registration-tab" data-toggle="tab" data-target="#nav-tab-field-account_button-registration" type="button" role="tab" aria-controls="nav-tab-field-account_button-registration" aria-selected="false">Registration</button>';
            content += '</div>';
            content += '</nav>';
            content += '<div class="tab-content" id="nav-tab-field-account_buttonContent">';
            content += '<div class="tab-pane fade show active" id="nav-tab-field-account_button-login" role="tabpanel" aria-labelledby="nav-field-account_button-login-tab">';
            content += loginForm();
            content += '</div>';
            content += '<div class="tab-pane fade" id="nav-tab-field-account_button-registration" role="tabpanel" aria-labelledby="nav-field-account_button-registration-tab">';
            content += registrationForm();
            content += '</div>';
            content += '</div>';
            return content;
        }
    });

    button.on('click', function() {
        $(this).popover('toggle');
        const icon = $(this).find('.bi');
        icon.toggleClass('bi-person-circle');
        icon.toggleClass('bi-x-lg');

        if (icon.hasClass('bi-person-circle')) {
            window.openedPopover = null;
        } else {
            if (window.openedPopover && window.openedPopover !== this) {
                $(window.openedPopover).trigger('click');
            }

            window.openedPopover = this;
        }
        return false;
    });
});
