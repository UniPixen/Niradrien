<section class="titre-page" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h2 class="page-title">Test on Essential Information</h2>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/">{$lang.home}</a> \
			</div>
		</div>
	</div>
</section>

<section id="conteneur">
	<div class="container">
		<p>Simply fill out this multiple choice questionnaire correctly to pass this author trial.</p>
		<form action="" method="post">
			{if $questions}
				{foreach from=$questions item=q name=foo}
					<h3>{$lang.question} {$smarty.foreach.foo.index+1}</h3>
					{$q.name}<br />

					{if isset($answers[$q.id])}
						{foreach from=$answers[$q.id] item=a}
							<br />
							<input id="answers_{$smarty.foreach.foo.index}_{$a.id}" name="answers[{$q.id}]" type="radio" value="{$a.id}" />
							{$a.name}  
						{/foreach}
					{/if}

					<br /><br />
				{/foreach}

				<div href="javascript: void(0);" onclick="$('#btn-sbm').click();">
					<button type="submit" name="submit">{$lang.check_quiz}</button>
				</div>
			{/if}
		</form>	
	</div>
</section>