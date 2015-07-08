{include file="$root_path/applications/members/templates/elite_profile.tpl"}

{if $member.homeimage}
    <div id="couverture-profil">
        <div id="image-couverture" style="background: url('{$data_server}uploads/members/{$member.member_id}/{$member.homeimage}');"></div>
    </div>
{else}
    <div id="couverture-profil">
        <div id="image-couverture" style="background: url('{$data_server}images/couverture-default.png');"></div>
    </div>
{/if}

<section id="conteneur" class="conteneur-profil">
    <div class="container">
        <div class="row">
            <div class="span8">
                <div id="profile-description">
                    {if $member.profile_title != ''}
                        <h2>{$member.profile_title}</h2>
                    {else}
                        <h2>{$lang.profile_of} {$member.username}</h2>
                    {/if}

                    <div id="member-description">{$member.profile_desc}</div>
                </div>

                <div id="profile-collections">
                    {if $collections}
                        <h2>{$lang.public_collections}</h2>
                        <div id="collections-publiques" class="row">
                            {foreach from=$collections item=c}
                                <div class="collection-case span3">
                                    <a href="/collections/view/{$c.id}" class="collection-image">
                                        {if $c.photo != ''}
                                            <img alt="{$c.name|escape}" src="{$data_server}/uploads/collections/{$c.photo}" />
                                        {else}
                                            <img alt="{$c.name|escape}" src="{$data_server}images/collection-default.png" />
                                        {/if}
                                    </a>

                                    <div class="collection-info">
                                        <h3><a href="/collections/view/{$c.id}">{$c.name}</a></h3>
                                        <span>
                                            <a class="auteur" href="/member/{$member.username}">{$lang.by} {$member.username}</a>
                                        </span>
                                    </div>

                                    <div class="collection-note">
                                        {section name=foo start=1 loop=6 step=1}
                                            {if $c.rating >= $smarty.section.foo.index}
                                                <i class="hd-star on"></i>
                                            {else}
                                                <i class="hd-star off"></i>
                                            {/if}
                                        {/section}
                                        <br /><small>{$c.rating} {$lang.ratings|@lower}</small>
                                    </div>
                                </div>
                            {/foreach}
                        </div>
                    {else}
                        {if check_login_bool() && $member.member_id == $smarty.session.member.member_id}
                            <div class="info-page">
                                <p>{$lang.profile_display_collections}</p>
                                {$lang.profile_getting_started} <a href="/account/collections/">{$lang.public_collection|@lower}</a> {$lang.profile_and_add_preview}.<br />{$lang.profile_three_last_collections}.
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>

            {include file="$root_path/applications/members/templates/aside-member.tpl"}
        </div>
    </div>
</section>

{if $follow.to < 1 && $follow.from < 1}{else}
    <div id="followers">
        <div class="container">
            <div class="row">
                <div class="span6">
                    <h3>
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
                    </h3>
                    <div class="center-image-container">
                        {if $follow.to}
                            {foreach from=$follow.to item=f}
                                <a class="avatar" title="{$f.username}" href="/member/{$f.username}">
                                    {if $f.avatar != ''}
                                        <img src="{$data_server}uploads/members/{$f.member_id}/{$f.avatar}" alt="{$f.username}" />
                                    {else}
                                        <img src="{$data_server}images/member-default.png" alt="{$f.username}" />
                                    {/if}
                                </a>
                            {/foreach}
                        {/if}

                        {if $follow.to|count > 8}
                            <strong> . . . </strong>
                        {/if}
                    </div>
                </div>

                <div class="span6">
                    <h3>
                        {$lang.authors_i_follow} :
                        {if $follow.from}
                            {$follow.from|count}
                        {else}
                            0
                        {/if}
                    </h3>
                    <div class="center-image-container">
                        {if $follow.from}
                            {foreach from=$follow.from item=f}
                                <a class="avatar" title="{$f.username}" href="/member/{$f.username}">
                                    {if $f.avatar != ''}
                                        <img src="{$data_server}uploads/members/{$f.member_id}/{$f.avatar}" alt="{$f.username}" />
                                    {else}
                                        <img src="{$data_server}images/member-default.png" alt="{$f.username}" />
                                    {/if}
                                </a>
                            {/foreach}
                        {/if}

                        {if $follow.from_count > 8}
                            <strong> . . . </strong>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}