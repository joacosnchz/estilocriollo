<div class="bsktwitterbox_scroll container hidden-xs" data-widgetid="{$widget_id}" data-limit="{$tweets_limit}">
    <i class="icon-twitter"></i>
    {if $follow_btn}
        <div class="followBtn">
            <a href="https://twitter.com/{$user}" class="twitter-follow-button" data-dnt="true" data-show-count="false">Follow @{$user}</a>
            <script>!function(d, s, id) {
               var js, fjs = d.getElementsByTagName(s)[0];
               if (!d.getElementById(id)) {
                   js = d.createElement(s);
                   js.id = id;
                   js.src = "//platform.twitter.com/widgets.js";
                   fjs.parentNode.insertBefore(js, fjs);
               }
           }(document, "script", "twitter-wjs");</script>
        </div>
    {/if}
</div>