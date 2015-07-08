<section id="titre-page">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.contact_support}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/">{$lang.support}</a> \
            </div>
        </div>
    </div>
</section>

<div id="conteneur">
    <div class="container">
        <div class="row">
            <aside id="aside-support" class="span3">
                <h3 class="aside-header">{$lang.informations}</h3>
                <p>{$lang.support_aside_text}</p>
                <div id="skype-support">
                    <i class="hd-skype"></i>Hadriien
                </div>
            </aside>

            <section id="section-support" class="span9">
                <p>{$lang.support_info1}</p>
                <p>{$lang.support_info2}</p>
                <form class="support-form" method="post" enctype="multipart/form-data" action="/">
                    <div id="name-support" class="span3">
                        <label for="username">
                            <strong>{$lang.name}</strong> ({$lang.required|lower})
                        </label>
                        <input id="username" name="username" required value="{if check_login_bool()}{$smarty.session.member.username} {$smarty.session.member.lastname}{/if}" type="text" />
                    </div>

                    <div class="span3">
                        <label for="email">
                            <strong>{$lang.email}</strong> ({$lang.required|lower})
                        </label>
                        <input id="email" name="email" required value="{if check_login_bool()}{$smarty.session.member.email}{/if}" type="text" />
                    </div>

                    <div class="span3">
                        <label for="issue_id">
                            <strong>{$lang.your_issue}</strong> ({$lang.required|lower})
                        </label>
                        <select name="issue_id" id="issue_id" size="1">
                            <option value="0">{$lang.select_issue}</option>
                            {if $categories}
                                {foreach from=$categories item=c}
                                    <option value="{$c.id}">
                                        {if $currentLanguage.code != 'fr'}
                                            {assign var='foo' value="name_`$currentLanguage.code`"}
                                            {$c.$foo}
                                        {else}
                                            {$c.name}
                                        {/if}
                                    </option>
                                {/foreach}
                            {/if}  	
                        </select>
                    </div>

                    <div id="textarea-support" class="span9">
                        <label for="issue_details">
                            <strong>{$lang.your_message}</strong> ({$lang.required|@lower})
                        </label>
                        <textarea name="issue_details" id="issue_details" placeholder="{$lang.contact_details4}"></textarea>
                    </div>

                    <div class="form-submit">
                        <input name="submit" value="submit" type="hidden" />
                        <button type="submit" class="btn">
                            <i class="hd-pen"></i>
                            {$lang.send}
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>