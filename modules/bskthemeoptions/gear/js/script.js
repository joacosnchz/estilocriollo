$(document).ready(function() {

    var gear = $('#bskGear');
    var loading = $('#loading');
    var form = $('#bskGearForm');

    // adjust parent height from outside the object tag
    setTimeout(function() {
        parent.set_size($('body').outerHeight());
        loading.hide(0, function() {
            gear.css('visibility', 'visible');
        });
    }, 1000);
    $('#settingsTabs a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
        parent.set_size($('body').outerHeight());
    });

    // action buttons
    var btnSave = $('#btnSave');
    var btnExport = $('#btnExport');
    var btnImport = $('#btnImport');
    btnSave.tooltip({placement: 'right'});
    btnExport.tooltip();
    btnImport.tooltip();

    btnImport.click(function(e) {
        e.preventDefault();
        $('input[name=importFile]').click();
    });
    $('input[name=importFile]').change(function() {
        gear.css('opacity', 0.5);
        loading.show();
        form.submit();
    });

    // init switch button
    $('.switch input[type="checkbox"]').bootstrapSwitch('size', 'small');

    // init color picker
    $('.color_picker').spectrum({
        showInput: true,
        showAlpha: true,
        showInitial: true,
        showPalette: true,
        showSelectionPalette: true,
        clickoutFiresChange: true,
        maxPaletteSize: 10,
        chooseText: "Ok",
        preferredFormat: "rgb",
        localStorageKey: "bsktgear.default"
    });

    // pattern option
    $('.pattern').click(function() {
        $(this).siblings('.pattern').removeClass('active');
        $(this).addClass('active');
        $(this).siblings('input[type=hidden]').val($(this).data('pattern'));
    });

    // google fonts
    var addgfont = $('.addFont');
    var gfontsinput = $('input[name=googleFonts]');
    var gfonts = $('.gfonts');
    var gfonts_labels = $('.gfonts_active');
    $.getJSON("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBAuuSRZNghQBrd-0OJVP6dIIxeadUi4D0", function(fonts) {
        $.each(fonts.items, function(key, font) {
            gfonts.append($("<option></option>").val(font.family).text(font.family));
        });
    });
    addgfont.click(function(e) {
        e.preventDefault();
        var font = gfonts.val();
        var gfont = font.replace(/\s/g, '+');
        var gfontsactive = gfontsinput.val();
        if (gfontsactive.indexOf(gfont) === -1) { // it's not already an active font
            gfont += ':normal,italic,bold,bolditalic';
            if (gfontsactive !== '')
                gfontsactive += '|';
            gfontsinput.val(gfontsactive + gfont);
            gfonts_labels.append('<span class="label label-success" data-value="' + gfont + '">' + font + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></span>');
            gfonts.val(0);
            addgfont.attr('disabled', true);
            generate_font_list();
        } else {
            alert('Font is already used!');
        }
    });
    gfonts.change(function() {
        if ($(this).val() === '0') {
            addgfont.attr('disabled', true);
        } else {
            addgfont.attr('disabled', false);
        }
    });
    gfonts_labels.on('click', '.close', function() {
        var gfont = $(this).parent().data('value');
        var gfontsactive = gfontsinput.val();
        gfontsactive = gfontsactive.replace('|' + gfont, '');
        gfontsactive = gfontsactive.replace(gfont + '|', ''); // first font in line
        gfontsactive = gfontsactive.replace(gfont, ''); // only one font
        gfontsinput.val(gfontsactive);
        $(this).parent().remove();
        generate_font_list();
    });

    // choose fonts
    generate_font_list();
    function generate_font_list() {
        var font_list = $('.font_list');
        font_list.each(function() {
            var me = $(this);
            var g_list = me.children('.google_fonts');
            g_list.empty();
            $('.gfonts_active').children('.label').each(function() {
                var label = $(this)[0].childNodes[0].nodeValue.trim();
                var option = $("<option></option>").val(label).text(label);
                if (me.data('value') === label) {
                    option.attr('selected', true);
                }
                g_list.append(option);
            });
        });
    }
    $('.font_list').on('change', function(){
        var me = $(this);
        me.data('value', me.val());
    });
    
    // radio change
    $('.radio input[type=radio]').on('change', function(){
        var me = $(this);
        
        if (me.attr('name') === 'prodImageType') {
            prodImageType();
        }
    });
    
    // product - image type
    prodImageType();
    function prodImageType() {
        if ($('#prodImageType_1').prop('checked')) { // portrait
            $('#prodThumbsPosition_0').prop('checked', true); // select bottom
            $('#prodThumbsPosition_1').prop('disabled', true).parent().addClass('disabled');
            $('#prodThumbsPosition_2').prop('disabled', true).parent().addClass('disabled');
        } else { // landscape
            $('#prodThumbsPosition_1').prop('disabled', false).parent().removeClass('disabled');
            $('#prodThumbsPosition_2').prop('disabled', false).parent().removeClass('disabled');
        }
    }

    // css editor
    var css_editor_wrap = $('#cssEditor');
    var use_css_editor = $('input[name=useCssEditor]');
    var css_editor_textarea = $('textarea.cssEditor');
    if (css_editor_textarea.length) {
        var css_editor = ace.edit("cssEditor");
        css_editor.setTheme("ace/theme/solarized_dark");
        css_editor.getSession().setMode("ace/mode/css");
        form.submit(function() {
            var css_code = css_editor.getSession().getValue();
            css_editor_textarea.val(css_code);
        });
    }
    if (!use_css_editor.bootstrapSwitch('state')) {
        css_editor_wrap.hide();
    }
    use_css_editor.on('switchChange', function(e, data) {
        if (data.value) { // on
            css_editor_wrap.fadeIn();
        } else {
            css_editor_wrap.fadeOut();
        }
    });
    
    // install fixtures
    $('#installFixtures').on('click', function(){
        loading.show();
        gear.hide();
    });

});