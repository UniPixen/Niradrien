<section id="titre-page">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.all_categories}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/category/tout">{$lang.files}</a> \
            </div>
        </div>
    </div>
</section>

<div id="tab" class="container">
    <ul>
        <li class="selected">
            <div></div>
            <a href="/categories/plan">{$lang.browse}</a>
        </li>
        <li class="last">
            <div></div>
            <a href="/category/" rel="nofollow">{$lang.list}</a>
            <div class="last"></div>
        </li>
    </ul>
</div>

<script>
    {literal}
        var categoriesPlan = function () {
            var e = $(this);
            return {
                setupcategoriesPlan: function (e) {
                    $("a", e).each(function () {
                        if ($("li", $("> ul", $(this).parent())).length) {
                            var e = $(this).parent();
                            e.addClass("expandable")
                        }
                    })
                },
                open_next: function (e, t) {
                    var n = $(e),
                        r = $(t),
                        i = $("ul", r),
                        s = $("> ul", n.parent()).children().clone();
                    if (n.hasClass("all-category") || $("li", $("> ul", n.parent())).length === 0) return;
                    n.parent().parent().find(".active").removeClass("active"), n.parent().addClass("active"), r.removeClass("empty"), i.empty(), $("ul", r).length || (i = $("<ul></ul>"), r.append(i)), i.append('<li><a href="' + e.get(0) + '" class="all-category">{/literal}{$lang.all}{literal} ' + $(e.get(0)).html() + "</a></li>"), i.append(s), i.is(":empty") && (i.remove(), r.addClass("empty"))
                }
            }
        }
    {/literal}
</script>

<div id="conteneur">
    <div class="container">
        <div class="row">
            <div id="first" class="category-section">
                <ul>{$categoriesbrowseList}</ul>
            </div>
            <div id="second" class="category-section empty">
                <p>{$lang.click_show_subcategories}</p>
                <p>{$lang.click_navigate_category}</p>
            </div>
            <div id="third" class="category-section empty"></div>
            <div id="fourth" class="category-section empty"></div>
            <script>
                {literal}
                    marketplace.queue(function(){
                        marketplace.initializers.categoriesPlan();
                    });
                {/literal}
            </script>
        </div>
    </div>
</div>