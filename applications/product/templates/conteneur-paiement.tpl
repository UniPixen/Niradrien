{if check_login_bool()}
    <div class="container conteneur-paiement" style="display: none">
        <div class="achat-prepaye prepaid">
            <h2>
                <a href="#" class="prepaid-submit">
                    {assign var=foo value=$product.your_profit|string_format:"%.0f"}
                    {$lang.with_prepaid_credit}
                </a>
            </h2>
            <p>
                {$lang.pay} <strong class="prepaid-figure">{$product.price|string_format:"%.0f"}</strong> {$currency.symbol} {$lang.from_balance|@lower}<br /><small>({$lang.you_have|@lower} {$smarty.session.member.total|string_format:"%.2f"} {$currency.symbol})</small>
                <a href="/account/deposit" class="btn">
                    <i class="hd-money"></i>
                    {$lang.make_deposit}
                </a>
            </p>
            <form id="prepaye-form" action="/product/{$product.id}/{$product.name|url}" method="post">
                <input id="stored-item-name" type="hidden" value="{$product.name|escape}" />
                <input type="hidden" name="licence" value="regular" />
                <input type="hidden" name="pay_method" value="deposit" />
                <input type="submit" name="buy" value="{$lang.purchase}"/>
            </form>
        </div>

        <span class="ou">{$lang.or}</span>

        <div class="achat-paypal buynow">
            <h2><a href="#" class="buynow-submit">{$lang.purchase} {$lang.via_paypal|@lcfirst}</a></h2>
            <p>{$lang.redirect_to_paypal} <strong class="buynow-figure">{$product.paypal_price|string_format:"%.0f"}</strong> {$currency.symbol} {$lang.via_paypal|@lcfirst}.<br />
                <small>({$prepaid_price_discount} {$currency.symbol} {$lang.surcharge|@lower})</small>
                <a href="#" class="buynow-submit">
                    <img alt="{$lang.paypal_payment}" height="32" src="{$data_server}/images/paypal-cards.svg" width="210" />
                </a>
            </p>

            <form id="paypal-form" action="/product/{$product.id}/{$product.name|url}" method="post">
                <input type="hidden" name="licence" value="regular" />
                <input type="hidden" name="pay_method" value="paypal" />
                <input type="submit" name="buy" value="{$lang.purchase}"/>
            </form>
        </div>
        <a class="fermer-conteneur-paiement" href="#"><i class="hd-cross"></i></a>
    </div>
{else}
    <div class="container paiement-connexion" style="display: none">
        <h2>{$lang.connect_to_purchase}</h2>
        <div class="choix">
            <a href="/register" role="button" class="btn btn-big-shadow">{$lang.create_account}</a>

            <span>{$lang.or}</span>

            <form action="/product/{$product.id}/{$product.name|url}" method="post" id="licence">
                <input type="hidden" name="licence" value="regular" />
                <a href="javascript:;" role="button" onclick="document.getElementById('licence').submit();" class="btn btn-big-shadow">{$lang.login}</a>
            </form>
        </div>
        <a class="fermer-conteneur-paiement" href="#">
            <i class="hd-cross"></i>
        </a>
    </div>
{/if}

<script>
    {literal}
        function confirm_purchase(e) {
            return confirm("{/literal}{$lang.about_to_purchase}{literal} " + e + " {/literal}{$lang.using_prepaid_balance}{literal}.\n\n{/literal}{$lang.review_product_attributes}{literal}. {/literal}{$lang.issue_refund_downloaded}{literal}.\n\n{/literal}{$lang.ok_purchase}{literal}")
        }
    {/literal}
</script>