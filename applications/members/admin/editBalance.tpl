<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
                <a href="?m={$smarty.get.m}&amp;c=balance&amp;id={$smarty.get.member_id}">{$lang.edit}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <section class="span12">
                <form action="" method="post">
                    <label class="inputs-label" for="balance">{$lang.balance}</label>
                    <div class="inputs">
                        <input type="text" name="balance" value="{$smarty.post.balance|escape}">
                        {if isset($error.balance)}
                            <span class="validate_error">{$error.balance}</span>
                        {/if}
                    </div>

                    {if $smarty.get.c=='editBalance'}
                        <button class="btn btn-big-shadow btn-middle" type="submit" name="edit" style="margin-top: 30px;">
                            <i class="hd-pen"></i> {$lang.edit}
                        </button>
                    {else}
                        <button class="btn btn-big-shadow btn-middle" type="submit" name="add" style="margin-top: 30px;">
                            <i class="hd-pen"></i> {$lang.add}
                        </button>
                    {/if}
                </form>
            </section>
        </div>
    </div>
</section>