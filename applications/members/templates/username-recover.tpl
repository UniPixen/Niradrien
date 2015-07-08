<section id="titre-page" class="oublie-titre">
    <div class="container">
        <div class="row">
            <div id="titre" class="span12">
                <h1 class="page-title">{$lang.forgot_username}</h1>
            </div>
        </div>
    </div>
</section>

<div id="conteneur" class="oublie-conteneur">
    <div class="container">
        <div class="row">
            <div id="oubli-legende">
                <p>{$lang.enter_email_address}</p>
            </div>
            <div id="oubli" class="span12">
                <form action="{$smarty.server.REQUEST_URI|escape}" method="post">
                    <div class="row-input">
                        <input class="input" id="email" name="email" required type="text" value="" placeholder="{$lang.email_adress}" />
                    </div>
                    <div class="row-input envoi-formulaire">
                        <input type="hidden" name="send" value="yes" />
                        <button class="btn btn-big-shadow" onclick="$(this).parent().submit();" name="commit" type="submit">
                            {$lang.recover_username}
                            <input type="hidden" name="send" value="yes" />
                        </button>
                        <p>
                            <small>
                                <a href="/login">{$lang.sign_in}</a> | <a href="/password-recover">{$lang.forgot_password}</a>
                            </small>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>