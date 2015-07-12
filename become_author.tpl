<div id="devenir-auteur" class="img-top">
    <h1>{$lang.become_author}</h1>
    <h3>{$lang.welcome_marketplace}</h3>
    <p>{$lang.welcome_marketplace_1} {$website_title}, {$lang.welcome_marketplace_2}</p>
    <a href="/help/author" class="btn btn-big-shadow">{$lang.learn_more}</a>
    {if check_login_bool()}
        <a href="#modal-invitation" data-toggle="modal" class="btn btn-big-shadow">{$lang.become_author}</a>
    {else}
        <a href="/login" class="btn btn-big-shadow">{$lang.sign_in}</a>
    {/if}
</div>

<div id="modal-invitation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-invitation" aria-hidden="true">
    <h4>{$lang.request_invitation}</h4>
    <p>{$lang.request_phrase_1} {$website_title}.</p>
    <p>{$lang.request_phrase_2}</p>

                
                    

<form id="formulaire-invitation" action="{$smarty.server.REQUEST_URI|escape}" method="post">
        <div class="input-modal">
            <input name="email" type="text" value="{$member_email}" placeholder="{$lang.email_adress}" disabled />
            <i class="hd-lock icon-input"></i>
        </div>

        <div class="input-modal">
            <textarea name="liens" placeholder="{$lang.link_to_creations}"></textarea>
        </div>

        <div class="input-modal">
            <textarea name="pourquoi" placeholder="{$lang.why_sell_on} {$website_title} ?"></textarea>
        </div>

        <input type="hidden" name="demande_invitation" value="yes" />
        <button type="submit" id="ajouter-invitation" name="demande_invitation" class="btn btn btn-big-shadow">
            {$lang.request_invitation}
        </button>
    </form>
</div>

<div id="conteneur">
    <div class="container">
        


        <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <label class="inputs-label">{$lang.author} ?</label>
                                <div class="inputs">
                                    <input id="author" type="checkbox" name="author" value="true" {if $member.author == 'false'}checked="checked"{/if}>
                                </div>
                        </div>        
{$smarty.server.REQUEST_URI|var_dump}
                {if $smarty.get.c=='edit'}  
                            <button class="btn btn-big-shadow btn-middle" type="submit" name="edit" style="margin-top: 30px;">
                                <i class="hd-pen"></i>
                                {$lang.edit}
                            </button>
                        {else}
                            <button class="btn btn-big-shadow btn-middle" type="submit" name="edit" style="margin-top: 30px;">
                                {$lang.edit}
                            </button>
                        {/if}
                    </form>
                

        
        <div class="row">
            <h3 class="titre-auteur">{$lang.just_advantages}</h3>
        </div>

        <div id="avantage-auteur" class="row">
            <div class="avantage-grid span6">
                <div class="avantage-icon prix"></div>
                <h3>{$lang.decide_your_prices}</h3>
                <p>{$lang.decide_your_prices_description}</p>
            </div>
            <div class="avantage-grid span6">
                <div class="avantage-icon reversements"></div>
                <h3>{$lang.earn_always} {$exclusive_author_percent} %</h3>
                <p>{$lang.earn_always_description} {$exclusive_author_percent} % {$lang.earn_up_to_description_2}</p>
            </div>
            <div class="avantage-grid span6">
                <div class="avantage-icon livraison"></div>
                <h3>{$lang.immediate_delivery}</h3>
                <p>{$lang.immediate_delivery_description}</p>
            </div>
            <div class="avantage-grid span6">
                <div class="avantage-icon exclusif"></div>
                <h3>{$lang.exclusivity_obligation}</h3>
                <p>{$lang.exclusivity_obligation_description} {$exclusive_author_percent} % {$lang.exclusivity_obligation_description_2} {$no_exclusive_author_percent} %.</p>
            </div>
            <div class="avantage-grid span6">
                <div class="avantage-icon reseau-mondial"></div>
                <h3>{$lang.worldwide_network}</h3>
                <p>{$lang.worldwide_network_description_1} {$website_title} {$lang.worldwide_network_description_2}</p>
            </div>
            <div class="avantage-grid span6">
                <div class="avantage-icon verification"></div>
                <h3>{$lang.quick_verification}</h3>
                <p>{$lang.quick_verification_description}</p>
            </div>
        </div>
        
        <div id="savoir-plus-btn">
            {*<a href="/help/author" class="btn btn-big-shadow">{$lang.learn_more}</a>*}
            {if check_login_bool()}
                <a href="#modal-invitation" data-toggle="modal" class="btn btn-big-shadow">{$lang.learn_more}</a>
            {else}
                <a href="/login" class="btn btn-big-shadow">{$lang.sign_in}</a>
            {/if}
        </div>
    </div>
</div>
