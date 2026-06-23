<?php
// =============================================
// 第 17 课：项目实战 —— 用户注册登录系统
// =============================================
// 上节课我们学了会话与安全。
// 这节课我们要把前面所有知识整合起来，构建一个完整的用户系统。

echo "============================================================\n";
echo "   第17课：项目实战 - 用户注册登录系统\n";
echo "============================================================\n\n";

// 【生活类比】
// 项目实战就像"期末考试"——
// 前面每节课学的知识点（变量、数组、函数、OOP、PDO...）就像平时的小测验，
// 现在要把所有知识综合起来，解决一个完整的问题。
// 不用紧张，你已经学了所有需要的知识，现在只是把它们串起来。
// 就像做菜：之前分别学了切菜、炒菜、调味，今天要独立做一桌完整的菜。

// =============================================
// 第一节：项目概述
// =============================================

echo "【第一节：项目概述】\n\n";

echo "项目功能：\n";
echo "  1. 用户注册（验证、加密、存储）\n";
echo "  2. 用户登录（验证、Session管理）\n";
echo "  3. 用户登出（清理Session）\n";
echo "  4. 个人中心（查看、修改信息）\n";
echo "  5. 密码修改\n";
echo "  6. 安全防护（CSRF、XSS、SQL注入）\n\n";

echo "技术栈：\n";
echo "  - PHP 8.0+\n";
echo "  - MySQL 8.0+\n";
echo "  - PDO（数据库操作）\n";
echo "  - Session（会话管理）\n\n";


// =============================================
// 第二节：数据库设计
// =============================================

echo "【第二节：数据库设计】\n\n";

echo "users 表结构：\n";
echo <<<'SQL'
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    is_admin BOOLEAN DEFAULT FALSE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
echo "\n\n";

echo "login_attempts 表结构（用于登录限制）：\n";
echo <<<'SQL'
CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_username_ip (username, ip_address),
    INDEX idx_attempted_at (attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
echo "\n\n";


// =============================================
// 第三节：配置文件
// =============================================

echo "【第三节：配置文件】\n\n";

echo "config.php：\n";
echo <<<'PHP'
<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'user_system');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// 安全配置
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 3600); // 1小时
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15分钟

// 文件上传配置
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
PHP;
echo "\n\n";


// =============================================
// 第四节：数据库连接类
// =============================================

echo "【第四节：数据库连接类】\n\n";

echo "Database.php：\n";
echo <<<'PHP'
<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("数据库连接失败：" . $e->getMessage());
            throw new Exception("数据库连接失败");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
PHP;
echo "\n\n";


// =============================================
// 第五节：用户模型类
// =============================================

echo "【第五节：用户模型类】\n\n";

echo "User.php：\n";
echo <<<'PHP'
<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 创建用户
    public function create($data) {
        // 验证用户名
        if (empty($data['username']) || strlen($data['username']) < 3) {
            throw new Exception("用户名至少3个字符");
        }

        // 验证邮箱
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("邮箱格式不正确");
        }

        // 验证密码
        if (strlen($data['password']) < 6) {
            throw new Exception("密码至少6个字符");
        }

        // 检查用户名是否已存在
        if ($this->findByUsername($data['username'])) {
            throw new Exception("用户名已存在");
        }

        // 检查邮箱是否已存在
        if ($this->findByEmail($data['email'])) {
            throw new Exception("邮箱已存在");
        }

        // 加密密码
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);

        // 插入数据库
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, phone)
                VALUES (:username, :email, :password, :first_name, :last_name, :phone)";

        $this->db->query($sql, [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $hashed_password,
            ':first_name' => $data['first_name'] ?? null,
            ':last_name' => $data['last_name'] ?? null,
            ':phone' => $data['phone'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    // 根据ID查找用户
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->query($sql, [':id' => $id]);
        return $stmt->fetch();
    }

    // 根据用户名查找用户
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($sql, [':username' => $username]);
        return $stmt->fetch();
    }

    // 根据邮箱查找用户
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->query($sql, [':email' => $email]);
        return $stmt->fetch();
    }

    // 更新用户信息
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $fields[] = "password = :password";
                $params[':password'] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        return $this->db->query($sql, $params)->rowCount();
    }

    // 更新最后登录时间
    public function updateLastLogin($id) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
        $this->db->query($sql, [':id' => $id]);
    }

    // 验证密码
    public function verifyPassword($password, $hashed_password) {
        return password_verify($password, $hashed_password);
    }

    // 删除用户
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        return $this->db->query($sql, [':id' => $id])->rowCount();
    }
}
PHP;
echo "\n\n";


