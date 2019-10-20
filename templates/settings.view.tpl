{extends file="default.view.tpl"}
{block name="title"}Paramètres{/block}

{block name="content"}
    <section>
        <div class="container">
            <h2 class="align-center">Paramètres</h2>
            <div class="row">
                <div class="col col-sm-12 col-md-8 col-md-offset-2">
                    <fieldset>
                        <legend>Alert threshold</legend>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>MIN:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.refresh_time_cli)}class="invalid"{/if} name="refresh_time_cli" value="{$options.refresh_time_cli|default: '120'}">
                                        {if isset($smarty.session.form_errors.refresh_time_cli)}
                                            <p class="validation-error">{$smarty.session.form_errors.refresh_time_cli}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>MAX:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.refresh_time_cli)}class="invalid"{/if} name="refresh_time_cli" value="{$options.refresh_time_cli|default: '120'}">
                                        {if isset($smarty.session.form_errors.refresh_time_cli)}
                                            <p class="validation-error">{$smarty.session.form_errors.refresh_time_cli}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="form-control">
                                    <button type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                    <fieldset>
                        <legend>Refresh time (in seconds):</legend>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>CLI:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.refresh_time_cli)}class="invalid"{/if} name="refresh_time_cli" value="{$options.refresh_time_cli|default: '120'}">
                                        {if isset($smarty.session.form_errors.refresh_time_cli)}
                                            <p class="validation-error">{$smarty.session.form_errors.refresh_time_cli}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>GUI:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.refresh_time_gui)}class="invalid"{/if} name="refresh_time_gui" value="{$options.refresh_time_gui|default: '120'}">
                                        {if isset($smarty.session.form_errors.refresh_time_gui)}
                                            <p class="validation-error">{$smarty.session.form_errors.refresh_time_gui}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="form-control">
                                    <button type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                    <fieldset>
                        <legend>Alert method:</legend>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>Pushbullet API key:</label>
                                        <input type="text" {if isset($smarty.session.form_errors.refresh_time_cli)}class="invalid"{/if} name="refresh_time_cli" value="{$options.refresh_time_cli|default: '120'}">
                                        {if isset($smarty.session.form_errors.refresh_time_cli)}
                                            <p class="validation-error">{$smarty.session.form_errors.refresh_time_cli}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                    <p>Method:</p>
                                        <label><input type="radio" name="radio">Pushbullet</label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label><input type="radio" name="radio">Email</label>
                                    </div>
                                </div>
                                 <div class="form-control">
                                    <button type="submit">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>
{/block}