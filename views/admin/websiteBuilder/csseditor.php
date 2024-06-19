<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>
<main>
    <header>
        <h1 class="big">CSS editor</h1>
    </header>
    <div class="wrapper">
        <form method="post" action="/admin/cssEditor">
        <div class="tableOptions">
            <a class="small button" onclick="saveCss()">Uložit</a>
        </div>
            <section>
                <article class="w100">
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
                </article>
                <script>
                    const codeInput = document.getElementById("codeInput");
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
                    function saveCss() {
                        isSaved = true
                        var xhr = new XMLHttpRequest();
                        var url = "/admin/cssEditor";
                        xhr.open("POST", url, true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.send("css="+encodeURIComponent(document.getElementById("codeInput").value));
                    }
                    document.addEventListener('keydown', function(event) {
                        if (event.ctrlKey && event.key === 's') {
                            event.preventDefault()
                            saveCss()
                        }
                        if (event.key === "Tab") {
                            event.preventDefault()
                            const start = codeInput.selectionStart;
                            const end = codeInput.selectionEnd;
                            codeInput.value = codeInput.value.substring(0, start) + "\t" + codeInput.value.substring(end)
                            codeInput.selectionStart = start + 1
                            codeInput.selectionEnd = start + 1
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
        </form>
    </div>
</main>
</body>
</html>