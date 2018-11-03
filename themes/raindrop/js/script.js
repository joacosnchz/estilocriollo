$(document).ready(function() {
    
    console.log('media query: '+mediaQueries.getBreakpoint());
    
    // inject svg icons
    var imgsvg = document.querySelectorAll('img.icon-svg');
    SVGInjector(imgsvg, {
      evalScripts: 'once',
//      pngFallback: 'assets/png'
    });

    /* Top Bar */
    var sticky_navigation_offset_top = $('#navSecondary').offset().top;
    var sticky_navigation = function() {
        var scroll_top = $(window).scrollTop(); // current vertical position from the top
        if (scroll_top > sticky_navigation_offset_top) {
            $('#navSecondary').css({'position': 'fixed', 'top': 0, 'left': 0});
        } else {
            $('#navSecondary').css({'position': 'relative'});
        }
    };
    /* Top Bar */
    
    /* width >= 992 */
    mediaQueries.addFunc({
        breakpoint: ['md', 'lg'],
        enter: function() {
            if (navSecondaryFixed) {
                sticky_navigation();
                $(window).scroll(function() {
                    sticky_navigation();
                });
            }
            
            // Tooltips
            $('.add_to_compare').tooltip();
            $('.addToWishlist').tooltip();
            $('#prev_link').tooltip();
            $('#next_link').tooltip();
        }
    });
    
    /* width < 992 */
    mediaQueries.addFunc({
        breakpoint: ['xs', 'sm'],
        enter: function() {
            /* device vertical menu */
            var left_menu_source = new Array();
            if ($('#search_block_top').length) {
                left_menu_source.push('#search_block_top');
            }
            if ($('#bskblockcategories').length) {
                left_menu_source.push('#bskblockcategories');
            }
            if ($('#block_top_menu').length) {
                left_menu_source.push('#block_top_menu');
            }
            $('#leftMenu').sidr({
                name: 'device-menu-left',
                source: left_menu_source.join()
            });
            $(window).touchwipe({
                wipeLeft: function() { // Close
                    $.sidr('close', 'device-menu-left');
                },
                wipeRight: function() { // Open
                    $.sidr('open', 'device-menu-left');
                },
                preventDefaultEvents: false
            });
            
            $('.sidr-class-frame_wrap_header').click(function(){
                $('.sidr-class-tree').toggle();
            });
            $('.sidr-class-cat-title').click(function(){
                $('.sidr-class-sf-menu').toggle();
            });
        }
    });
    
    /* Fix height for position: absolute */
    var productWrapper = $('.product-wrapper');
    productWrapper.css('height', productWrapper.height());
    
    /* Product Hover */
    var product = $('.ajax_block_product');
    var productImage = $('.product-list-image');
    
    product.hover(function() {
        var me = $(this);
        var newImage = me.find(productImage).attr('data-image-hover');
        me.find(productImage).attr('src', newImage);
        /* fix width for IE, Firefox: add width to the hovered image */
        var imageWidth = me.find(productImage).width();
        me.find(productImage).css('width', imageWidth);
        /* /fix width for IE, Firefox */
    }, function() {
        var me = $(this);
        var originalImage = me.find(productImage).attr('data-image');
        me.find(productImage).attr('src', originalImage);
        /* fix width for IE, Firefox: set width back to normal */
        me.find(productImage).css('width', "100%");
        /* /fix width for IE, Firefox */
    });
    
    var page = $("#page");
    if (pageLoader) {
        page.css('opacity', 0.7); // page opacity back to 1 is done in pace.js
    }
    
    /* Fade in on homepage */
    if (page_name == "index" && fadeInScroll) {
        var fadeInSections = $('.fadeInScroll');
        
        $(window).load(function() {
            makeSectionsVisible(fadeInSections);
        });

        $(window).scroll(function() {
            makeSectionsVisible(fadeInSections);
        });
    }
    /* & Fade in on homepage */

});

function makeSectionsVisible(sections) {
    var invisible = sections.filter(function(){ return $(this).css('opacity') == 0; });
    if (invisible.length) {
        invisible.each(function(){
            isVisible($(this));
        });
    }
}

function isVisible(e1) {
    if (e1) {
        var top_of_element = e1.offset().top;
        var bottom_of_screen = $(window).scrollTop() + $(window).height();

        if (bottom_of_screen > top_of_element) {
            e1.animate({
                'opacity': '1'
            }, 2000);

            return true;
        }
    } else {
        return false;
    }
}