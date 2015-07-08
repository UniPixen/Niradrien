<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span5" id="titre"><h1 class="page-title">{$member.username}</h1></div>
            <div class="span7" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
            </div>
        </div>
    </div>
</section>

<script>
    {literal}
        $(document).ready(function() {
            $("#content-profile ul li:eq(0)").siblings().hide();
            $("#tab-compte ul li").click(function(){
                var val = $(this).index();
                showthis(val+1);
            });

            function showthis(val) {
                var btn_index = val-1;

                // Liens
                $("#tab-compte ul li:eq("+btn_index+")").siblings().removeClass("active");
                $("#tab-compte ul li:eq("+btn_index+")").addClass("active");

                // Contenu
                $("#content-profile ul li:eq("+btn_index+")").show();
                $("#content-profile ul li:eq("+btn_index+")").siblings().hide();
            }
        });
    {/literal}
</script>

<div id="menu-page">
    <div class="dashboard container" id="tab-membre">
        <ul>
            {if $member.products != '0'}
                <li class="selected">
                    <div></div>
                    <a href="/admin/?m=members&amp;c=edit&amp;id={$member.member_id}">{$lang.profile}</a>
                </li>
                <li>
                    <div></div>
                    <a href="/admin/?m=members&amp;c=balance&amp;id={$member.member_id}">{$lang.edit_balance}</a>
                </li>
                <li class="last">
                    <div></div>
                    <a href="/admin/?m=product&amp;c=list&amp;member={$member.member_id}">{$lang.shop}</a>
                    <div class="last"></div>
                </li>
            {else}
                <li class="selected">
                    <div></div>
                    <a href="/admin/?m=members&amp;c=edit&amp;id={$member.member_id}">{$lang.profile}</a>
                </li>
                <li class="last">
                    <div></div>
                    <a href="/admin/?m=members&amp;c=balance&amp;id={$member.member_id}">{$lang.edit_balance}</a>
                    <div class="last"></div>
                </li>
            {/if}
        </ul>
    </div>
    
    <div class="container" id="tab-compte">
        <ul>
            <li class="active">
                <a class="categorie-list" href="javascript:;">{$lang.personal_information}</a>
            </li>
            <li>
                <a class="categorie-list" href="javascript:;">{$lang.change_your_password}</a>
            </li>
            <li>
                <a class="categorie-list" href="javascript:;">{$lang.badges}</a>
            </li>
        </ul>
    </div>
</div>

