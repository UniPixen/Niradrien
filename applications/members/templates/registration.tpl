<section id="conteneur" class="inscription-page">
	<div class="container">
		{if isset($confirmation)}
			<div id="confirmation-inscription" class="row">
				<div class="span12">
					<h3 class="titre-centre">{$lang.please_confirm_email}</h3>
					<p>{$lang.verify_registration}</p>
				</div>
			</div>
		{else}
			<div id="formulaire-inscription" class="row">
				<form action="/register" method="post">
					<div class="span12">
						<h1>{$lang.create_account}</h1>
						<div id="informations-inscription">
							<h3 class="titre-centre">{$lang.personal_information}</h3>
							<div class="row-input">
								<input type="text" required="required" value="{$smarty.post.firstname|escape}" placeholder="{$lang.first_name}" name="firstname" id="membre-prenom" />
								<input type="text" required="required" value="{$smarty.post.lastname|escape}" placeholder="{$lang.last_name}" name="lastname" id="membre-nom" />
							</div>

							<div class="row-input">
								<input type="text" id="membre-nom-utilisateur" name="username" placeholder="{$lang.username}" value="{$smarty.post.username|escape}" required="required" />
							</div>

							<div class="row-input">
								<input type="password" id="membre-mot-de-passe" name="password" placeholder="{$lang.password}" value="" required="required" />
							</div>

							<div class="row-input">
								<input type="password" id="confirmation-mot-de-passe" name="password_confirm" placeholder="{$lang.re_type}" value="" required="required" />
							</div>

							<div class="row-input">
								<input type="text" id="membre-email" name="email" placeholder="{$lang.email}" value="{$smarty.post.email|escape}" required="required" />
							</div>

							<div class="row-input">
								<input type="text" id="membre-email-confirmation" name="email_confirm" placeholder="{$lang.re_type}" value="" required="required" />
							</div>
						</div>

						<div id="captcha-inscription">
							<h3 class="titre-centre">{$lang.verification_code}</h3>
							{$recaptcha}
						</div>

						<div id="confirmation-inscription">
							<button type="submit" name="add" value="yes" class="btn btn-big-shadow">
								<i class="hd-user"></i>
								{$lang.create_account}
							</button>
							<span>{$lang.sign_up_agree_terms}</span>
						</div>
					</div>
				</form>
			</div>
		{/if}
	</div>
</section>