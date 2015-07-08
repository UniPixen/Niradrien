<section id="titre-page" class="page-header">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$thread[$threadID].name}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.home}</a> \
                <a href="/forum">{$lang.forum}</a> \
                <a href="/forum/topic/{$thread[$threadID].topic_name|url}/{$thread[$threadID].topic_id}">{$thread[$threadID].topic_name}</a> \
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
		{if $topics}
			{foreach from=$topics item=t name=foo}
				<li {if $smarty.foreach.foo.last && $t.id == $thread[$threadID].topic_id}class="selected last"{elseif $smarty.foreach.foo.last}class="last"{elseif $t.id == $thread[$threadID].topic_id}class="selected"{/if}>
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

{if isset($smarty.session.member.access.forum)}
	<div id="modal-move" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-move" aria-hidden="true">
	    <h4>{$lang.move_subject}</h4>
	    <p>{$lang.move_subject_info}</p>
	    <form action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/move" method="post">
	        <div class="input-modal">
				<select name="topic_id">
					{if $existTopics}
						{foreach from=$existTopics item=t}
							<option value="{$t.id}" {if $t.id == $thread[$threadID].topic_id}selected{/if}>{$t.name}</option>
						{/foreach}
					{/if}
				</select>
				<input type="hidden" value="{$thread[$threadID].id}" name="thread_id" />
	        </div>

	        <button type="submit" id="ajouter-invitation" class="btn btn btn-big-shadow">{$lang.move}</button>
	    </form>
	</div>
{/if}

