<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.support}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label for="name">{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" value="{$smarty.post.name|escape}" readonly="readonly" />
                </div>
            </div>

            <div class="row-input">
                <label for="email">{$lang.email}</label>
                <div class="inputs">
                    <input type="text" name="email" value="{$smarty.post.email|escape}" readonly="readonly" />
                </div>
            </div>

            <div class="row-input">
                <label for="message">{$lang.message}</label>
                <div class="inputs">
                    <textarea name="short_text" readonly="readonly" style="width: 90%; height: 400px;">{$smarty.post.short_text}</textarea>
                </div>
            </div>

            <div class="row-input">
                <label for="answer">{$lang.answer}</label>
                <div class="inputs">
                    <textarea name="answer" style="width: 90%; height: 400px;"></textarea>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit" name="send" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.send}</button>
            </div>
        </form>
    </div>
</section>