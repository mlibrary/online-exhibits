if (!ThanksRoy) {
    var ThanksRoy = {};
}

(function ($) {

    ThanksRoy.mobileMenu = function() {
        var subNavId = 1;
        $('#primary-nav li ul').each(function() {
            var childMenu = $(this);
            var subnavToggle = $('<button type="button" class="sub-nav-toggle" aria-label="Toggle subnavigation" aria-expanded="false"></button>');
            childMenu.parent().addClass('parent');
            childMenu.attr('id', 'sub-nav-' + subNavId);
            subnavToggle.attr('aria-controls', 'sub-nav-' + subNavId);
            subNavId++;
            childMenu.addClass('sub-nav').before(subnavToggle);
        });

        $('#primary-nav').on('click', '.sub-nav-toggle', function() {
            var subnavToggle = $(this);
            subnavToggle.parent('.parent').toggleClass('open');
            if (subnavToggle.attr('aria-expanded', 'false')) {
                subnavToggle.attr('aria-expanded', 'true');
            } else {
                subnavToggle.attr('aria-expanded', 'false');
            }
        });

        $(document).on('click', '.menu-button', function(e) {
            e.preventDefault();
            var toggle = $(this);
            var navigation = $('#primary-nav ul.navigation');
            navigation.toggleClass('open');
            if (navigation.hasClass('open')) {
                toggle.attr('aria-expanded', 'true');
            } else {
                toggle.attr('aria-expanded', 'false')
            }
        });
    };

})(jQuery);
