<!DOCTYPE html>
<html>
    <head>
        <title>{$title}</title>
        {*All CSS files*}
        {foreach $css_files as $file}
        <link rel="stylesheet" href="{$file}" />
        {/foreach}
        {*All JS files*}
        {foreach $js_files as $file}
        <script src="{$file}"></script>
        {/foreach}
    </head>
    <body>
    <img id="loading" src="img/loading.gif" />
    <div id="bskGear" class="container">
        <form action="{$form_action}" method="post" id="bskGearForm" enctype="multipart/form-data">
        {$form_msg}
        <div class="row">
            <div class="col-md-3">
                <figure id="logo">
                    <img src="img/bskgear.png" />
                </figure>
                <ul class="nav nav-pills nav-stacked" id="settingsTabs">
                {foreach $sections as $section}
                    <li class="{if $section@first}active{/if} {if !$section->active}unsupported{/if}">
                        <a href="#{$section->name}" data-toggle="pill">{$section->label}</a>
                    </li>
                {/foreach}
                </ul>
                <div class="action_buttons clearfix">
                    <input type="image" name="saveSubmit" value="Save" title="Save" id="btnSave" src="img/save.png" />
                    <input type="image" name="exportSubmit" value="Export" title="Export" id="btnExport" src="img/export.png" />
                    <input type="image" name="importSubmit" value="Import" title="Import" id="btnImport" src="img/import.png" />
                    <input type="file" name="importFile" class="hidden" accept="application/json" />
                </div>
            </div>

            <div class="tab-content col-md-9">