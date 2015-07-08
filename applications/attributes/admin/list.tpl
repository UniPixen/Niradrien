<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.attributes}</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		{if $paging != ""}
			<div class="row page-controls">
				<div class="pagination-fichiers span12">
					{$paging}
				</div>
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.name} {$lang.english}</th>
						<th>{$lang.type}</th>
						<th>{$lang.na_allowed}</th>
						<th>{$lang.visible}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>
								{if $d.type != 'input'}
									<a href="?m={$smarty.get.m}&amp;c=attr&amp;id={$d.id}" title="{$lang.attributes}">{$d.name}</a>
								{else}
									{$d.name}
								{/if}
							</td>
							<td>{$d.name_en}</td>
							<td>
								{if $d.type == 'select'}
									Select box
								{elseif $d.type == 'multiple'}
									Multiple
								{elseif $d.type == 'check'}
									Checkbox
								{elseif $d.type == 'radio'}
									Radio
								{else}
									Input
								{/if}
							</td>
							<td>
								{if $d.not_applicable == 'true'}
									<img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								{if $d.visible == 'true'}
									<img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=list&amp;down={$d.id}&amp;p={$smarty.get.p}" title="{$lang.up}">
									<img src="{$data_server}/admin/images/icons/16x16/up.png" />
								</a>
								<a href="?m={$smarty.get.m}&amp;c=list&amp;up={$d.id}&amp;p={$smarty.get.p}" title="{$lang.down}">
									<img src="{$data_server}/admin/images/icons/16x16/download1.png" />
								</a>				
							</td>								
							<td>
								{if $d.type != 'input'}
									<a href="?m={$smarty.get.m}&amp;c=attr&amp;id={$d.id}" title="{$lang.attributes}">
										<img src="{$data_server}/admin/images/icons/16x16/attachment.png" />
										{$lang.attributes}
									</a><br />
								{/if}
								<a href="?m={$smarty.get.m}&amp;c=edit&amp;id={$d.id}" title="{$lang.edit}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br/>
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
									<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
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

		<div class="form-submit">
			<button onclick="window.location='?m={$smarty.get.m}&amp;c=add';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
		</div>
	</div>
</section>