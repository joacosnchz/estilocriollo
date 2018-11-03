{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
    {if isset($params.type) && $params.type == 'link'}
        <a href="{$tr.$key|escape:'html':'UTF-8'}" target="_blank">{$tr.$key|escape:'html':'UTF-8'}</a>
    {elseif isset($params.type) && $params.type == 'image'}
        <img class="img-thumbnail" src="{$params.image_baseurl}{$tr.$key|escape:'html':'UTF-8'}" style="height: 100px">
    {elseif isset($params.type) && $params.type == 'movable'}
        <span class="banner_li" data-id="{$tr.$key|escape:'html':'UTF-8'}"><i class="icon-arrows "></i></span>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}