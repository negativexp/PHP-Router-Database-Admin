<?php
class Router {
    public function __construct()
    {
        $parsedURL = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $this->checkBlockedFolders($parsedURL);
        $this->checkAllowedFileTypes($parsedURL);
    }

    private function checkBlockedFolders($parsedURL): void
    {
        include_once("db.php");
        $db = new Database();
        $sql = "select * from router_blocked_folders";
        $blockedFolders = $db->fetchRows($db->executeQuery($sql));
        foreach ($blockedFolders as $blockedFolder) {
            if(str_contains($parsedURL, $blockedFolder["name"])) {
                $this->not_found();
            }
        }
    }
    private function checkAllowedFileTypes($parsedURL): void
    {
        include_once("db.php");
        $db = new Database();
        $sql = "select * from router_allowed_file_types";
        $results = $db->fetchRows($db->executeQuery($sql));
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
    public function get($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->route($route, $path_to_include);
        }
    }
    public function post($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->route($route, $path_to_include);
        }
    }
    public function getpost($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->route($route, $path_to_include);
        }
    }
    public function put($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->route($route, $path_to_include);
        }
    }
    public function patch($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $this->route($route, $path_to_include);
        }
    }
    public function delete($route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->route($route, $path_to_include);
        }
    }
    public function any($route, $path_to_include): void
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
    public function out($text): void
    {
        echo htmlspecialchars($text);
    }
    public function set_csrf(): void
    {
        session_start();
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }
    public function is_csrf_valid(): bool
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
    public function not_found(): void {
        http_response_code(404);
        die();
    }

    public function adminMiddleware(): void {
        session_start();
        if(!isset($_SESSION["admin"])) {
            header("location: /admin/login");
        }
    }
}
include_once("db.php");
$router = new Router();
$db = new Database();
$sql = "select * from router_routes";
$routes = $db->fetchRows($db->executeQuery($sql));
foreach($routes as $route) {
    switch ($route["type"]) {
        case "get": {
            $router->get($route["route"], $route["path"]);
            break;
        }
        case "post": {
            $router->post($route["route"], $route["path"]);
            break;
        }
        case "getpost": {
            $router->getpost($route["route"], $route["path"]);
            break;
        }
        case "patch": {
            $router->patch($route["route"], $route["path"]);
            break;
        }
        case "put": {
            $router->put($route["route"], $route["path"]);
            break;
        }
        case "delete": {
            $router->delete($route["route"], $route["path"]);
            break;
        }
        case "any": {
            $router->any($route["route"], $route["path"]);
            break;
        }
    }
}
//default admin routes
$router->get("/admin/login", "views/admin/adminLogin.php");
$router->adminMiddleware();
$router->get("/admin", "views/admin/admin.php");
$router->post("/admin/auth", "actions/admin/login.php");
$router->get("/admin/logout", "actions/admin/logout.php");
$router->post("/admin/router/addRoute", "actions/admin/router/addRoute.php");
$router->post("/admin/router/removeRoute", "actions/admin/router/removeRoute.php");
$router->post("/admin/router/addBlockedFolder", "actions/admin/router/addBlockedFolder.php");
$router->post("/admin/router/removeBlockedFolder", "actions/admin/router/removeBlockedFolder.php");
$router->post("/admin/router/addAllowedFileType", "actions/admin/router/addAllowedFileType.php");
$router->post("/admin/router/removeAllowedFileType", "actions/admin/router/removeAllowedFileType.php");
$router->get("/admin/router", "views/admin/router.php");
$router->get("/admin/database", "views/admin/database.php");
$router->get('/admin/database/table/$name', "views/admin/components/getDatabase.php");
$router->post("/admin/database/addTable", "actions/admin/database/addTable.php");
$router->post("/admin/database/removeTable", "actions/admin/database/removeTable.php");
$router->not_found();