<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.settings}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}/">{$lang.my_account}</a> \
            </div>
        </div>
    </div>
</section>

{literal}
    <script>
        $(document).ready(function() {
            $("#content-profile ul li:eq(0)").siblings().hide();
            $("#tab-compte ul li").click(function() {
                var val = $(this).index();
                showthis(val + 1);
            });

            function showthis(val) {
                var btn_index = val - 1;

                // Liens
                $("#tab-compte ul li:eq("+btn_index+")").siblings().removeClass("active");
                $("#tab-compte ul li:eq("+btn_index+")").addClass("active");

                // Contenu
                $("#content-profile ul li:eq(" + btn_index + ")").show();
                $("#content-profile ul li:eq(" + btn_index + ")").siblings().hide();
            }
        });
    </script>
{/literal}

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    <div id="tab-compte" class="container">
        <ul>
            <li class="active"><a href="javascript:;" class="categorie-list">{$lang.personal_information}</a></li>
            <li><a href="javascript:;" class="categorie-list">{$lang.password}</a></li>
            <li><a href="javascript:;" class="categorie-list">{$lang.my_avatar}</a></li>
            <li><a href="javascript:;" class="categorie-list">{$lang.api_key}</a></li>
            {if $smarty.session.member.author == 'true'}
                <li><a href="javascript:;" class="categorie-list">{$lang.exclusivity}</a></li>
                <li><a href="javascript:;" class="categorie-list">{$lang.placed_in_front_products}</a></li>
                <li><a href="javascript:;" class="categorie-list">{$lang.licenses}</a></li>
            {/if}
        </ul>
    </div>
</div>

