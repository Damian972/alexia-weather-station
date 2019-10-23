{extends file="default.view.tpl"}
{block name="title"}Connexion{/block}

{block name="content"}
    <div class="container align-center">
        <h2>Connexion</h2>
        {if !empty($smarty.session.notification.message)}
            <p class="alert alert-{$smarty.session.notification.type}">{$smarty.session.notification.message}</p>
        {/if}

        <form method="post" style="width: 300px;">
            <div class="form-control">
                <label>Email:</label>
                <input type="text" {if isset($smarty.session.form_errors.email)}class="invalid"{/if} placeholder="Entrez votre email" name="email">
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
                <button type="submit" name="login_form">Soumettre</button>
            </div>
        </form>
    </div>
{/block}