// =============================================
// 第六节：认证类
// =============================================

echo "【第六节：认证类】\n\n";

echo "Auth.php：\n";
echo <<<'PHP'
<?php
class Auth {
    private $user;
    private $db;

    public function __construct() {
        $this->user = new User();
        $this->db = Database::getInstance();

        // 启动Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 用户注册
    public function register($data) {
        // CSRF验证
        $this->verifyCSRF($data[CSRF_TOKEN_NAME] ?? '');

        // 创建用户
        $user_id = $this->user->create($data);

        // 自动登录
        $this->loginById($user_id);

        return $user_id;
    }

    // 用户登录
    public function login($username, $password) {
        // 检查登录尝试次数
        if ($this->isLockedOut($username)) {
            throw new Exception("登录尝试次数过多，请15分钟后再试");
        }

        // 查找用户
        $user = $this->user->findByUsername($username);
        if (!$user) {
            $this->recordAttempt($username);
            throw new Exception("用户名或密码错误");
        }

        // 验证密码
        if (!$this->user->verifyPassword($password, $user['password'])) {
            $this->recordAttempt($username);
            throw new Exception("用户名或密码错误");
        }

        // 检查账户是否激活
        if (!$user['is_active']) {
            throw new Exception("账户已被禁用");
        }

        // 登录成功
        $this->clearAttempts($username);
        $this->loginById($user['id']);
        $this->user->updateLastLogin($user['id']);

        return $user;
    }

    // 通过ID登录
    private function loginById($user_id) {
        // 重新生成Session ID
        session_regenerate_id(true);

        // 存储Session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['login_time'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    // 用户登出
    public function logout() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    // 检查是否登录
    public function isLoggedIn() {
        if (empty($_SESSION['user_id'])) {
            return false;
        }

        // 检查Session是否过期
        if (time() - $_SESSION['login_time'] > SESSION_LIFETIME) {
            $this->logout();
            return false;
        }

        // 检查IP和User-Agent是否变化（可选）
        if ($_SESSION['ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
            $this->logout();
            return false;
        }

        return true;
    }

    // 获取当前登录用户
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return $this->user->findById($_SESSION['user_id']);
    }

    // 修改密码
    public function changePassword($old_password, $new_password) {
        $user = $this->getCurrentUser();
        if (!$user) {
            throw new Exception("请先登录");
        }

        // 验证旧密码
        if (!$this->user->verifyPassword($old_password, $user['password'])) {
            throw new Exception("旧密码错误");
        }

        // 验证新密码
        if (strlen($new_password) < 6) {
            throw new Exception("新密码至少6个字符");
        }

        // 更新密码
        $this->user->update($user['id'], ['password' => $new_password]);

        // 重新生成Session ID
        session_regenerate_id(true);
    }

    // CSRF Token
    public function generateCSRFToken() {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }

    private function verifyCSRF($token) {
        if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
            throw new Exception("CSRF验证失败");
        }
        if (!hash_equals($_SESSION[CSRF_TOKEN_NAME], $token)) {
            throw new Exception("CSRF验证失败");
        }
    }

    // 登录限制
    private function isLockedOut($username) {
        $sql = "SELECT COUNT(*) FROM login_attempts
                WHERE username = :username
                AND attempted_at > DATE_SUB(NOW(), INTERVAL :lockout SECOND)";
        $stmt = $this->db->query($sql, [
            ':username' => $username,
            ':lockout' => LOCKOUT_TIME
        ]);
        return $stmt->fetchColumn() >= MAX_LOGIN_ATTEMPTS;
    }

    private function recordAttempt($username) {
        $sql = "INSERT INTO login_attempts (username, ip_address) VALUES (:username, :ip)";
        $this->db->query($sql, [
            ':username' => $username,
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]);
    }

    private function clearAttempts($username) {
        $sql = "DELETE FROM login_attempts WHERE username = :username";
        $this->db->query($sql, [':username' => $username]);
    }
}
PHP;
echo "\n\n";


// =============================================
// 第七节：表单验证类
// =============================================

echo "【第七节：表单验证类】\n\n";

echo "FormValidator.php：\n";
echo <<<'PHP'
<?php
class FormValidator {
    private $errors = [];
    private $data = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function validate($field, $rules, $label = '') {
        $value = $this->data[$field] ?? '';
        $label = $label ?: $field;

        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $this->checkRule($value, $rule, [], $label);
            } elseif (is_array($rule)) {
                [$rule_name, $params] = $rule;
                $this->checkRule($value, $rule_name, $params, $label);
            }
        }
    }

    private function checkRule($value, $rule, $params, $label) {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[] = "{$label}不能为空";
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "{$label}格式不正确";
                }
                break;

            case 'min':
                if (strlen($value) < $params[0]) {
                    $this->errors[] = "{$label}至少{$params[0]}个字符";
                }
                break;

            case 'max':
                if (strlen($value) > $params[0]) {
                    $this->errors[] = "{$label}最多{$params[0]}个字符";
                }
                break;

            case 'alpha_dash':
                if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
                    $this->errors[] = "{$label}只能包含字母、数字、下划线、连字符";
                }
                break;

            case 'confirmed':
                $confirm_field = $params[0] ?? $field . '_confirmation';
                if ($value !== ($this->data[$confirm_field] ?? '')) {
                    $this->errors[] = "{$label}两次输入不一致";
                }
                break;
        }
    }

    public function fails() {
        return !empty($this->errors);
    }

    public function errors() {
        return $this->errors;
    }

    public function validated() {
        return array_intersect_key($this->data, array_flip(array_keys($this->data)));
    }
}
PHP;
echo "\n\n";


