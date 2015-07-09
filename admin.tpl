<!DOCTYPE html>
<html lang="{$currentLanguage.code}" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="utf-8">
        <title>{if isset($website_title)} {$website_title} |{/if} {if isset($title)}{$title}{/if}</title>
        <meta name="description" content="{if isset($website_description)}{$website_description}{else}{$lang.page_title}{/if}">
        <meta name="keywords" content="{if isset($website_keywords)}{$website_keywords}{else}{$lang.page_keywords}{/if}" />
        <link href="{$data_server}css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,700,300" />
        <!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
        <link rel="shortcut icon" href="{$data_server}images/favicon.png" />
        <script src="{$data_server}js/jquery-1.9.1.min.js"></script>
        <script src="{$data_server}js/head.js"></script>
        <script>var data_server = '{$data_server}';</script>
        <script src="{$data_server}js/highcharts/highcharts.js"></script>
    </head>
    <body>
        {if isset($errorMessage)}
            {$errorMessage}
        {/if}

        <div class="navbar navbar-static-top topbar-admin" id="topbar">
            <div class="navbar-inner">
                <div class="container">
                    <div class="row">
                        <div class="span12">
                            <ul class="nav liens">
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == ''} selected{/if}" href="/{$smarty.get.module}/">
                                        <i class="hd-dashboard"></i>
                                        <span>{$lang.dashboard}</span>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'product' || $smarty.get.m == 'collections' || $smarty.get.m == 'tags'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=product&amp;c=list">
                                        {if $newProducts}
                                            <span class="badge">{$newProducts|count}</span>
                                        {/if}
                                        <i class="hd-file"></i>
                                        <span>{$lang.products}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=product&amp;c=sales">{$lang.sales}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=tags&amp;c=list">{$lang.tags}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=product&amp;c=queue">
                                                {$lang.awaiting_approval}
                                                {if $newProducts}
                                                    <span class="badge">{$newProducts|count}
                                                {/if}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=product&amp;c=queue_update">
                                                {$lang.awaiting_updates}
                                                {if $updatedProducts}
                                                    <span class="badge">{$updatedProducts|count}
                                                {/if}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=collections&amp;c=list">{$lang.collections}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=product&amp;c=free">{$lang.free_files}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=product&amp;c=featured">{$lang.weekly_features}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'members' || $smarty.get.m == 'help'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=members&amp;c=list">
                                        {if $payoutRequests || $reportedComments}
                                            <span class="badge">1</span>
                                        {/if}
                                        <i class="hd-user"></i>
                                        <span>{$lang.members}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=members&amp;c=withdraws">
                                                {$lang.payout_requests}
                                                {if $payoutRequests}
                                                    <span class="badge">{$payoutRequests|count}</span>
                                                {/if}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=members&amp;c=comments">
                                                {$lang.report_comments}
                                                {if $reportedComments}
                                                    <span class="badge">{$reportedComments|count}</span>
                                                {/if}
                                            </a>
                                        </li>
                                        <li><a href="/{$smarty.get.module}/?m=members&amp;c=groups">{$lang.groups}</a></li>
                                        <li><a href="/{$smarty.get.module}/?m=help&amp;c=team">{$lang.the_team}</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'forum'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=forum&amp;c=list">
                                        {if $reportedMessages}
                                            <span class="badge">{$reportedMessages|count}</span>
                                        {/if}
                                        <i class="hd-comment"></i>
                                        <span>{$lang.forum}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=forum&amp;c=list">{$lang.topics}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=forum&amp;c=messages">
                                                {$lang.report_messages}
                                                {if $reportedMessages}
                                                    <span class="badge">{$reportedMessages|count}</span>
                                                {/if}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'newsletter'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=newsletter&amp;c=list">
                                        <i class="hd-paperplane"></i>
                                        <span>{$lang.newsletter}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=newsletter&amp;c=emails">
                                                {$lang.subscribers}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=newsletter&amp;c=add">
                                                {$lang.send_newsletter}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle {if $smarty.get.m == 'news'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=news&amp;c=list">
                                        <i class="hd-preview"></i>
                                        <span>{$lang.news}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=news&amp;c=add">{$lang.add_news}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle {if $smarty.get.m == 'announcements'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=announcements&amp;c=list">
                                        <i class="hd-info"></i>
                                        <span>{$lang.announcements}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=announcements&amp;c=list&amp;type=system">{$lang.system}</a>
                                            <a href="/{$smarty.get.module}/?m=announcements&amp;c=list&amp;type=authors">{$lang.authors}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'support'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=support&amp;c=list">
                                        {if isset($supportTicket)}
                                            <span class="badge">{$supportTicket|count}</span>
                                        {/if}
                                        <i class="hd-support"></i>
                                        <span>{$lang.support}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=support&amp;c=category">{$lang.issue_categories}</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle{if $smarty.get.m == 'system' || $smarty.get.m == 'category' || $smarty.get.m == 'quiz' || $smarty.get.m == 'pages' || $smarty.get.m == 'reports' || $smarty.get.m == 'languages' || $smarty.get.m == 'payments' || $smarty.get.m == 'countries' || $smarty.get.m == 'percents'} selected{/if}" data-hover="dropdown" data-target="#" href="/{$smarty.get.module}/?m=system&amp;c=list">
                                        <i class="hd-settings"></i>
                                        <span>{$lang.system}</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=attributes&amp;c=list">{$lang.attributes}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=system&amp;c=badges">{$lang.badges}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=category&amp;c=list">{$lang.categories}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=system&amp;c=currency">{$lang.currency}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=languages&amp;c=list">{$lang.languages}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=system&amp;c=logo">{$lang.site_logo}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=payments&amp;c=list">{$lang.payments}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=pages&amp;c=list">{$lang.pages}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=countries&amp;c=list">{$lang.countries}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=quiz&amp;c=list">{$lang.quiz}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=system&amp;c=social">{$lang.social_networks}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=reports&amp;c=list">{$lang.reports}</a>
                                        </li>
                                        <li>
                                            <a href="/{$smarty.get.module}/?m=percents&amp;c=list">{$lang.payment_rates}</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            {if check_login_bool()}
                                <ul class="nav pull-right hidden-phone">
                                    <li class="dropdown">
                                        <a href="#" id="profil-dropdown" class="dropdown-toggle" data-hover="dropdown">
                                            {if $smarty.session.member.avatar != ''}
                                                <img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" />
                                            {else}
                                                <img alt="{$smarty.session.member.username}" src="{$data_server}images/member-default.png" />
                                            {/if}
                                            {$smarty.session.member.username}
                                            <span>{$smarty.session.member.total|string_format:"%.2f"} {$currency.symbol}</span> <i class="hd-simple-arrow-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="profile"><a href="/member/{$smarty.session.member.username}"><i class="hd-user"></i> {$lang.profile}</a></li>
                                            <li class="settings"><a href="/account/settings"><i class="hd-settings"></i> {$lang.settings}</a></li>
                                            <li class="downloads"><a href="/account/downloads"><i class="hd-cloud-download"></i> {$lang.downloads}</a></li>
                                            <li class="bookmarks"><a href="/account/collections"><i class="hd-bookmark"></i> {$lang.bookmarks}</a></li>
                                            <li class="deposit"><a href="/account/deposit"><i class="hd-money"></i> {$lang.deposit_cash_set}</a></li>
                                            {if $smarty.session.member.author == 'true'}
                                                <li class="auteur-dashboard"><a href="/author"><i class="hd-tools"></i> {$lang.author}</a></li>
                                                {if $smarty.session.member.products != '0'}
                                                    <li class="auteur-boutique">
                                                        <a href="/member/{$smarty.session.member.username}/shop"><i class="hd-suitcase"></i> {$lang.shop}</a>
                                                    </li>
                                                {/if}
                                                <li class="auteur-upload"><a href="/upload"><i class="hd-cloud-upload"></i> {$lang.upload}</a></li>
                                                <li class="auteur-earnings"><a href="/account/earnings"><i class="hd-stats"></i> {$lang.earnings}</a></li>
                                                <li class="auteur-statement"><a href="/account/history"><i class="hd-reward"></i> {$lang.history}</a></li>
                                                <li class="auteur-withdrawal"><a href="/account/withdraw"><i class="hd-withdrawal"></i> {$lang.withdrawal}</a></li>
                                            {else}
                                                <li class="devenir-auteur"><a href="/become-author"><i class="hd-tools"></i> {$lang.become_author}</a></li>
                                            {/if}
                                            {if $smarty.session.member.access}
                                                <li class="admin-dashboard"><a href="/admin"><i class="hd-dashboard"></i> {$lang.admin_dashboard}</a></li>
                                            {/if}
                                            <li><a href="/logout"><i class="hd-exit"></i> {$lang.logout}</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            {else}
                                <div class="nav pull-right hidden-phone">
                                    <div id="nav-connexion">
                                        <a class="create-account" href="/member/inscription/">{$lang.create_account}</a>
                                        <a href="/connexion/">{$lang.login}</a>
                                    </div>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <header id="header">
            <div class="container">
                <div class="row">
                    <div id="logo" class="span4">
                        <a href="/" class="logo-header">
                            <span>{$website_title}</span>
                        </a>
                    </div>
                    <div class="navbar span8">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <i class="hd-toggle"></i>
                        </a>
                    </div>
                    <nav id="nav">
                        <div class="navbar">
                            <div class="nav-collapse collapse">
                                <ul>
                                    <li>
                                        <a href="/" data-target="#" data-hover="dropdown" class="dropdown-toggle ">
                                        {$lang.home}
                                        </a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="/category/tout/" data-target="#" data-hover="dropdown" class="dropdown-toggle ">
                                            {$lang.all_files}
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li><a href="/popular">{$lang.popular_files}</a></li>
                                            <li><a href="/best-authors">{$lang.top_authors}</a></li>
                                            <li><a href="/categories/plan">{$lang.all_categories}</a></li>
                                        </ul>
                                    </li>
                                    {if isset($mainCategories)}
                                        {foreach from=$mainCategories item=c}
                                            <li class="dropdown">
                                                <a href="/category/{$c.keywords|url}" data-target="#" data-hover="dropdown" class="dropdown-toggle">
                                                    {if $currentLanguage.code == 'en'}
                                                        {$c.name_en}
                                                    {else}
                                                        {$c.name}
                                                    {/if}
                                                </a>
                                                {if isset($allCats[$c.id])}
                                                    <ul class="dropdown-menu">
                                                        <li><a href="/category/{$c.keywords|url}?sort=sales&amp;order=desc">{$lang.popular_products}</a></li>
                                                        {foreach from=$allCats[$c.id] item=s}
                                                            <li>
                                                                <a href="/category/{$c.keywords|url}/{$s.keywords|url}">
                                                                    {if $currentLanguage.code == 'en'}
                                                                        {$s.name_en}
                                                                    {else}
                                                                        {$s.name}
                                                                    {/if}
                                                                </a>
                                                            </li>
                                                        {/foreach}
                                                    </ul>
                                                {/if}
                                            </li>
                                        {/foreach}
                                    {/if}
                                    <li class="search-nav" onclick="search(this)">
                                        <i class="hd-search not-open"></i>
                                    </li>
                                    <li class="visible-phone nav-inscription">
                                        <a href="/register"><i class="icon-unlock-alt"></i> {$lang.create_account}</a>
                                    </li>
                                    <li class="visible-phone nav-connexion">
                                        <a href="/signin">{$lang.login}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <div id="search-nav" style="display: none">
            <div class="container">
                <form action="/search" id="searchform" method="get">
                    <fieldset id="search-fieldset">
                        <input type="text" class="span12" name="term" placeholder="{$lang.search}" value="" />
                    </fieldset>
                </form>
            </div>
        </div>

        {include file=$content_template}

        <footer id="footer">
            <div id="top-footer">
                <div class="container">
                    <div class="row">
                        <div class="span2">
                            <h3>{$lang.discover}</h3>
                            {if !check_login_bool()}
                                <p><a href="/register">{$lang.create_account}</a></p>
                            {/if}
                            <p><a href="/become-author">{$lang.become_author}</a></p>
                            <p><a href="/affiliate">{$lang.affiliates}</a></p>
                            <p><a href="/categories/plan">{$lang.sitemap}</a></p>
                            <p><a href="/terms">{$lang.terms_of_use}</a></p>
                            <p><a href="/licenses">{$lang.licenses_types}</a></p>
                        </div>
                        <div class="span3">
                            <h3>{$lang.community}</h3>
                            <p><a href="/forum">{$lang.forum}</a></p>
                            <p><a href="/collections">{$lang.public_collections}</a></p>
                            <p><a href="/popular">{$lang.popular_files}</a></p>
                            <p><a href="/best-authors">{$lang.top_authors}</a></p>
                        </div>
                        <div class="span3">
                            <h3>{$lang.company}</h3>
                            <p><a href="/about">{$lang.about}</a></p>
                            <p><a href="/brand">{$lang.the_brand}</a></p>
                            <p><a href="/support">{$lang.help_and_support}</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="span10">
                            <ul>
                                <li>
                                    <a href="/" id="logo-footer">
                                        <span>{$website_title}</span>
                                    </a>
                                </li>
                                <li>
                                    &copy; {$smarty.now|date_format: "%Y"} {$website_title}. {$lang.all_right_reserved}.
                                </li>
                            </ul>
                        </div>
                        <div id="back-top" class="span2">
                            <a href="#topbar" class="scroll">
                                <i class="hd-simple-arrow-up"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <div id="landscape-image-magnifier" class="magnifier">
            <div class="size-limiter"></div>
            <strong></strong>
            <div class="info">
                <div class="author-category">
                    <span class="author"></span>
                    <span class="category"></span>
                </div>
                <div class="price">
                    <span class="cost"></span>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="{$data_server}js/admin.js"></script>
        <script src="{$data_server}js/start.js"></script>
        <script src="{$data_server}js/jquery-1.9.1.min.js"></script>
        {if $smarty.get.module == 'index'}
            <script src="{$data_server}js/recent-products.js"></script>
        {/if}
        {if $smarty.get.module == 'product'}
            <script src="{$data_server}js/jquery.easing-1.3.min.js"></script>
            <script src="{$data_server}js/jquery.cycle.all.min.js"></script>
            <script src="{$data_server}js/slider-options.js"></script>
        {/if}
        <script src="{$data_server}js/slider-accueil.js"></script>
        <script src="{$data_server}js/bootstrap.js"></script>
    </body>
</html>
