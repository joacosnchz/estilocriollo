$(document).ready(function() {
    // autoAdvance
    if(autoAdvance == '1') {
        autoAdvance = true;
    } else if(autoAdvance == '0') {
        autoAdvance = false;
    }
    
    // mobileAutoAdvance
    if(mobileAutoAdvance == '1') {
        mobileAutoAdvance = true;
    } else if(mobileAutoAdvance == '0') {
        mobileAutoAdvance = false;
    }
    
    // barDirection 
    if(barDirection == '1') {
        barDirection = 'leftToRight';
    } else if(barDirection == '2') {
        barDirection = 'rightToLeft';
    } else if(barDirection == '3') {
        barDirection = 'topToBottom';
    } else if(barDirection == '4') {
        barDirection = 'bottomToTop';
    }
    
    //barPosition
    if(barPosition == '1') {
        barPosition = 'left';
    } else if(barPosition == '2') {
        barPosition = 'right';
    } else if(barPosition == '3') {
        barPosition = 'top';
    } else if(barPosition == '4') {
        barPosition = 'bottom';
    }
    
    // hover
    if(hover == '1') {
        hover = true;
    } else if(hover == '0') {
        hover = false;
    }
    
    // loader
    if(loader == '1') {
        loader = 'pie';
    } else if(loader == '2') {
        loader = 'bar'
    } else if(loader == '3') {
        loader = 'none';
    }
    
    // navigation
    if(navigation == '1') {
        navigation = true;
    } else if(navigation == '0') {
        navigation = false;
    }
    
    // navigationHover
    if(navigationHover == '1') {
        navigationHover = true;
    } else if(navigationHover == '0') {
        navigationHover = false;
    }
    
    // pagination
    if(pagination == '1') {
        pagination = true;
    } else if(pagination == '0') {
        pagination = false;
    }
    
    // playPause
    if(playPause == '1') {
        playPause = true;
    } else if(playPause == '0') {
        playPause = false;
    }
    
    // pauseOnClick
    if(pauseOnClick == '1') {
        pauseOnClick = true;
    } else if(pauseOnClick == '0') {
        pauseOnClick = false;
    }
    
    // piePosition
    if(piePosition == '1') {
        piePosition = 'rightTop';
    } else if(piePosition == '2') {
        piePosition = 'leftTop';
    } else if(piePosition == '3') {
        piePosition = 'leftBottom';
    } else if(piePosition == '4') {
        piePosition = 'rightBottom';
    }
    
    // portrait
    if(portrait == '1') {
        portrait = true;
    } else if(portrait == '0') {
        portrait = false;
    }
    
    jQuery('#camera_wrap_1').camera({
        thumbnails: false, //TODO thumbnails option
        alignment: 'center', // TODO - not working?
        autoAdvance: autoAdvance,
        mobileAutoAdvance: mobileAutoAdvance,
        barDirection: barDirection,
        barPosition: barPosition,
        fx: fx,
        height: height,
        hover: hover,
        loader: loader,
        loaderColor: loaderColor,
        loaderBgColor: loaderBgColor,
        loaderOpacity: loaderOpacity,
        loaderPadding: parseInt(loaderPadding),
        loaderStroke: loaderStroke,
        minHeight: minHeight,
        navigation: navigation,
        navigationHover: navigationHover,
        pagination: pagination,
        playPause: playPause,
        pauseOnClick: pauseOnClick,
        pieDiameter: pieDiameter,
        piePosition: piePosition,
        portrait: portrait,
        time: parseInt(time),
        transPeriod: parseInt(transPeriod),
        imagePath: imagePath
    });
    jQuery('#camera_wrap_1').css('margin', '0');
});