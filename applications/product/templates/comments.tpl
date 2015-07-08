{if $product.status == 'active'}
    {if $product.votes > 2}
        <div id="modal-rating" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-rating" aria-hidden="true">
            <h4>{$lang.buyers_ratings}</h4>
            <div id="rating-stars">
                {section name=foo start=1 loop=6 step=1}
                    {if $productRatings.average >= $smarty.section.foo.index}
                        <i class="hd-star on"></i>
                    {else}
                        <i class="hd-star off"></i>
                    {/if}
                {/section}
            </div>
            <p>{$lang.average_of} {$productRatings.average} {$lang.based_on|lower} {$productRatings.count} {$lang.ratings|lower}.</p>
            <ul class="rating-stats">
                {section name=foo start=1 loop=6 step=1}
                    <li>
                        <span class="rating-level">
                            {$smarty.section.foo.index}
                            {if $smarty.section.foo.index > 1}
                                {$lang.stars|@lower}
                            {else}
                                {$lang.star|@lower}
                            {/if}
                        </span>
                        <div class="rating-graph">
                            <div class="rating-graph-bar">
                                <div class="rating-graph-bar-progress" style="width: {math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%;">{math equation="{$productRatings.stats.{$smarty.section.foo.index}} / {$productRatings.count} * 100" format="%.0f"}%</div>
                            </div>
                        </div>
                        <span class="rating-count">{$productRatings.stats.{$smarty.section.foo.index}}</span>
                    </li>
                {/section}
            </ul>
        </div>
    {/if}
    <section id="titre-page" class="titre-item">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title{if $product.name|strlen > 20} small-title{/if}" itemprop="name">{$product.name}</h1>
                    {if $product.votes > 2}
                        <div id="note-produit" itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating" data-placement="right" data-toggle="tooltip" data-original-title="{$lang.average_of} {$product.rating}, {$lang.based_on|@lower} {$product.votes} {$lang.ratings}.">
                            <meta content="{$product.rating}" itemprop="ratingValue">
                            <meta content="{$product.votes}" itemprop="ratingCount">
                            {section name=foo start=1 loop=6 step=1}
                                {if $product.rating >= $smarty.section.foo.index}
                                    <i class="hd-star on"></i>
                                {else}
                                    <i class="hd-star off"></i>
                                {/if}
                            {/section}
                            <a href="#modal-rating" data-toggle="modal" role="button">{$product.votes} {$lang.buyers_ratings|lower}</a>
                        </div>
                    {else}
                        <div id="note-produit" class="unknow-note" data-placement="right" data-toggle="tooltip" data-original-title="Moins de 3 votes, note inconnue">
                            <i class="hd-star-unknow"></i>
                            <i class="hd-star-unknow"></i>
                            <i class="hd-star-unknow"></i>
                            <i class="hd-star-unknow"></i>
                            <i class="hd-star-unknow"></i>
                        </div>
                    {/if}
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/category/all">{$lang.files}</a> \
                    {foreach from=$product.categories item=e}
                        {foreach from=$e item=c name=foo}
                            <a href="/category/{$categories[$c].keywords|url}" title="">
                                {if $currentLanguage.code != 'fr'}
                                    {assign var='foo' value="name_`$currentLanguage.code`"}
                                    {$categories[$c].$foo}
                                {else}
                                    {$categories[$c].name}
                                {/if}
                            </a>
                            {if !$smarty.foreach.foo.last} \ {/if}
                        {/foreach}
                    {/foreach} \
                </div>
            </div>
        </div>
    </section>

    <div id="tab" class="container">
        <ul>
            <li class="{if $smarty.get.controller != 'faq' && $smarty.get.controller != 'comments' && $smarty.get.controller != 'edit'} selected {/if} ">
                <a href="/product/{$product.id}/{$product.name|url}">{$lang.product_details}</a>
            </li>
            {if $faqs>0 || check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                <li class="{if $smarty.get.controller == 'faq'}selected{/if}" id="faq-tab">
                    <a href="/product/{$product.id}/{$product.name|url}/faq">{$lang.faqs}</a>
                </li>
            {/if}
            {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                <li class="{if $smarty.get.controller == 'edit'}selected{/if}" >
                    <a href="/product/{$product.id}/{$product.name|url}/edit">{$lang.edit}</a>
                </li>
            {/if}
            <li class="{if $smarty.get.controller == 'comments'}selected last{/if}  {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}last{/if}">
                <a href="/product/{$product.id}/{$product.name|url}/comments">{$lang.comments}</a>
            </li>
        </ul>
    </div>

    {include file="$root_path/applications/product/templates/conteneur-paiement.tpl"}

    <section class="container page">
        <div class="row">
            <div id="commentaires" class="span8">
                {if $paging}
                    <div class="page-controls">
                        <div class="pagination">
                            {$paging}
                        </div>
                    </div>
                {/if}

                {if $comments}
                    <div class="liste-commentaires">
                        {foreach from=$comments item=c}
                            <div class="commentaire row">
                                <div class="span1 commentaire-auteur">
                                    <a href="/member/{$members[$c.member_id].username}" class="avatar" title="{$members[$c.member_id].username}">
                                        {if $members[$c.member_id].avatar != ''}
                                            <img alt="{$members[$c.member_id].username}" class="" src="{$data_server}uploads/members/{$c.member_id}/{$members[$c.member_id].avatar}" width="80" height="80">
                                        {else}
                                            <img alt="{$members[$c.member_id].username}" class="" src="{$data_server}images/member-default.png" width="80" height="80">
                                        {/if}
                                    </a>
                                    <ul class="badges">
                                        {foreach from=$badges[$c.member_id] item=b name=foo}
                                            {if $currentLanguage.code != 'fr'}
                                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                                <li data-original-title="{$b.$foo|escape}" title="{$b.$foo|escape}" data-toggle="tooltip" data-placement="right">
                                                    <img src="{$data_server}{$b.photo}" alt="{$b.$foo|escape}" />
                                                </li>
                                            {else}
                                                <li data-original-title="{$b.name|escape}" title="{$b.name|escape}" data-toggle="tooltip" data-placement="right">
                                                    <img src="{$data_server}{$b.photo}" alt="{$b.name|escape}" />
                                                </li>
                                            {/if}
                                        {/foreach}
                                    </ul>
                                </div>
                                <div id="comment-{$c.id}" class="span7 commentaire-container">
                                    <div class="commentaire-message">
                                        <div class="commentaire-header">
                                            <a href="/member/{$members[$c.member_id].username}" class="commentaire-nom-auteur">{$members[$c.member_id].username}</a>
                                            {if $product.member_id == $c.member_id}
                                                <strong class="commentaire-type-auteur">{$lang.author}</strong>
                                            {elseif isset($buyFromMembers[$c.member_id])}
                                                <strong class="commentaire-type-acheteur">{$lang.purchased}</strong>
                                            {/if}
                                            <div class="commentaire-date">
                                                {$lang.posted} {$c.datetime|date_format:"%d/%m/%Y"}
                                                {if check_login_bool()}
                                                    {if $c.report_by == '0'}
                                                        <div id="signalement-{$c.id}" class="modal-signalement modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <h4>{$lang.report_the_comment}</h4>
                                                            <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                                                                <div class="input-signalement">
                                                                <textarea name="report_reason" placeholder="{$lang.explain_why_report}"></textarea>
                                                                </div>

                                                                <input type="hidden" name="report" value="{$c.id}" />
                                                                <button type="submit" class="btn btn-big-shadow btn-signaler-commentaire">
                                                                    <i class="hd-bookmark"></i>
                                                                    {$lang.submit_report}
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <a role="button" href="#signalement-{$c.id}" data-toggle="modal" class="signaler-commentaire" title="{$lang.report}">
                                                            <i class="hd-flag"></i>
                                                        </a>
                                                    {else}
                                                        <i class="hd-flag commentaire-signale" title="{$lang.comment_reported}"></i>
                                                    {/if}
                                                {/if}
                                            </div>
                                        </div>
                                        <p>{$c.comment|nl2br}</p>
                                    </div>
                                    {if isset($c.reply)}
                                        {foreach from=$c.reply item=reply}
                                            <div class="row">
                                                <div class="span7">
                                                    <div class="commentaire-reponse">
                                                        <a href="/member/{$members[$reply.member_id].username}" class="commentaire-reponse-auteur-avatar">
                                                            {if $members[$reply.member_id].avatar != ''}
                                                                <img alt="{$members[$reply.member_id].username}" height="30" src="{$data_server}uploads/members/{$reply.member_id}/{$members[$reply.member_id].avatar}" width="30" />
                                                            {else}
                                                                <img alt="{$members[$reply.member_id].username}" class="avt" height="30" src="/static/images/member-default.png" width="30" />
                                                            {/if}
                                                        </a>
                                                        <div class="commentaire-reponse-message">
                                                            <div class="commentaire-header">
                                                                <a href="/member/{$members[$reply.member_id].username}" class="commentaire-nom-auteur">{$members[$reply.member_id].username}</a>
                                                                {if $product.member_id == $members[$reply.member_id].member_id}
                                                                    <strong class="commentaire-type-auteur">{$lang.author}</strong>
                                                                {elseif isset($buyFromMembers[$c.member_id])}
                                                                    <strong class="commentaire-type-acheteur">{$lang.purchased}</strong>
                                                                {/if}
                                                                <div class="commentaire-date">
                                                                    {$lang.posted} {$reply.datetime|date_format:"%d/%m/%Y"}
                                                                    {if check_login_bool()}
                                                                        {if $reply.report_by == '0'}
                                                                            <div id="signalement-{$reply.id}" class="modal-signalement modal hide fade" tabindex="-1" role="dialog"  aria-hidden="true">
                                                                                <h4>{$lang.report_the_comment}</h4>
                                                                                <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                                                                                    <div class="input-signalement">
                                                                                    <textarea name="report_reason" placeholder="{$lang.explain_why_report}"></textarea>
                                                                                    </div>

                                                                                    <input type="hidden" name="report" value="{$reply.id}" />
                                                                                    <button type="submit" class="btn btn btn-big-shadow btn-signaler-commentaire">
                                                                                    <i class="hd-bookmark"></i> {$lang.submit_report}
                                                                                    </button>
                                                                                </form>
                                                                            </div>
                                                                            <a role="button" href="#signalement-{$reply.id}" data-toggle="modal" class="signaler-commentaire" title="{$lang.report}">
                                                                                <i class="hd-flag"></i>
                                                                            </a>
                                                                        {else}
                                                                            <i class="hd-flag commentaire-signale" title="{$lang.comment_reported}"></i>
                                                                        {/if}
                                                                    {/if}
                                                                </div>
                                                            </div>
                                                            <p>{$reply.comment|nl2br}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}
                                    {if check_login_bool()}
                                        <div class="reponse" id="reponse-{$c.id}"></div>
                                        {literal}
                                            <script>
                                                $("{/literal}#reponse-{$c.id}{literal}").load("{/literal}/product/reply/{$c.id}{literal}");
                                            </script>
                                        {/literal}
                                    {/if}
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    {$lang.nobody_comments}.
                {/if}

                {if check_login_bool()}
                    <div id="ajouter-commentaire">
                        <h3>{$lang.add_a_comment}</h3>
                        <div class="row">
                            <div class="span1">
                                <a href="/member/{$smarty.session.member.username}" class="avatar" title="{$smarty.session.member.username}">
                                    {if $smarty.session.member.avatar != ''}
                                        <img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" />
                                    {else}
                                        <img alt="{$smarty.session.member.username}" src="{$data_server}images/member-default.png" />
                                    {/if}
                                </a>
                            </div>
                            <div class="span7">
                                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                    <div class="row-input">
                                        <textarea class="big input supertall" name="comment" id="commentaire" placeholder="{$lang.comment}"></textarea>
                                    </div>
                                    <div class="envoyer-commentaire">
                                        <button type="submit" name="add" class="btn btn-little-shadow">
                                            <i class="hd-comment"></i> {$lang.post_comment}
                                        </button>
                                    </div>
                                    <div class="abonnement-commentaire">
                                        <label class="checkbox">
                                            <input name="reply_notification" type="checkbox" value="1" />
                                            {$lang.comment_reply_notify}
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {literal}
                        <script>
                            $(function(){
                                $("textarea").live("keyup keydown",function(){
                                    var h=$(this);
                                    h.height(20).height(h[0].scrollHeight);
                                });
                            });
                        </script>
                    {/literal}
                {/if}
            </div>
            {include file="$root_path/applications/product/templates/aside-fichier.tpl"}
        </div>
    </section>

    <script src="{$data_server}js/licenses.js"></script>
{else}
    <div id="page-not-available-product">
        <div class="container page">
            <div class="row">
                <article id="not-available-product" class="span8">
                    <h1>{$lang.product_not_longer_available}</h1>
                    <p>{$lang.not_longer_available_sentences}</p>
                    <div id="fichiers-interessants">
                        <span>{$lang.some_interesting_products} :</span>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                        <div class="thumbnail"></div>
                    </div>
                </article>
                <div id="not-available-product-picture" class="span4">
                    <img src="{$data_server}images/carton-vide.svg" alt="{$lang.empty_box}" />
                </div>
            </div>
        </div>
    </div>
{/if}