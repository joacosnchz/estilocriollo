{if isset($categories) AND !empty($categories)}
    <div id="category_blog_block_left"  class="block blogModule boxPlain">
        <div class="frame_wrap frame_wrap_header clearfix">
            <div class="fwh-title"><a href="{smartblog::GetSmartBlogLink('smartblog_list')}">{l s='Blog Categories' mod='smartblogcategories'}</a></div>
        </div>
        
        <div class="block_content list-block frame_wrap frame_wrap_content clearfix">
            <ul>
                {foreach from=$categories item="category"}
                    {assign var="options" value=null}
                    {$options.id_category = $category.id_smart_blog_category}
                    {$options.slug = $category.link_rewrite}
                    <li>
                        <a href="{smartblog::GetSmartBlogLink('smartblog_category',$options)}">{$category.meta_title} <span>({$category.count})</span></a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}