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
                <div id="breadcrumbs" class="span5" itemprop="breadcrumb">
                    <a href="/">{$lang.home}</a> \
                    <a href="/category/all/">{$lang.files}</a> \
                    {foreach from=$product.categories item=e}
                        {foreach from=$e item=c name=foo}
                            <a href="/category/{$categories[$c].keywords|url}">
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
            {if $faqs > 0 || check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                <li class="{if $smarty.get.controller == 'faq'}selected{/if}" id="faq-tab">
                    <a href="/product/{$product.id}/{$product.name|url}/faq">{$lang.faqs}</a>
                </li>
            {/if}
            {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                <li class="{if $smarty.get.controller == 'edit'}selected{/if}" >
                    <a href="/product/{$product.id}/{$product.name|url}/edit">{$lang.edit}</a>
                </li>
            {/if}
            <li class="last{if $smarty.get.controller == 'comments'}selected last{/if}  {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}last{/if}">
                <a href="/product/{$product.id}/{$product.name|url}/comments">{$lang.comments}</a>
            </li>
        </ul>
    </div>

    {include file="$root_path/applications/product/templates/conteneur-paiement.tpl"}

    <section id="conteneur" class="edit-item">
        <div class="container">
            <div class="row">
                <div id="faq" class="span8">
                    {if $faq}
                        {foreach from=$faq item=f key=index name=count}
                            <div class="question">
                                <span>{$f.question|nl2br}</span>
                                {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                                    <a href="/product/faq/{$product.id}/?del={$f.id}" data-original-title="{$lang.delete}" data-toggle="tooltip" data-placement="left">
                                        <i class="hd-trash"></i>
                                    </a>
                                {/if}
                                <div class="answer">{$f.answer|nl2br}</div>
                            </div>
                        {/foreach}
                    {/if}

                    {if check_login_bool() && $product.member_id == $smarty.session.member.member_id}
                        <div id="ajouter-faq"{if !$faq} class="no-faq"{/if}>
                            <form action="" method="post">
                                <div style="margin-bottom: 30px;">
                                    <h2>{$lang.create_faq}</h2>
                                </div>

                                <div style="margin-bottom: 30px;">
                                    <label class="inputs-label" for="item_question">{$lang.question}</label>
                                    <div class="inputs">
                                        <textarea name="question"></textarea>
                                    </div>
                                </div>

                                <div style="margin-bottom: 30px;">
                                    <label class="inputs-label" for="item_answer">{$lang.answer}</label>
                                    <div class="inputs">
                                        <textarea name="answer"></textarea>
                                    </div>
                                </div>

                                <div style="margin-bottom: 30px;">
                                    <input type="hidden" name="add" value="yes" />
                                    <button type="submit" name="add" class="btn">{$lang.create_faq}</button>
                                </div>
                            </form>
                        </div>
                    {/if}
                </div>

                {include file="$root_path/applications/product/templates/aside-fichier.tpl"}
            </div>
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