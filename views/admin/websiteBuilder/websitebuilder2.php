<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body id="websiteBuilderBody">
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
        <a class="small" onclick="saveWebsite()">Uložit stránku</a>
        <a class="small" onclick="toggleEditorStyle()">Deaktivace admin stylu</a>
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
            <a class="small" onclick="addClass('w25')">w25</a>
            <a class="small" onclick="addClass('w50')">w50</a>
            <a class="small" onclick="addClass('w75')">w75</a>
            <a class="small" onclick="addClass('w100')">w100</a>
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
    <div id="displayDeleteButton" class="hidden">
        <a class="button" onclick="deleteElement()">Smazat</a>
    </div>
    <a class="button" onclick="">nastavit ID</a>
    <a class="button" onclick="">nastavit Třídy</a>
    <div id="displayImgSettings" class="hidden">
        <a class="button" onclick="">nastavit src</a>
    </div>
</div>
<div id="helperBox">
    <p>Element: <span id="activeElementSpan">...</span></p>
    <p>Třídy: <span id="activeElementStylesSpan">...</span></p>
    <p>ID: <span id="activeElementId">...</span></p>
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
            let copyElement = null
            const displayImgSettingsDiv = document.getElementById("displayImgSettings");
            const displayDeleteButtonDiv = document.getElementById("displayDeleteButton")
            const activeElementSpan = document.getElementById("activeElementSpan")
            const activeElementStylesSpan = document.getElementById("activeElementStylesSpan")
            const activeElementId = document.getElementById("activeElementId")
            const webBuilderBody = document.getElementById("webBuilder-Body")
            const webBuilderMain = document.getElementById("webBuilder-Main")
            const contextMenu = document.getElementById("contextMenu")
            const webBuilder = document.getElementById("webBuilder")
            const textElements = ['p', 'h1', 'h2', 'h3', 'h4', 'h5']
            const fixedElements = [document.getElementById("webBuilder-Body"), document.getElementById("webBuilder-Main")]

            let stylesRemoved = false;
            let deletedRules = [
                '#webBuilder { }',
                '#webBuilder-Body, #webBuilder-Main { padding-bottom: 20px; position: relative; }',
                '#webBuilder-Body { padding-top: 20px; }',
                '#webBuilder-Main::after { content: "main"; }',
                '#webBuilder-Body::after { content: "body"; }',
                '#webBuilder-Body::after, #webBuilder-Main::after { width: 100%; height: 20px; position: absolute; bottom: 0; left: 0; text-align: center; opacity: 0.3; }'
            ];
            function toggleEditorStyle() {
                const styleElement = document.getElementById('adminStyle');
                const targetSelectors = [
                    '#webBuilder',
                    '#webBuilder-Body',
                    '#webBuilder-Main',
                    '#webBuilder-Body, #webBuilder-Main',
                    '#webBuilder-Body::after',
                    '#webBuilder-Main::after'
                ];

                if (stylesRemoved) {
                    // Re-insert deleted rules
                    for (let rule of deletedRules) {
                        try {
                            styleElement.sheet.insertRule(rule, styleElement.sheet.cssRules.length);
                        } catch (e) {
                            console.warn("Error re-inserting rule: ", e);
                        }
                    }
                } else {
                    for (let sheet of document.styleSheets) {
                        try {
                            for (let i = sheet.cssRules.length - 1; i >= 0; i--) {
                                let rule = sheet.cssRules[i];
                                if (targetSelectors.includes(rule.selectorText)) {
                                    deletedRules.push(rule.cssText);
                                    sheet.deleteRule(i);
                                }
                            }
                        } catch (e) {
                            console.warn("Error deleting rule: ", e);
                        }
                    }
                }

                stylesRemoved = !stylesRemoved;
            }
            function elementMouseDown(event, el) {
                event.stopPropagation()
                closeContextMenu()
                setActiveElement(el)
            }
            const openContextMenu = () => {
                if(activeElement.tagName === "img") {
                    displayImgSettingsDiv.classList.remove("hidden")
                } else displayImgSettingsDiv.classList.add("hidden")
                if(activeElement.tagName !== "DIV" && activeElement.id !== "webBuilder-Body") {
                    displayDeleteButtonDiv.classList.remove("hidden")
                } else displayDeleteButtonDiv.classList.add("hidden")
                contextMenu.classList.remove("hidden")
            }
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
            function addClass(className) {
                if(activeElement && !activeElement.classList.contains("webBuilder-block") && activeElement.id !== "webBuilder-Body") {
                    activeElement.classList.toggle(className)
                    isSaved = false
                    setActiveElement(activeElement)
                }
            }
            function addElement(tagname) {
                isSaved = false;
                if(activeElement) {
                    const el = document.createElement(tagname)
                    if(tagname === "img") {
                        el.src = document.getElementById("imgSrc").value
                    }
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
            function deleteElement() {
                if (activeElement.tagName !== "DIV" && activeElement.id !== "webBuilder-Body") {
                    activeElement.remove()
                    deactivateSelected()
                    closeContextMenu()
                } else {
                    closeContextMenu()
                }
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
                contextMenu.style.left = e.pageX + "px"
                contextMenu.style.top = e.pageY + "px"
                e.preventDefault();
                openContextMenu()
            }
            window.addEventListener("resize", () => {
                closeContextMenu()
                displayTextOptions()
            })
            document.addEventListener('keydown', function(event) {
                if (event.ctrlKey && event.key === 's') {
                    event.preventDefault()
                    isSaved = true
                    saveWebsite()
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
                if(event.ctrlKey && event.key === 'c') {
                    if(!textElements.includes(activeElement.tagName.toLowerCase())) {
                        event.preventDefault()
                        console.log("copy block")
                        copyElement = activeElement.cloneNode(true)
                    }
                }
                if(event.ctrlKey && event.key === 'v') {
                    //napicu zkopirovany picoviny nejdou
                    if(copyElement) {
                        if(!textElements.includes(activeElement.tagName.toLowerCase())) {
                            event.preventDefault()
                            console.log("paste block")
                            if(activeElement) {
                                copyElement.addEventListener("mousedown", (event) => elementMouseDown(event, copyElement))
                                copyElement.childNodes.forEach(child => {
                                    processAllElements(child, childEl => {
                                        childEl.addEventListener("mousedown", (event) => elementMouseDown(event, childEl))
                                    })
                                })
                                if(activeElement === webBuilderBody || activeElement === webBuilderMain) {
                                    const wrapper = document.createElement("div")
                                    wrapper.classList.add("webBuilder-block")
                                    wrapper.appendChild(copyElement)
                                    activeElement.appendChild(wrapper)
                                } else {
                                    activeElement.appendChild(copyElement)
                                }
                            }
                            deactivateSelected()
                            setActiveElement(copyElement)
                            copyElement = activeElement.cloneNode(true)
                        }
                    }
                }
            })
            window.addEventListener('beforeunload', function (event) {
                if(!isSaved) {
                    event.preventDefault()
                    //chrome
                    event.returnValue = ''
                }
            });
            function applyDeletedStyles(element, deletedRules) {
                deletedRules.forEach(rule => {
                    const style = rule.split('{')[1].trim().slice(0, -1);
                    const declarations = style.split(';').filter(Boolean);

                    declarations.forEach(declaration => {
                        const [property, value] = declaration.split(':').map(part => part.trim());
                        element.style[property] = value;
                    });
                });
            }
            document.addEventListener('DOMContentLoaded', () => {
                // Use document.styleSheets[1] to target the second stylesheet
                const sheet = document.styleSheets[1];

                // Array to store deleted rules from the second stylesheet
                let deletedRulesIndexStyle = [];

                // Loop through the CSS rules of the second stylesheet and delete 'body' styles
                for (let i = sheet.cssRules.length - 1; i >= 0; i--) {
                    let rule = sheet.cssRules[i];
                    if (rule.type === CSSRule.STYLE_RULE && ['body'].includes(rule.selectorText)) {
                        deletedRulesIndexStyle.push(rule.cssText);
                        sheet.deleteRule(i);
                    }
                }

                // Apply deleted styles to #webBuilder-Body
                const webBuilderBody = document.getElementById('webBuilder-Body');
                applyDeletedStyles(webBuilderBody, deletedRulesIndexStyle);
                fixedElements.forEach(el => {
                    new Sortable(el, {
                        group: "nested",
                        animation: 150,
                        fallbackOnBody: true,
                        swapThreshold: 0.65
                    })
                    el.addEventListener("mousedown", (event) => {
                        elementMouseDown(event, el)
                        closeContextMenu()
                    })
                    el.addEventListener("contextmenu", rightClick)
                })
                function isBeforeOrAfter(element, referenceElement) {
                    // Function to check if the element is before the reference element
                    function isBefore(element, referenceElement) {
                        var prevSiblings = [];
                        var prevSibling = element.previousSibling;
                        while (prevSibling) {
                            if (prevSibling.nodeType === Node.ELEMENT_NODE) {
                                prevSiblings.push(prevSibling);
                            }
                            prevSibling = prevSibling.previousSibling;
                        }

                        return prevSiblings.includes(referenceElement);
                    }

                    // Function to check if the element is after the reference element
                    function isAfter(element, referenceElement) {
                        var nextSiblings = [];
                        var nextSibling = element.nextSibling;
                        while (nextSibling) {
                            if (nextSibling.nodeType === Node.ELEMENT_NODE) {
                                nextSiblings.push(nextSibling);
                            }
                            nextSibling = nextSibling.nextSibling;
                        }

                        return nextSiblings.includes(referenceElement);
                    }

                    // Check if element is before or after the reference element
                    return {
                        isBefore: isBefore(element, referenceElement),
                        isAfter: isAfter(element, referenceElement)
                    };
                }

                function loadWebsite(html) {
                    // Parse the HTML string into a document
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Extract body content
                    const webBuilderBody = document.getElementById('webBuilder-Body');
                    const webBuilderMain = document.getElementById('webBuilder-Main');
                    const bodyContent = Array.from(doc.body.children);
                    bodyContent.forEach(bodyChild => {
                        processAllElements(bodyChild, bodyChildChild => {
                            if(bodyChildChild.tagName !== "MAIN") {
                                if(textElements.includes(bodyChildChild.tagName.toLowerCase())) {
                                    bodyChildChild.setAttribute("contenteditable", "true")
                                    bodyChildChild.setAttribute("onkeydown", "textKeyDown(event, this)")
                                    bodyChildChild.addEventListener("paste", (event) => {
                                        event.preventDefault();
                                        const text = (event.clipboardData || window.clipboardData).getData('text');
                                        document.execCommand("insertText", false, text);
                                    })
                                } else {
                                    bodyChildChild.addEventListener("contextmenu", rightClick)
                                }
                                bodyChildChild.addEventListener("mousedown", (event) => elementMouseDown(event, bodyChildChild))
                            }
                        })
                        if (bodyChild.tagName === "MAIN") {
                            Array.from(bodyChild.children).forEach(mainChild => {
                                webBuilderMain.appendChild(mainChild);
                            });
                            webBuilderMain.classList = bodyChild.classList
                        } else {
                            var result = isBeforeOrAfter(bodyChild, doc.body.querySelector("main"));
                            if(result.isAfter) {
                                webBuilderBody.insertBefore(bodyChild, webBuilderMain)
                            }
                            if(result.isBefore) {
                                webBuilderBody.appendChild(bodyChild)
                            }
                        }
                    });
                }

                // Example usage: you can get the HTML content from an API or any other source
                const savedHTML = `<?= file_get_contents("views/index.php") ?>`;
                loadWebsite(savedHTML);
            });
            function elementToJson(element) {
                const obj = {
                    tag: element.tagName.toLowerCase(),
                };
                if (element.hasAttributes()) {
                    const attrs = element.attributes;
                    for (let i = 0; i < attrs.length; i++) {
                        obj[attrs[i].name] = attrs[i].value;
                    }
                }
                if (element.childElementCount > 0) {
                    obj.children = Array.from(element.children).map(child => elementToJson(child));
                } else if (element.textContent.trim()) {
                    obj.text = element.textContent.trim();
                }
                return obj;
            }
            function saveWebsite() {
                const webBuilderElement = document.getElementById('webBuilder-Body');
                const inputHTML = webBuilderElement.innerHTML;
                const parser = new DOMParser();
                const doc = parser.parseFromString(inputHTML, 'text/html');
                var xhr = new XMLHttpRequest();
                var url = "/admin/websiteBuilder/editor";
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.send(JSON.stringify(elementToJson(doc.body), null, 2));
                console.log(elementToJson(doc.body))
                console.log("Saved:");
            }
        </script>
    </div>
</main>
</body>
</html>