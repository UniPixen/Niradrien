<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$title}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m=system&amp;c=list">{$lang.settings}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        {if is_array($payments)}
            <table class="table-grey">
                <thead>
                    <tr>
                        <th>{$lang.name}</th>
                        <th>{$lang.sandbox}</th>
                        <th>{$lang.merchant_id}</th>
                        <th>{$lang.token}</th>
                        <th>{$lang.status}</th>
                        <th>{$lang.sort}</th>
                        <th width="150"></th>
                    </tr>
                </thead>

                <tbody>
                    {foreach from=$payments item=p}
                        <tr>
                            <td>{$p.name}</td>
                            <td>
                                {if $p.sandbox == 'true'}
                                    <img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
                                {else}
                                    <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                                {/if}
                            </td>
                            <td>{$p.merchant_id}</td>
                            <td>{$p.token}</td>
                            <td>
                                {if $p.status == 'active'}
                                    <img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
                                {else}
                                    <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                                {/if}
                            </td>
                            <td>
                                <a href="?m={$smarty.get.m}&amp;c=list&amp;down={$p.id}" title="{$lang.up}">
                                    <img src="{$data_server}admin/images/icons/16x16/up.png" />
                                </a>
                                <a href="?m={$smarty.get.m}&amp;c=list&amp;up={$p.id}" title="{$lang.down}">
                                    <img src="{$data_server}admin/images/icons/16x16/download1.png" />
                                </a>
                            </td>
                            <td>
                                <a href="?m=payments&amp;c=edit&amp;id={$p.id}" title="{$lang.edit}">
                                    <img src="{$data_server}admin/images/icons/16x16/edit.png" />
                                    {$lang.edit}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {/if}
    </div>
</section>