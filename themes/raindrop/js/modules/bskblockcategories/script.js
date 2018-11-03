$(document).ready(function() {
    if (vertical_menu_fixed) {
        var htm = $('#hookTopMenu');
        var vm = $('#bskblockcategories');

        mediaQueries.addFunc({
            breakpoint: ['md', 'lg'],
            enter: function() {
                // unite hookTopMenu with bskblockcategories
                if (htm.length && vm.length) {
                    var offset = vm.offset().top - (htm.offset().top + htm.height());
                    vm.css('margin-top', '-'+offset+'px');
                }
            }
        });
    }
});