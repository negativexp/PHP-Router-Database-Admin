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
            <a class="button" onclick="hidePopupForm()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('img')">Přidat</a>
        </div>
    </form>
</div>
<div id="popupForm2" class="popupform">
    <form method="post" action="/admin/fileManager">
        <h2>Přidat vlastní HTML/JS/CSS</h2>
        <textarea spellcheck="false" id="customHtml"></textarea>
        <div class="options">
            <a class="button" onclick="hidePopupForm2()">Zavřít</a>
            <a class="small button" type="submit" onclick="addElement('custom')">Přidat</a>
        </div>
    </form>
</div>
<main>
    <header>
        <h1 class="big">Website Builder (lolíky)</h1>
    </header>
    <style>
        #richtextbox-blocks .block {
            transition: border .2s ease;
            border: 1px dashed rgba(128, 128, 128, 0.3);
        }
        #richtextbox-blocks .block:hover {
            border: 1px dashed rgba(128, 128, 128, 1);
        }
        #richtextbox-blocks .block section:first-child{
            padding-bottom: 20px;
        }
        #richtextbox-blocks .block * {
            all: revert;
        }
        #richtextbox-blocks .active {
            border: 1px solid red;
        }
        #richtextbox-blocks .block section div {height: 20px; border: 1px solid black;}
        #richtextbox-blocks .block .w100 { width: 100%; }
        #richtextbox-blocks .block .w50 { width: 50%; }
        #richtextbox-blocks .block .w33 { width: 33.333%; }
        #richtextbox-blocks .block .w25 { width: 25%; }
    </style>
    <div class="wrapper-content">
        <a class="button" onclick="unsetActiveElement()">deaktivovat</a>
        <div class="tableOptions">
            <a class="button" onclick="addElement('p', true)">p</a>
            <a class="button" onclick="addElement('h1', true)">h1</a>
            <a class="button" onclick="addElement('h2', true)">h2</a>
            <a class="button" onclick="addElement('h3', true)">h3</a>
            <a class="button" onclick="addElement('h4', true)">h4</a>
        </div>
        <div class="tableOptions">
            <a class="button" onclick="addElement('section')">section</a>
            <a class="button" onclick="addElement('div')">div</a>
            <a class="button" onclick="addElement('div', false, 'w100')">w100</a>
            <a class="button" onclick="addElement('div', false, 'w50')">w50</a>
            <a class="button" onclick="addElement('div', false, 'w33')">w33</a>
            <a class="button" onclick="addElement('div', false, 'w25')">w25</a>
        </div>

        <div id="richtextbox">
            <div class="blocks" id="richtextbox-blocks">
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        const blocks = document.getElementById("richtextbox-blocks")
        let activeElement = null
        let activeSubElement = null

        function appendToBlocks(element) {
            const div = document.createElement("div")
            div.classList.add("block")
            addClickHandle(element)
            div.appendChild(element)
            div.setAttribute("isediting", "false")
            addDoubleClickHandle(div)
            blocks.appendChild(div)
        }
        function addClickHandle(element) {
            element.setAttribute("onclick", "setActiveElement(this)")
        }
        function addClickHandleSubElement(element) {
            element.setAttribute("onclick", "setActiveSubElement(this)")
        }
        function unsetActiveSubElement() {
            activeSubElement = null
        }
        function setActiveSubElement(element) {
            activeSubElement = element
            console.log("activce sub element: " + element)
        }
        function setActiveElement(element) {
            activeElement = element
            console.log("active element: " + element)
        }
        function unsetActiveElement() {
            activeElement = null
        }
        function addDoubleClickHandle(element) {
            element.setAttribute("ondblclick", "displayHTML(this)")
        }
        function displayHTML(element) {
            if(element.getAttribute("isediting") === "false") {
                const textarea = document.createElement("textarea")
                textarea.value = element.innerHTML
                element.innerHTML = ""
                element.appendChild(textarea)
                element.setAttribute("isediting", "true")
            } else {
                element.innerHTML = element.children[0].value
                element.setAttribute("isediting", "false")
            }
        }
        function addElement(el, editable, classname) {
            const element = document.createElement(el)
            if(editable) {
                element.setAttribute("contentEditable", "true")
                element.setAttribute("style","width:100%")
            }
            if(classname) {
                element.classList.add(classname)
            }
            if(activeElement) {
                addClickHandleSubElement(element)
                activeElement.appendChild(element)
            } else appendToBlocks(element)
        }

    </script>
</main>
</body>
</html>