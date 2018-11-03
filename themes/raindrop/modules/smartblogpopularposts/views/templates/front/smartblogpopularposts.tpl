{if isset($posts) AND !empty($posts)}
    <div class="block blogModule boxPlain" id="popular_articles">
        <div class="frame_wrap frame_wrap_header clearfix">
            <div class="fwh-title"><a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Popular Articles' mod='smartblogpopularposts'}</a></div>
        </div>
        <div class="block_content sdsbox-content frame_wrap frame_wrap_content clearfix">
            <ul class="popularArticles">
                {foreach from=$posts item="post"}
                    {assign var="options" value=null}
                    {$options.id_post= $post.id_smart_blog_post}
                    {$options.slug= $post.link_rewrite}
                    <li>
                        <a class="image"
                           title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                            <img alt="{$post.meta_title}"
                                 src="{$modules_dir}/smartblog/images/{$post.post_img}-raindrop-sidebar.jpg">
                        </a>
                        <a class="title"  title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.meta_title}</a>
                        <span class="info">{$post.created|date_format:"%b %d, %Y"}</span>
                    </li> 
                {/foreach}
            </ul>
        </div>
        <div class="box-footer"><span></span></div>
    </div>
{/if}