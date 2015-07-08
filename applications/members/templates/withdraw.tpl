<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.withdrawal}</h1>
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
        <div id="retrait-maximum-historique" class="row">
            <div id="retrait-maximum" class="span9">
                <h4>{$lang.withdraw_to} {$member.earning|string_format:"%.2f"} {$currency.symbol}</h4>
            </div>
            <div id="retrait-historique" class="span3">
                <a href="/account/history" class="btn btn-big-shadow">{$lang.history}</a>
            </div>
        </div>
    </div>
</div>

<section id="conteneur" class="retraits">
    <div class="container">
        <div id="retrait-header" class="row">
            <h3 class="titre-centre">{$lang.make_payment_request}</h3>
            <p>{$lang.payment_info}</p>
        </div>
        <form id="retrait-argent" action="{$smarty.server.REQUEST_URI|escape}" method="post">
            <div id="montant">
                <div id="montant-retrait" class="row">
                    <div class="inputs">
                        <input checked="checked" id="maximum_at_period_end_false" name="maximum_at_period_end" value="false" type="radio" />
                        <input id="amount" name="amount" placeholder="0.00" value="" type="text" />
                        <div id="montant-devise">{$currency.symbol}</div>
                        <label for="amount">{$lang.amount_withdrawal}</label>
                    </div>
                </div>

                <div id="paiement-retrait-header" class="row">
                    <h3 class="titre-centre">{$lang.payment_method}</h3>
                </div>
                <div id="paiement-retrait" class="row">
                    <div class="retrait-box span3">
                        <img alt="PayPal" src="/static/uploads/payments/paypal.svg" /><br />
                        <small>{$lang.50_minimum}</small>
                        <input id="service_paypal" name="service" value="paypal" type="radio" />
                    </div>
                    <div class="retrait-box span3">
                        <img alt="Skrill" src="/static/uploads/payments/skrill.svg" /><br />
                        <small>{$lang.50_minimum}</small>
                        <input id="service_skrill" name="service" value="moneybookers" type="radio" />
                    </div>
                </div>

                <div class="row-input payment-informations hide">
                    <label for="payment_email_address">{$lang.paypal_skrill_email}</label>
                    <div id="payment_email_address_field">
                        <input id="payment_email_address" name="payment_email_address" value="" placeholder="{$lang.address}" type="email" />
                    </div>
                </div>
                <div class="row-input payment-informations hide">
                    <label for="payment_email_address_confirmation">{$lang.confirm_paypal_skrill_email}</label>
                    <input id="payment_email_address_confirmation" name="payment_email_address_confirmation" value="" placeholder="{$lang.confirm_adress}" type="email" />
                </div>

                <div id="imposition-header" class="row">
                    <h3 class="titre-centre">{$lang.taxation}</h3>
                </div>
                <div class="row-input">
                    <label class="checkbox" for="taxable_french_resident" style="width: 190px; margin: auto;">
                        <input id="taxable_french_resident" name="taxable_french_resident" value="true" type="checkbox" />
                        {$lang.french_resident}
                        <span class="ctrl-overlay"></span>
                    </label>
                    <div class="inputs">
                        <div id="taxation-details" class="hide">
                            <label for="hobbyist_true">
                                <input id="hobbyist_true" name="hobbyist" value="true" type="radio" checked />
                                {$lang.i_am_author}
                            </label>
                            <label for="hobbyist_false">
                                <input id="hobbyist_false" name="hobbyist" value="false" type="radio" />
                                {$lang.i_am_not_author}
                            </label>
                        </div>
                    </div>
                </div>

                <div id="submit-retrait">
                    <input type="hidden" name="submit" value="submit" />
                    <button type="submit" class="btn btn-big-shadow">{$lang.submit_request}</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="text/javascript">
    $('#taxable_french_resident').change(function(){
        if ($(this).is(":checked"))
            $('#taxation-details').fadeIn('slow');
        else
            $('#taxation-details').fadeOut('slow');
    });

    $('#service_paypal').change(function(){
        if ($(this).is(":checked"))
            $('.payment-informations').fadeIn('slow');
        else
            $('.payment-informations').fadeOut('slow');
    });

    $('#service_skrill').change(function(){
        if ($(this).is(":checked"))
            $('.payment-informations').fadeIn('slow');
        else
            $('.payment-informations').fadeOut('slow');
    });
</script>