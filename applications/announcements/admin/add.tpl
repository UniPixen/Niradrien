<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.announcements}</a> \
            </div>
        </div>
    </div>
</section>

<div class="dashboard container" id="tab-membre">
    <ul>
        {if $smarty.get.c == 'edit'}
            <li {if $announcementType == 'system'}class="selected"{/if}>
                <div></div>
                <a href="/admin/?m=announcements&amp;c=list&amp;type=system">{$lang.system}</a>
            </li>
            <li class="{if $announcementType == 'authors'}selected{/if} last">
                <div></div>
                <a href="/admin/?m=announcements&amp;c=list&amp;type=authors">{$lang.authors}</a>
                <div class="last"></div>
            </li>
        {else}
            <li>
                <div></div>
                <a href="/admin/?m=announcements&amp;c=list&amp;type=system">{$lang.system}</a>
            </li>
            <li class="selected last">
                <div></div>
                <a href="/admin/?m=announcements&amp;c=list&amp;type=authors">{$lang.authors}</a>
                <div class="last"></div>
            </li>
        {/if}
    </ul>
</div>

<section id="conteneur">
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label for="name">{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" value="{$smarty.post.name}" />
                    {if isset($error.name)}
                        <small>{$error.name}</small>
                    {/if}
                </div>
            </div>

            {if $announcementType == 'authors'}
                <div class="row-input">
                    <label for="name">{$lang.message}</label>
                    <div class="inputs">
                        <textarea name="message">{$smarty.post.message}</textarea>
                        {if isset($error.message)}
                            <small>{$error.message}</small>
                        {/if}
                    </div>
                </div>
            {/if}

            {if $smarty.get.c == 'edit'}
                {if $announcementType == 'authors'}
                    <div class="row-input">
                        <label for="name">{$lang.date}</label>
                        <div class="inputs">
                            <input type="text" name="datetime" value="{$smarty.post.datetime}" />
                            {if isset($error.datetime)}
                                <small>{$error.datetime}</small>
                            {/if}
                        </div>
                    </div>
                {/if}

                {if $announcementType == 'system'}
                    <div class="row-input">
                        <label for="name">{$lang.url}</label>
                        <div class="inputs">
                            <input type="text" name="url" value="{$smarty.post.url}" />
                            {if isset($error.url)}
                                <small>{$error.url}</small>
                            {/if}
                        </div>
                    </div>

                    <div class="row-input">
                        <label>{$lang.photo}</label>
                        <div class="inputs">
                            <div style="background: #263235; border: 1px solid #2d4349; padding: 10px;">
                                <img src="{$data_server}uploads/announcements/{$smarty.post.photo}" alt="" />
                            </div>
                            <input type="file" name="photo" />
                            {if isset($error.photo)}
                                <small>{$error.photo}</small>
                            {/if}
                        </div>
                    </div>
                {/if}
            {/if}

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="form-submit">
                {if $smarty.get.c == 'edit'}
                    <input type="hidden" name="type" value="{$announcementType}" />
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 44px; width: 200px; margin-top: 40px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 44px; width: 200px; margin-top: 40px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>