$(function() {
    var banners = $('.easyads tbody');
    banners.sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function() {
            var ids = '';
            $(this).find('.banner_li').each(function(){
                ids += '&ids[]='+$(this).data('id');
            });
            var order = ids + '&action=updatePosition';
            $.post(module_dir+'easyads/ajax_action.php', order, function(data){
                //console.log(data);
            });
        }
    });
});

$(document).ready(function(){
    exceptionsInit();
    embedList();
    
    $('.easyads').on('click', '.list-action-enable.disabled', function(e){
        e.preventDefault();
    });
    $('.easyads').on('click', 'td.no-action .list-action-enable', function(e){
        e.preventDefault();
    });
    
    if ($('#embed_on').prop('checked')) {
        embedOptions('show');
    } else {
        embedOptions('hide');
    }
    $('#embed_on').change(function(){
        embedOptions('show');
    });
    $('#embed_off').change(function(){
        embedOptions('hide');
    });
});

function exceptionsInit() {
    var active = $('#module_form .active_exceptions');
    var exceptions = $('#module_form .exceptions');
    
    function active_change(){
        var values = active.val().split(',');
        $.each(values, function(i, value){
            exceptions.children('option[value="'+$.trim(value)+'"]').prop('selected', true);
        });
    }
    active_change();
    
    active.change(function() {
        active_change();
    });
    
    exceptions.change(function() {
        active.val( exceptions.val().join(', ') );
    });
}

function embedOptions(status) {
    var embed_code = $('#embed_code').parent().parent();
    var embed_popup = $('#embed_popup_on').parent().parent().parent();
    var url = $('#url_1').parent().parent();
    var target = $('#target_on').parent().parent().parent();
    
    switch (status) {
        case 'hide':
            embed_code.hide();
            $('#embed_code').text('');
            embed_popup.hide();
            url.show();
            target.show();
            
            break;
            
        case 'show':
            embed_code.show();
            embed_popup.show();
            url.hide();
            $('#url_1').val('');
            target.hide();
            
            break;
            
        default: break;
    }
}

function embedList() {
    var listTable = $('.easyads');
    listTable.each(function(){
        var ads = $(this).find('tbody tr');
        ads.each(function(){
            var ad = $(this);
            var target = ad.children('.target');
            var embed = ad.children('.embed');
            var embed_popup = ad.children('.embed_popup');
            
            if (embed.children('.list-action-enable').hasClass('action-enabled')) {
                target.children('.list-action-enable').addClass('disabled').attr('title', 'Not available');
            } else {
                embed_popup.children('.list-action-enable').addClass('disabled').attr('title', 'Not available');
            }
        });
    });
}