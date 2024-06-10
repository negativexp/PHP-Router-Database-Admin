<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
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
        <a class="small" href="/admin/websiteBuilder">Zpátky</a>
        <a class="small" onclick="saveSite()">Uložit stránku</a>
        <a class="small" onclick="deactivateEditorStyle()">Deaktivace admin stylu</a>
        <a class="small" onclick="subnav('sub-nav1', this)">Elementy</a>
        <div class="sub-nav" id="sub-nav1">
            <a class="small" onclick="addElement('header')">header</a>
            <a class="small" onclick="addElement('section')">section</a>
            <a class="small" onclick="addElement('article')">article</a>
            <a class="small" onclick="MessageBox('popupForm')">img</a>
            <a class="small" onclick="addElement('div')">div</a>
            <a class="small" onclick="addElement('footer')">footer</a>
            <a class="small" onclick="addElement('a')">a</a>
        </div>
        <a class="small" onclick="subnav('sub-nav2', this)">Třídy</a>
        <div class="sub-nav" id="sub-nav2">
            <a class="small" onclick="addClass('column')">column</a>
            <a class="small" onclick="addClass('row')">row</a>
            <a class="small" onclick="addClass('vhCen')">vhCen</a>
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
        <a class="button" href="/admin/logout">Odhlásit se</a>
    </div>
</div>
<style>
    .tableOptions {
        padding: 0 !important;
    }
</style>
<div id="popupForm" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat fotku</h2>
        <label>
            <span>Název cesty či webové adres</span>
            <input spellcheck="false" type="text" id="imgSrc" required>
        </label>
        <div class="options">
            <a class="small button" onclick="MessageBox('popupForm')">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('img')">Přidat</a>
        </div>
    </form>
</div>
<div id="popupForm2" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat vlastní HTML/JS/CSS</h2>
        <textarea spellcheck="false" id="customHtml"></textarea>
        <div class="options">
            <a class="small button" onclick="MessageBox('popupForm2')">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('custom')">Přidat</a>
        </div>
    </form>
</div>
<div id="contextMenu" class="context-menu hidden">
    <span id="contextMenuActive"></span>
    <span id="contextMenuClasses"></span>
    <a class="button" onclick="deleteElement()">Smazat</a>
</div>
<main>
    <div class="wrapper-content">
        <div id="textOptions" class="hidden">
            <a class="small button" onclick="addElement(this.innerText)">p</a>
            <a class="small button" onclick="addElement(this.innerText)">h1</a>
            <a class="small button" onclick="addElement(this.innerText)">h2</a>
            <a class="small button" onclick="addElement(this.innerText)">h3</a>
            <a class="small button" onclick="addElement(this.innerText)">h4</a>
            <a class="small button" onclick="addElement(this.innerText)">h5</a>
            <a class="small button" onclick="addClass(this.innerText)">w100</a>
            <a class="small button" onclick="addClass(this.innerText)">w50</a>
            <a class="small button" onclick="addClass(this.innerText)">w33</a>
            <a class="small button" onclick="addClass(this.innerText)">w25</a>
        </div>

        <div id="webBuilder">
            <style>
                #webBuilder-Body, #webBuilder-Main {
                    margin: 5px;
                    padding-bottom: 20px;
                    border: 1px solid black;
                }
            </style>
            <div id="webBuilder-Body">
                <div id="webBuilder-Main">
                </div>
            </div>
            <link id="indexStyle" rel="stylesheet" href="../../resources/style.css">
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        </div>

        <script>
            let activeElement = null;
            let isSaved = true;
            const webBuilderBody = document.getElementById("webBuilder-Body")
            const contextMenu = document.getElementById("contextMenu")
            const webBuilder = document.getElementById("webBuilder")
            const fixedElements = [document.getElementById("webBuilder-Body"), document.getElementById("webBuilder-Main")]
            fixedElements.forEach(el => {
                new Sortable(el, {
                    group: "nested",
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65
                })
                el.addEventListener("mousedown", (event) => elementMouseDown(event, el))
            })
            function elementMouseDown(event, el) {
                setActiveElement(event, el)
                event.stopPropagation()
            }
            const openContextMenu = () => {contextMenu.classList.remove("hidden")}
            const closeContextMenu = () => {contextMenu.classList.add("hidden")}
            function deactivateSelected() {
                removeActiveClass()
                activeElement = null
                console.log("active el: NULL")
            }
            function removeActiveClass() {
                const arr = Array.from(document.querySelectorAll(".webBuilder-block"));
                arr.concat(fixedElements);
                arr.forEach(arrEl => {
                    if(arrEl.classList.contains("active")) {
                        arrEl.classList.remove("active")
                    }
                })
            }
            function setActiveElement(event, el) {
                removeActiveClass()
                el.classList.add("active")
                activeElement = el
                console.log("active el", el)
            }
            function addElement(tagname) {
                isSaved = false;
                if(activeElement) {
                    const el = document.createElement(tagname)
                    const wrapper = document.createElement("div")
                    wrapper.classList.add("webBuilder-block")
                    wrapper.appendChild(el)
                    el.addEventListener("mousedown", (event) => elementMouseDown(event, el))
                    activeElement.appendChild(wrapper)
                }
            }
            function deleteElement(el) {
                for(i = 0; i < fixedElements.count(); i++) {
                    if(fixedElements[i] === el) {
                        el.remove()
                        break
                    }
                }
            }

            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 's') {
                    event.preventDefault()
                    isSaved = true
                    console.log("saved")
                }
                if(event.ctrlKey && event.key === 'd') {
                    event.preventDefault()
                    closeContextMenu()
                    deactivateSelected()
                }
                if(event.key === "Delete" && activeElement) {
                    deleteElement(activeElement)
                    activeElement = null
                }
            })
            window.addEventListener('beforeunload', function (event) {
                if(!isSaved) {
                    event.preventDefault()
                    //chrome
                    event.returnValue = ''
                }
            });
        </script>
    </div>
</main>
</body>
</html>