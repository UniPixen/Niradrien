<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span5" id="titre"><h1 class="page-title">{$payment.name}</h1></div>
            <div class="span7" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=payments&amp;c=list">{$lang.payments}</a> \
            </div>
        </div>
    </div>
</section>

<div id="menu-page">
    {include file="$root_path/applications/admin/admin_tabsy.tpl"}
    <div class="container" id="tab-compte">
        <ul>
            {foreach from=$paymentList item=p}
                <li{if $p.id == $smarty.get.id} class="active"{/if}>
                    <a class="categorie-list" href="/admin/?m=payments&amp;c=edit&amp;id={$p.id}">{$p.name}</a>
                </li>
            {/foreach}
        </ul>
    </div>
</div>

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.name}</label>
                <div class="inputs">
                    <input id="name" type="text" name="name" value="{$payment.name}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.sandbox}</label>
                <div class="inputs">
                    <input type="radio" name="sandbox" value="true" {if isset($payment.sandbox) && $payment.sandbox == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                    <input type="radio" name="sandbox" value="false" {if isset($payment.sandbox) && $payment.sandbox == 'false'}checked="checked"{/if} />
                    {$lang.no}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.merchant_id}</label>
                <div class="inputs">
                    <input id="merchant_id" type="text" name="merchant_id" value="{$payment.merchant_id}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.token}</label>
                <div class="inputs">
                    <input id="token" type="text" name="token" value="{$payment.token}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.minimum_amount}</label>
                <div class="inputs">
                    <input id="minimum_amount" type="text" name="minimum_amount" value="{$payment.minimum_amount}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.maximum_amount}</label>
                <div class="inputs">
                    <input id="maximum_amount" type="text" name="maximum_amount" value="{$payment.maximum_amount}" />
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.purchase}</label>
                <div class="inputs">
                    <input type="radio" name="purchase" value="true" {if isset($payment.purchase) && $payment.purchase == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                    <input type="radio" name="purchase" value="false" {if isset($payment.purchase) && $payment.purchase == 'false'}checked="checked"{/if} />
                    {$lang.no}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.make_deposit}</label>
                <div class="inputs">
                    <input type="radio" name="deposit" value="true" {if isset($payment.deposit) && $payment.deposit == 'true'}checked="checked"{/if} />
                    {$lang.yes}
                    <input type="radio" name="deposit" value="false" {if isset($payment.deposit) && $payment.deposit == 'false'}checked="checked"{/if} />
                    {$lang.no}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.logo}</label>
                <div class="inputs">
                    <input type="file" name="photo" />
                    {if $payment.logo != ''}
                        <br />
                        <img src="{$data_server}/uploads/{$smarty.get.m}/{$payment.logo}" alt="{$payment.name}" style="width: 270px" />
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="status">{$lang.status}</label>
                <div class="inputs">
                    <input type="checkbox" id="status" name="status" value="active" class="checkbox" {if $payment.status == 'active'}checked="checked"{/if} />
                    {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="edit" class="btn btn-big-shadow btn-middle" style="margin-top: 30px;">{$lang.edit}</button>
            </div>
        </form>
    </div>
</section>