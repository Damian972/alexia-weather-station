{extends file="default.view.tpl"}
{block name="title"}Paramètres{/block}

{block name="content"}
    <section>
        <div class="container">
            <h2 class="align-center">Paramètres</h2>
            {if !empty($smarty.session.notification.message)}
                <center><p class="alert alert-{$smarty.session.notification.type}" style="width: 300px;">{$smarty.session.notification.message}</p></center>
            {/if}
            <div class="row">
                <div class="col col-sm-12 col-md-8 col-md-offset-2">
                    <fieldset>
                        <legend>Alert threshold</legend>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>MIN:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.alert_threshold_min_temperature)}class="invalid"{/if} name="alert_threshold_min_temperature" value="{$options.alert_threshold_min_temperature|default: '10'}">
                                        {if isset($smarty.session.form_errors.alert_threshold_min_temperature)}
                                            <p class="validation-error">{$smarty.session.form_errors.alert_threshold_min_temperature}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                        <label>MAX:</label>
                                        <input type="number" {if isset($smarty.session.form_errors.alert_threshold_max_temperature)}class="invalid"{/if} name="alert_threshold_max_temperature" value="{$options.alert_threshold_max_temperature|default: '40'}">
                                        {if isset($smarty.session.form_errors.alert_threshold_max_temperature)}
                                            <p class="validation-error">{$smarty.session.form_errors.alert_threshold_max_temperature}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="form-control">
                                    <button type="submit" name="settings_alert_threshold_form">Enregistrer</button>
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
                                    <button type="submit" name="settings_refresh_time_form">Enregistrer</button>
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
                                        <input type="text" {if isset($smarty.session.form_errors.alert_method_pushbullet_api_key)}class="invalid"{/if} name="alert_method_pushbullet_api_key" value="{$options.alert_method_pushbullet_api_key|default: ''}">
                                        {if isset($smarty.session.form_errors.alert_method_pushbullet_api_key)}
                                            <p class="validation-error">{$smarty.session.form_errors.alert_method_pushbullet_api_key}</p>
                                        {/if}
                                    </div>
                                </div>
                                <div class="col col-sm-12 col-md-6">
                                    <div class="form-control">
                                    <p>Method:</p>
                                        {if isset($options.alert_method) && $options.alert_method eq '0'}
                                            <label><input type="radio" name="alert_method" value="0" checked>Pushbullet</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label><input type="radio" name="alert_method" value="1">Email</label>
                                        {else}
                                            <label><input type="radio" name="alert_method" value="0">Pushbullet</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label><input type="radio" name="alert_method" value="1" checked>Email</label>
                                        {/if}
                                        
                                        {if isset($smarty.session.form_errors.alert_method)}
                                            <p class="validation-error">{$smarty.session.form_errors.alert_method}</p>
                                        {/if}
                                    </div>
                                    
                                </div>
                                 <div class="form-control">
                                    <button type="submit" name="alert_method_form">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </section>
{/block}