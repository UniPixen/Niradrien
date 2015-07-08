

  <h1 class="title">{$lang.percents}</h1>

  <div class="clear"></div>



  {if $percents}

  <table class="general_table" cellpadding="0" cellspacing="0">

    <thead>

      <tr>

        <th class="sprite_vert">{$lang.percent}</th>

        <th class="sprite_vert">{$lang.from}</th>

        <th class="sprite_vert">{$lang.to}</th>

      </tr>

    </thead>

    <tbody>

    {foreach from=$percents item=p}

      <tr class="{$year}">

    	  <td><strong>%{$p.percent|string_format:"%.0f"}</strong></td>

    	  <td>{$p.from|string_format:"%.0f"}</td>

    	  <td>

    	  	{if $p.to == '0'}

    	  		-

    	  	{else}

    	  		{$p.to|string_format:"%.0f"}

    	  	{/if}

    	  </td>

    	</tr>

    {/foreach}

    </tbody>

  </table>

	{/if}

	

	<br /><br /><br />