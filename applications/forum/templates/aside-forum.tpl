<aside id="forum-aside" class="span3">
	{if $smarty.get.controller != 'new'}
		<div id="forum-new" class="aside-content">
			{if $smarty.get.controller == 'thread'}
				<a class="btn btn-big-shadow scroll" href="#forum-reply">{$lang.post_reply}</a>
			{else}
				{if $smarty.get.controller != 'new'}
					<a href="/forum/new" class="btn btn-big-shadow">{$lang.new_subject}</a><br />
				{/if}
			{/if}
		</div>
	{/if}

	<div id="forum-search" class="aside-content">
		<form method="get" action="/search">
			<input type="text" value="" placeholder="{$lang.search_within_forum}" name="term" id="term" />
			<input type="hidden" value="forum" name="type" />
			<button type="submit" class="btn">
				<i class="hd-search"></i>
				{$lang.search}
			</button>
		</form>
	</div>

	{if check_login_bool() && $recentMemberThreads}
		<div id="forum-member-subjects" class="aside-content">
			<h2>{$lang.your_last_subjects}</h2>
			{foreach from=$recentMemberThreads item=thread}
				{if $thread.deleted == 'false'}
					<p>
						<a href="/forum/thread/{$thread.thread_name|url}/{$thread.id}" class="thread-title">{$thread.thread_name}</a>
						{$thread.topic_count} {$lang.replies|@lower}<br />
						{$lang.last_by} <a href="/member/{$members[{$thread.last_message_member_id}].username}">{$members[{$thread.last_message_member_id}].username}</a><br />
						{if $currentLanguage.code != 'fr'}
							{$thread.last_message_datetime|timeAgo|lower} {$lang.ago|lower}
						{else}
							{$lang.ago} {$thread.last_message_datetime|timeAgo|lower}
						{/if}
					</p>
				{/if}
			{/foreach}
		</div>
	{/if}
</aside>