<section id="conteneur" class="forum-thread-container">
	<div class="container">
		<div id="forum" class="row">
			<div id="thread" class="span9">
				<div id="posts-list">
					{if isset($smarty.session.member.access.forum)}
						<div id="thread-paginate" class="row">
	                    	{if $paging}
	                    		<div id="pagination-moderator" class="pagination-fichiers span3">{$paging}</div>
	                    		<div id="pagination-moderator-tools" class="span6">
	                    	{else}
	                    		<div id="moderator-tools" class="span9">
	                    	{/if}
								<form action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/lock" method="post" class="thread-modal">
									{if $thread[$threadID].locked == 'true'}
			                    		<button type="submit" name="locked" value="false" class="btn btn-big-shadow">
				                    		<i class="hd-lock"></i>
				                    		{$lang.unlock}
				                    	</button>
									{else}
			                    		<button type="submit" name="locked" value="true" class="btn btn-big-shadow disabled-tool">
			                    			<i class="hd-lock"></i>
			                    			{$lang.lock}
			                    		</button>
									{/if}
									<input type="hidden" value="{$thread[$threadID].id}" name="thread_id" />
								</form>
								<form action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/sticky" method="post" class="thread-modal">
									{if $thread[$threadID].sticky == 'true'}
			                    		<button type="submit" name="sticky" value="false" class="btn btn-big-shadow">
				                    		<i class="hd-bookmark"></i>
				                    		{$lang.sticked}
				                    	</button>
									{else}
			                    		<button type="submit" name="sticky" value="true" class="btn btn-big-shadow disabled-tool">
			                    			<i class="hd-bookmark"></i>
			                    			{$lang.stick}
			                    		</button>
									{/if}
									<input type="hidden" value="{$thread[$threadID].id}" name="thread_id" />
	                    		</form>
	                    		<a id="move-thread" class="btn btn-big-shadow" href="#modal-move" data-toggle="modal">
	                    			<i class="hd-arrow-left"></i>
	                    			{$lang.move}
	                    		</a>
								<form action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/delete" method="post" class="thread-modal">
									{if $thread[$threadID].deleted == 'true'}
			                    		<button type="submit" name="deleted" value="false" class="btn btn-big-shadow">
				                    		<i class="hd-trash"></i>
				                    		{$lang.deleted}
				                    	</button>
									{else}
			                    		<button type="submit" name="deleted" value="true" class="btn btn-big-shadow disabled-tool">
			                    			<i class="hd-trash"></i>
			                    			{$lang.delete}
			                    		</button>
									{/if}
									<input type="hidden" value="{$thread[$threadID].id}" name="thread_id" />
								</form>
	                    	</div>
	                    </div>
                    {else}
                    	{if $paging}
	                    	<div class="row">
	                    		<div class="pagination-fichiers span9">{$paging}</div>
	                    	</div>
                    	{/if}
                    {/if}

		            {foreach from=$thread item=t name=foo}
			            {if isset($t.comment)}
			            	<div id="post-{$thread[$t.id].id}" class="thread row">
								{if check_login_bool() && $members[$t.member_id].member_id != $smarty.session.member.member_id}
									<div id="report-{$t.id}" class="modal-signalement modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
									    <h4>{$lang.report_message}</h4>
								        <form method="post" action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/report">
								            <div class="input-signalement">
								            	<textarea name="report_reason" placeholder="{$lang.explain_why_report}"></textarea>
								            </div>

								            <input type="hidden" name="report" value="{$t.id}" />
								            <button type="submit" class="btn btn btn-big-shadow btn-signaler-commentaire">
									            <i class="hd-flag"></i>
									            {$lang.submit_report}
								            </button>
								        </form>
									</div>
								{/if}
								{if check_login_bool() && isset($smarty.session.member.access.forum)}
									<div id="moderate-{$t.id}" class="modal-moderate modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
									    <h4>{$lang.moderate_message}</h4>
								        <form method="post" action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/moderate">
								            {if $thread[$threadID].id == $t.id}
									            <div class="input-moderate">
									            	<input type="text" name="post_name" value="{$thread[$threadID].name}" />
												</div>
								            {/if}
								            <div class="input-moderate">
								            	<textarea name="post_message">{$t.comment}</textarea>
											</div>
											<div class="input-moderate">
												<label class="checkbox">
													<input type="checkbox" name="censor_message" value="1" {if $t.moderate == 'true'}checked{/if} />
													{$lang.censor_message}
												</label>
								            </div>

								            <input type="hidden" name="moderate" value="{$t.id}" />
								            <button type="submit" class="btn btn btn-big-shadow btn-moderer-commentaire">{$lang.report_check}</button>
								        </form>
									</div>
								{/if}
			            		<div class="thread-avatar span1">
			            			<a href="/member/{$members[$t.member_id].username}" class="avatar" title="{$members[$t.member_id].username}">
			            				{if $members[$t.member_id].avatar != ''}
			            					<img src="{$data_server}uploads/members/{$t.member_id}/{$members[$t.member_id].avatar}" alt="{$members[$t.member_id].username}" class="thread-author" />
			            				{else}
			            					<img src="{$data_server}images/member-default.png" alt="{$members[$t.member_id].username}" class="thread-author">
			            				{/if}
			            			</a>
									<small class="member-messages">
										{$memberTotalMessages.{$members[$t.member_id].member_id}.total_member_messages}
										{if $memberTotalMessages.{$members[$t.member_id].member_id}.total_member_messages > 1}
											{$lang.messages|@lower}
										{else}
											{$lang.message|@lower}
										{/if}
									</small>
			                        <ul class="badges">
			                            {foreach from=$badges[$t.member_id] item=b name=foo}
			                                {if $currentLanguage.code != 'fr'}
			                                    {assign var='foo' value="name_`$currentLanguage.code`"}
			                                    <li data-original-title="{$b.$foo|escape}" title="{$b.$foo|escape}" data-toggle="tooltip" data-placement="right">
			                                        <img src="{$data_server}{$b.photo}" alt="{$b.$foo|escape}" />
			                                    </li>
			                                {else}
			                                    <li data-original-title="{$b.name|escape}" title="{$b.name|escape}" data-toggle="tooltip" data-placement="right">
			                                        <img src="{$data_server}{$b.photo}" alt="{$b.name|escape}" />
			                                    </li>
			                                {/if}
			                            {/foreach}
			                        </ul>
			            		</div>
			            		<div class="post-content span8">
			            			{if $t.moderate == 'false'}
				            			<div class="post-header">
						                	<a href="/member/{$members[$t.member_id].username}" class="post-author">
						                		{$members[$t.member_id].username}
						                	</a>
						                	{if isset($members[$t.member_id].applications.forum)}
						                		<strong class="message-type-moderator">{$lang.moderator}</strong>
						                	{/if}
						                	<small>{$lang.said|@lower}</small>
					                	</div>
				                		<div class="post-message">
				                			<p>{$t.comment}</p>
				                		</div>
				                		<div class="post-footer">
				                			<ul>
				                				<li>
													{if $currentLanguage.code != 'fr'}
														{$t.datetime|timeAgo|@lower} {$lang.ago|@lower}
													{else}
														{$lang.ago} {$t.datetime|timeAgo|@lower}
													{/if}
				                				</li>
				                				{if check_login_bool() && isset($smarty.session.member.access.forum)}
					                				<li><a href="#moderate-{$t.id}" data-toggle="modal">{$lang.report_check}</a></li>
				                				{/if}
				                				{if check_login_bool() && $members[$t.member_id].member_id != $smarty.session.member.member_id}
				                					<li>
				                						<a href="#report-{$t.id}" data-toggle="modal">
				                							<i class="hd-flag" title="{$lang.report}"></i>
				                						</a>
				                					</li>
				                				{/if}
				                			</ul>
				                		</div>
			                		{else}
			                			<div class="post-message">{$lang.moderated_thread}</div>
			                				{if check_login_bool() && isset($smarty.session.member.access.forum)}
				                				<div class="post-footer">
					                				<ul>
						                				<li>
															{if $currentLanguage.code != 'fr'}
																{$t.datetime|timeAgo|@lower} {$lang.ago|@lower}
															{else}
																{$lang.ago} {$t.datetime|timeAgo|@lower}
															{/if}
						                				</li>
						                				<li><a href="#moderate-{$t.id}" data-toggle="modal">{$lang.report_check}</a></li>
					                				</ul>
					                			</div>
			                				{/if}
			                		{/if}
			            		</div>
			            	</div>
			            {/if}
		            {/foreach}

					{if $paging}
						<div class="row">
	                    	<div class="pagination-fichiers span9">{$paging}</div>
		                </div>
	                {/if}
	            </div>

				{if $thread[$threadID].locked != 'true'}
					<div id="forum-reply">
						{if check_login_bool()}
							<h3>
								{if $thread|@count > 1}
									{$lang.post_reply}
								{else}
									{$lang.be_first_comment}
								{/if}
							</h3>
							<div class="row">
		                        <div class="span1">
		                            <a title="{$smarty.session.member.username}" class="avatar" href="/member/{$smarty.session.member.username}">
		                                {if $smarty.session.member.avatar != ''}
		                                    <img alt="{$smarty.session.member.username}" src="{$data_server}uploads/members/{$smarty.session.member.member_id}/{$smarty.session.member.avatar}" />
		                                {else}
		                                    <img alt="{$smarty.session.member.username}" src="{$data_server}images/member-default.png" />
		                                {/if}
		                            </a>
		                        </div>
		                        <div class="span8">
		                            <form method="post" action="/forum/thread/{$thread[$threadID].name|url}/{$thread[$threadID].id}/reply">
		                                <div class="row-input">
		                                    <textarea placeholder="{$lang.comment}" name="comment" id="thread-reply"></textarea>
		                                    <input type="hidden" value="{$thread[$threadID].id}" name="thread_id" />
		                                </div>
		                                <div id="post-message">
		                                    <button class="btn btn-little-shadow" name="add" type="submit">
		                                        <i class="hd-comment"></i>
		                                        {$lang.reply}
		                                    </button>
		                                </div>
		                                <div id="subscribe-comments">
		                                    <label class="checkbox">
		                                        <input type="checkbox" value="1" name="reply_notification" />
		                                        {$lang.follow_thread}
		                                    	<span class="ctrl-overlay"></span>
		                                	</label>
		                                </div>
		                            </form>
		                        </div>
		                    </div>
						{else}
							<h3>{$lang.login_to_answer}</h3>
							<a href="/login" class="btn btn-little-shadow">
								<i class="hd-comment"></i>
								{$lang.login}
							</a>
						{/if}
					</div>
				{else}
					<div id="forum-reply-locked">
						<i class="hd-lock"></i>
						{$lang.thread_is_locked}
					</div>
				{/if}
			</div>
			
			{include file="$root_path/applications/forum/templates/aside-forum.tpl"}
		</div>
	</div>
</section>