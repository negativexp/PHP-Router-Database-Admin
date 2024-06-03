<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
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
<div id="contextMenu" class="context-menu" style="display:none">
    <p id="contextMenuActive"></p>
    <p id="contextMenuClasses"></p>
    <a class="button" onclick="deleteElement()">Smazat</a>
</div>
<main>
    <header>
        <h1 class="big">CSS editor</h1>
    </header>
    <div class="wrapper">
            <section>
                <article class="w100">
                    <form method="post" action="/admin/cssEditor">
                        <input type="submit" value="Uložit">
                    <?php
                    $content = file_get_contents("resources/style.css");
                    if($content) {
                        echo "
  <div class='code-editor'>
    <div class='row-numbers' id='rowNumbers'>
      <p>1</p>
    </div>
    <textarea name='css' id='codeInput' spellcheck='false'>".htmlspecialchars($content)."</textarea>
  </div>";
                    }
                    ?>
                    </form>
                </article>
                <script>
                    var codeInput = document.getElementById("codeInput");
                    const symbols = {
                        '[': ']',
                        '(': ')',
                        "'": "'",
                        "{": "}",
                    }
                    function sleep(ms) {
                        return new Promise(resolve => setTimeout(resolve, ms));
                    }
                    function updateNumbers() {
                        var rowNumbersDiv = document.getElementById("rowNumbers")
                        rowNumbersDiv.innerHTML = ''
                        for (var i = 1; i <= codeInput.value.split('\n').length; i++) {
                            var newRow = document.createElement("span")
                            newRow.id = "coderow"+i
                            newRow.textContent = i
                            rowNumbersDiv.appendChild(newRow)
                        }
                    }
                    function duplicateSpecificSymbols(e) {
                        for (const key in symbols) {
                            if(e.data === key) {
                                const currentPos = codeInput.selectionStart
                                var newValue = codeInput.value.slice(0, currentPos) + symbols[key] + codeInput.value.slice(currentPos, codeInput.value.length)
                                console.log(newValue)
                                codeInput.value = newValue
                                codeInput.selectionEnd = currentPos
                            }
                        }
                    }
                    function getCaretPosition(element, x, y) {
                        var rect = element.getBoundingClientRect();
                        var lineHeight = parseInt(window.getComputedStyle(element).lineHeight);
                        var row = Math.floor((y - rect.top) / lineHeight) + 1;
                        var textBeforeCursor = element.value.substring(0, element.selectionStart);
                        var column = textBeforeCursor.split("\n").pop().length + 1;
                        return { row: row, column: column };
                    }
                    function getCursorPosition() {
                        var text = codeInput.value.substr(0, codeInput.selectionStart);
                        var lines = text.split("\n");
                        var row = lines.length;
                        return row;
                    }
                    codeInput.addEventListener("input", (e) => {
                        updateNumbers()
                        duplicateSpecificSymbols(e)
                        for(let i = 1; i <= document.getElementById("rowNumbers").children.length; i++) {
                            if(i === getCursorPosition()) {
                                document.getElementById("coderow"+i).style.fontWeight = "bolder"
                            } else {
                                document.getElementById("coderow"+i).style.fontWeight = "normal"
                            }
                        }
                    })

                    codeInput.addEventListener("click", (e) => {
                        var cursorPosition = getCaretPosition(codeInput, e.clientX, e.clientY);
                        for(let i = 1; i <= document.getElementById("rowNumbers").children.length; i++) {
                            if(i === cursorPosition.row) {
                                document.getElementById("coderow"+i).style.fontWeight = "bolder"
                            } else {
                                document.getElementById("coderow"+i).style.fontWeight = "normal"
                            }
                        }
                    });
                    let isSaved = false;
                    codeInput.addEventListener("keydown", (e) => {
                        if(e.key === "ArrowDown" || e.key === "ArrowUp") {
                            sleep(20).then(() => {
                                for(let i = 1; i <= document.getElementById("rowNumbers").children.length; i++) {
                                    if(i === getCursorPosition()) {
                                        document.getElementById("coderow"+i).style.fontWeight = "bolder"
                                    } else {
                                        document.getElementById("coderow"+i).style.fontWeight = "normal"
                                    }
                                }
                            });
                        }
                    });
                    document.addEventListener('keydown', function(event) {
                        if (event.ctrlKey && event.key === 's') {
                            event.preventDefault()
                            isSaved = true
                            var xhr = new XMLHttpRequest();
                            var url = "/admin/cssEditor";
                            xhr.open("POST", url, true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send("css="+encodeURIComponent(document.getElementById("codeInput").value));
                        }
                    })
                    window.addEventListener('beforeunload', function (event) {
                        if(!isSaved) {
                            event.preventDefault()
                            //chrome
                            event.returnValue = ''
                        }
                    });
                    updateNumbers()
                </script>
            </section>
    </div>
</main>
</body>
</html>