<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list&amp;p={$smarty.get.p}">{$lang.attributes}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
        <form action="" action="" method="post" enctype="multipart/form-data">
            <div class="row-input">
                <label for="name">{$lang.name}</label>
                <div class="inputs">
                    <input type="text" name="name" id="name" required="true" value="{$smarty.post.name}" />
                    {if isset($error.name)}
                        <span class="validate_error">{$error.name}</span>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="name">{$lang.name} {$lang.english}</label>
                <div class="inputs">
                    <input type="text" name="name_en" id="name_en" required="true" value="{$smarty.post.name_en}" />
                    {if isset($error.name_en)}
                        <span class="validate_error">{$error.name_en}</span>
                    {/if}
                </div>
            </div>

            <div class="row-input">
                <label for="type">{$lang.type}</label>
                <div class="inputs">
                    <select id="type" name="type">
                        <option value=""></option>
                        <option value="select" {if $smarty.post.type == 'select'}selected="selected"{/if}>Select box</option>
                        <option value="multiple" {if $smarty.post.type == 'multiple'}selected="selected"{/if}>Multiple</option>
                        <option value="check" {if $smarty.post.type == 'check'}selected="selected"{/if}>Checkbox</option>
                        <option value="radio" {if $smarty.post.type == 'radio'}selected="selected"{/if}>Radio</option>
                        <option value="input" {if $smarty.post.type == 'input'}selected="selected"{/if}>Input</option>
                    </select>
                    {if isset($error.select)}
                        <small>{$error.select}</small>
                    {/if}
                </div>
            </div>

            {if $categories}
                <div class="row-input">
                    <label for="name">{$lang.categories}</label>
                    <div class="inputs">
                        {foreach from=$categories item=c}
                            <input type="checkbox" name="category[{$c.id}]" value="{$c.id}" class="checkbox" {if isset($smarty.post.category[$c.id])}checked="checked"{/if} />
                            {$c.name}<br />
                        {/foreach}
                    </div>
                </div>
            {/if}

            <div class="row-input">
                <label for="visible">{$lang.na_allowed}</label>
                <div class="inputs">
                    <input type="checkbox" name="not_applicable" id="not_applicable" value="true" class="checkbox" {if $smarty.post.not_applicable == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>

            <div class="row-input">
                <label for="visible">{$lang.visible}</label>
                <div class="inputs">
                    <input type="checkbox" name="visible" id="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
                </div>
            </div>
            
            <div class="form-submit">
                {if $smarty.get.c=='edit'}
                    <button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                {else}
                    <button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                {/if}
            </div>
        </form>
    </div>
</section>