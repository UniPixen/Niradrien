<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span5" id="titre">
            	<h1 class="page-title">{$lang.edit_balance}</h1>
            </div>
            <div class="span7" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
                <a href="/admin/?m=members&amp;c=edit&amp;id={$smarty.get.id}">{$members[$smarty.get.id].username}</a> \
            </div>
        </div>
    </div>
</section>

<div class="dashboard container" id="tab-membre">
    <ul>
		{if $members[$smarty.get.id].products != '0'}
			<li>
				<div></div>
				<a href="/admin/?m=members&amp;c=edit&amp;id={$smarty.get.id}">{$lang.profile}</a>
			</li>
			<li class="selected">
				<div></div>
				<a href="/admin/?m=members&amp;c=balance&amp;id={$smarty.get.id}">{$lang.edit_balance}</a>
			</li>
			<li class="last">
				<div></div>
				<a href="/admin/?m=products&amp;c=list&amp;member={$smarty.get.id}">{$lang.shop}</a>
				<div class="last"></div>
			</li>
		{else}
			<li>
				<div></div>
				<a href="/admin/?m=members&amp;c=edit&amp;id={$smarty.get.id}">{$lang.profile}</a>
			</li>
			<li class="selected last">
				<div></div>
				<a href="/admin/?m=members&amp;c=balance&amp;id={$smarty.get.id}">{$lang.edit_balance}</a>
				<div class="last"></div>
			</li>
		{/if}
    </ul>
</div>

<section id="conteneur">
    <div class="container">
    	<div class="row">
    		<section class="span12">
    			{if is_array($data)}
    				<table class="table-grey">
    					<thead>
    						<tr>
    							<th>{$lang.amount}</th>
    							<th>{$lang.date}</th>
    							<th>{$lang.paid}</th>
    							<th>{$lang.from_admin}</th>
    							<th width="150"></th>
    						</tr>
    					</thead>
    					<tbody>
    						{foreach from=$data item=d}
    							<tr>
    								<td>{$d.deposit|string_format:"%.2f"} {$currency.symbol}</td>
    								<td>{$d.datetime|date_format:"%d %B %Y à %Hh%M"}</td>
									<td style="font-weight:bold; color:{if $d.paid == 'true'} #48691d; {else} #c00; {/if}">{if $d.paid == 'true'} {$lang.yes} {else} {$lang.no} {/if}</td>
									<td style="font-weight:bold; color:{if $d.from_admin} #48691d; {else} #c00; {/if}">{if $d.from_admin} {$lang.yes} {else} {$lang.no} {/if}</td>
									<td>
										<a href="?m={$smarty.get.m}&c=editBalance&id={$d.id}" title="{$lang.edit}">
											<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
											{$lang.edit}
										</a><br />
										<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteBalance':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
											<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
											{$lang.delete}
										</a>
									</td>					
								</tr>
							{/foreach}
						</tbody>
					</table>
				{/if}

				<div class="form-submit">
					<button onclick="window.location='?m={$smarty.get.m}&amp;c=addBalance&amp;member_id={$smarty.get.id}';" type="button" class="btn btn-big-shadow" style="width: 250px; height: 45px;">
						<i class="hd-reward"></i>
						{$lang.add_balance}
					</button>
				</div>
			</section>
		</div>
	</div>
</section>