<div id="conteneur">
    <div class="container">
        <div class="row">
            <section id="content-profile" class="span8">
                <ul>
                    <li>
                        <div id="settings-personal-informations" class="tab-content active">
                            <div class="row">
                                <h2>{$lang.personal_information}</h2>
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                <fieldset>
                                    <div class="row">
                                        <label class="inputs-label" for="firstname">{$lang.first_name} :</label>
                                        <div class="inputs">
                                            <input id="firstname" name="firstname" required type="text" value="{$smarty.post.firstname|escape}" placeholder="{$lang.first_name}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="inputs-label" for="lastname">{$lang.last_name} :</label>
                                        <div class="inputs">
                                            <input id="lastname" name="lastname" required type="text" value="{$smarty.post.lastname|escape}" placeholder="{$lang.last_name}" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="company">{$lang.company} :</label>
                                        <div class="inputs">
                                            <input id="company" name="company_name" type="text" value="{$smarty.post.company_name|escape}" placeholder="{$lang.company_name}" />
                                            <small>{$lang.company_name_info}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="city">{$lang.city} :</label>
                                        <div class="inputs">
                                            <input id="city" name="live_city" type="text" value="{$smarty.post.live_city|escape}" placeholder="{$lang.lives_in}" />
                                            <small>{$lang.lives_in_info}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="email">{$lang.email} :</label>
                                        <div class="inputs">
                                            <input id="email" name="email" value="{$smarty.post.email|escape}" type="text" placeholder="{$lang.email}" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="country">{$lang.country} :</label>
                                        <div class="inputs">
                                            <select id="country" name="country_id">
                                                <option value="">{$lang.do_not_display_country}</option>
                                                {if $countries}
                                                    {foreach from=$countries item=c}
                                                        <option value="{$c.id}" {if $smarty.post.country_id == $c.id}selected="selected"{/if}>
                                                            {if $currentLanguage.code != 'fr'}
                                                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                                                {$c.$foo}
                                                            {else}
                                                                {$c.name}
                                                            {/if}
                                                        </option>
                                                    {/foreach}
                                                {/if}
                                            </select>
                                            <small>{$lang.country_info}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="titre-profil">{$lang.profile_head} :</label>
                                        <div class="inputs">
                                            <input id="titre-profil" name="profile_title" type="text" value="{$smarty.post.profile_title|escape}" placeholder="{$lang.profile_head}" />
                                            <small>{$lang.profile_head_info}</small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="description-profil">Bio :</label>
                                        <div class="inputs">
                                            <textarea id="description-profil" name="profile_desc" placeholder="{$lang.profile_text}" class="textarea">{$smarty.post.profile_desc}</textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="inputs">
                                            <label class="checkbox">
                                                <input id="available_for_freelance" name="freelance" type="checkbox" value="true" {if $smarty.post.freelance == 'true'}checked="checked"{/if} />
                                                {$lang.freelancer}
                                                <span class="ctrl-overlay"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row envoi-formulaire">
                                        <input type="hidden" name="personal_edit" value="yes" />
                                        <button id="personal_info_submit_button" class="btn btn-big-shadow" type="submit">
                                            <i class="hd-disk"></i>
                                            {$lang.save}
                                        </button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </li>

                    <li>
                        <div id="settings-password" class="tab-content">
                            <div class="row">
                                <h2>{$lang.change_your_password}</h2>
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                <fieldset>
                                    <div class="row">
                                        <label class="inputs-label" for="mot-de-passe-actuel">{$lang.current_password} :</label>
                                        <div class="inputs">
                                            <input id="mot-de-passe-actuel" name="password" required type="password" value="" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="nouveau-mot-de-passe">{$lang.new_password} :</label>
                                        <div class="inputs">
                                            <input type="password" id="nouveau-mot-de-passe" name="new_password" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="repeter-nouveau-mot-de-passe">{$lang.confirm_password} :</label>
                                        <div class="inputs">
                                            <input type="password" id="repeter-nouveau-mot-de-passe" name="new_password_confirm" />
                                        </div>
                                    </div>

                                    <div class="row envoi-formulaire">
                                        <input type="hidden" name="change_password" value="yes" />
                                        <button class="btn btn-big-shadow" type="submit"><i class="hd-disk"></i> {$lang.save}</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </li>

                    <li>
                        <div id="settings-avatar" class="tab-content">
                            <div class="row">
                                <h2>{$lang.avatar}</h2>
                            </div>
                            <form action="{$smarty.server.REQUEST_URI|escape}" method="post" enctype="multipart/form-data">
                                <fieldset>
                                    <div class="row">
                                        <label class="inputs-label" for="profile_image">{$lang.avatar} :</label>
                                        <div class="inputs">
                                            <div style="float: left; margin-right: 20px;">
                                                {if $smarty.session.member.avatar != ''}
                                                    <img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" width="80" height="80" />
                                                {else}
                                                    <img alt="{$smarty.session.member.username}" width="80" height="80" src="{$data_server}images/member-default.png" />
                                                {/if}
                                            </div>
                                            <label class="btn" for="profile_image">
                                                <i class="hd-cloud-upload"></i>
                                                {$lang.change_my_avatar}
                                            </label>
                                            <input id="profile_image" name="profile_image" type="file" style="display: none;" />
                                            <small>JPEG / PNG 80x80px</small>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-top: 60px;">
                                        <h2>{$lang.cover_image}</h2>
                                        <label class="inputs-label" for="profile_image">{$lang.cover_image}</label>
                                        <div class="inputs">
                                            <div style="float: left; margin-right: 20px; width: 300px;">
                                                {if $smarty.session.member.homeimage != ''}
                                                    <img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.homeimage}" width="475" height="242" />
                                                {else}
                                                    <img src="{$data_server}images/couverture-default.png" alt="{$smarty.session.member.username}" />
                                                {/if}
                                            </div>
                                            <label class="btn" for="homepage_image" style="width: 250px;">
                                                <i class="hd-cloud-upload"></i>
                                                {$lang.change_my_cover_photo}
                                            </label>
                                            <input id="homepage_image" name="homepage_image" type="file" style="display: none;" />
                                            <small>JPEG / PNG 1150x320px</small>
                                        </div>
                                    </div>

                                    <div class="row envoi-formulaire">
                                        <input type="hidden" name="change_avatar_image" value="yes" />
                                        <button type="submit" class="btn btn-big-shadow">
                                            <i class="hd-disk"></i>
                                            {$lang.save}
                                        </button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </li>

                    <li>
                        <div id="api-key" class="tab-content">
                            <div class="row">
                                <h2>{$lang.api_key}</h2>
                                <form>
                                    <label for="secret-key" class="inputs-label">{$lang.secret_key}</label>
                                    <div class="inputs">
                                        <input id="secret-key" type="text" value="{$smarty.session.member.api_key}" />
                                    </div>
                                </form>
                            </div>
                            <div class="row">
                                <h2>{$lang.documentation}</h2>
                                <label class="inputs-label">check-purchase</label>
                                <div class="inputs">
                                    <em>{$lang.example} :</em><br />
                                    <a href="/api/USERNAME/API-KEY/PURCHASE-KEY/check-purchase.json">http://{$domain}/api/USERNAME/API-KEY/PURCHASE-KEY/check-purchase.json</a>
                                </div>
                                <label class="inputs-label">balance</label>
                                <div class="inputs">
                                    <em>{$lang.example} :</em><br />
                                    <a href="/api/USERNAME/API-KEY/balance.json">http://{$domain}/api/USERNAME/API-KEY/balance.json</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    {if $smarty.session.member.author == 'true'}
                        <li>
                            <div id="exclusivity" class="tab-content">
                                <h2>{$lang.exclusivity_products}</h2>
                                {if $smarty.session.member.exclusive_author == 'true'}
                                    <div id="exclusive-author">
                                        <img src="/static/uploads/badges/exclusive_author.svg" alt="{$lang.exclusive_author}" />
                                        {$lang.exclusive_author}
                                    </div>
                                    <p>{$lang.exclusive_author_info}</p>
                                {else}
                                    {$lang.no_exclusive_author}
                                    <p>{$lang.no_exclusive_author_info}</p>
                                {/if}
                                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                    <div class="envoi-formulaire">
                                        {if $smarty.session.member.exclusive_author == 'true'}
                                            <input type="hidden" name="exclusive_false" value="yes" />
                                            <button type="submit" class="btn btn-big-shadow"><i class="hd-crew"></i> {$lang.exclusive_off}</button>
                                        {else}
                                            <input type="hidden" name="exclusive_true" value="yes" />
                                            <button type="submit" class="btn btn-big-shadow"><i class="hd-check"></i> {$lang.exclusive_on}</button>
                                        {/if}
                                    </div>
                                </form>
                            </div>
                        </li>

                        <li>
                            <div id="featured-product" class="tab-content retraits">
                                <h2>{$lang.placed_in_front_products}</h2>
                                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                    <select name="featured_item_id" id="category">
                                        <option value="0">{$lang.none}</option>
                                        {if $products}
                                            {foreach from=$products item=i}
                                                <option value="{$i.id}" {if $i.id == $smarty.session.member.featured_product_id}selected="selected"{/if}>{$i.name}</option>
                                            {/foreach}
                                        {/if}
                                    </select><br />
                                    <input type="hidden" name="feature_save" value="yes" />
                                    <button class="btn btn-big-shadow" type="submit"><i class="hd-disk"></i> {$lang.save}</button>
                                </form>
                            </div>
                        </li>

                        <li>
                            <div id="item_licenses" class="tab-content">
                                <h2>{$lang.product_licenses}</h2>
                                <p>{$lang.sale_license_info}</p>
                                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                                    <label class="inputs-label">{$lang.licence_type}</label>
                                    <div class="inputs" style="padding-top: 20px;">
                                        <ul>
                                            <li>
                                                <label for="extended" class="checkbox">
                                                    <input id="extended" name="license[extended]" type="checkbox" value="extended" {if isset($smarty.session.member.license.extended)}checked="checked"{/if} />
                                                    {$lang.extended_license}
                                                    <span class="ctrl-overlay"></span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="checkbox">
                                                    <input id="regular" name="license[personal]" type="checkbox" value="personal" {if isset($smarty.session.member.license.personal)}checked="checked"{/if} />
                                                    {$lang.regular_licence}
                                                    <span class="ctrl-overlay"></span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="envoi-formulaire">
                                        <input type="hidden" name="save_license" value="yes" />
                                        <button type="submit" class="btn btn-big-shadow">
                                            <i class="hd-disk"></i>
                                            {$lang.save}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </li>
                    {/if}
                </ul>
            </section>

            <aside id="aside-profil" class="span4">
                <div class="auteur-item-profil" id="auteur-item">
                    <div id="avatar-auteur">
                        {if $smarty.session.member.avatar != ''}
                            <img src="{$data_server}/uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" alt="{$smarty.session.member.username}" />
                        {else}
                            <img src="{$data_server}images/member-default.png" />
                        {/if}
                    </div>
                    <div id="informations-auteur" class="auteur-reglages">
                        <h3>{$smarty.session.member.username}</h3>
                        {if $smarty.session.member.country_id != '0'}
                            {if $currentLanguage.code != 'fr'}
                                {assign var='foo' value="name_`$currentLanguage.code`"}
                                {$smarty.session.member.country.$foo}
                            {else}
                                {$smarty.session.member.country.name}
                            {/if}<br />
                        {/if}
                        {$lang.member_since} {$smarty.session.member.register_datetime|date_format:"%B %Y"}
                    </div>
                </div>
                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                    <h3 class="reseaux-sociaux">{$lang.social_profiles}</h3>
                    {foreach from=$getSocial item=s}
                        <div class="social {$s.name|lower}">
                            <label class="inputs-label" for="{$s.name|lower}">
                                <i class="{$s.icon}"></i>
                            </label>
                        </div>

                        <div class="inputs">
                            <input id="{$s.name|lower}" name="social[{$s.name|lower}]" placeholder="{$lang.public_profile_url} {$s.name}" value="{$smarty.post.social.{$s.name|lower}|escape}" type="text">
                        </div>
                    {/foreach}

                    <div class="envoi-formulaire">
                        <div class="envoi-formulaire"><input type="hidden" name="social_edit" value="yes" />
                            <button type="submit" class="btn btn-big-shadow"><i class="hd-disk"></i> {$lang.save}</button>
                        </div>
                    </div>
                </form>
            </aside>
        </div>
    </div>
</div>