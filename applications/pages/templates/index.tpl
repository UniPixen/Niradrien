<section id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <div class="span9">
                {$page.text}
            </div>
        </div>
    </div>
</section>
{*
{if $menuPages}
    {foreach from=$menuPages[0] item=p name=foo}
        <li><a href="/pages/{$p.key}.html" class="icon-licence">{$p.name}</a></li>
        {if isset($menuPages[$p.id])}
            {foreach from=$menuPages[$p.id] item=sp name=foo1}
                <li><a href="/pages/{$sp.key}.html" class="icon-licence">{$sp.name}</a></li>
            {/foreach}
        {/if}
    {/foreach}
{/if}
*}