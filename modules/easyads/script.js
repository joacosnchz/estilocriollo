$(document).ready(function() {
    $('.embed_popup').fancybox({
        beforeLoad: function() {
            this.title = $(this.element).attr('title');
        }
    });
    
    if ($.type(easyads_stats) !== 'undefined' && easyads_stats) {
        $('.easyads .banner').click(function(){
            var $me = $(this);
            $.post(
                easyads_dir+'ajax_clicks.php',
                '&id='+$me.data('id')+'&id_lang='+id_lang,
                function(data){
//                    console.log(data);
            });
        });
    }
});