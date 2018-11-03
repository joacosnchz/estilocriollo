/*
 * jQuery bskCarousel v1.0
 * @dependencies jQuery.scrollTo, jQuery.serialScroll, jQuery.touchSwipe
 * @license http://creativecommons.org/licenses/by/3.0/ CC BY 3.0
 * 

Usage:

- Markup:
<div id="bskCarousel">
    <div id="prevBtn">Prev</div>
    <div class="carousel">
        <ul>
            <li>Item 1</li>
            <li>Item 2</li>
        </ul>
    </div>
    <div id="nextBtn">Next</div>
</div>

- Script
$('#bskCarousel').bskCarousel({
    prev: '#prevBtn',
    next: '#nextBtn'
});

*/

(function( $ ) {
    
    if ( $.fn.bskCarousel ) { return; } // already loaded
 
    $.fn.bskCarousel = function( options ) {
        
        var base = this;
        
        if (base.length > 0) {
            var settings = $.extend({
                // These are the defaults.
                prev: null,
                next: null,
                swipe: true,
                axis: 'x',
                duration: 700,
                cycle: false
            }, options);

            /* Properties */
            var prop = {};
            prop.carousel = base.find('.carousel');
            if (!prop.carousel.length)
                throw new Error('Incorect markup: missing .carousel');
            prop.items = prop.carousel.find('li');
            prop.prev = $();
            prop.next = $();
            if ($.type(settings.prev) === 'string' && $.type(settings.next) === 'string') {
                prop.prev = base.find(settings.prev);
                prop.next = base.find(settings.next);
            }
            prop.swipe = true;
            if ($.type(settings.swipe) === 'boolean')
                prop.swipe = settings.swipe;
            /* & Properties */

            /* Functions */
            var FN = {};

            /**
             * Initiate serial scroll
             */
            FN.init = function() {
                var visible_nb = FN.getVisibleNb();
                FN.calcCarouselWidth();
                FN.makeItemsLink();
                if (prop.swipe)
                    FN.doSwipe();

                prop.carousel.serialScroll({
                    items: 'li',
                    prev: settings.prev,
                    next: settings.next,
                    axis: settings.axis,
                    offset: 0,
                    start: 0,
                    stop: true,
                    duration: settings.duration,
                    step: visible_nb,
                    lazy: true,
                    lock: false,
                    force: false,
                    cycle: settings.cycle,
                    onBefore: function serialScrollFixLock(event, targeted, scrolled, items, position) {
                        var items_nb = prop.items.length;
                        var visible_nb = FN.getVisibleNb();

                        if (position === 0) { // left arrow
                            prop.prev.addClass('disabled');
                        } else {
                            prop.prev.removeClass('disabled');
                        }

                        if (position + visible_nb >= items_nb) { // right arrow
                            prop.next.addClass('disabled');
                        } else {
                            prop.next.removeClass('disabled');
                        }

                        return true;
                    }
                });

                prop.carousel.trigger('goto', 1);// SerialScroll Bug on goto 0 ?
                prop.carousel.trigger('goto', 0);
            };

            /**
             * Swipe left and right events
             */
            FN.doSwipe = function() {
                prop.carousel.swipe({
                    swipe: function(event, direction, distance, duration, fingerCount) {
                        switch (direction) {
                            case 'left':
                                $(this).trigger('next');
                                break;

                            case 'right':
                                $(this).trigger('prev');
                                break;

                            default:
                                break;
                        }
                    }
                });
            };

            /**
             * Make items behave like anchor
             * Swipe doesn't work with anchor tags
             */
            FN.makeItemsLink = function() {
                prop.items.each(function() {
                    var link = $(this).data('href');
                    if (link.length > 0) {
                        $(this).css('cursor', 'pointer');
                        $(this).click(function() {
                            window.location.href = link;
                        });
                    }
                });
            };

            /**
             * Set visible width and seriallScroll width
             */
            FN.calcCarouselWidth = function() {
                FN.setVisibleWidth();
                FN.setInlineWidth();
                // see if visible width is higher than items width
                var vw = prop.carousel.width();
                var iw = prop.carousel.find('ul').width();
                if (vw > iw) {
                    prop.carousel.width(iw);
                }
            };

            /**
             * Get carousel width
             * @returns {Number}
             */
            FN.getWidth = function() {
                return parseInt(prop.carousel.width());
            };

            /**
             * Get item width
             * @returns {Number}
             */
            FN.getItemWidth = function() {
                return parseInt(prop.items.outerWidth(true));
            };

            /**
             * Get visibile items number
             * @returns {Number}
             */
            FN.getVisibleNb = function() {
                var visible_nb = Math.floor(FN.getWidth() / FN.getItemWidth());
                return visible_nb;
            };

            /**
             * Set the width of the carousel based on visible items
             * @param {Number|String} width
             */
            FN.setVisibleWidth = function(width) {
                if ($.type(width) !== 'undefined') {
                    prop.carousel.width(width);
                } else {
                    prop.carousel.width(FN.getVisibleNb() * FN.getItemWidth())
                }
            };

            /**
             * Set the width for inline items
             * @param {Number|String} width
             */
            FN.setInlineWidth = function(width) {
                if ($.type(width) !== 'undefined') {
                    prop.carousel.find('ul').width(width);
                } else {
                    prop.carousel.find('ul').width(prop.items.length * FN.getItemWidth());
                }
            };

            FN.init();

            return base;
        }

    };
 
}( jQuery ));