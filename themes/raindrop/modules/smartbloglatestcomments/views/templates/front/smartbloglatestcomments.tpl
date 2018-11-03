{if isset($latesComments) AND !empty($latesComments)}
    <div class="block blogModule boxPlain">
        <div class="frame_wrap frame_wrap_header clearfix">
            <div class="fwh-title">{l s='Latest Comments' mod='smartbloglatestcomments'}</div>
        </div>
        <div class="block_content sdsbox-content frame_wrap frame_wrap_content clearfix">
            <ul class="recentComments">
                {foreach from=$latesComments item="comment"}
                    {assign var="options" value=null}
                    {$options.id_post= $comment.id_post}
                    {$options.slug= $comment.slug}
                    <li>
                        <a title="" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">
                            <img class="image" alt="Avatar" src="{$modules_dir}/smartblog/images/avatar/avatar-author-default.jpg">
                        </a>
                        <div class="author-name">
                            {$comment.name}
                        </div>
                        <a class="title" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}">{$comment.content}</a>
                    </li>
                {/foreach}
            </ul>
        </div>
        <div class="box-footer"><span></span></div>
    </div>
{/if}