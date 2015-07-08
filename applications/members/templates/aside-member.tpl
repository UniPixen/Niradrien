<aside class="span4">
    <div id="auteur-item" class="auteur-item-profil">
        <div id="avatar-auteur">
            <a href="/member/{$member.username}" class="avatar">
                {if $member.avatar}
                    <img alt="{$member.username}" src="{$data_server}uploads/members/{$member.member_id}/{$member.avatar}" />
                {else}
                    <img alt="{$member.username}" src="{$data_server}images/member-default.png" />
                {/if}
            </a>
        </div>

        <div id="informations-auteur">
            <h3>{$member.username}</h3>
            <ul class="badges">
                {foreach from=$member_badges item=b name=foo}
                    {if $currentLanguage.code != 'fr'}
                        {assign var='foo' value="name_`$currentLanguage.code`"}
                        <li data-original-title="{$b.name|escape}" data-toggle="tooltip" data-placement="top">
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

        {if $member.products != '0' }
            <div id="boutique-auteur">
                <a href="/member/{$member.username}/shop" class="btn btn-big-shadow">
                    <i class="hd-suitcase"></i>
                    {$lang.view_shop}
                </a>
            </div>
        {/if}
    </div>

    <div id="statistiques-item">
        <table>
            <tbody>
                <tr>
                    <td>
                        <i class="hd-cart"></i>
                        {$member.sales}
                        {if $member.sales > 1}
                            {$lang.sales|lower}
                        {else}
                            {$lang.sale|@lower}
                        {/if}
                    </td>
                    <td>
                        <i class="hd-user"></i>
                        {if $follow.to}
                            {$follow.to|count}
                        {else}
                            0
                        {/if}

                        {if $follow.to|count <= 1}
                            {$lang.follower|@lower}
                        {else}
                            {$lang.followers|@lower}
                        {/if}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {if check_login_bool()}
        {if $smarty.session.member.member_id != $member.member_id}
            <div id="contact-et-suivre">
                <a href="/members/{$member.username}/?follow" id="follow-user">
                    <span id="follow" class="follow btn">
                        {if $member.is_follow}
                            {$lang.unfollow}
                        {else}
                            {$lang.follow}
                        {/if}
                    </span>
                </a>
                
                <div id="modal-contact" class="modal hide fade modal-dix" tabindex="-1" role="dialog" aria-labelledby="modal-contact" aria-hidden="true">
                    <h4>{$lang.email_author} {$member.username}</h4>
                    <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                        <div class="input-contact">
                            <small>{$lang.profile_email_info}</small>
                        </div>

                        <div class="input-contact">
                            <input type="email" value="{$smarty.session.member.email}" name="from" placeholder="{$lang.email_adress}" disabled />
                            <i class="hd-lock icon-input"></i>
                        </div>

                        <div class="input-contact">
                            <textarea name="message" placeholder="{$lang.your_message}"></textarea>
                        </div>

                        <input type="hidden" name="send_email" value="yes" />
                        <button type="submit" class="btn btn-big-shadow" name="send_email" value="yes">
                            <i class="hd-paperplane"></i>
                            {$lang.send}
                        </button>
                    </form>
                </div>
                
                <a class="btn" data-toggle="modal" href="#modal-contact" role="button">
                    <i class="hd-paperplane"></i>
                    {$lang.email_author}
                </a>
            </div>
        {/if}
    {else}
        <div id="contact-et-suivre">
            <div id="modal-suivre" class="modal hide fade modal-trentecinq" tabindex="-1" role="dialog" aria-labelledby="modal-contact" aria-hidden="true">
                <h4>{$lang.follow} {$member.username}</h4>
                {$lang.please} <a href="/login">{$lang.please_signin}</a> {$lang.to_follow_this_author}.
            </div>
            
            <a id="follow" class="follow btn" data-toggle="modal" href="#modal-suivre" role="button">{$lang.follow}</a>

            <div id="modal-contact" class="modal hide fade modal-trentecinq" tabindex="-1" role="dialog" aria-labelledby="modal-contact" aria-hidden="true">
                <h4>{$lang.email_author} {$member.username}</h4>
                {$lang.please} <a href="/login">{$lang.please_signin}</a> {$lang.to_contact_this_author}.
            </div>

            <a class="btn" data-toggle="modal" href="#modal-contact" role="button">
                <i class="hd-paperplane"></i>
                {$lang.email_author}
            </a>
        </div>
    {/if}

    {if $member.votes > 2}
        <div class="attribut-fichier">
            <strong>{$lang.author_rating} :</strong>
            <div id="note-item">
                {section name=foo start=1 loop=6 step=1}
                    {if $member.rating >= $smarty.section.foo.index}
                        <i class="hd-star on"></i>
                    {else}
                        <i class="hd-star off"></i>
                    {/if}
                {/section}
            </div>
            <small>{$lang.average_of} {$member.rating}, {$lang.based_on|@lower} {$member.votes} {$lang.ratings|@lower}.</small>
        </div>
    {else}
        <div class="attribut-fichier notes-minimum">
            <strong>{$lang.buyer_rating} :</strong> {$lang.minimum_votes}
        </div>
    {/if}

    {if isset($featureProduct) && $featureProduct}
        <div class="attribut-fichier fichier-avant">
            <a href="/product/{$featureProduct.id}/{$featureProduct.name|url}">
                <img width="80" height="80" src="{$data_server}uploads/products/{$featureProduct.id}/{$featureProduct.thumbnail}" data-preview-width="" data-preview-url="{$data_server}uploads/products/{$featureProduct.id}/preview.jpg" data-preview-height="" data-item-name="{$featureProduct.name|escape}" data-item-cost="{$featureProduct.price} {$currency.symbol}" data-item-category="{foreach from=$featureProduct.categories item=e}{foreach from=$e item=c name=foo}{$categories[$c].name} {if !$smarty.foreach.foo.last} \ {/if}{/foreach}{/foreach}" data-item-author="{$lang.by} {$member.username}" class="landscape-image-magnifier preload no_preview" alt="{$featureProduct.name|escape}" />
            </a>
            <div id="fichier-avant-infos">
                <small>{$lang.feature_file}</small>
                <a href="/product/{$featureProduct.id}/{$featureProduct.name|url}">{$featureProduct.name}</a><br />
                <i class="hd-cart"></i> {$featureProduct.sales} {$lang.sales|lower}
            </div>
        </div>
    {/if}

    <div id="informations-item">
        <table>
            <tbody>
                {if $member.live_city != ''}
                    <tr>
                        <td class="informations-titre">{$lang.lives_in}</td>
                        <td class="informations-detail">{$member.live_city}</td>
                    </tr>
                {/if}

                {if $member.country_id != '0'}
                    <tr>
                        <td class="informations-titre">{$lang.country}</td>
                        <td class="informations-detail">
                            {if $currentLanguage.code != 'fr'}
                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                {$member.country.$foo}
                            {else}
                                {$member.country.name}
                            {/if}
                        </td>
                    </tr>
                {/if}

                <tr>
                    <td class="informations-titre">{$lang.member_since}</td>
                    <td class="informations-detail date-membre">{$member.register_datetime|date_format:"%B %Y"}</td>
                </tr>

                {if $member.freelance == 'true'}
                    <tr>
                        <td class="informations-titre">{$lang.freelance}</td>
                        <td class="informations-detail">{$lang.i_am_available}</td>
                    </tr>
                {/if}

                {if $member.last_login_datetime != ''}
                    <tr>
                        <td class="informations-titre">{$lang.last_signin}</td>
                        <td class="informations-detail">{$member.last_login_datetime|date_format:"%A %e %B %Y à %H:%M"}</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>

    {include file="$root_path/applications/members/templates/social_profile.tpl"}
</aside>