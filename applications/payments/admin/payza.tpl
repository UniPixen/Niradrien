<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m=system&amp;c=list">{$lang.settings}</a> \
                <a href="?m=payments&amp;c=list">{$lang.payments}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
    <div class="container">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="row-input">
				<label for="status">{$lang.status}</label>
				<div class="inputs">
					<select name="form[{$group}_status]">
						{foreach from=$statuses item=s key=k}
						{if $k == $status}
						<option selected="selected" value="{$k}">{$s}</option>
						{else}
						<option value="{$k}">{$s}</option>
						{/if}
						{/foreach}
					</select>
				</div>
			</div>
			<div class="row-input">
				<label for="sandbox_mode">{$lang.payza_sandbox_mode}</label>
				<div class="inputs">
					<select name="form[{$group}_sandbox_mode]">
						{if $sandbox_mode}
						<option selected="selected" value="1">{$lang.yes}</option>
						<option value="0">{$lang.no}</option>
						{else}
						<option value="1">{$lang.yes}</option>
						<option selected="selected" value="0">{$lang.no}</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="row-input">
				<label for="ivalue">{$lang.payza_merchant_id}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_merchant_id]" value="{$merchant_id|escape}" class="half" />
				</div>
			</div>
			<div class="row-input">
				<label for="ivalue">{$lang.payza_security_code}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_security_code]" value="{$security_code|escape}" class="half" />
				</div>
			</div>
			<div class="row-input">
				<label>{$lang.payza_alert_url}</label>
				<div class="inputs">
					<span class="formatting-help">
						http://{$domain}/payments/payza/
						<br />{$lang.payza_alert_url_help}
					</span>
				</div>
			</div>
			<div class="row-input">
				<label for="ivalue">{$lang.sort_order}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_sort_order]" value="{$sort_order|escape}" class="half" />
				</div>
			</div>
			<div class="row-input">
				<label for="ivalue">{$lang.logo}</label>
				<div class="inputs">
					<input type="file" name="photo" />
					{if $logo != ''}
						<br />
						<img src="{$data_server}/uploads/{$smarty.get.m}/{$logo}" alt="" />
					{/if}
				</div>
			</div>
			<div class="row-input">
				<label for="ivalue">Documentation</label>
				<div class="inputs">
					<span class="formatting-help">{$lang.payza_help}</span>
				</div>
			</div>
			<div class="form-submit">
				<button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.save}</button>
			</div>
		</form>
	</div>
</section>