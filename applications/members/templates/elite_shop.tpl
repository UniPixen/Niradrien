{if $member.super_elite_author  == 'true' || $member.elite_author  == 'true'}
    <section id="titre-page" class="titre-profil {if $member.super_elite_author  == 'true'}auteur-super-elite{/if}{if $member.elite_author  == 'true'}auteur-elite{/if}">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <div class="elite-icon"></div>
                    <h2>{$member.username}</h2>
                    <div class="elite-titre">{if $member.super_elite_author  == 'true'}{$lang.super}{/if} {$lang.elite_author}</div>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/member/{$member.username}">{$lang.profile}</a> \
                    <a href="/member/{$member.username}">{$member.username}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="tab-membre" class="dashboard container">
        {if check_login_bool() && $member.member_id == $smarty.session.member.member_id}
            <ul>
                <li>
                    <div></div>
                    <a href="/author">{$lang.author}</a>
                </li>
                <li>
                    <div></div>
                    <a href="/member/{$smarty.session.member.username}">{$lang.profile}</a>
                </li>
                {if $smarty.session.member.products != '0'}
                    <li class="selected">
                        <div></div>
                        <a href="/member/{$smarty.session.member.username}/shop">{$lang.shop}</a>
                    </li>
                {/if}
                <li>
                    <div></div>
                    <a href="/account/settings">{$lang.settings}</a>
                </li>
                <li>
                    <div></div>
                    <a href="/account/downloads">{$lang.downloads}</a>
                </li>
                <li>
                    <div></div>
                    <a href="/account/collections">{$lang.collections}</a>
                </li>
                <li>
                    <div></div>
                    <a href="/account/deposit">{$lang.deposit}</a>
                </li>
                {if $smarty.session.member.author == 'true'}
                    <li>
                        <div></div>
                        <a href="/account/withdraw">{$lang.withdrawal}</a>
                    </li>
                {/if}
                <li class="last">
                    <div></div>
                    <a href="/account/history">{$lang.history}</a>
                    <div class="last"></div>
                </li>
            </ul>
        {else}
            <ul>
                <li>
                    <div></div>
                    <a href="/member/{$member.username}">{$lang.profile}</a>
                </li>
                <li class="selected last">
                    <div></div>
                    <a href="/member/{$member.username}/shop">{$lang.shop}</a>
                    <div class="last"></div>
                </li>
            </ul>
        {/if}
    </div>
{else}
    <section id="titre-page" class="titre-profil">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">{$lang.shop}</h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/member/{$member.username}">{$lang.profile}</a> \
                    <a href="/member/{$member.username}">{$member.username}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="tab-membre" class="dashboard container">
        {if check_login_bool() && $member.member_id == $smarty.session.member.member_id}
            <ul>
                <li>
                    <div></div>
                    <a href="/author">{$lang.author}</a>
                </li>
    			<li>
                    <div></div>
                    <a href="/member/{$member.username}">{$lang.profile}</a>
                </li>
    			{if $smarty.session.member.products != '0'}
                    <li class="selected">
                        <div></div>
                        <a href="/member/{$smarty.session.member.username}/shop">{$lang.shop}</a>
                    </li>
    			{/if}
    			<li>
                    <div></div>
                    <a href="/account/settings">{$lang.settings}</a>
                </li>
    			<li>
                    <div></div>
                    <a href="/account/downloads">{$lang.downloads}</a>
                </li>
    			<li>
                    <div></div>
                    <a href="/account/collections">{$lang.collections}</a>
                </li>
    			<li>
                    <div></div>
                    <a href="/account/deposit">{$lang.deposit}</a>
                </li>
                {if $smarty.session.member.author == 'true'}
                    <li>
                        <div></div>
                        <a href="/account/withdraw">{$lang.withdrawal}</a>
                    </li>
                {/if}
    			<li class="last">
                    <div></div>
                    <a href="/account/history">{$lang.history}</a>
                    <div class="last"></div>
                </li>
            </ul>

        	{else}
                <ul>
                    <li>
                        <div></div>
                        <a href="/member/{$member.username}">{$lang.profile}</a>
                    </li>
                    <li class="selected last">
                        <div></div>
                        <a href="/member/{$member.username}/shop">{$lang.shop}</a>
                        <div class="last"></div>
                    </li>
                </ul>
            {/if}
        {/if}
    </div>