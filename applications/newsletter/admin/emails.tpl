<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$lang.newsletter_subscribers}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.newsletter}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
        {if $paging !=""}
            <div class="page-controls">{$paging}</div>
        {/if}

        {if is_array($data)}
            <table class="table-grey">
                <thead>
                    <tr>
                        <th>{$lang.email}</th>
                        <th>{$lang.unsubscribe}</th>
                        <th width="150"></th>
                    </tr>
                </thead>

                <tbody>
                    {foreach from=$data item=d}
                        <tr id="row{$d.id}">
                            <td>{$d.email}</td>
                            <td>
                                {if $d.newsletter_subscribe == 'false'}
                                    <img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
                                {else}
                                    <img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
                                {/if}
                            </td>
                            <td>
                                {if $d.newsletter_subscribe == 'false'}
                                    <a href="?m={$smarty.get.m}&c=emails&subscribe={$d.id}" title="{$lang.subscribe}">
                                        <img src="{$data_server}admin/images/icons/16x16/edit.png" />
                                        {$lang.subscribe}
                                    </a>
                                {else}
                                    <a href="?m={$smarty.get.m}&c=emails&unsubscribe={$d.id}" title="{$lang.unsubscribe}">
                                        <img src="{$data_server}admin/images/icons/16x16/edit.png" />
                                        {$lang.unsubscribe}
                                    </a>
                                {/if}<br />
                                <a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteSEmail':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow','{$lang.are_you_sure_to_delete} {$d.email|replace:"'":" "|replace:'"':' '} ?');">
                                    <img src="{$data_server}admin/images/icons/16x16/delete.png" />
                                    {$lang.delete}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {else}
            {$lang.no_records}
        {/if}
    </div>
</section>