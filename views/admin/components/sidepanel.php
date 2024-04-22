<div class="sidepanel">
    <div class="button" onclick="mobilenav()">
        <img class="icon" src="../imgs/nav.svg">
    </div>
    <nav>
        <a class="small" href="/admin">Dashboard</a>
        <a class="small" onclick="subnav('sub-nav1')">Router</a>
        <div class="sub-nav" id="sub-nav1">
            <a class="small" href="/admin/router/routes">Routes</a>
            <a class="small" href="/admin/router/allowedFiles">Povolené soubory</a>
            <a class="small" href="/admin/router/blockedFolders">Zablokované složky</a>
        </div>
        <a class="small" onclick="subnav('sub-nav2')">Databáze</a>
        <div class="sub-nav" id="sub-nav2">
            <a class="small" href="/admin/database/tables">Tabulky</a>
        </div>
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
        <a class="button small">Odhlásit se</a>
    </div>
</div>
