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
        <form action="" action="" method="post" enctype="multipart/form-data">
			<div class="row-input">
				<label>{$lang.status}</label>
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
				<label>{$lang.skrill_secret}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_secret]" value="{$secret|escape}" class="half" /><br />
					{$lang.skrill_secret_help}
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.email}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_email]" value="{$email|escape}" class="half" />
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.sort_order}</label>
				<div class="inputs">
					<input id="ivalue" type="text" name="form[{$group}_sort_order]" value="{$sort_order|escape}" class="half" />
				</div>
			</div>

			<div class="row-input">
				<label>{$lang.logo}</label>
				<div class="inputs">
					<input type="file" name="photo" />
					{if $logo != ''}
						<br />
						<img src="{$data_server}/uploads/{$smarty.get.m}/{$logo}" alt="" />
					{/if}
				</div>
			</div>

			<div class="form-submit">
				<button type="submit" name="edit" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.edit}</button>
			</div>
		</form>
	</div>
</section>