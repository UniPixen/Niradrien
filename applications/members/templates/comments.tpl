<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.comments}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
            </div>
        </div>
    </div>
</section>

<div id="commission">
    <div id="commission-container">
        <div class="btn btn btn-big-shadow btn-grey">
            {$lang.commission} : {$commission.percent} &#37;
        </div>
    </div>
</div>

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    {include file="$root_path/applications/members/templates/author-tab.tpl"}
</div>

<div id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span12">
                {if $comments}
                    <div class="liste-commentaires">
                        {foreach from=$comments item=c}
                            <div class="commentaire row">
                                <div class="span1 commentaire-auteur">
                                    <div class="avatar-wrapper">
                                        <a href="/member/{$members[$c.member_id].username}" class="avatar" title="{$members[$c.member_id].username}">
                                            {if $members[$c.member_id].avatar != ''}
                                                <img alt="{$members[$c.member_id].username}" class="" src="{$data_server}uploads/members/{$c.member_id}/{$members[$c.member_id].avatar}" />
                                            {else}
                                                <img alt="{$members[$c.member_id].username}" class="" src="{$data_server}images/member-default.png" />
                                            {/if}
                                        </a>
                                    </div>
                                </div>
                                <div class="span7 commentaire-container">
                                    <div class="commentaire-message">
                                        <div class="commentaire-header">
                                            <a href="/member/{$members[$c.member_id].username}" class="commentaire-nom-auteur">{$members[$c.member_id].username}</a>
                                            <div class="commentaire-date">
                                                {$lang.posted} {$c.datetime|date_format:"%d/%m/%Y"}
                                                <a href="/product/{$c.product_id}/{$c.product_name|url}">{$c.product_name}</a>
                                            </div>
                                        </div>
                                        <p>{$c.comment|nl2br}</p>
                                    </div>
                                    {if isset($c.reply)}
                                        {foreach from=$c.reply item=cc}
                                            <div class="row">
                                                <div class="span7">
                                                    <div class="commentaire-reponse">
                                                        <a href="/member/{$members[$cc.member_id].username}" class="commentaire-reponse-auteur-avatar">
                                                            {if $members[$cc.member_id].avatar != ''}
                                                                <img alt="{$members[$cc.member_id].username}" src="{$data_server}uploads/members/{$cc.member_id}/{$members[$cc.member_id].avatar}" />
                                                                {else}
                                                                <img alt="{$members[$cc.member_id].username}" src="/static/images/member-default.png" />
                                                            {/if}
                                                        </a>
                                                        <div class="commentaire-reponse-message">
                                                            <div class="commentaire-header">
                                                                <a href="/member/{$members[$cc.member_id].username}" class="commentaire-nom-auteur">{$members[$cc.member_id].username}</a>
                                                                <div class="commentaire-date">
                                                                    {$lang.posted} {$cc.datetime|date_format:"%d.%m.%Y"}
                                                                </div>
                                                            </div>
                                                            <p>{$cc.comment|nl2br}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}
                                    <div class="reponse" id="reponse-{$c.id}"></div>
                                    {literal}
                                        <script>
                                            $("{/literal}#reponse-{$c.id}{literal}").load("{/literal}/product/reply/{$c.id}{literal}");
                                        </script>
                                    {/literal}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.nobody_comments_products}.
                {/if}
            </div>
        </div>
    </div>
</div>

{literal}
    <script>
        $(function () {
            $("textarea").live("keyup keydown", function () {
                var h = $(this);
                h.height(20).height(h[0].scrollHeight);
            });
        });
    </script>
{/literal}