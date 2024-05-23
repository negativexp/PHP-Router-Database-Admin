<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("components/sidepanel.php"); ?>
<div id="popupForm" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat fotku</h2>
        <label>
            <span>Název cesty či webové adres</span>
            <input spellcheck="false" type="text" id="imgSrc" required>
        </label>
        <div class="options">
            <a class="small button" onclick="hidePopupForm()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('img')">Přidat</a>
        </div>
    </form>
</div>
<div id="popupForm2" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat vlastní HTML/JS/CSS</h2>
        <textarea spellcheck="false" id="customHtml"></textarea>
        <div class="options">
            <a class="small button" onclick="hidePopupForm2()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('custom')">Přidat</a>
        </div>
    </form>
</div>
<div id="contextMenu" class="context-menu"
     style="display:none">
    <ul>
        <li><a class="button" onclick="deleteElement()">Smazat</a></li>
    </ul>
</div>
<main>
    <header>
        <h1 class="big">Website Builder (lolíky)</h1>
    </header>
    <link rel="stylesheet" href="../../resources/style.css">
    <style>
        #webBuilder-blocks {
            padding: 5px;
            display: flex;
            flex-flow: column;
            gap: 10px;
        }
        #webBuilder-blocks .webBuilder-block {
            padding: 0px 0px 20px 0px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px inset, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        }
        #webBuilder-blocks .active {
            border: 1px dashed rgba(255, 0, 0, 0.5) !important;
        }
        #webBuilder .text {
            width: 100% !important;
        }
        #webBuilder-blocks .webBuilder-block section, #webBuilder-blocks .webBuilder-block div, #webBuilder-blocks .webBuilder-block article  {
            padding: 0 0 20px 0;
            min-height: 20px;
            min-width: 20px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px inset, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        }
        #webBuilder p,#webBuilder h1,#webBuilder h2,#webBuilder h3,#webBuilder h4,#webBuilder h5 {
            border: 1px dashed gray;
            padding: 0 !important;
        }
        .context-menu {
            position: absolute;
            text-align: center;
            background: lightgray;
            border: 1px solid black;
        }

        .context-menu ul {
            padding: 0px;
            margin: 0px;
            list-style: none;
        }

        .context-menu ul li {
            padding-bottom: 7px;
            padding-top: 7px;
            border: 1px solid black;
        }

        .context-menu ul li a {
            text-decoration: none;
            color: black;
        }

        .context-menu ul li:hover {
            background: darkgray;
        }
    </style>
    <div class="wrapper-content">
        <a class="button" onclick="unsetActiveElement()">deaktivace aktivního elementu</a>
        <div class="tableOptions">
            <a class="button" onclick="addElement('p')">p</a>
            <a class="button" onclick="addElement('h1')">h1</a>
            <a class="button" onclick="addElement('h2')">h2</a>
            <a class="button" onclick="addElement('h3')">h3</a>
            <a class="button" onclick="addElement('h4')">h4</a>
            <a class="button" onclick="addElement('h5')">h5</a>
        </div>
        <div class="tableOptions">
            <a class="button" onclick="addClass(this.innerText)">w100</a>
            <a class="button" onclick="addClass(this.innerText)">w50</a>
            <a class="button" onclick="addClass(this.innerText)">w33</a>
            <a class="button" onclick="addClass(this.innerText)">w25</a>
            <a class="button" onclick="addClass(this.innerText)">column</a>
            <a class="button" onclick="addClass(this.innerText)">row</a>
            <a class="button" onclick="addClass(this.innerText)">red</a>
            <a class="button" onclick="addClass(this.innerText)">purple</a>
        </div>
        <div class="tableOptions">
            <a class="button" onclick="addElement(null)">empty block</a>
            <a class="button" onclick="addElement(this.innerText)">section</a>
            <a class="button" onclick="addElement(this.innerText)">article</a>
            <a class="button" onclick="addElement(this.innerText)">div</a>
            <a class="button" onclick="displayPopupForm()">img</a>
        </div>

        <div id="webBuilder">
            <div id="webBuilder-blocks">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        const blocks = document.getElementById("webBuilder-blocks")
        let activeElement = null
        let isSaved = false;

        function processAllElements(element, callback) {
            callback(element)
            element.querySelectorAll('*').forEach(child => {
                callback(child)
            })
        }
        function setActiveElement(el) {
            activeElement = el
            console.log(el)
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
        }
        function getClosestBlock(element) {
            return element.closest(".webBuilder-block")
        }
        function append(el) {
            el.tabIndex = 0
            el.setAttribute("onfocus", "setActiveElement(this)")
            if (['P', 'H1', 'H2', 'H3', 'H4', 'H5'].includes(el.tagName)) {
                el.classList.add("text")
                el.setAttribute("contenteditable", "true")
                el.setAttribute("spellcheck", "false")
            }
            if (activeElement) {
                if (el.tagName !== "NULL") {
                    activeElement.appendChild(el)
                }
            } else {
                const div = document.createElement("div")
                div.addEventListener("contextmenu", rightClick)
                div.classList.add("webBuilder-block")
                div.tabIndex = 0
                div.setAttribute("onfocus", "setActiveElement(this)")
                if (el.tagName !== "NULL") {
                    div.appendChild(el)
                }
                blocks.appendChild(div)
            }
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
            hidePopupForm2()
            hidePopupForm()
        }
        function deleteElement() {
            activeElement.remove()
            activeElement = null
            hideMenu()
            isSaved = false
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
            else{
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




        function modifyAndOutputCSS(ruleName, newSelector) {
            // Fetch the CSS file
            fetch('../../resources/style.css')
                .then(response => response.text())
                .then(cssText => {
                    // Split the CSS text into individual rules based on '}'
                    let rules = cssText.split('}');

                    // Modify the specific rule if found
                    rules = rules.map(rule => {
                        return ".test "+rule
                    });

                    // Join the modified rules back into a single CSS string
                    let modifiedCSS = rules.join('}');

                    // Output the modified CSS
                    console.log(modifiedCSS);
                })
                .catch(error => {
                    console.error('Error fetching CSS file:', error);
                });
        }

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault()
                //dodelat ajax
                isSaved = true
                console.log("ctrl s")
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
</main>
</body>
</html>