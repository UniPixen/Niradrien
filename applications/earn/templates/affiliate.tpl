<div id="programme-parrainage">
    <div id="parrainage-illustration">
        <h1>{$lang.affiliate_program}</h1>
        <p>{$lang.earn_earnings_referrals}</p>
        <a href="#lien-parrainage" class="btn btn-big-shadow scroll">{$lang.get_my_link}</a>
    </div>
</div>

<section id="conteneur">
    <div class="container">
        <div id="parrainage-comment-marche" class="row">
            <h2>{$lang.how_does_work}</h2>
            <p>{$lang.must_comply_conditions}</p>
        </div>
        <div class="row" id="conditions-parrainage">
            <div class="condition-grille span6">
                <div class="parrainage-icon cookies"></div>
                <h3>{$lang.use_cookies}</h3>
                <p>{$lang.use_cookies2}</p>
            </div>
            <div class="condition-grille span6">
                <div class="parrainage-icon premier-clic"></div>
                <h3>{$lang.first_click}</h3>
                <p>{$lang.first_click2}</p>
            </div>
            <div class="condition-grille span6">
                <div class="parrainage-icon trois-mois"></div>
                <h3>{$lang.have_three_mounths}</h3>
                <p>{$lang.have_three_mounths2}</p>
            </div>
            <div class="condition-grille span6">
                <div class="parrainage-icon gains-retirables"></div>
                <h3>{$lang.withdraw_earnings}</h3>
                <p>{$lang.withdraw_earnings2}</p>
            </div>
            <div class="condition-grille span6">
                <div class="parrainage-icon filleul-client"></div>
                <h3>{$lang.customer_referrals}</h3>
                <p>{$lang.customer_referrals2}</p>
            </div>
            <div class="condition-grille span6">
                <div class="parrainage-icon verification-possible"></div>
                <h3>{$lang.possible_verification}</h3>
                <p>{$lang.possible_verification2}</p>
            </div>
        </div>
    </div>
</section>

<section id="telecharger-logos">
    <div class="container">
        <div class="row">
            <h2>{$lang.banners_and_logos}</h2>
            <p>{$lang.use_logo_ad}</p>
            <img src="{$data_server}images/logo-brand.svg" alt="Logo" /><br />
            <a href="/static/uploads/logos/HadrienDesign-Logo.zip" class="btn btn btn-big-shadow">
                <i class="hd-file-zip"></i>
                {$lang.download}
            </a>
        </div>
    </div>
</section>

<section id="lien-parrainage">
    <div class="container">
        <div class="row">
            <h2>{$lang.affiliate_link}</h2>
            <p>{$lang.build_referral_link}</p>
            <form data-view="referralLinkGenerator" name="referral_link_generator" novalidate="">
                <input type="text" value="{if check_login_bool()}{$smarty.session.member.username}{/if}" placeholder="{$lang.username}" class="js-referral-link-username" /><br />
                <input type="text" class="js-referral-link-url" value="{$domain}" /><br />
                <input class="lien parrainage js-referral-link-result" readonly type="text" value="" placeholder="{$domain}?ref=" /><br />
                <span class="js-referral-link-instructions is-hidden">{$lang.copy_referral_link}</span>
            </form>
        </div>
    </div>
</section>

<script src="{$data_server}js/generateur-lien-parrainage.js"></script>