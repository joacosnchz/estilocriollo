{if isset($posts) AND !empty($posts)}
    <div id="articleRelated">
        <div class="frame_wrap frame_wrap_header clearfix">
            <div class="fwh-title">{l s='Related Posts' mod='smartblogrelatedposts'}</div>
        </div>
        <div class="sdsbox-content frame_wrap frame_wrap_content clearfix"> 
            <ul class="fullwidthreleted">
                {foreach from=$posts item="post"}
                    {assign var="options" value=null}
                    {$options.id_post= $post.id_smart_blog_post}
                    {$options.slug= $post.link_rewrite}
                    <li>
                        <a class="title paddleftreleted"  title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.meta_title}</a>
                        <span class="info">{$post.created|date_format:"%b %d, %Y"}</span>
                    </li> 
                {/foreach}
            </ul>
        </div>
    </div>
{/if}