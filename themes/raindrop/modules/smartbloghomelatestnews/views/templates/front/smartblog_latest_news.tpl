{if !empty($view_data)}
<div class="block">
    <div class="frame_wrap frame_wrap_header clearfix">
        <div class='fwh-title'><a href="{smartblog::GetSmartBlogLink('smartblog')}">{l s='Latest News' mod='smartbloghomelatestnews'}</a></div>
    </div>
    <div class="frame_wrap frame_wrap_content clearfix">
        <div class="row">
            {assign var='i' value=1}
            {foreach from=$view_data item=post}
                    {assign var="options" value=null}
                    {$options.id_post = $post.id}
                    {$options.slug = $post.link_rewrite}
                    <div class="sds_blog_post col-xs-12 col-sm-4 col-md-3">
                        <span class="news_module_image_holder">
                             <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"><img alt="{$post.title}" class="feat_img_small" src="{$modules_dir}smartblog/images/{$post.post_img}-home-default.jpg"></a>
                        </span>
                        <h4 class="sds_post_title"><a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$post.title}</a></h4>
                        <p>
                            {$post.short_description|escape:'htmlall':'UTF-8'|truncate:50:'...'} <a href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}"  class="r_more">{l s='read more' mod='smartbloghomelatestnews'}</a>
                        </p>
                        <span class="post-date">Posted on {$post.date_added|date_format}</span>
                    </div>
                
                {$i=$i+1}
            {/foreach}
        </div>
     </div>
</div>
{/if}