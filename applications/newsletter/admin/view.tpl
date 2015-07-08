<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$data.name}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.newsletter}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$title}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label>{$lang.title}</label>
                <div class="right">
                    {$data.name}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.posted}</label>
                <div class="right">
                    {$data.datetime|date_format:"%d %B %Y à %H:%M"}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.readed}</label>
                <div class="right">
                    {$data.readed}
                </div>
            </div>

            <div class="row-input">
                <label>{$lang.description}</label>
                <div class="right">
                    {$data.text}
                </div>
            </div>
        </form>
    </div>
</section>