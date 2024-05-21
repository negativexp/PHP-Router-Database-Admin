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
        #code-editor-content {
            width: 100%;
            display: flex;
            flex-flow: column;
        }
        #code-editor-content .wrapper-content {
            padding: 0;
        }
        #code-editor-content .block {
            border-radius: 7px;
            border: 1px solid white;
            display: flex;
            align-items: center;
        }
        #code-editor-content .block:hover {
            border: 1px solid rgb(128, 128, 128);
        }
        #code-editor-content .block:hover a {
            display: block !important;
        }

        #code-editor-content .block span {
            padding: 10px;
            text-align: right;
            opacity: 0.3;
            transition: opacity .2s ease;
        }
        #code-editor-content .block:hover span {
            opacity: 1;
        }
        #code-editor-content .block *:nth-child(2) {
            flex: 1;
        }
        #code-editor-content .block a {
            display: none;
        }
        #code-editor-content .block textarea {
            width: 100%;
        }
        .s1 {
            border: 1px solid black;
            height: 100px;
        }
        .s2 {
            border: 1px solid black;
            height: 100px;
        }
        .s3 {
            border: 1px solid black;
            height: 100px;
        }
        .s4 {
            border: 1px solid black;
            height: 100px;
        }
        .w100 {
            width: 100%;
            height: 100px;
            background: red;
        }
        .w50 {
            width: 50%;
            background: yellow;
        }
        .w33 {
            width: 33.333%;
            background: orange;
        }
        .w25 {
            width: 25%;
            background: purple;
        }
    </style>
    <div class="wrapper-content">
        <div class="tableOptions">
            <a class="button" onclick="addElement('p')">Text</a>
            <a class="button" onclick="addElement('h1')">H1</a>
            <a class="button" onclick="addElement('h2')">H2</a>
            <a class="button" onclick="addElement('h3')">H3</a>
            <a class="button" onclick="addElement('h4')">H4</a>
            <a class="button" onclick="addElement('h5')">H5</a>
            <a class="button" onclick="displayPopupForm()">img</a>
            <a class="button" onclick="displayPopupForm2()">html</a>
            <a class="button" onclick="addSection('w100')">w100</a>
        </div>

        <div class="code-editor">
            <div class="content" id="code-editor-content">
            </div>
            <div class="data">
                <form id="code-editor-data"></form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        const cec = document.getElementById("code-editor-content")
        const cee = document.getElementById("code-editor-data")
        let activeBlock = null

        function updateNumbers() {
            const numArr = document.querySelectorAll(".code-editor .content .block .number")
            for(i = 0; i < numArr.length; i++) {
                numArr[i].innerText = i+1
            }
        }

        function removeElement(el) {
            const block = el.closest('.block');
            if (block) {
                block.remove();
            }
            updateNumbers()
        }

        function addSection(classname) {
            const div = document.createElement("div")
            div.classList.add("block")
            div.setAttribute("onclick", "setActiveBlock(this)")
            div.appendChild(elementNumber())
            const divWrapper = document.createElement("div")
            divWrapper.classList.add("wrapper")
            divWrapper.setAttribute("ondblclick", "changeBlockHtml(this)")
            divWrapper.setAttribute("isEditing", "false")

            const section = document.createElement("section")
            section.classList.add(classname)
            divWrapper.appendChild(section)
            div.appendChild(divWrapper)
            div.appendChild(deleteButton())

            cec.appendChild(div)
        }

        function changeBlockHtml(el) {
            if(el.getAttribute("isediting") === "false") {
                const innerHtml = el.innerHTML
                el.innerHTML = ""
                const textarea = document.createElement("textarea")
                textarea.value = innerHtml
                textarea.setAttribute("style", "resize:auto;")
                textarea.setAttribute("spellcheck", "false")
                el.appendChild(textarea)
                el.setAttribute("isediting", true)
            } else {
                el.innerHTML = el.children[0].value
                el.setAttribute("isediting", false)
            }
        }


        function setActiveBlock(block) {
            activeBlock = block
            let arr = Array.from(cec.childNodes)
            arr.shift()
            arr.forEach(child => {
                if(child === block) {
                    child.setAttribute("style", "border-bottom:1px solid rgba(255, 0, 0, 0.2);")
                } else child.setAttribute("style", "")
            })
        }

        function addElement(el) {
            const div = document.createElement("div")
            div.classList.add("block")
            div.setAttribute("onclick", "setActiveBlock(this)")
            div.appendChild(elementNumber())
            const divWrapper = document.createElement("div")
            divWrapper.classList.add("wrapper")
            divWrapper.setAttribute("ondblclick", "changeBlockHtml(this)")
            divWrapper.setAttribute("isEditing", "false")
            if(el === "custom") {
                divWrapper.innerHTML = document.getElementById("customHtml").value
            } else if (el === "img") {
                const img = document.createElement("img")
                img.src = document.getElementById("imgSrc").value
                divWrapper.appendChild(img)
            } else {
                const element = document.createElement(el)
                element.setAttribute("contenteditable", "true")
                element.setAttribute("spellcheck", "false")
                divWrapper.appendChild(element)
            }
            div.appendChild(divWrapper)
            div.appendChild(deleteButton())

            cec.appendChild(div)
            hidePopupForm()
            hidePopupForm2()
        }

        new Sortable(cec, {
            animation: 150,
            onEnd: function () {
                updateNumbers(); // Update numbers after sorting
            }
        });

        function deleteButton() {
            const button = document.createElement("a")
            button.innerText = "smazat"
            button.classList.add("button")
            button.setAttribute("onclick", "removeElement(this)")
            return button
        }
        function elementNumber() {
            const span = document.createElement("span")
            span.innerText = cec.childElementCount+1
            span.classList.add("number")
            return span
        }
    </script>
</main>
</body>
</html>