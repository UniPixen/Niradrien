{if $payments_data}
    <section id="titre-page" class="page-header">
        <div class="container">
            <div class="row">
                <div id="titre" class="span7">
                    <h1 class="page-title">{$lang.choose_payment_method}</h1>
                </div>
                <div id="breadcrumbs" class="span5">
                    <a href="/">{$lang.home}</a> \
                    <a href="/members/{$smarty.session.user.username}">{$lang.my_account}</a> \
                </div>
            </div>
        </div>
    </section>

    <div id="menu-page">
        <div class="container" id="menu-haut">
            <ol id="progression-paiement">
                <li class="active">{$lang.choose_product}</li>
                <li class="active">{$lang.choose_payment_method}</li>
                <li>{$lang.make_payment}</li>
                <li>{$lang.back_to_site}</li>
            </ol>
        </div>
    </div>

    <section id="conteneur">
        <div class="container">
            <div class="row">
                <div class="span12">
                    <div class="row">
                        {foreach from=$payments_data item=pm}
                            <div class="paiement-box span4">
                                <img class="option" src="{$data_server}/uploads/payments/{$pm.logo}" alt="{$lang.pay_via} {$pm.title}" />
                                <div class="pay">{$pm.form}</div>
                            </div>
                        {/foreach}
                    </div>
                </div>
            </div>
        </div>
    </section>
{/if}