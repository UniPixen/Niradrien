<section id="conteneur">
    <div class="container">
        <div class="row">
            <div id="about-logo" class="span3">
                <img src="{$data_server}images/logo-symbol.svg" alt="Logo" />
            </div>
            <div id="about-description" class="span9">
                <h1>{$lang.about}</h1>
                <p>{$website_title} {$lang.about_text1|@lower}<br />{$lang.about_text2}</p>
                {if $lowPrice && $highPrice}<p>{$lang.about_text3} {$lowPrice} {$currency.symbol} {$lang.and} {$highPrice} {$currency.symbol}, {$lang.about_text4|lower}</p>{/if}
            </div>
        </div>
    </div>
</section>

{if $team}
    <section id="marketplace-team">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <h2>{$lang.the_team}</h2>
                </div>
            </div>
            <div id="team" class="row">
                {foreach from=$team item=t}
                    <div class="team-member span6">
                        <div class="photo">
                            <img src="{$data_server}uploads/team/{$t.photo}" alt="{$t.firstname} {$t.lastname}" />
                        </div>
                        <div class="informations">
                            <h3>{$t.firstname} {$t.lastname}</h3>
                            <span>
                                {if $currentLanguage.code != 'fr'}
                                    {assign var='foo' value="role_`$currentLanguage.code`"}
                                    {$t.$foo}
                                {else}
                                    {$t.role}
                                {/if}
                            </span>
                            <p>
                                {if $t.live_city}{$t.live_city}, {/if}
                                {if $currentLanguage.code != 'fr'}
                                    {assign var='foo' value="name_`$currentLanguage.code`"}
                                    {$t.country.$foo}
                                {else}
                                    {$t.country.name}
                                {/if}
                            </p>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}