// =============================================
// 第八节：视图模板
// =============================================

echo "【第八节：视图模板】\n\n";

echo "views/register.php：\n";
echo <<<'HTML'
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>用户注册</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        .error { color: red; font-size: 14px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>用户注册</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="form-group">
            <label>用户名：</label>
            <input type="text" name="username" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>邮箱：</label>
            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>密码：</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>确认密码：</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">注册</button>
    </form>

    <p>已有账号？<a href="login.php">登录</a></p>
</body>
</html>
HTML;
echo "\n\n";


// =============================================
// 第九节：控制器
// =============================================

echo "【第九节：控制器】\n\n";

echo "register.php（注册处理）：\n";
echo <<<'PHP'
<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';
require_once 'Auth.php';
require_once 'FormValidator.php';

$auth = new Auth();
$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;

    // 验证表单
    $validator = new FormValidator($_POST);
    $validator->validate('username', ['required', ['min', 3], ['max', 50], 'alpha_dash'], '用户名');
    $validator->validate('email', ['required', 'email'], '邮箱');
    $validator->validate('password', ['required', ['min', 6]], '密码');
    $validator->validate('password', ['confirmed'], '密码');

    if ($validator->fails()) {
        $errors = $validator->errors();
    } else {
        try {
            $auth->register($_POST);
            header('Location: dashboard.php');
            exit;
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$csrf_token = $auth->generateCSRFToken();
require_once 'views/register.php';
PHP;
echo "\n\n";

echo "login.php（登录处理）：\n";
echo <<<'PHP'
<?php
require_once 'config.php';
require_once 'Database.php';
require_once 'User.php';
require_once 'Auth.php';

$auth = new Auth();
$errors = [];

// 已登录则跳转
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $auth->login($username, $password);
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}

$csrf_token = $auth->generateCSRFToken();
require_once 'views/login.php';
PHP;
echo "\n\n";


// =============================================
// 第十节：项目总结
// =============================================

echo "【第十节：项目总结】\n\n";

echo "项目文件结构：\n";
echo "  user-system/\n";
echo "  ├── config.php          # 配置文件\n";
echo "  ├── Database.php        # 数据库类\n";
echo "  ├── User.php            # 用户模型\n";
echo "  ├── Auth.php            # 认证类\n";
echo "  ├── FormValidator.php   # 表单验证\n";
echo "  ├── register.php        # 注册页面\n";
echo "  ├── login.php           # 登录页面\n";
echo "  ├── logout.php          # 登出处理\n";
echo "  ├── dashboard.php       # 个人中心\n";
echo "  ├── change-password.php # 修改密码\n";
echo "  ├── views/              # 视图模板\n";
echo "  │   ├── register.php\n";
echo "  │   ├── login.php\n";
echo "  │   └── dashboard.php\n";
echo "  └── uploads/            # 上传文件\n\n";

echo "项目亮点：\n";
echo "  1. 使用PDO预处理语句防止SQL注入\n";
echo "  2. 使用password_hash()安全存储密码\n";
echo "  3. 使用CSRF Token防止跨站请求伪造\n";
echo "  4. 使用htmlspecialchars()防止XSS\n";
echo "  5. 登录失败次数限制\n";
echo "  6. Session安全配置\n";
echo "  7. 表单验证和错误提示\n";
echo "  8. 面向对象的代码结构\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：为用户系统添加"记住我"功能\n";
echo "  提示：登录时生成随机token，存到数据库和Cookie中（httponly + secure）\n";
echo "  提示：下次访问时用Cookie中的token查数据库，匹配则自动登录\n\n";

echo "练习2：添加邮箱验证功能\n";
echo "  提示：注册时生成验证token，发邮件给用户（含验证链接）\n";
echo "  提示：用户点击链接时验证token，成功则激活账户\n\n";

echo "练习3：添加密码重置功能\n";
echo "  提示：用户输入邮箱，生成重置token存数据库，发邮件包含重置链接\n";
echo "  提示：token 设置过期时间（如1小时），重置成功后删除token\n\n";

echo "练习4：添加用户头像上传功能\n";
echo "  提示：复用第10课的文件上传知识，验证MIME类型和大小\n";
echo "  提示：用 bin2hex(random_bytes(16)) 生成安全文件名，路径存到数据库\n\n";

echo "练习5：添加管理员后台功能\n";
echo "  提示：在 Auth 类中添加 isAdmin() 方法检查 is_admin 字段\n";
echo "  提示：用中间件或权限检查保护管理员路由，普通用户无权访问\n\n";


// =============================================
// 练习参考答案
// =============================================
echo "\n" . str_repeat("=", 50) . "\n";
echo "练习参考答案\n";
echo str_repeat("=", 50) . "\n";


// 练习1参考：为用户系统添加"记住我"功能
echo "\n--- 练习1 参考：记住我功能 ---\n";
echo <<<'PHP'
<?php
// 在 Auth 类中添加以下方法：

// 记住我：生成token并设置Cookie
public function rememberMe($user_id) {
    // 生成随机token
    $token = bin2hex(random_bytes(32));
    $hashed_token = hash('sha256', $token);

    // 存储到数据库（需要新增 remember_token 字段）
    $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
    $this->db->query($sql, [
        ':token' => $hashed_token,
        ':id' => $user_id
    ]);

    // 设置Cookie，有效期30天
    setcookie('remember_token', $token, [
        'expires'  => time() + 86400 * 30,  // 30天
        'path'     => '/',
        'domain'   => '',
        'secure'   => true,     // 仅HTTPS
        'httponly'  => true,     // 防止JS读取
        'samesite' => 'Strict'
    ]);
}

// 通过记住我token自动登录
public function loginByRememberToken() {
    if (empty($_COOKIE['remember_token'])) {
        return false;
    }

    $token = $_COOKIE['remember_token'];
    $hashed_token = hash('sha256', $token);

    // 查找用户
    $sql = "SELECT * FROM users WHERE remember_token = :token AND is_active = 1";
    $stmt = $this->db->query($sql, [':token' => $hashed_token]);
    $user = $stmt->fetch();

    if ($user) {
        $this->loginById($user['id']);
        return true;
    }

    return false;
}

// 登出时清除记住我
public function logout() {
    // 清除数据库中的token
    if (!empty($_SESSION['user_id'])) {
        $sql = "UPDATE users SET remember_token = NULL WHERE id = :id";
        $this->db->query($sql, [':id' => $_SESSION['user_id']]);
    }

    // 清除Cookie
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
    }

    // 清除Session
    $_SESSION = [];
    session_destroy();
}

// 在登录方法中使用：
// public function login($username, $password, $remember = false) {
//     // ... 验证逻辑 ...
//     if ($remember) {
//         $this->rememberMe($user['id']);
//     }
// }

// SQL: ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) NULL;
PHP;
echo "\n";


// 练习2参考：添加邮箱验证功能
echo "\n--- 练习2 参考：邮箱验证功能 ---\n";
echo <<<'PHP'
<?php
// 需要新增 email_verification_token 和 email_verified_at 字段
// SQL: ALTER TABLE users ADD COLUMN email_verification_token VARCHAR(64) NULL;
//      ALTER TABLE users ADD COLUMN email_verified_at TIMESTAMP NULL;

class EmailVerification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 发送验证邮件
    public function sendVerificationEmail($user_id) {
        $user = $this->getUserById($user_id);

        // 生成token
        $token = bin2hex(random_bytes(32));
        $hashed_token = hash('sha256', $token);

        // 存储到数据库
        $sql = "UPDATE users SET email_verification_token = :token WHERE id = :id";
        $this->db->query($sql, [
            ':token' => $hashed_token,
            ':id' => $user_id
        ]);

        // 构建验证链接
        $verify_url = "https://yoursite.com/verify-email.php?token=" . $token;

        // 发送邮件
        $subject = "请验证您的邮箱";
        $message = "请点击以下链接验证您的邮箱：\n\n";
        $message .= $verify_url . "\n\n";
        $message .= "链接24小时内有效。";

        mail($user['email'], $subject, $message);
    }

    // 验证邮箱
    public function verifyEmail($token) {
        $hashed_token = hash('sha256', $token);

        $sql = "SELECT * FROM users WHERE email_verification_token = :token";
        $stmt = $this->db->query($sql, [':token' => $hashed_token]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("验证链接无效");
        }

        // 标记为已验证
        $sql = "UPDATE users SET email_verified_at = NOW(), email_verification_token = NULL
                WHERE id = :id";
        $this->db->query($sql, [':id' => $user['id']]);

        return true;
    }

    // 检查是否已验证
    public function isVerified($user_id) {
        $sql = "SELECT email_verified_at FROM users WHERE id = :id";
        $stmt = $this->db->query($sql, [':id' => $user_id]);
        $user = $stmt->fetch();

        return !empty($user['email_verified_at']);
    }
}
PHP;
echo "\n";


