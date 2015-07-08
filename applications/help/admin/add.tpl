<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre"><h1 class="page-title">{$title}</h1></div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=help&amp;c=team">{$lang.the_team}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        <div class="row">
            <section class="span12">
                <form action="" action="" method="post" enctype="multipart/form-data">
                    {if $smarty.get.c == 'add'}
                        {if is_array($members)}
                            <div class="row-input">
                                <label for="member">{$lang.member}</label>
                                <div class="inputs">
                                    <select id="member_id" name="member_id">
                                        <option value=""></option>
                                        {foreach from=$members item=d}
                                            <option value="{$d.member_id}" {if $smarty.post.member_id == $d.member_id}selected="selected"{/if}>{$d.username}</option>
                                        {/foreach}
                                    </select>
                                    {if isset($error.member_id)}
                                        <span class="validate_error">{$error.member_id}</span>
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    {/if}

                    <div class="row-input">
                        <label for="role">{$lang.role}</label>
                        <div class="inputs">
                            <input type="text" name="role" value="{$smarty.post.role|escape}">
                            {if isset($error.role)}
                                <span class="validate_error">{$error.role}</span>
                            {/if}
                        </div>
                    </div>

                    <div class="row-input">
                        <label for="role">{$lang.role} {$lang.english}</label>
                        <div class="inputs">
                            <input type="text" name="role_en" value="{$smarty.post.role_en|escape}">
                            {if isset($error.role_en)}
                                <span class="validate_error">{$error.role_en}</span>
                            {/if}
                        </div>
                    </div>

                    <div class="row-input">
                        <label>{$lang.photo}</label>
                        <div class="inputs">
                            <input type="file" name="photo" />
                            {if $smarty.post.photo != ''}
                                <br />
                                <img src="{$data_server}uploads/team/{$smarty.post.photo}" alt="" style="width: 120px;" />
                                <input type="checkbox" name="deletePhoto" value="yes" />
                                {$lang.delete}
                            {/if}
                            {if isset($error.photo)}
                                <small>{$error.photo}</small>
                            {/if}
                        </div>
                    </div>
                    <input type="hidden" name="firstname" value="{$member.firstname|lower}" />

                    {if $smarty.get.c == 'edit'}
                        <button type="submit" name="edit" value="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.edit}</button>
                    {else}
                        <button type="submit" name="add" value="edit" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
                    {/if}
                </form>
            </section>
        </div>
    </div>
</section>