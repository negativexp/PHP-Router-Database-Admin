<?php
include_once("db.php");
include_once("config.php");
class Router {
    private $db;
    public function __construct()
    {
        $this->db = new Database();
        $this->db->checkTables();
        $parsedURL = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $this->checkBlockedFolders($parsedURL);
        $this->checkAllowedFileTypes($parsedURL);
        $this->checkNormalRoutes();
        $this->checkAdminRoutes();
        $this->not_found();
    }
    private function checkAdminRoutes(): void {
        $this->get("/admin/login", "views/admin/adminLogin.php");
        $this->post("/admin/auth", "actions/admin/login.php");
        $this->adminMiddleware();
        $this->get("/admin", "views/admin/admin.php");
        $this->get("/admin/logout", "actions/admin/logout.php");
        $this->get("/admin/logs", "views/admin/logs.php");
        $this->post("/admin/router/addRoute", "actions/admin/router/addRoute.php");
        $this->post("/admin/router/removeRoute", "actions/admin/router/removeRoute.php");
        $this->post("/admin/router/addBlockedFolder", "actions/admin/router/addBlockedFolder.php");
        $this->post("/admin/router/removeBlockedFolder", "actions/admin/router/removeBlockedFolder.php");
        $this->post("/admin/router/addAllowedFileType", "actions/admin/router/addAllowedFileType.php");
        $this->post("/admin/router/removeAllowedFileType", "actions/admin/router/removeAllowedFileType.php");
        $this->get("/admin/router/routes", "views/admin/router/routes.php");
        $this->get("/admin/router/allowedFiles", "views/admin/router/allowedFiles.php");
        $this->get("/admin/router/blockedFolders", "views/admin/router/blockedFolders.php");
        $this->get("/admin/database/tables", "views/admin/database/tables.php");
        $this->get('/admin/database/table/$name', "views/admin/database/getTable.php");
        $this->get('/admin/database/customSql', "views/admin/database/customSql.php");
        $this->post("/admin/database/addTable", "actions/admin/database/addTable.php");
        $this->post("/admin/database/removeTable", "actions/admin/database/removeTable.php");
        $this->post("/admin/database/addRow", "actions/admin/database/addRow.php");
        $this->post("/admin/database/removeRow", "actions/admin/database/removeRow.php");
        $this->post("/admin/database/customSql", "actions/admin/database/customSql.php");
        $this->get('/admin/fileManager', "views/admin/fileManager/fileManager.php");
        $this->get('/admin/fileManager/$file', "views/admin/fileManager/file.php");
        $this->post('/admin/fileManager', "actions/admin/filemanager.php");
    }
    private function checkNormalRoutes(): void {
        $sql = "select * from ".DB_PREFIX."_routes";
        $routes = $this->db->fetchRows($this->db->executeQuery($sql));
        foreach($routes as $route) {
            switch ($route["type"]) {
                case "get": {
                    $this->get($route["route"], $route["path"]);
                    break;
                }
                case "post": {
                    $this->post($route["route"], $route["path"]);
                    break;
                }
                case "getpost": {
                    $this->getpost($route["route"], $route["path"]);
                    break;
                }
                case "patch": {
                    $this->patch($route["route"], $route["path"]);
                    break;
                }
                case "put": {
                    $this->put($route["route"], $route["path"]);
                    break;
                }
                case "delete": {
                    $this->delete($route["route"], $route["path"]);
                    break;
                }
                case "any": {
                    $this->any($route["route"], $route["path"]);
                    break;
                }
            }
        }
    }
    private function checkBlockedFolders($parsedURL): void
    {
        $sql = "select * from ".DB_PREFIX."_blocked_folders";
        $blockedFolders = $this->db->fetchRows($this->db->executeQuery($sql));
        foreach ($blockedFolders as $blockedFolder) {
            if(str_contains($parsedURL, $blockedFolder["name"])) {
                $this->not_found();
            }
        }
    }
    private function checkAllowedFileTypes($parsedURL): void
    {
        $sql = "select * from ".DB_PREFIX."_allowed_file_types";
        $results = $this->db->fetchRows($this->db->executeQuery($sql));
        $allowedFileTypes = [];
        foreach ($results as $result) {
            $allowedFileTypes[$result["filetype"]] = $result["mimetype"];
        }
        if (array_key_exists(pathinfo($parsedURL, PATHINFO_EXTENSION), $allowedFileTypes)) {
            $filepath = "." . $parsedURL;
            if (file_exists($filepath)) {
                header("Content-Type: " . $allowedFileTypes[pathinfo($filepath, PATHINFO_EXTENSION)]);
                readfile($filepath);
                exit();
            } else {
                $this->not_found();
            }
        }
    }
    private function get($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->route($route, $path_to_include);
        }
    }
    private function post($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->route($route, $path_to_include);
        }
    }
    private function getpost($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->route($route, $path_to_include);
        }
    }
    private function put($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->route($route, $path_to_include);
        }
    }
    private function patch($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $this->route($route, $path_to_include);
        }
    }
    private function delete($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->route($route, $path_to_include);
        }
    }
    private function any($route, $path_to_include): void
    {
        $this->route($route, $path_to_include);
    }
    private function route($route, $path_to_include): void
    {
        $callback = $path_to_include;
        if (!is_callable($callback)) {
            if (!strpos($path_to_include, '.php')) {
                $path_to_include .= '.php';
            }
        }
        if ($route == "/404") {
            include_once __DIR__ . "/$path_to_include";
            exit();
        }
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);
        if ($route_parts[0] == '' && count($request_url_parts) == 0) {
            // Callback function
            if (is_callable($callback)) {
                call_user_func_array($callback, []);
                exit();
            }
            include_once __DIR__ . "/$path_to_include";
            exit();
        }
        if (count($route_parts) != count($request_url_parts)) {
            return;
        }
        $parameters = [];
        for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
            $route_part = $route_parts[$__i__];
            if (preg_match("/^[$]/", $route_part)) {
                $route_part = ltrim($route_part, '$');
                $parameters[] = $request_url_parts[$__i__];
                $$route_part = $request_url_parts[$__i__];
            } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
                return;
            }
        }
        // Callback function
        if (is_callable($callback)) {
            call_user_func_array($callback, $parameters);
            exit();
        }
        include_once __DIR__ . "/$path_to_include";
        exit();
    }
    private function out($text): void
    {
        echo htmlspecialchars($text);
    }
    private function set_csrf(): void
    {
        session_start();
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }
    private function is_csrf_valid(): bool
    {
        session_start();
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }
        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        }
        return true;
    }
    private function not_found(): void {
        http_response_code(404);
        die();
    }
    private function adminMiddleware(): void {
        session_start();
        if(!isset($_SESSION["admin"])) {
            header("location: /admin/login");
        }
    }
}
$router = new Router();