// 练习3参考：添加密码重置功能
echo "\n--- 练习3 参考：密码重置功能 ---\n";
echo <<<'PHP'
<?php
// 需要新增 password_reset_token 和 password_reset_expires 字段
// SQL: ALTER TABLE users ADD COLUMN password_reset_token VARCHAR(64) NULL;
//      ALTER TABLE users ADD COLUMN password_reset_expires TIMESTAMP NULL;

class PasswordReset {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 发送密码重置邮件
    public function sendResetLink($email) {
        // 查找用户
        $sql = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        $stmt = $this->db->query($sql, [':email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            // 安全考虑：不透露邮箱是否存在
            return true;
        }

        // 生成token
        $token = bin2hex(random_bytes(32));
        $hashed_token = hash('sha256', $token);

        // 存储到数据库，1小时有效
        $sql = "UPDATE users SET
                    password_reset_token = :token,
                    password_reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                WHERE id = :id";
        $this->db->query($sql, [
            ':token' => $hashed_token,
            ':id' => $user['id']
        ]);

        // 发送邮件
        $reset_url = "https://yoursite.com/reset-password.php?token=" . $token;
        $subject = "密码重置请求";
        $message = "请点击以下链接重置密码（1小时内有效）：\n\n" . $reset_url;

        mail($email, $subject, $message);
        return true;
    }

    // 验证token并重置密码
    public function resetPassword($token, $new_password) {
        $hashed_token = hash('sha256', $token);

        // 查找有效token
        $sql = "SELECT * FROM users
                WHERE password_reset_token = :token
                AND password_reset_expires > NOW()";
        $stmt = $this->db->query($sql, [':token' => $hashed_token]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("重置链接无效或已过期");
        }

        // 更新密码
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET
                    password = :password,
                    password_reset_token = NULL,
                    password_reset_expires = NULL
                WHERE id = :id";
        $this->db->query($sql, [
            ':password' => $hashed_password,
            ':id' => $user['id']
        ]);

        return true;
    }
}
PHP;
echo "\n";


// 练习4参考：添加用户头像上传功能
echo "\n--- 练习4 参考：头像上传功能 ---\n";
echo <<<'PHP'
<?php
class AvatarUploader {
    private $uploadDir;
    private $maxSize;
    private $allowedTypes;

