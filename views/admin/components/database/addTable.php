<form method="post" action="/admin/database/addTable">
    <label>
        <span>Table name:</span>
        <input type="text" name="tableName">
    </label>
    <div id="rowContainer">
        <div class="row">
            <label>
                <span>Column name:</span>
                <input type="text" name="name[]" required>
            </label>
            <label>
                <span>Data type:</span>
                <select name="type[]">
                    <option value="TEXT">text</option>
                    <option value="INT">int</option>
                    <option value="BOOL">bool</option>
                </select>
            </label>
            <label>
                <span>Not null:</span>
                <input type="radio" name="isNull[0]" value="true">
            </label>
            <label>
                <span>Null:</span>
                <input type="radio" name="isNull[0]" value="false" checked>
            </label>
            <label>
                <span>Auto Increment:</span>
                <input type="checkbox" name="autoIncrement[0]" value="true">
            </label>
            <button type="button" onclick="removeRow(this)">Remove</button>
        </div>
    </div>
    <div id="newRow"></div>
    <button type="button" onclick="addRow()">Add Another Row</button>
    <input type="submit">
</form>

<script>
    var rowCount = 0; // Track number of rows

    function addRow() {
        rowCount++;
        var newRow = document.createElement('div');
        newRow.className = 'row';
        newRow.innerHTML = `
            <label>
                <span>Column name:</span>
                <input type="text" name="name[]" required>
            </label>
            <label>
                <span>Data type:</span>
                <select name="type[]">
                    <option value="TEXT">text</option>
                    <option value="INT">int</option>
                    <option value="BOOL">bool</option>
                </select>
            </label>
            <label>
                <span>Not null:</span>
                <input type="radio" name="isNull[${rowCount}]" value="true">
            </label>
            <label>
                <span>Null:</span>
                <input type="radio" name="isNull[${rowCount}]" value="false" checked>
            </label>
            <label>
                <span>Auto Increment:</span>
                <input type="checkbox" name="autoIncrement[${rowCount}]" value="true">
            </label>
            <button type="button" onclick="removeRow(this)">Remove</button>
        `;
        document.getElementById('newRow').appendChild(newRow);
    }

    function removeRow(button) {
        button.parentNode.remove();
    }
</script>