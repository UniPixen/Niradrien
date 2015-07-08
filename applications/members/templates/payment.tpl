<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.deposit_cash_set}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/members/{$smarty.session.member.username}/">{$lang.my_account}</a> \
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
            <li>{$lang.payment}</li>
            <li>{$lang.back_to_site}</li>
        </ol>
    </div>
</div>

{if $payments_data}
    <div id="conteneur">
        <div class="container">
            <div class="span12">
                <div class="row">
                    {foreach from=$payments_data item=pm}
                        <div class="paiement-box span4">
                            <img class="option" src="{$data_server}uploads/payments/{$pm.logo}" alt="{$lang.pay_via} {$pm.title}" />
                            <div class="pay">{$pm.form}</div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
{else}
    <div id="conteneur">
        <div class="container">
            <div class="row">

            </div>
        </div>
    </div>
{/if}