    public function __construct() {
        $this->uploadDir = __DIR__ . '/uploads/avatars/';
        $this->maxSize = 2 * 1024 * 1024;  // 2MB
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // 确保目录存在
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    // 上传头像
    public function upload($file) {
        // 验证上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("上传失败，错误码：" . $file['error']);
        }

        // 验证文件大小
        if ($file['size'] > $this->maxSize) {
            throw new Exception("文件大小不能超过2MB");
        }

        // 验证文件类型
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new Exception("只允许上传 JPG、PNG、GIF 图片");
        }

        // 生成唯一文件名
        $extension = match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif'
        };
        $filename = uniqid('avatar_') . '.' . $extension;
        $filepath = $this->uploadDir . $filename;

        // 移动文件
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception("文件保存失败");
        }

        // 返回相对路径（用于存数据库）
        return '/uploads/avatars/' . $filename;
    }

    // 删除旧头像
    public function delete($avatarPath) {
        if ($avatarPath && file_exists(__DIR__ . $avatarPath)) {
            unlink(__DIR__ . $avatarPath);
        }
    }
}

// 控制器中使用：
// $uploader = new AvatarUploader();
// $path = $uploader->upload($_FILES['avatar']);
// $user->update($user_id, ['avatar' => $path]);
PHP;
echo "\n";


