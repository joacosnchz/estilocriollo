{if isset($tags) AND !empty($tags)}
<div  id="tags_blog_block_left"  class="block blogModule boxPlain">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class="fwh-title"><a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Tags Post' mod='smartblogtag'}</a></div>
    </div>
    <div class="block_content frame_wrap frame_wrap_content clearfix">
            {foreach from=$tags item="tag"}
            {assign var="options" value=null}
                {$options.tag = $tag.name}
                {if $tag!=""}
                    <a href="{smartblog::GetSmartBlogLink('smartblog_tag',$options)}">{$tag.name}</a>
                {/if}
            {/foreach}
   </div>
</div>
{/if}