<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/admin">{$lang.home}</a> \
				<a href="?m={$smarty.get.m}&amp;c=list">{$lang.settings}</a> \
				<a href="?m={$smarty.get.m}&amp;c=badges&amp;type={$smarty.get.type}&amp;p={$smarty.get.p}">{$lang.badges}</a> \
			</div>
		</div>
	</div>
</section>

<section id="conteneur">
	<div class="container">
		<form action="" method="post">
			<div class="row-input">
				<label for="name">{$lang.name}</label>
				<div class="inputs">
					<input id="name" type="text" name="name" value="{$smarty.post.name|escape}">
					{if isset($error.name)}
						<small>{$error.name}</small>
					{/if}
				</div>
			</div>

			<div class="row-input">
				<label for="name">{$lang.name} {$lang.english}</label>
				<div class="inputs">
					<input id="name_en" type="text" name="name_en" value="{$smarty.post.name_en|escape}">
					{if isset($error.name_en)}
						<small>{$error.name_en}</small>
					{/if}
				</div>
			</div>

			{if $is_from_to}
				<div class="row-input">
					<label for="from">{$lang.from}</label>
					<div class="inputs">
						<input id="from" type="text" name="from" value="{$smarty.post.from|escape}">
					</div>
				</div>

				<div class="row-input">
					<label for="too">{$lang.to}</label>
					<div class="inputs">
						<input id="too" type="text" name="to" value="{$smarty.post.to|escape}">
					</div>
				</div>
			{/if}	

			{if $types_system}
				<div class="row-input">
					<label>{$lang.key_for_that_system_badge}</label>
					<div class="inputs">
						<select name="sys_key">
							{foreach from=$types_system item=t key=k}
								<option {if $k == $smarty.post.sys_key}selected="selected"{/if} value="{$k}">{$t}</option>
							{/foreach}
						</select>
					</div>
				</div>
			{else}
				<input type="hidden" name="sys_key" value="" /> 
			{/if}
			
			<div class="row-input">
				<label for="photo">{$lang.photo}</label>
				<div class="inputs">
					<input type="file" for="photo" name="photo" />
					{if $smarty.post.photo != ''}
						<br /><br />
						<img src="{$data_server}uploads/countries/{$smarty.post.photo}" alt="" />
						{if $smarty.get.type != 'system'}
							<input type="checkbox" name="deletePhoto" value="yes" />
							{$lang.delete}
						{/if}
					{/if}
					
					{if isset($error.photo)}
						{$error.photo}
					{/if}
				</div>
			</div>
			
			<div class="row-input">
				<label for="visible">{$lang.visible}</label>
				<div class="inputs">
					<input type="checkbox" name="visible" id="visible" value="true" class="checkbox" {if $smarty.post.visible == 'true'}checked="checked"{/if} /> {$lang.yes}
				</div>
			</div>

			<div class="form-submit">
				<button type="submit" name="add" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add}</button>
			</div>
		</form>
	</div>
</section>