// 练习5参考：添加管理员后台功能
echo "\n--- 练习5 参考：管理员后台功能 ---\n";
echo <<<'PHP'
<?php
class AdminMiddleware implements Middleware {
    public function handle($request, $next) {
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => '请先登录']);
            exit;
        }

        // 检查是否是管理员
        $user = (new User())->findById($_SESSION['user_id']);
        if (!$user || !$user['is_admin']) {
            http_response_code(403);
            echo json_encode(['error' => '权限不足']);
            exit;
        }

        return $next($request);
    }
}

class AdminController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    // 用户列表（支持分页和搜索）
    public function index($page = 1, $keyword = '') {
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $db = Database::getInstance();

        if ($keyword) {
            $sql = "SELECT * FROM users
                    WHERE username LIKE :kw OR email LIKE :kw
                    ORDER BY id DESC LIMIT :offset LIMIT :limit";
            $kw = "%{$keyword}%";
        } else {
            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT :offset LIMIT :limit";
        }

        // 获取总数
        $countSql = $keyword
            ? "SELECT COUNT(*) FROM users WHERE username LIKE :kw OR email LIKE :kw"
            : "SELECT COUNT(*) FROM users";

        // 返回JSON响应
        return jsonSuccess([
            'users' => $users,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    // 禁用/启用用户
    public function toggleActive($user_id) {
        $user = $this->user->findById($user_id);
        if (!$user) {
            return jsonError('用户不存在', 404);
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $this->user->update($user_id, ['is_active' => $newStatus]);

        return jsonSuccess(null, $newStatus ? '已启用' : '已禁用');
    }

    // 设置/取消管理员
    public function toggleAdmin($user_id) {
        $user = $this->user->findById($user_id);
        if (!$user) {
            return jsonError('用户不存在', 404);
        }

        $newRole = $user['is_admin'] ? 0 : 1;
        $this->user->update($user_id, ['is_admin' => $newRole]);

        return jsonSuccess(null, $newRole ? '已设为管理员' : '已取消管理员');
    }

    // 删除用户
    public function destroy($user_id) {
        // 不能删除自己
        if ($user_id == $_SESSION['user_id']) {
            return jsonError('不能删除自己的账号');
        }

        $this->user->delete($user_id);
        return jsonSuccess(null, '用户已删除');
    }
}

// 路由配置（需要AdminMiddleware）：
// GET    /admin/users          -> AdminController@index
// PUT    /admin/users/{id}/toggle-active -> AdminController@toggleActive
// PUT    /admin/users/{id}/toggle-admin  -> AdminController@toggleAdmin
// DELETE /admin/users/{id}     -> AdminController@destroy
PHP;
echo "\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 完整的用户系统包含：数据库设计 -> 模型层 -> 认证层 -> 验证层 -> 视图层 -> 控制器
 * - 安全措施贯穿始终：password_hash加密、预处理语句防SQL注入、CSRF Token、htmlspecialchars防XSS
 * - 代码模块化：每个类只负责一件事（单一职责原则）
 *
 * 常见陷阱：
 * - 登录成功后必须session_regenerate_id(true)，防止Session固定攻击
 * - 登录失败次数要限制，防止暴力破解，但锁定时间不要太长
 * - 表单回显时必须用htmlspecialchars()，否则存储型XSS会攻击其他用户
 *
 * 下节课预告：
 * - 第18课我们将学习RESTful API、Composer、MVC架构等进阶主题
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第17课项目实战！\n";
echo "   下节课：PHP与Web开发 —— 进阶之路\n";
echo "============================================================\n";
