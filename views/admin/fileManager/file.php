<?php
if(isset($file)) {
    $file = urldecode($file);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("views/admin/components/head.php"); ?>
<body>
<?php include_once("views/admin/components/sidepanel.php"); ?>

<main>
    <header>
        <h1 class="big">Soubor: <?= isset($file) ? str_replace($_SERVER["DOCUMENT_ROOT"]."\\", "root", $file) : "" ?></h1>
    </header>
    <div class="wrapper">
        <form method="post" action="/admin/fileManager">
            <div class="tableOptions">
                <input type="hidden" name="backlink" value="<?= isset($_GET["backlink"]) ? $_GET["backlink"] : ""?>">
                <input type="hidden" name="filepath" value="<?= isset($file) ? $file : ""?>">
                <a class="button" href="/admin/fileManager<?= isset($_GET["backlink"]) ? "?folder=".$_GET["backlink"] : ""?>">Zpátky</a>
                <input type="submit" name="saveFile" value="Uložit">
            </div>
            <section>
                <article class="w100">
                    <?php
                    if(isset($file)) {
                        if(is_file($file)) {
                            $content = file_get_contents($file);
                            echo "
  <div class='code-editor'>
    <div class='row-numbers' id='rowNumbers'>
      <p>1</p>
    </div>
    <textarea name='contents' id='codeInput' spellcheck='false'>".htmlspecialchars($content)."</textarea>
  </div>";
                        }
                    }
                    ?>
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


                    updateNumbers()
                </script>
            </section>
        </form>
    </div>
</main>
</body>
</html>