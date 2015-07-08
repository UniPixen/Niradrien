{if isset($depositSuccess)}
    <section id="titre-page" class="page-header">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">{$lang.deposit_complete}</h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="menu-page">
        {include file="$root_path/applications/members/templates/tabsy.tpl"}
        <div id="menu-haut" class="container">
            <ol id="progression-paiement">
                <li class="active">{$lang.amount}</li>
                <li class="active">{$lang.payment_method}</li>
                <li class="active">{$lang.payment}</li>
                <li class="active">{$lang.back_to_site}</li>
            </ol>
        </div>
    </div>

    <div id="conteneur">
        <div class="container">
            <div class="row">
                <section class="span12">
                    <div id="deposit-complete" class="row">
                        <h3 class="titre-depot">{$lang.your_deposit_of} {$deposit.deposit} {$currency.symbol} {$lang.is_complete}.</h3>
                        <img src="{$data_server}/images/deposit-wallet.svg" alt="{$lang.deposit_complete}" />
                        <a class="btn btn-big-shadow" role="button" href="/category/all">{$lang.start_browsing}</a>
                        <a class="btn btn-big-shadow" role="button" href="/popular">{$lang.popular_files}</a>
                    </div>
                </section>
            </div>
        </div>
    </div>
{else}
    <section id="titre-page" class="page-header">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">{$lang.deposit_cash_set}</h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="menu-page">
        {include file="$root_path/applications/members/templates/tabsy.tpl"}
        <div id="menu-haut" class="container">
            <ol id="progression-paiement">
                <li class="active">{$lang.amount}</li>
                <li>{$lang.payment_method}</li>
                <li>{$lang.payment}</li>
                <li>{$lang.back_to_site}</li>
            </ol>
        </div>
    </div>

    <div id="conteneur">
        <div class="container">
            <div class="row">
                <section class="span12">
                    <div class="row">
                        <h3 class="titre-depot">{$lang.how_much_deposit}</h3>
                    </div>
                    <form id="depot-argent" method="post" action="{$smarty.server.REQUEST_URI|escape}">
                        <p>{$lang.deposits_non_refundable}</p>
                        <div class="row">
                            {section name=foo start=15 loop=121 step=15}
                                <div class="depot-box span3 {if $smarty.section.foo.first} depot-active{/if}">
                                    <span>{$smarty.section.foo.index} {$currency.symbol}</span><br />
                                    <input{if $smarty.section.foo.first} checked="checked"{/if} class="checkbox" id="amount_{$smarty.section.foo.index}" name="amount" type="radio" value="{$smarty.section.foo.index}" />
                                </div>
                            {/section}
                            <script>
                                {literal}
                                    $('.depot-box').click(function () {
                                        $('.depot-active').toggleClass('depot-active');
                                        $(this).toggleClass('depot-active');
                                        var depositId = $(this).find('input[type=radio]').attr('id');
                                        document.getElementById(depositId).click();
                                    });
                                {/literal}
                            </script>
                        </div>
                        <button type="submit" id="paypal-purchase-button" name="paypal" onclick="$('#depot-argent').submit();" class="btn btn-big-shadow">
                            {$lang.make_deposit}
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
{/if}