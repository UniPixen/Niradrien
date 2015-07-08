<section id="titre-page" class="page-header">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$lang.new_subject}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
				<a href="/forum">{$lang.forum}</a> \
			</div>
		</div>
	</div>
</section>

<section id="conteneur">
	<div class="container">
		<div id="forum" class="row">
			<div id="new" class="span9">
				<form method="post" action="/forum/new">
					<div class="input-group">
						<label class="inputs-label">{$lang.topic}</label>
						<div class="inputs">
							{if is_array($data)}
								<select name="topic_id">
									{foreach from=$data item=d}
										<option value="{$d.id}">
											{if $currentLanguage.code == 'en'}
												{$d.name_en}
											{else}
												{$d.name}
											{/if}
										</option>
									{/foreach}
								</select>
							{/if}
						</div>
					</div>

					<div class="input-group">
						<label class="inputs-label">{$lang.subject}</label>
						<div class="inputs">
							<input type="text" value="" name="subject" />
						</div>
					</div>

					<div class="input-group">
						<label class="inputs-label">{$lang.message}</label>
						<div class="inputs">
							<textarea name="comment"></textarea>
						</div>
					</div>

					<div class="input-group">
						<label for="subscribe" class="inputs-label no-padding">{$lang.notifications}</label>
						<div class="inputs">
							<label class="checkbox">
								<input type="checkbox" value="1" name="notify" />
								{$lang.comment_reply_notify}
							</label>
						</div>
					</div>

					<div class="form-submit">
						<button type="submit" class="btn btn-big-shadow btn-middle" name="add">{$lang.send}</button>
					</div>
				</form>
			</div>

			{include file="$root_path/applications/forum/templates/aside-forum.tpl"}
		</div>
	</div>
</section>