<?php
class Database {
    private $servername = "localhost";
    private $dbusername = "root";
    private $dbpassword = "password";
    private $database = "routerdatabaseadmin";
    private $conn;
    public function __construct() {
        $this->connect();
    }
    private function connect() {
        $this->conn = new mysqli($this->servername, $this->dbusername, $this->dbpassword, $this->database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function checkTables() {
        $sql = "SELECT COUNT(DISTINCT `table_name`) FROM `information_schema`.`columns` WHERE `table_schema` = ?";
        $params = [$this->database];
        if($this->fetchSingleRow($this->executeQuery($sql, $params))["COUNT(DISTINCT `table_name`)"] == 0) {
            $sql = "
            CREATE TABLE " . DB_PREFIX . "_allowed_file_types (id INT AUTO_INCREMENT, filetype TEXT, mimetype TEXT, PRIMARY KEY (id));
            CREATE TABLE " . DB_PREFIX . "_blocked_folders (id INT AUTO_INCREMENT, name TEXT, folderExists TINYINT(1), PRIMARY KEY (id));
            CREATE TABLE " . DB_PREFIX . "_routes (id INT AUTO_INCREMENT, route TEXT, type TEXT, path TEXT, fileExists TINYINT(1), PRIMARY KEY (id));
            CREATE TABLE " . DB_PREFIX . "_users (id INT AUTO_INCREMENT, username TEXT, password TEXT, PRIMARY KEY (id));
            CREATE TABLE " . DB_PREFIX . "_logs (id INT AUTO_INCREMENT, route TEXT, getArr TEXT, postArr TEXT, time DATETIME, PRIMARY KEY (id));
            INSERT INTO " . DB_PREFIX . "_allowed_file_types (filetype, mimetype) VALUES ('css', 'text/css');
            INSERT INTO " . DB_PREFIX . "_allowed_file_types (filetype, mimetype) VALUES ('js', 'text/javascript');
            INSERT INTO " . DB_PREFIX . "_routes (route, type, path, fileExists) VALUES ('/', 'get', 'views/index.php', 0);
            INSERT INTO " . DB_PREFIX . "_users (username, password) VALUES ('root', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8');
            ";
            $this->executeMultipleQueries($sql);
            echo "tables created... Refresh for change...";
            exit();
        }
    }
    private function disconnect() {
        $this->conn->close();
    }
    // for non-select queries, set returnResult to false on call.
    public function executeQuery($sql, $params = [], $returnResult = true) {
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $types = '';
            $bindParams = [&$types];
            foreach ($params as &$param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $param = $this->sanatize($param);
                    $types .= 's';
                } else {
                    $types .= 'b';
                }
                $bindParams[] = &$param;
            }
            call_user_func_array([$stmt, 'bind_param'], $bindParams);
        }
        $stmt->execute();
        if ($stmt->error) {
            return false;
        }
        if ($returnResult) {
            $result = $stmt->get_result();
            if ($result === false) {
                return false;
            }
            return $result;
        }

        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return $success;
    }
    public function fetchRows($result): array {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    public function fetchSingleRow($result) {
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }
    public function updateRouter(): void {
        $this->updateRoutes();
        $this->updateBlockedFolders();
    }
    private function updateRoutes(): void {
        $sql = "select * from ".DB_PREFIX."_routes";
        $routes = $this->fetchRows($this->executeQuery($sql));
        foreach ($routes as $route) {
            $sql = "update ".DB_PREFIX."_routes set fileExists = ? where id = ?";
            if(file_exists($route["path"])) {
                $params = [1, $route["id"]];
            } else {
                $params = [0, $route["id"]];
            }
            $this->executeQuery($sql, $params, false);
        }
    }
    private function updateBlockedFolders(): void {
        $sql = "select * from ".DB_PREFIX."_blocked_folders";
        $blockedFolders = $this->fetchRows($this->executeQuery($sql));
        foreach ($blockedFolders as $folder) {
            $sql = "update ".DB_PREFIX."_blocked_folders set folderExists = ? where id = ?";
            if(is_dir($folder["name"])) {
                $params = [1, $folder["id"]];
            } else {
                $params = [0, $folder["id"]];
            }
            $this->executeQuery($sql, $params, false);
        }
    }
    private function fetchSingleColumn($result): array {
        $rows = [];
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $rows[] = $row[0];
        }
        return $rows;
    }
    public function getTables(): array {
        $sql = "SHOW TABLES";
        return $this->fetchSingleColumn($this->executeQuery($sql));
    }
    public function executeMultipleQueries($sql) {
        $queries = explode(';', $sql);

        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $this->executeQuery($query, [], false);
            }
        }
    }
    public function sanatize($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}