<div id="menu-haut" class="container">
    <ul>
        <li {if $smarty.get.controller == 'author'}class="active"{/if}>
            <a href="/author" class="collections-list">{$lang.dashboard}</a></li>
        <li {if $smarty.get.controller == 'comments'}class="active"{/if}>
            <a href="/author/comments" class="collections-list">
                {$lang.comments}
<!--                 {if $commentaires_auteur > 0}
                    <span class="notification">{$commentaires_auteur}</span>
                {/if} -->
            </a>
        </li>
        <li {if $smarty.get.controller == 'products'}class="active"{/if}>
            <a href="/author/products" class="collections-list">{$lang.my_products}</a>
        </li>
        <li {if $smarty.get.controller == 'sales'}class="active"{/if}>
            <a href="/author/sales" class="collections-list">{$lang.my_sales}</a>
        </li>
        <li {if $smarty.get.controller == 'check-purchase'}class="active"{/if}>
            <a href="/author/check-purchase" class="collections-list">{$lang.check_purchase}</a>
        </li>
        <li {if $smarty.get.controller == 'earnings'}class="active"{/if}>
            <a href="/author/earnings" class="collections-list">{$lang.earnings}</a>
        </li>
        <li {if $smarty.get.controller == 'form' || $smarty.get.controller == 'index'}class="active"{/if}>
            <a href="/author/upload" class="collections-list">{$lang.upload_theme}</a>
        </li>
    </ul>
</div>