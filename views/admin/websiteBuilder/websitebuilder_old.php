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
<div id="contextMenu" class="context-menu" style="display:none">
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
                body > main {
                    padding-bottom: 100Px;
                }
                .tableOptions {
                    border-radius: 0 !important;
                    padding-bottom: 10px !important;
                }
            </style>
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
            <link id="indexStyle" rel="stylesheet" href="../../resources/style.css">
            <main id="webBuilder-blocks">
                <?php
                function setAttributesRecursively($element) {
                    if ($element->nodeType === XML_ELEMENT_NODE) {
                        $element->setAttribute('tabindex', '0');
                        $element->setAttribute('onfocus', 'setActiveElement(this)');

                        $tagName = $element->tagName;
                        if (in_array($tagName, ['p', 'h1', 'h2', 'h3', 'h4', 'h5'])) {
                            $element->setAttribute('contenteditable', 'true');
                            $currentClass = $element->getAttribute('class');
                            $newClass = $currentClass ? $currentClass . ' editingStyleText' : 'editingStyleText';
                            $element->setAttribute('class', $newClass);
                            $element->setAttribute('onkeydown', 'textKeyDown(event, this)');
                        }
                    }

                    foreach ($element->childNodes as $child) {
                        if ($child->nodeType === XML_ELEMENT_NODE) {
                            setAttributesRecursively($child);
                        }
                    }
                }

                function wrapAndAddAttributes($element, $dom) {
                    setAttributesRecursively($element);

                    $wrapper = $dom->createElement('div');
                    $wrapper->setAttribute('class', 'webBuilder-block');
                    $wrapper->setAttribute('tabindex', '0');
                    $wrapper->setAttribute('onfocus', 'setActiveElement(this)');
                    $wrapper->setAttribute('spellcheck', 'false');
                    $wrapper->setAttribute('oncontextmenu', 'rightClick(event)');
                    $wrapper->setAttribute('ondblclick', 'toggleHtml(this)');

                    $wrapper->appendChild($element->cloneNode(true));
                    return $wrapper;
                }

                function getBodyContent($filePath) {
                    $content = file_get_contents($filePath);
                    $dom = new DOMDocument;
                    libxml_use_internal_errors(true);
                    if($content) {
                        $dom->loadHTML($content);
                        libxml_clear_errors();

                        $body = $dom->getElementsByTagName('body')->item(0);
                        $html = '';

                        foreach ($body->childNodes as $child) {
                            if ($child->nodeName === 'main') {
                                foreach ($child->childNodes as $mainChild) {
                                    if (trim($dom->saveHTML($mainChild)) !== '') {
                                        $wrappedElement = wrapAndAddAttributes($mainChild, $dom);
                                        $html .= $dom->saveHTML($wrappedElement);
                                    }
                                }
                            } else {
                                if (trim($dom->saveHTML($child)) !== '') {
                                    $wrappedElement = wrapAndAddAttributes($child, $dom);
                                    $html .= $dom->saveHTML($wrappedElement);
                                }
                            }
                        }

                        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $html);
                    }
                    return "";
                }

                if (isset($viewName)) {
                    $file = "views/" . $viewName . ".php";
                    if (file_exists($file)) {
                        $bodyContent = getBodyContent($file);
                        echo $bodyContent;
                    } else {
                        echo "<p>The view file does not exist.</p>";
                    }
                } else {
                    echo "<p>No view name specified.</p>";
                }
                ?>
            </main>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the webBuilder-blocks element
            const webBuilderBlocks = document.getElementById('webBuilder-blocks');

            // Function to apply styles from body to webBuilder-blocks
            function applyBodyStyles() {
                // Get all stylesheets
                const stylesheet = document.getElementById("indexStyle").sheet;

                try {
                    // Get all rules in the stylesheet
                    const rules = stylesheet.cssRules || stylesheet.rules;

                    for (const rule of rules) {
                        // Check if the rule is for the body
                        if (rule.selectorText === 'body') {
                            // Apply each style property to webBuilder-blocks
                            for (let style of rule.style) {
                                webBuilderBlocks.style[style] = rule.style[style];
                            }
                        }
                    }
                } catch (e) {
                    console.log(`Couldn't read the stylesheet: ${stylesheet.href}`);
                }
            }

            applyBodyStyles();
        });

        const blocks = document.getElementById("webBuilder-blocks")
        const textOptions = document.getElementById("textOptions")
        const contextMenuActive = document.getElementById("contextMenuActive")
        const contextMenuActiveClasses = document.getElementById("contextMenuClasses")
        const textElements = ['P', 'H1', 'H2', 'H3', 'H4', 'H5']
        let lastAppended = null
        let activeElement = null
        let isSaved = false;
        let isDeactivatedAdminStyle = true;

        function deactivateEditorStyle() {
            const webBuilder = document.getElementById("webBuilder");
            webBuilder.classList.toggle('admin-styles');

            isDeactivatedAdminStyle = !isDeactivatedAdminStyle;
        }

        function processAllElements(element, callback) {
            callback(element)
            element.querySelectorAll('*').forEach(child => {
                callback(child)
            })
        }
        function setActiveElement(el) {
            activeElement = el
            Array.from(blocks.children).forEach(block => {
                processAllElements(block, elem => {
                    if (elem === activeElement) {
                        elem.classList.add('active')
                    } else {
                        elem.classList.remove('active')
                    }
                })
            })
        }
        function unsetActiveElement() {
            activeElement = null
            Array.from(blocks.children).forEach(block => {
                processAllElements(block, elem => {
                    elem.classList.remove('active');
                });
            });
            textOptions.classList.add("hidden")
        }
        function textKeyDown(event, el) {
            if (event.keyCode === 13 || event.keyCode === 27) {
                el.blur();
                const parent = activeElement.parentNode
                unsetActiveElement()
                setActiveElement(parent)
                if(el.tagName === "P") {
                    setActiveElement(parent)
                    append(document.createElement("p"))
                }
                // Workaround for webkit's bug
                window.getSelection().removeAllRanges();
            }
        }
        // Object to store attributes when toggling HTML
        let storedAttributes = {};

        function toggleHtml(block) {
            hideMenu();

            if (block.children[0].tagName !== "TEXTAREA") {
                var outerHtml = block.outerHTML;

                function extractAttributes(html) {
                    const attributesToStore = ["class", "tabindex", "onfocus", "spellcheck", "oncontextmenu", "ondblclick", "draggable", "onkeydown", "contenteditable"];
                    let extractedAttributes = {};

                    attributesToStore.forEach(attr => {
                        const regex = new RegExp(`\\s*${attr}="([^"]*)"`, 'gi');
                        const matches = html.matchAll(regex);
                        for (const match of matches) {
                            if (!extractedAttributes[attr]) {
                                extractedAttributes[attr] = [];
                            }
                            extractedAttributes[attr].push(match[1]);
                        }
                        html = html.replace(regex, '');
                    });

                    // Remove extra spaces inside tags
                    html = html.replace(/<\s*(\w+)\s*(.*?)\s*>/g, '<$1$2>');

                    return { html, extractedAttributes };
                }

                if (block.classList.contains("webBuilder-block") || block.classList.contains("editingStyleText")) {
                    const result = extractAttributes(outerHtml);
                    outerHtml = result.html;
                    storedAttributes = result.extractedAttributes;
                }

                Array.from(block.children).forEach(child => child.remove());

                const textarea = document.createElement("textarea");
                textarea.classList.add("admin-styles");
                textarea.value = outerHtml;
                block.appendChild(textarea);

            } else {
                let outerHtml = block.children[0].value;
                block.children[0].remove();

                if (storedAttributes && (outerHtml.includes('class="webBuilder-block"') || outerHtml.includes('class="editingStyleText"'))) {
                    Object.keys(storedAttributes).forEach(attr => {
                        const regex = new RegExp(`<([a-z]+)([^>]*)>`, 'gi');
                        let index = 0;
                        outerHtml = outerHtml.replace(regex, (match, p1, p2) => {
                            if (storedAttributes[attr][index]) {
                                const replacement = `${p1}${p2} ${attr}="${storedAttributes[attr][index]}"`;
                                index++;
                                return `<${replacement.trim()}>`;
                            }
                            return match;
                        });
                    });
                }

                block.outerHTML = outerHtml;
            }
        }


        function append(el) {
            el.tabIndex = 0
            el.setAttribute("onfocus", "setActiveElement(this)")
            if (textElements.includes(el.tagName)) {
                el.classList.add("editingStyleText")
                el.setAttribute("onkeydown", "textKeyDown(event, this)")
                el.setAttribute("contenteditable", "true")
                el.setAttribute("spellcheck", "false")
            }
            if (activeElement) {
                activeElement.appendChild(el)
            } else {
                const div = document.createElement("div")
                div.addEventListener("contextmenu", rightClick)
                div.classList.add("webBuilder-block")
                div.tabIndex = 0
                div.setAttribute("onfocus", "setActiveElement(this)")
                div.setAttribute("ondblclick", "toggleHtml(this)")
                if (el.tagName !== "NULL") {
                    div.appendChild(el)
                }
                blocks.appendChild(div)
            }
            lastAppended = el

            //jinak to nefocusuje P nevim
            setTimeout(() => {
                el.focus();
            }, 0);
        }

        function addElement(tagName) {
            let element
            if (tagName === "custom") {
                const customHtml = document.getElementById("customHtml").value
                const tempDiv = document.createElement('div')
                tempDiv.innerHTML = customHtml.trim()
                element = tempDiv.firstChild
            } else {
                element = document.createElement(tagName)
                if(tagName === "img") {
                    element.setAttribute("src", document.getElementById("imgSrc").value)
                }
            }
            append(element)
            isSaved = false
        }
        function getClosestBlockElement(element) {
            return element.closest(".webBuilder-block > *")
        }
        function getClosestBlock(element) {
            return element.closest(".webBuilder-block")
        }
        function deleteElement() {
            activeElement.remove()
            activeElement = null
            hideMenu()
            isSaved = false
            textOptions.classList.add("hidden")
            Array.from(blocks.childNodes).forEach(block => {
                if(block.childElementCount === 0) {
                    block.remove()
                }
            })
        }
        function addClass(className) {
            if(activeElement && !activeElement.classList.contains("webBuilder-block")) {
                activeElement.classList.toggle(className)
                isSaved = false
            }
        }
        document.onclick = hideMenu;
        function hideMenu() {
            document.getElementById("contextMenu")
                .style.display = "none"
        }
        function rightClick(e) {
            e.preventDefault();
            if (document.getElementById("contextMenu").style.display == "block")
                hideMenu();
            else {
                if(activeElement.tagName) {
                    contextMenuActive.innerText = activeElement.tagName
                }
                var dupClasses = Array.from(activeElement.classList)
                dupClasses.splice(dupClasses.indexOf("editingStyleText"), 1)
                contextMenuActiveClasses.innerText = dupClasses
                var menu = document.getElementById("contextMenu")
                menu.style.display = 'block'
                menu.style.left = e.pageX + "px"
                menu.style.top = e.pageY + "px"
            }
        }
        sortable = new Sortable(blocks, {
            animation: 150, // Optional: Animation speed when moving items
            // Add any other options here as needed
        });


        function getOffset(el) {
            const rect = el.getBoundingClientRect();
            return {
                left: rect.left + window.scrollX,
                top: rect.top + window.scrollY - 39
            };
        }

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault()
                saveSite()
                isSaved = true
                console.log("ctrl s")
            }
            if(event.ctrlKey && event.key === 'd') {
                event.preventDefault()
                unsetActiveElement()
                activeElement.blur()
                console.log("ctrl d")
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

        function saveSite() {
            const cleanedMain = {
                blocks: [],
                viewName: '<?= $viewName ?>'
            };

            Array.from(blocks.childNodes).forEach(block => {
                block.childNodes.forEach(child => {
                    if(child.tagName === "TEXTAREA" && child.classList.contains("admin-styles")) {
                        toggleHtml(block)
                    }
                })
            })

            Array.from(blocks.childNodes).forEach(block => {
                block.childNodes.forEach(child => {
                    if (child.nodeType === Node.ELEMENT_NODE) {
                        cleanedMain.blocks.push(htmlToJson(child))
                    }
                })
            })

            console.log(cleanedMain)
            var xhr = new XMLHttpRequest();
            var url = "/admin/websiteBuilder/editor";
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify(cleanedMain));
        }
        function htmlToJson(node) {
            const clonedNode = node.cloneNode(true);

            const obj = {
                tag: clonedNode.tagName.toLowerCase()
            };

            if (clonedNode.attributes) {
                const attrs = {};
                let classList = clonedNode.classList;

                // Remove classes from the cloned node
                if(classList.contains("editingStyleText")) {
                    classList.remove("editingStyleText");
                }
                if(classList.contains("active")) {
                    classList.remove("active");
                }

                for (let attr of clonedNode.attributes) {
                    if (![
                        'id',
                        'tabindex',
                        'onfocus',
                        'draggable',
                        'contenteditable',
                        'spellcheck',
                        'onkeydown',
                        'ondbclick'
                    ].includes(attr.name)) {
                        if(attr !== "class" && classList.length !== 0) {
                            attrs[attr.name] = attr.value;
                        }
                    }
                }

                if (Object.keys(attrs).length > 0) {
                    obj.attributes = attrs;
                }
            }

            const children = [];
            for (let child of clonedNode.childNodes) {
                if (child.nodeType === Node.ELEMENT_NODE) {
                    children.push(htmlToJson(child));
                } else if (child.nodeType === Node.TEXT_NODE) {
                    if (child.textContent.trim().length > 0) {
                        children.push({
                            text: child.textContent.trim()
                        });
                    }
                }
            }

            if (children.length > 0) {
                obj.children = children;
            }

            return obj;
        }
    </script>
</main>
</body>
</html>