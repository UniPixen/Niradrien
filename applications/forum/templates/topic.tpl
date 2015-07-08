<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">
                	{$topic.name}
                	{if isset($smarty.session.member.access.forum)}
                		<a class="btn" href="/admin/?m=forum&amp;c=edit&amp;id={$topic.id}&amp;p=">{$lang.edit}</a>
                	{/if}
                </h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/forum">{$lang.forum}</a> \
            </div>
        </div>
    </div>
</section>

<div id="tab-membre" class="dashboard container">
	<ul>
		<li {if $smarty.get.controller == 'index'}class="selected"{/if}>
			<div></div>
			<a href="/forum">{$lang.all_topics}</a>
		</li>
		{if $topic}
			{foreach from=$topics item=t name=foo}
				<li {if $smarty.foreach.foo.last && $t.id == $threadID}class="selected last"{elseif $smarty.foreach.foo.last}class="last"{elseif $t.id == $threadID}class="selected"{/if}>
					<div></div>
					<a href="/forum/topic/{$t.name|url}/{$t.id}">
                        {if $currentLanguage.code != 'fr'}
                            {assign var='foo' value="name_`$currentLanguage.code`"}
                            {$t.$foo}
                        {else}
                            {$t.name}
                        {/if}
					</a>
					{if $smarty.foreach.foo.last}<div class="last"></div>{/if}
				</li>
			{/foreach}
		{/if}
	</ul>
</div>

<section id="conteneur">
	<div class="container">
		<div id="forum" class="row">
			<div id="threads-list" class="span9">
	            {if $thread}
	                {foreach from=$thread item=t name=foo}
		                {if $t.deleted == 'false' || check_login_bool() && isset($smarty.session.member.access.forum)}
		                	<div class="thread row{if $t.deleted == 'true'} deleted{/if}">
		                		<div class="thread-avatar span1">
		            				{if $members[$t.member_id].avatar != ''}
		            					<img src="{$data_server}uploads/members/{$t.member_id}/{$members[$t.member_id].avatar}" alt="{$members[$t.member_id].username}" class="thread-author" />
		            				{else}
		            					<img src="{$data_server}images/member-default.png" alt="{$members[$t.member_id].username}" class="thread-author">
		            				{/if}
									{if $t.topic_count > 0}
			            				{if $members[{$t.last_message_member_id}].avatar != ''}
			            					<img src="/static/uploads/members/{$t.last_message_member_id}/{$members[{$t.last_message_member_id}].avatar}" alt="{$members[{$t.last_message_member_id}].username}" class="thread-reply" />
			            				{else}
			            					<img src="{$data_server}images/member-default.png" alt="{$members[{$t.last_message_member_id}].username}" class="thread-reply">
			            				{/if}
									{/if}
		                		</div>
		                		<div class="thread-content span7">
			                		<a href="/forum/thread/{$t.thread_name|url}/{$t.id}">
				                		<h4>
											{if $t.sticky == 'true'}
												<i class="hd-bookmark" title="{$lang.sticked_thread}"></i>
											{/if}
				                			{$t.thread_name}
											{if $t.locked == 'true'}
												<i class="hd-lock"></i>
											{/if}
				                		</h4>
			                		</a>
			                		<div class="thread-informations">
				                		<p>
				                			{$lang.started_by} <a href="/member/{$members[{$t.member_id}].username}">{$members[{$t.member_id}].username}</a><br />
				                			{if $t.topic_count > 0}
				                				{$lang.last_answer_by} <a href="/member/{$members[{$t.last_message_member_id}].username}">{$members[{$t.last_message_member_id}].username}</a>
				                			{/if}
				                		</p>
			                		</div>
		                		</div>
		                		<div class="thread-answers span1">
		                			<small>
										{if $currentLanguage.code != 'fr'}
											{{$t.last_message_datetime|timeAgo}|@lower} {$lang.ago|@lower}
										{else}
											{$lang.ago} {{$t.last_message_datetime|timeAgo}|@lower}
										{/if}
		                			</small>
		                			<div class="thread-answers-count">
			                			<span>{$t.topic_count}</span>
			                			<small>{$lang.answers|lower}</small>
		                			</div>
		                		</div>
		                	</div>
		                {/if}
	                {/foreach}
				{else}
					{$lang.no_message}
				{/if}
			</div>
			
			{include file="$root_path/applications/forum/templates/aside-forum.tpl"}
		</div>
	</div>
</section>