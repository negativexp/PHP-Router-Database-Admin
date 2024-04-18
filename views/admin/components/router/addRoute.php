<form method="post" action="/admin/router/addRoute">
    <label>
        <span>Route</span>
        <input type="text" name="route" required>
    </label>
    <label>
        <span>Type:</span>
        <select name="type" required>
            <option>get</option>
            <option>post</option>
            <option>getpost</option>
            <option>put</option>
            <option>patch</option>
            <option>delete</option>
            <option>any</option>
        </select>
    </label>
    <label>
        <span>Path:</span>
        <input type="text" name="path" required>
    </label>
    <input type="submit">
</form>