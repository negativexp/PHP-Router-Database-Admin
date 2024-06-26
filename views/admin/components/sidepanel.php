<div class="sidepanel">
    <div class="button" onclick="mobilenav()">
        <img class="icon" src="../imgs/nav.svg">
    </div>
    <nav>
        <?php
            $parsedURL = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            function active($url, $parsedurl): string {
                if($url == $parsedurl) {
                    return "active";
                }
                return "";
            }
        ?>
        <a class="small <?= $parsedURL == "/admin" ? "active" : "" ?>" href="/admin">Dashboard</a>
        <a class="small <?= str_contains($parsedURL, "/admin/router") ? "active" : "" ?>" onclick="subnav('sub-nav1', this)">Router</a>
        <div class="sub-nav <?= str_contains($parsedURL, "/admin/router") ? "subnavopen" : "" ?>" id="sub-nav1">
            <a class="small <?= active("/admin/router/routes", $parsedURL) ?>" href="/admin/router/routes">Routes</a>
            <a class="small <?= active("/admin/router/allowedFiles", $parsedURL)?>" href="/admin/router/allowedFiles">Povolené soubory</a>
            <!-- <a class="small <?= active("/admin/router/blockedFolders", $parsedURL) ?>" href="/admin/router/blockedFolders">Zablokované složky</a> -->
        </div>
        <a class="small <?= str_contains($parsedURL, "/admin/database") ? "active" : "" ?>" onclick="subnav('sub-nav2', this)">Databáze</a>
        <div class="sub-nav <?= str_contains($parsedURL, "/admin/database") ? "subnavopen" : "" ?>" id="sub-nav2">
            <a class="small <?= active("/admin/database/tables", $parsedURL) ?>" href="/admin/database/tables">Tabulky</a>
            <a class="small <?= active("/admin/database/customSql", $parsedURL) ?>" href="/admin/database/customSql">Vlastní SQL</a>
        </div>
        <a class="small <?= active("/admin/fileManager", $parsedURL) ?>" href="/admin/fileManager">Správce souborů</a>
        <a class="small <?= active("/admin/websiteBuilder", $parsedURL) ?>" href="/admin/websiteBuilder">Website Builder</a>
        <a class="small <?= active("/admin/cssEditor", $parsedURL) ?>" href="/admin/cssEditor">CSS editor</a>
        <a class="small <?= active("/admin/web-settings", $parsedURL) ?>" href="/admin/web-settings">Web nastavení</a>
        <a class="small <?= active("/admin/logs", $parsedURL) ?>" href="/admin/logs">Logs</a>
        <a class="logout small button">Odhlásit se</a>
    </nav>
    <div class="profile">
        <div class="wrapper">
            <img class="icon" src="../imgs/typek.jpg">
            <div class="info">
                <p class="medium">Matyáš Pavel Schuller</p>
                <p class="small">Administrátor</p>
            </div>
        </div>
        <a class="button" href="/admin/logout">Odhlásit se</a>
    </div>
</div>
