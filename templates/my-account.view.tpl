{extends file="default.view.tpl"}
{block name="title"}Mon compte{/block}

{block name="content"}
    <div class="container align-center">
        <h2>Mon compte</h2>
        {if !empty($smarty.session.notification.message)}
            <p class="alert alert-{$smarty.session.notification.type}" style="width: 300px;">{$smarty.session.notification.message}</p>
        {/if}

        <form method="post" style="width: 300px;">
            <div class="form-control">
                <label>Nom d'utilisateur:</label>
                <input type="text" {if isset($smarty.session.form_errors.username)}class="invalid"{/if} placeholder="Entrez votre nom" name="username" value="{$user_account.username|default:''}">
                {if isset($smarty.session.form_errors.username)}
                    <p class="validation-error">{$smarty.session.form_errors.username}</p>
                {/if}
               
            </div>
            <div class="form-control">
                <label>Email:</label>
                <input type="text" {if isset($smarty.session.form_errors.email)}class="invalid"{/if} placeholder="Entrez votre email" name="email" value="{$user_account.email|default:''}">
                {if isset($smarty.session.form_errors.email)}
                    <p class="validation-error">{$smarty.session.form_errors.email}</p>
                {/if}
            </div>
            <div class="form-control">
                <label>Mot de passe:</label>
                <input type="password" {if isset($smarty.session.form_errors.password)}class="invalid"{/if} placeholder="Entrez votre mot de passe" name="password">
                {if isset($smarty.session.form_errors.password)}
                    <p class="validation-error">{$smarty.session.form_errors.password}</p>
                {/if}
            </div>
            <div class="form-control">
                <label>Confirmer votre mot de passe:</label>
                <input type="password" {if isset($smarty.session.form_errors.password)}class="invalid"{/if} placeholder="Confirmez votre mot de passe" name="password_v">
            </div>

            <div class="form-control">
                <button type="submit" class="btn-primary" name="change_my_account_info">Soumettre</button>
            </div>
        </form>
    </div>
{/block}