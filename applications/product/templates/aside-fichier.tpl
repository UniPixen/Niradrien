<aside class="span4">
    {if check_login_bool() && $smarty.session.member.member_id == $product.member_id}
        <div id="fichier-membre" class="attribut-fichier">
            <div class="attribut-icon"><img alt="{$lang.this_is_your_file}" src="/static/images/fichier-membre.svg"></div>
            {$lang.this_is_your_file}
        </div>
    {/if}

    {if isset($product.is_buyed)}
        <div id="item-achat">
            <div id="achat-icon"><i class="hd-cloud-download"></i></div>
            {$lang.you_already} <a href="/account/downloads">{$lang.have_purchased|@lower}</a> {$lang.this_product|@lower}.<br />
            {$lang.buy_again}.
        </div>
    {/if}

    {if $product.free_file == 'true' && check_login_bool() && $smarty.session.member.member_id != $product.member_id || !check_login_bool()}
        <div id="fichier-gratuit" class="attribut-fichier">
            {if check_login_bool()}
                <a href="/account/download/free_file_of_the_month" class="btn btn-big-shadow"><i class="hd-cloud-download"></i> {$lang.download_free}</a>
                {$lang.this_file_is} <strong>{$lang.free_file_of} {$smarty.now|date_format:"%B"}.</strong><br />
                {$lang.eligible_to} <a href="/licenses">{$lang.a_regular_licence}</a>.
            {else}
                <button type="submit" class="btn btn-big-shadow"><i class="hd-cloud-download"></i> {$lang.download_free}</button>
                {$lang.this_file_is} <strong>{$lang.free_file_of} {$smarty.now|date_format:"%B"}.</strong><br />
                {$lang.connect_to_download}.
            {/if}
        </div>
    {/if}

    {if !check_login_bool() || $smarty.session.member.member_id !== $product.member_id}
        <div class="licenses" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
            <meta itemprop="priceCurrency" content="{$currency.code}" />
            <meta content="{$product.price|string_format:"%.0f"}" itemprop="price">
            <form action="/product/{$product.id}/{$product.name|url}" id="purchase-form" method="post" name="purchase-form">
                {if isset($product.member.license.personal)}
                    <div class="choix-licence js-active">
                        <div class="purchase-container">
                            <span><span>{$product.price|string_format:"%.0f"} {$currency.symbol}</span></span>{$lang.regular_licence}
                        </div>
                    </div>

                    <div class="section js-open">
                        <div class="purchase-container">
                            <div class="purchase">
                                <span class="price">
                                    {$product.price|string_format:"%.0f"} {$currency.symbol}
                                </span>
                            </div>
                            <p>
                                <strong>{$lang.regular_licence}</strong>
                                <a href="/licenses">{$lang.see_conditions}</a>
                            </p>
                            <input class="licence-type" name="licence" type="hidden" value="regular" />
                            <button class="btn btn-big-shadow" onclick="chooseLicence('regular', '{$product.price|string_format:"%.0f"}', '{$product.prepaid_price|string_format:"%.0f"}', 'block')" type="submit"><i class="hd-cart"></i> {$lang.purchase}</button>
                        </div>
                    </div>
                {/if}

                {if isset($product.member.license.extended)}
                    <div class="choix-licence">
                        <div class="purchase-container">
                            <span><span>{$product.extended_price|string_format:"%.0f"} {$currency.symbol}</span></span>{$lang.extended_licence}
                        </div>
                    </div>
                    <div class="section js-closed">
                        <div class="purchase-container">
                            <div class="purchase">
                                <span class="price">
                                    {$product.extended_price|string_format:"%.0f"} {$currency.symbol}
                                </span>
                            </div>
                            <p>
                                <strong>{$lang.extended_licence}</strong>
                                <a href="/licenses">{$lang.see_conditions}</a>
                            </p>
                            <button id="button-licence" class="btn btn-big-shadow" onclick="chooseLicence('extended', '{$product.extended_price|string_format:"%.0f"}', '{$product.extended_price|string_format:"%.0f"}', 'none')" type="submit"><i class="hd-cart"></i> {$lang.purchase}</button>
                        </div>
                    </div>
                {/if}
            </form>
        </div>
    {/if}

    <span style="font-size: 12px; display: block; margin: 30px auto; text-align: center"><img src="//d85wutc1n854v.cloudfront.net/live/imgs/coffee-bean.png" alt="Café" style="margin-right: 5px;" /> Soit environ {$coffeeCups} tasses de café.</span>

    {if $product.weekly_to}
        <div id="fichier-recompense" class="attribut-fichier">
            <div class="attribut-icon">
                <img src="/static/images/fichier-recompense.png" alt="{$lang.product_elite_author}" />
            </div>
            {$lang.product_featured}
        </div>
    {/if}

    {if $product.member.elite_author  == 'true'}
        <div id="auteur-elite" class="attribut-fichier">
            <div id="elite-icon"></div>
            {$lang.product_elite_author}
        </div>
    {/if}

    {if $product.member.super_elite_author  == 'true'}
        <div id="auteur-elite" class="attribut-fichier">
            <div id="super-elite-icon"></div>
            {$lang.product_super_elite_author}
        </div>
    {/if}

    {literal}
        <script>
            function chooseLicence(licence, price, prepaidprice, display) {
                $("#paypal-form input[name=licence], #prepaye-form input[name=licence]").val(licence);
                $("strong.buynow-figure").text("{/literal}{literal}" + prepaidprice);
                $("strong.prepaid-figure").text("{/literal}{literal}" + price);
            }
        </script>
    {/literal}


    <div id="statistiques-item">
        <table>
            <tbody>
                <tr>
                    <td>
                        <i class="hd-cart"></i>
                        <strong>{$product.sales}</strong>
                        {if $product.sales > 1}
                            {$lang.purchases|lower}
                        {else}
                            {$lang.purchase_singular|@lower}
                        {/if}
                    </td>
                    <td>
                        <a href="/product/comments/{$product.id}/{$product.name|url}">
                            <i class="hd-comment"></i>
                            <strong>{$product.comments}</strong>
                            {if $product.comments > 1}
                                {$lang.comments|lower}
                            {else}
                                {$lang.comment|lower}
                            {/if}
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <meta content="UserDownloads:{$product.sales}" itemprop="interactionCount">
    <meta content="UserComments:{$product.comments}" itemprop="interactionCount">

    <div id="informations-item">
        <table>
            <tbody>
                <tr>
                    <td class="informations-titre">{$lang.created}</td>
                    <td class="informations-detail">{$product.datetime|date_format:"%e %B %Y"}</td>
                </tr>
                {if $product.update_datetime && $product.datetime|date_format:"%e %B %Y" != $product.update_datetime|date_format:"%e %B %Y"}
                    <tr>
                        <td class="informations-titre">{$lang.last_update}</td>
                        <td class="informations-detail">{$product.update_datetime|date_format:"%e %B %Y"}</td>
                    </tr>
                {/if}
                {if isset($product.attributes)}
                    {foreach from=$product.attributes item=a key=c}
                        {if $a && $a != 'na'}
                            <tr>
                                <td class="informations-titre">
                                    {if $currentLanguage.code != 'fr'}
                                        {assign var='foo' value="name_`$currentLanguage.code`"}
                                        {$attributeCategories[$c].$foo}
                                    {else}
                                        {$attributeCategories[$c].name}
                                    {/if}
                                </td>
                                {if $smarty.get.controller != 'edit'}
                                    <td class="informations-detail">
                                        {if is_array($a)}
                                            {assign var=foo value=0}
                                            {foreach from=$a item=ai name=foo}
                                                {if $attributes[$ai].photo != ''}
                                                    <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributes[$ai].name|escape}">
                                                        <img alt="{$attributes[$ai].name|escape}" title="{$attributes[$ai].name|escape}" src="{$data_server}uploads/attributes/{$attributes[$ai].photo}" class="attribut-image" />
                                                    </span>
                                                {else}
                                                    {if $currentLanguage.code != 'fr'}
                                                        {assign var='foo' value="name_`$currentLanguage.code`"}
                                                        {if $attributes[$ai].category_id == '2'}
                                                            <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributes[$ai].tooltip}">
                                                                {$attributes[$ai].$foo}
                                                            </span>
                                                        {else}
                                                            {$attributes[$ai].$foo}
                                                        {/if}

                                                        {if !$smarty.foreach.foo.last && $attributes[$ai].category_id != '2'},{/if}
                                                    {else}
                                                        {if $attributes[$ai].category_id == '2'}
                                                            <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributes[$ai].tooltip}">
                                                                {$attributes[$ai].name}
                                                            </span>
                                                        {else}
                                                            {$attributes[$ai].name}
                                                            {if !$smarty.foreach.foo.last && $attributes[$ai].category_id != '2'},{/if}
                                                        {/if}
                                                    {/if}
                                                {/if}
                                                {assign var=foo value=$foo+1}
                                            {/foreach}
                                        {else}
                                            {if $attributes[$a].photo != ''}
                                                <img class="tooltip" alt="{$attributes[$a].name|escape}" title="{$attributes[$ai].name|escape}" src="{$data_server}/uploads/attributes/{$attributes[$a].photo}" />
                                            {else}
                                                {if $attributeCategories[$c].type == 'input'}
                                                    {$a}
                                                {else}
                                                    {$attributes[$a].name}
                                                {/if}
                                            {/if}
                                        {/if}
                                    </td>
                                {else}
                                    <td class="informations-detail">
                                        {if is_array($a)}
                                            {assign var=foo value=0}
                                            {foreach from=$a item=ai name=foo}
                                                {if $attributesAside[$ai].photo != ''}
                                                    <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributesAside[$ai].name|escape}">
                                                        <img alt="{$attributesAside[$ai].name|escape}" title="{$attributesAside[$ai].name|escape}" src="{$data_server}uploads/attributes/{$attributesAside[$ai].photo}" class="attribut-image" />
                                                    </span>
                                                {else}
                                                    {if $currentLanguage.code != 'fr'}
                                                        {assign var='foo' value="name_`$currentLanguage.code`"}
                                                        {if $attributesAside[$ai].category_id == '2'}
                                                            <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributesAside[$ai].tooltip}">
                                                                {$attributesAside[$ai].$foo}
                                                            </span>
                                                        {else}
                                                            {$attributesAside[$ai].$foo}
                                                        {/if}

                                                        {if !$smarty.foreach.foo.last && $attributesAside[$ai].category_id != '2'},{/if}
                                                    {else}
                                                        {if $attributesAside[$ai].category_id == '2'}
                                                            <span data-placement="top" data-toggle="tooltip" data-original-title="{$attributesAside[$ai].tooltip}">
                                                                {$attributesAside[$ai].name}
                                                            </span>
                                                        {else}
                                                            {$attributesAside[$ai].name}{/if}
                                                            {if !$smarty.foreach.foo.last && $attributesAside[$ai].category_id != '2'},{/if}
                                                        {/if}
                                                    {/if}

                                                    {assign var=foo value=$foo+1}
                                            {/foreach}
                                        {else}
                                            {if $attributesAside[$a].photo != ''}
                                                <img class="tooltip" alt="{$attributesAside[$a].name|escape}" title="{$attributesAside[$ai].name|escape}" src="{$data_server}/uploads/attributes/{$attributesAside[$a].photo}" />
                                            {else}
                                                {if $attributeCategories[$c].type == 'input'}
                                                    {$a}
                                                {else}
                                                    {$attributesAside[$a].name}
                                                {/if}
                                            {/if}
                                        {/if}
                                    </td>
                                {/if}
                            </tr>
                        {/if}
                    {/foreach}
                {/if}
            </tbody>
        </table>
    </div>

    <div id="auteur-item">
        <div id="avatar-auteur">
            <a href="/member/{$product.member.username}/" class="avatar">
                {if $product.member.avatar != ''}
                    <img alt="{$product.member.username}"  height="80" src="{$data_server}uploads/members/{$product.member.member_id}/{$product.member.avatar}" width="80" />
                {else}
                    <img alt="{$product.member.username}" height="80" src="{$data_server}images/member-default.png" width="80" />
                {/if}
            </a>
        </div>

        <div id="informations-auteur">
            <h3><a href="/member/{$product.member.username}">{$product.member.username}</a></h3>
            <ul class="badges">
                {foreach from=$member_badges item=b name=foo}
                    {if $currentLanguage.code != 'fr'}
                        {assign var='foo' value="name_`$currentLanguage.code`"}
                        <li data-original-title="{$b.$foo|escape}" data-toggle="tooltip" data-placement="top">
                            <img src="{$data_server}{$b.photo}" alt="{$b.$foo|escape}" title="{$b.$foo|escape}" />
                        </li>
                    {else}
                        <li data-original-title="{$b.name|escape}" data-toggle="tooltip" data-placement="top">
                            <img src="{$data_server}{$b.photo}" alt="{$b.name|escape}" title="{$b.name|escape}" />
                        </li>
                    {/if}
                {/foreach}
            </ul>
        </div>

        <div id="boutique-auteur">
            <a href="/member/{$product.member.username}/shop" role="button" class="btn btn-big-shadow">
                <i class="hd-suitcase"></i>
                {$lang.view_shop}
            </a>
        </div>
    </div>

    {if check_login_bool()}
        <div id="collections">
            <div id="modal-collection" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-collection" aria-hidden="true">
                <h4>{$lang.create_collection}</h4>
                <form id="formulaire-collection" method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                    <div class="input-collection">
                        <input id="nom-collection" name="name" type="text" value="" placeholder="{$lang.new_collection}" />
                    </div>

                    <div class="input-collection">
                        <textarea id="description-collection" name="description" placeholder="{$lang.description}"></textarea>
                    </div>

                    <label id="upload-photo" for="file_upload" class="btn btn-little-shadow"><i class="hd-cloud-upload"></i> {$lang.choose_couverture_photo}</label>
                    <div class="input-collection">
                        <input id="file_upload" name="file_upload" type="file" />
                        <small>(260 x 140px)</small>
                    </div>

                    <div class="input-collection">
                        <label class="checkbox" for="publically_visible">
                            <input id="publically_visible" name="publically_visible" type="checkbox" value="1" />
                            {$lang.public_visible} ?
                        </label>
                    </div>

                    <input type="hidden" name="add_collection" value="yes" />
                    <button type="submit" id="ajouter-collection" class="btn btn btn-big-shadow">
                        <i class="hd-bookmark"></i>
                        {$lang.bookmark_this}
                    </button>
                </form>
            </div>

            <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                {if $bookCollections}
                    <select name="collection_id">
                        {foreach from=$bookCollections item=c}
                            <option value="{$c.id}">{$c.name}</option>
                        {/foreach}
                    </select>

                    <p id="creer-collection">
                        {$lang.or} <a role="button" href="#modal-collection" data-toggle="modal">{$lang.create_collection}</a>
                    </p>

                    <input type="hidden" name="add_collection" value="yes" />
                    <button type="submit" class="btn btn-little-shadow">
                        <i class="hd-bookmark"></i>
                        {$lang.bookmark_this}
                    </button>
                {else}
                    <a id="premiere-collection" class="btn btn-big-shadow" role="button" href="#modal-collection" data-toggle="modal">{$lang.create_collection}</a>
                {/if}
            </form>
        </div>

        <div id="favorites" style="margin-bottom: 30px; height: 40px; display: block;">
            <form method="post" action="{$smarty.server.REQUEST_URI|escape}" enctype="multipart/form-data">
                {if $isFavorite}
                    <button type="submit" class="btn btn-little-shadow">
                        <i class="hd-heart"></i>
                        {$lang.added_to_favorites}
                    </button>
                    <input type="hidden" name="favorite" value="delete" />
                {else}
                    <button type="submit" class="btn btn-little-shadow" style="background-color: #999; box-shadow: 0 5px 0 #777;">
                        <i class="hd-heart"></i>
                        {$lang.add_to_favorites}
                    </button>
                    <input type="hidden" name="favorite" value="add" />
                {/if}
            </form>
        </div>
    {/if}

    <div id="tags-item" itemprop="keywords">
        {foreach from=$product.tags item=t}
            <a class="tag" href="/tag/{$t}">{$t}</a>
        {/foreach}
    </div>
</aside>
