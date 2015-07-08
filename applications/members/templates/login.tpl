<section id="titre-page" class="connexion-titre">
    <div class="container">
        <div class="row">
            <div id="titre" class="span12">
                <h1 class="page-title">{$lang.sign_in}</h1>
            </div>
        </div>
    </div>
</section>

<div id="conteneur" class="connexion-conteneur">
    <div class="container">
        <div class="row">
            <div id="connexion" class="span12">
                <form method="post">
                    <div class="row-input">
                        <input type="text" id="username" name="username" value="" placeholder="{$lang.username}" required="required" />
                    </div>

                    <div class="row-input">
                        <input type="password" id="password" name="password" value="" placeholder="{$lang.password}" required="required" />
                    </div>

                    <div class="row-input envoi-formulaire">
                        <button class="btn btn-big-shadow" value="yes" name="login" type="submit">
                            <input type="hidden" value="yes" name="login" />
                            {$lang.login}
                        </button>
                        <p>
                            <small>
                                <a href="/password-recover">{$lang.forgot_password}</a> | <a href="/username-recover">{$lang.forgot_username}</a>
                            </small>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>