<!--
Alexia's Weather station
Contributors: Damian972 (main), Eterismo
-->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Damian972">
    <title>{$smarty.const.WEBSITE_NAME|cat: ' ♦ '}{block name="title"}{/block}</title>
    <link rel="stylesheet" href="{$smarty.const.BASE_URL|cat: '/assets/css/mustard-ui.css'}">
    <link rel="stylesheet" href="{$smarty.const.BASE_URL|cat: '/assets/css/mustard-ui-fixes.css'}">
    {block name="header_css"}{/block}
    <script>
        const BASE_URL = "{$smarty.const.BASE_URL}";
        const API_URL = "{$smarty.const.BASE_URL|cat: '/api.php'}";
    </script>
    {block name="header_js"}{/block}
</head>
<body>
    <nav style="height: 80px;" id="top">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="/" class="text-white">{$smarty.const.WEBSITE_NAME}</a>
            </div>
        {if $is_logged}
            <ul class="nav-links">
                <li><a class="text-white" href="{$smarty.const.BASE_URL}">Dashboard</a></li>
                <li><a class="text-white" href="{$smarty.const.BASE_URL|cat: '/my-account.php'}">Mon compte</a></li>
                <li><a class="text-white" href="{$smarty.const.BASE_URL|cat: '/settings.php'}">Paramètres</a></li>
                <li><a class="text-white" href="{$smarty.const.BASE_URL|cat: '/logout.php'}">Déconnexion</a></li>
            </ul>
            <a class="mobile-menu-toggle text-white"></a>
            <ul class="mobile-menu menu">
                <li><a href="{$smarty.const.BASE_URL}">Dashboard</a></li>
                <li><a href="{$smarty.const.BASE_URL|cat: '/my-account.php'}">Mon compte</a></li>
                <li><a href="{$smarty.const.BASE_URL|cat: '/settings.php'}">Paramètres</a></li>
                <li><a href="{$smarty.const.BASE_URL|cat: '/logout.php'}contact.html">Déconnexion</a></li>
            </ul>
        {/if}
        </div>
    </nav>
    {block name="content"}{/block}

    <script src="{$smarty.const.BASE_URL|cat: '/assets/js/jquery.js'}"></script>
    <script src="{$smarty.const.BASE_URL|cat: '/assets/js/mustard-ui.js'}"></script>
    {block name="footer_js"}{/block}
    </body>
</html>