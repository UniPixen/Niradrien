<div id="reseaux-sociaux">
	{foreach from=$getSocial item=s}
		{if $member.social.{$s.name|lower} != ''}
			<a data-original-title="{$s.name}" data-toggle="tooltip" data-placement="top" target="_blank" href="{$socialUrl_{$s.name|lower}}" title="{$s.name}" class="social {$s.name|lower}">
				<i class="{$s.icon}"></i>
			</a>
		{/if}
	{/foreach}
	{if $member.products != '0'}
		<a data-original-title="{$lang.member_feeds} {$member.username}" data-toggle="tooltip" data-placement="top" target="_blank" href="/feeds/member/{$member.username}" title="{$lang.member_feeds} {$member.username}" class="social feed">
			<i class="hd-feed"></i>
		</a>
	{/if}
</div>