<section id="conteneur">
    <div class="container">
        <div class="row">
            <section class="span8" id="content-profile">
                {if !isset($personalEdit)}
                    <form action="" method="post" enctype="multipart/form-data">
                        <ul>
                            <li>
                                <div id="profile" class="tab-content active">
                                    <div class="row">
                                        <h2>{$lang.personal_information}</h2>
                                    </div>

                                    {foreach from=$groups item=g}
                                        <div class="row">
                                            <label class="inputs-label" for="group_{$g.ug_id}">{$g.name}</label>
                                            <div class="inputs">
                                                <input id="group_{$g.ug_id}" type="checkbox" name="groups[{$g.ug_id}]" value="yes" {if isset($smarty.post.groups[$g.ug_id])}checked="checked"{/if}>
                                            </div>
                                        </div>
                                    {/foreach}

                                    <div class="row">
                                        <label class="inputs-label">{$lang.status}</label>
                                        <div class="inputs">
                                            <select name="status">
                                                <option value="activate" {if $smarty.post.status == 'activate'}selected="selected"{/if}>{$lang.activate}</option>
                                                <option value="waiting" {if $smarty.post.status == 'waiting'}selected="selected"{/if}>{$lang.waiting}</option>
                                                <option value="banned" {if $smarty.post.status == 'banned'}selected="selected"{/if}>{$lang.banned}</option>
                                            </select>
                                        </div>
                                    </div>

                                    {if $member.author == 'true'}
                                        <div class="row">
                                            <label class="inputs-label" for="commission_percent">Commission (%)</label>
                                            <div class="inputs">
                                                <input type="text" name="commission_percent" value="{$member.commission_percent}">
                                                <small>{$lang.set_commission_percent}</small>
                                            </div>
                                        </div>
                                    {/if}

                                    <div class="row">
                                        <label class="inputs-label" for="firstname">{$lang.first_name}:</label>
                                        <strong>{$member.firstname}</strong>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="surname">{$lang.last_name}:</label>
                                        <strong>{$member.lastname}</strong>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="email">{$lang.email}:</label>
                                        <strong>{$member.email}</strong>
                                    </div>

                                    {if $member.company_name != ''}
                                        <div class="row">
                                            <label class="inputs-label" for="invoice_to">{$lang.company_name}:</label>
                                            <strong>{$member.company_name}</strong>
                                        </div>
                                    {/if}

                                    {if $member.live_city != ''}
                                        <div class="row">
                                            <label class="inputs-label" for="lives_in">Lives In:</label>
                                            <strong>{$member.live_city}</strong>
                                        </div>
                                    {/if}

                                    {if $member.country.name != ''}
                                        <div class="row">
                                            <label class="inputs-label" for="country">{$lang.country}</label>
                                            <strong>{$member.country.name}</strong>
                                        </div>
                                    {/if}

                                    <div class="row">
                                        <label class="inputs-label">{$lang.deposit_money2}</label>
                                        <strong>{$member.stats.deposit|string_format:"%.2f"} {$currency.symbol}</strong>
                                    </div>

                                    {if !isset($member.stats.products)}{else}
                                        <div class="row">
                                            <label class="inputs-label">{$lang.purchased_products}</label>
                                            <strong>{$member.buy} {$lang.products|@lower} {$lang.for} {$member.stats.total|string_format:"%.2f"} {$currency.symbol}</strong>
                                        </div>
                                    {/if}

                                    <div class="row">
                                        <label class="inputs-label">{$lang.author} ?</label>
                                        <div class="inputs">
                                            <input id="author" type="checkbox" name="author" value="yes" {if $member.author == 'true'}checked="checked"{/if}>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label">{$lang.sign_up}</label>
                                        <strong>{$member.register_datetime|date_format:"%e %B %Y à %H:%M"}</strong>
                                    </div>

                                    {if $member.last_login_datetime != ''}
                                        <div class="row">
                                            <label class="inputs-label">{$lang.last_signin}</label>
                                            <strong>{$member.last_login_datetime|date_format:"%e %B %Y à %H:%M"}</strong>
                                        </div>
                                    {/if}

                                    {if $member.ip_address != ''}
                                        <div class="row">
                                            <label class="inputs-label">{$lang.ip_adress}</label>
                                            <strong>{$member.ip_address}</strong>
                                        </div>
                                    {/if}

                                    {if $member.profile_title != ''}
                                        <div class="row">
                                            <label class="inputs-label">{$lang.profile_head}</label>
                                            <strong>{$member.profile_title}</strong>
                                        </div>
                                    {/if}

                                    {if $member.profile_desc != ''}
                                        <div class="row">
                                            <label class="inputs-label">{$lang.profile_text}</label>
                                            <strong>{$member.profile_desc}</strong>
                                        </div>
                                    {/if}
                                </div>
                            </li>
                            
                            <li> 
                                <div id="profile" class="tab-content active">
                                    <div class="row">
                                        <h2>{$lang.change_your_password}</h2>
                                    </div>

                                    <div class="row">
                                        <label class="inputs-label" for="nouveau-mot-de-passe">{$lang.new_password} :</label>
                                        <div class="inputs">
                                            <input name="password" type="text" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li> 
                                <div id="badges" class="tab-content active">
                                    <div class="row">
                                        <h2>{$lang.badges}</h2>
                                    </div>

                                    <div class="row">
                                        <div class="badge-checkbox">
                                            <img alt="{$lang.elite_author}" src="{$data_server}uploads/badges/elite_author.svg" />
                                            {$lang.elite_author}
                                            <input id="badge_elite_author" name="elite_author" value="true" type="checkbox" {if $smarty.post.elite_author == 'true'}checked="checked"{/if}>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="badge-checkbox">
                                            <img alt="{$lang.super_elite_author}" src="{$data_server}uploads/badges/super_elite_author.svg" />
                                            {$lang.super_elite_author}
                                            <input id="badge_super_elite_author" name="super_elite_author" value="true" type="checkbox" {if $smarty.post.super_elite_author == 'true'}checked="checked"{/if}>
                                        </div>
                                    </div>

                                    {if $badges}
                                        {foreach from=$badges item=b}
                                            <div class="row">
                                                <div class="badge-checkbox">
                                                    {if $b.photo != ''}
                                                        <img src="{$data_server}uploads/badges/{$b.photo}" alt="{$b.name}" />
                                                    {/if}
                                                    {$b.name}
                                                    <input id="badge_{$b.id}" name="badges[]" value="{$b.id}" type="checkbox" {if is_array($smarty.post.badges) && in_array($b.id, $smarty.post.badges)}checked="checked"{/if} /> 
                                                </div>
                                            </div>
                                        {/foreach}
                                    {/if}

                                    <div class="row">
                                        <div class="badge-checkbox">
                                            <img src="{$data_server}uploads/badges/author_was_featured.svg" alt="{$lang.featured_author}" />
                                            {$lang.featured_author}
                                            <input id="badge_featured_author" name="featured_author" value="true" type="checkbox" class="checkbox" {if $smarty.post.featured_author == 'true'}checked="checked"{/if} />
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        {if $smarty.get.c=='edit'}  
                            <button class="btn btn-big-shadow btn-middle" type="submit" name="edit" style="margin-top: 30px;">
                                <i class="hd-pen"></i>
                                {$lang.edit}
                            </button>
                        {else}
                            <button class="btn btn-big-shadow btn-middle" type="submit" name="add" style="margin-top: 30px;">
                                {$lang.edit}
                            </button>
                        {/if}
                    </form>
                {/if}
            </section>

            <aside id="aside-profil" class="span4">
                <div class="auteur-item-profil" id="auteur-item">
                    <div id="avatar-auteur">
                        {if $member.avatar != ''}
                            <img src="{$data_server}/uploads/members/{$member.member_id}/{$member.avatar}" alt="{$smarty.session.member.username}" />
                        {else}
                            <img src="{$data_server}images/member-default.png" />
                        {/if}
                    </div>
                    <div id="informations-auteur" class="auteur-reglages">
                        <h3>{$member.username}</h3>
                        {$lang.current_balance} : {$member.total|string_format:"%.2f"} {$currency.symbol}<br />   
                        
                        {if $member.ip_address != ''}
                            {$lang.ip_adress} : {$member.ip_address}<br />
                        {/if}
                        
                        {$lang.member_since} : {$member.register_datetime|date_format:"%B %Y"}<br />
                        
                        {if $member.last_login_datetime != ''}
                            {$lang.last_signin} : {$member.last_login_datetime|date_format:"%e %B %Y à %H:%M"}<br />
                        {/if}
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>