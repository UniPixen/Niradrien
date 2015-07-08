<section id="titre-page" class="oublie-titre">
    <div class="container">
        <div class="row">
            <div id="titre" class="span12">
                {if $newPassword}
                    <h1 class="page-title">{$lang.reset_password}</h1>
                {else}
                    <h1 class="page-title">{$lang.forgot_password}</h1>
                {/if}
            </div>
        </div>
    </div>
</section>

<div id="conteneur" class="oublie-conteneur">
    <div class="container">
        {if $newPassword}
            <div class="row">
                <div id="oubli-legende">
                    <p>{$lang.enter_new_password}</p>
                </div>
                <div id="oubli" class="span12">
                    <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                        <div class="row-input">
                            <input type="password" name="password" value="" placeholder="{$lang.new_password}" required="required" />
                        </div>

                        <div class="row-input">
                            <input type="password" name="password_confirm" value="" placeholder="{$lang.confirm_password}" required="required" />
                        </div>

                        <div class="row-input envoi-formulaire">
                            <button class="btn btn-big-shadow" onclick="$(this).parent().submit();">
                                {$lang.reset_password}
                                <input type="hidden" name="password_key" value="{$key}" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        {else}
            <div class="row">
                <div id="oubli-legende">
                    <p>{$lang.enter_username_and_email}</p>
                </div>
                <div id="oubli" class="span12">
                    <form action="/" method="post">
                        <div class="row-input">
                            <input type="text" name="username" value="" placeholder="{$lang.username}" required="required" />
                        </div>

                        <div class="row-input">
                            <input type="email" name="email" value="" placeholder="{$lang.email}" required="required" />
                        </div>

                        <div class="row-input envoi-formulaire">
                            <button class="btn btn-big-shadow" onclick="$(this).parent().submit();">
                                {$lang.reset_password}
                                <input type="hidden" name="send" value="yes" />
                            </button>
                            <p>
                                <small>
                                    <a href="/login">{$lang.sign_in}</a> | <a href="/username-recover">{$lang.forgot_username}</a>
                                </small>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        {/if}
    </div>
</div>