<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/admin">{$lang.home}</a> \
				<a href="?m={$smarty.get.m}&amp;c=list">{$lang.settings}</a> \ 
			</div>
		</div>
	</div>
</section>

<div class="dashboard container" id="tab-membre">
	<ul>
		<li {if $smarty.get.type == 'system'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=system">{$lang.system}</a>
		</li>
		<li {if $smarty.get.type == 'authors'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=authors">{$lang.authors}</a>
		</li>
		<li {if $smarty.get.type == 'buyers'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=buyers">{$lang.buyers}</a>
		</li>
		<li {if $smarty.get.type == 'referrals'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=referrals">{$lang.affiliates}</a>
		</li>
		<li {if $smarty.get.type == 'anciennete'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=anciennete">{$lang.seniority}</a>
		</li>
		<li class="{if $smarty.get.type == 'other'}selected{/if} last">
			<div></div>
			<a href="/admin/?m=system&amp;c=badges&amp;type=other">{$lang.others}</a>
			<div class="last"></div>
		</li>
	</ul>
</div>

<section id="conteneur">
	<div class="container">
		{if $paging !=""}
			<div class="page-controls">{$paging}</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.photo}</th>
						<th>{$lang.visible}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$d.name}</td>
							<td>
								{if $d.photo != ''}<img src="{$data_server}uploads/badges/{$d.photo}" alt="" style="width: 20px;" />{/if}
							</td>
							<td>
								{if $d.visible == 'true'}
									<img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>							
							<td>
								<a href="?m={$smarty.get.m}&c=editBadge&type={$d.type}&fid={$d.id}" title="{$lang.edit}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a>
								{if $smarty.get.type != 'system'}
									<br />
									<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteRow':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
										<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
										{$lang.delete}
									</a>
								{/if}
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>	
		{else}
			{$lang.no_records}	
		{/if}

		{if $smarty.get.type != 'system'}
			<div class="form-submit" style="margin-top: 30px;">
				<button onclick="window.location='?m={$smarty.get.m}&amp;c=addBadge&amp;type={$smarty.get.type}';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add_new}</button>
			</div>
		{/if}
	</div>
</section>