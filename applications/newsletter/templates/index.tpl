{if $newsletters}
    <ul>
        {foreach from=$newsletters item=b}
            <li>
                <a href="/newsletter/view/{$b.id}" target="_blank">{$b.name}</a>
            </li>
        {/foreach}
    </ul>
{else}
    {$lang.no_newsletter}
{/if}