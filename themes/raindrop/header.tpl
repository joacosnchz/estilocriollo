<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 " lang="{$lang_iso}"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7" lang="{$lang_iso}"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8" lang="{$lang_iso}"><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9" lang="{$lang_iso}"><![endif]-->
<html lang="{$lang_iso}">
    <head>
        <meta charset="utf-8" />
        <title>{$meta_title|escape:'html':'UTF-8'}</title>
        {if isset($meta_description) AND $meta_description}
            <meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
        {/if}
        {if isset($meta_keywords) AND $meta_keywords}
            <meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
        {/if}
        <meta name="generator" content="DSN Empresas" />

        <meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />

        <meta name="author" content="DSN Empresas" />

        <meta name="creator" content="DSN Empresas" />

        <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" /> 
        <meta name="apple-mobile-web-app-capable" content="yes" /> 
        <link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
        <link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
        {if isset($css_files)}
            {foreach from=$css_files key=css_uri item=media}
                <link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
            {/foreach}
        {/if}
        {if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
            {$js_def}
            {foreach from=$js_files item=js_uri}
            <script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
            {/foreach}
        {/if}
        {$HOOK_HEADER}
        <link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family=Open+Sans:300,600" type="text/css" media="all" />
        <!--[if IE 8]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($header_type)} header_{$header_type}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} has-left-column{/if}{if $hide_right_column} hide-right-column{else} has-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">
        {if !isset($content_only) || !$content_only}
            {if isset($restricted_country_mode) && $restricted_country_mode}
                <div id="restricted-country">
                    <p>{l s='You cannot place a new order from your country.'} <span class="bold">{$geolocation_country|escape:'html':'UTF-8'}</span></p>
                </div>
            {/if}
            <div id="page">
                <div class="header-container">
                    <header id="header" class="fadeInScroll">
                        <div class="banner">
                            <div class="container">
                                <div class="row">
                                    {hook h="displayBanner"}
                                </div>
                            </div>
                        </div>
                        <div class="nav">
                            <div class="container">
                                <div class="row">
                                    {* @todo-bsk Option to display home link or not*}
                                    <div class="col-xs-12">
                                        <div class="home">
                                            <a href="{$link->getPageLink('index')}">{l s='Home'}</a>
                                        </div>
                                        {if isset($HOOK_NAV_MAIN_LINKS)}
                                            <div id="header_nav_main_links" class="clearfix">
                                                {$HOOK_NAV_MAIN_LINKS}
                                            </div>
                                        {/if}
                                        <div id="header_scl" class="clearfix">
                                            {hook h="displayNav"}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- #navSecondary -->
                        <div id="navSecondary">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <a id="leftMenu" class="visible-xs visible-sm" href="#left-menu"><i class="icon-reorder"></i></a>
                                        <a href="{$base_dir}" id="header_logo" title="{$shop_name|escape:'html':'UTF-8'}">
                                            <img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width}{* width="{$logo_image_width} *}{/if}{if isset($logo_image_height) && $logo_image_height} {* height="{$logo_image_height}" *} {/if}/>
                                        </a>
                                    {if isset($HOOK_TOP)}{$HOOK_TOP}{/if}
                                    </div>
                                </div>
                            </div>
                        </div><!-- /#navSecondary -->
                    {if isset($HOOK_SLIDER)}
                        <!-- #sliderWrapper -->
                        <div id="sliderWrapper" class="container frame_wrap hidden-xs hidden-sm">{$HOOK_SLIDER}</div>
                        <!-- /#sliderWrapper -->
                    {/if}
                    {if isset($HOOK_ADS)}
                        <!-- #adsWrapper -->
                        <div id="adsWrapper" class="container">{$HOOK_ADS}</div>
                        <!-- /#adsWrapper -->
                    {/if}
                    {if isset($HOOK_TOP_MENU)}
                        <div id="hookTopMenu" class="container hidden-xs hidden-sm">
                            <div class="row">
                                <div class="cat_title {if !$hide_left_column && $vertical_menu_fixed} col-sm-3{else} hidden{/if}"><h2>{l s="Categorias" mod="blocktopmenu"}</h2></div>
                                <div class="{if !$hide_left_column && $vertical_menu_fixed} col-sm-9{else} col-sm-12{/if}">
                                {$HOOK_TOP_MENU}
                                </div>
                            </div>
                        </div>
                    {/if}
                </header>
            </div>
            <div class="columns-container">
                <div id="columns" class="container">
                    {if $page_name !='index' && $page_name !='pagenotfound'}
                        {include file="$tpl_dir./breadcrumb.tpl"}
                    {/if}
                    <div id="slider_row" class="row">
                        <div id="top_column" class="center_column col-xs-12 col-sm-12">{hook h="displayTopColumn"}</div>
                    </div>
                    <div class="row">
                        {if isset($left_column_size) && !empty($left_column_size)}
                            <div id="left_column" class="column fadeInScroll col-xs-12 col-sm-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
                        {/if}
                        {if isset($left_column_size) && isset($right_column_size)}{assign var='cols' value=(12 - $left_column_size - $right_column_size)}{else}{assign var='cols' value=12}{/if}
                        <div id="center_column" class="center_column col-xs-12 col-sm-{$cols|intval}">
                        {if isset($HOOK_SLIDER2)}
                            <!-- #displaySlider2 -->
                            <div id="displaySlider2" class="frame_wrap clearfix fadeInScroll hidden-xs hidden-sm">{$HOOK_SLIDER2}</div>
                            <!-- /#displaySlider2 -->
                        {/if}
            {/if}
