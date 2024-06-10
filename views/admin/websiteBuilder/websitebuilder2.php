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
    <a class="button" onclick="deleteElement()">Smazat</a>
</div>
<div id="helperBox">
    <p>aktivní element: <span id="activeElementSpan"></span></p>
    <p>třídy: <span id="activeElementStylesSpan"></span></p>
    <p>id: <span id="activeElementId"></span></p>
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
                #webBuilder {
                    all: initial;
                }
                #webBuilder-Body, #webBuilder-Main {
                    padding-top: 20px;
                    padding-bottom: 20px;
                    outline: 1px solid black;
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
            const activeElementSpan = document.getElementById("activeElementSpan")
            const activeElementStylesSpan = document.getElementById("activeElementStylesSpan")
            const activeElementId = document.getElementById("activeElementId")
            const webBuilderBody = document.getElementById("webBuilder-Body")
            const webBuilderMain = document.getElementById("webBuilder-Main")
            const contextMenu = document.getElementById("contextMenu")
            const webBuilder = document.getElementById("webBuilder")
            const textElements = ['p', 'h1', 'h2', 'h3', 'h4', 'h5']
            const fixedElements = [document.getElementById("webBuilder-Body"), document.getElementById("webBuilder-Main")]

            function elementMouseDown(event, el) {
                setActiveElement(el)
                event.stopPropagation()
            }
            const openContextMenu = () => {contextMenu.classList.remove("hidden")}
            const closeContextMenu = () => {contextMenu.classList.add("hidden")}
            const displayTextOptions = () => {
                if (activeElement) {
                    const rect = activeElement.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const spaceAbove = rect.top;

                    if (spaceBelow >= textOptions.offsetHeight) {
                        // There is enough space below the activeElement
                        textOptions.style.top = `${rect.bottom + window.scrollY}px`;
                        textOptions.style.left = `${rect.left + window.scrollX}px`;
                    } else if (spaceAbove >= textOptions.offsetHeight) {
                        // There is not enough space below, but there is enough space above
                        textOptions.style.top = `${rect.top + window.scrollY - textOptions.offsetHeight}px`;
                        textOptions.style.left = `${rect.left + window.scrollX}px`;
                    } else {
                        // Default to placing it below the activeElement if possible
                        textOptions.style.top = `${rect.bottom + window.scrollY}px`;
                        textOptions.style.left = `${rect.left + window.scrollX}px`;
                    }

                    textOptions.classList.remove('hidden');
                }
            }
            const hideTextOptions = () => { textOptions.classList.add("hidden") }
            function deactivateSelected() {
                activeElement = null
                activeElementSpan.innerText = ""
                activeElementStylesSpan.innerText = ""
                activeElementId.innerText = ""
                setActiveClass()
                hideTextOptions()
            }
            function textKeyDown(event, el) {
                if(event.key === "Enter") {
                    if(el.tagName === "P") {
                        event.preventDefault()
                        setActiveElement(el.parentElement)
                        addElement("p")
                    } else {
                        setActiveElement(el.parentElement)
                        el.blur()
                    }
                }
                if(event.key === "Backspace") {
                    if(el.innerText.length < 1) {
                        setActiveElement(el.parentElement)
                        el.remove()
                    }
                }
                if(event.key === "Escape") {
                    deactivateSelected()
                    el.blur()
                }
            }
            function addElement(tagname) {
                isSaved = false;
                if(activeElement) {
                    const el = document.createElement(tagname)
                    if(textElements.includes(tagname)) {
                        el.setAttribute("contenteditable", "true")
                        el.setAttribute("onkeydown", "textKeyDown(event, this)")
                        el.addEventListener("paste", (event) => {
                            event.preventDefault();
                            const text = (event.clipboardData || window.clipboardData).getData('text');
                            document.execCommand("insertText", false, text);
                        })
                    } else {
                        el.addEventListener("contextmenu", rightClick)
                    }
                    el.addEventListener("mousedown", (event) => elementMouseDown(event, el))
                    if(activeElement === webBuilderBody || activeElement === webBuilderMain) {
                        const wrapper = document.createElement("div")
                        wrapper.classList.add("webBuilder-block")
                        wrapper.appendChild(el)
                        activeElement.appendChild(wrapper)
                    } else {
                        activeElement.appendChild(el)
                    }
                    el.focus()
                    setActiveElement(el)
                }
            }
            function deleteElement(el) {
                el.remove()
                deactivateSelected()
            }
            function setActiveClass() {
                Array.from(document.getElementById("webBuilder").children).forEach(child => {
                    processAllElements(child, elem => {
                        if (elem === activeElement) {
                            elem.classList.add('active')
                        } else {
                            elem.classList.remove('active')
                        }
                    })
                })
            }
            function setActiveElement(el) {
                activeElement = el
                if(!textElements.includes(el.tagName.toLowerCase())) {
                    displayTextOptions()
                } else {
                    hideTextOptions()
                }
                setActiveClass()
                activeElementSpan.innerText = el.tagName
                let updatedClassList = el.classList.toString()
                    .replace("active", "")
                    .replace("sortable-chosen", "")
                activeElementStylesSpan.innerText = updatedClassList
                activeElementId.innerText = el.id

            }
            function processAllElements(element, callback) {
                callback(element)
                element.querySelectorAll('*').forEach(child => {
                    callback(child)
                })
            }
            function rightClick(e) {
                e.preventDefault();
                openContextMenu()
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
            fixedElements.forEach(el => {
                new Sortable(el, {
                    group: "nested",
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65
                })
                el.addEventListener("mousedown", (event) => elementMouseDown(event, el))
                el.addEventListener("contextmenu", rightClick)
            })
        </script>
    </div>
</main>
</body>
</html>