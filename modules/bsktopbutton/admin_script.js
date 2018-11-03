$(document).ready(function() {
    var select = $('#style');

    var second_form_group = $('.panel .form-group:nth-child(3)');

    if (select.val() == '1') {
        second_form_group.show();
    } else {
        second_form_group.hide();
    }

    select.change(function() {
        var select = $('#style');

        if (select.val() == '1') {
            second_form_group.show();
        } else {
            second_form_group.hide();
        }
    });
});