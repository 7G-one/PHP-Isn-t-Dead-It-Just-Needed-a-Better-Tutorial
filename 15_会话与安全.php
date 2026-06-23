<?php
// =============================================
// 第 15 课：会话与安全
// =============================================
// 上节课我们学了数据库操作（PDO）。
// 这节课我们要学习会话管理与Web安全防护。

echo "============================================================\n";
echo "   第15课：会话与安全\n";
echo "============================================================\n\n";

// 【生活类比】
// 会话（Session）就像游乐园的"会员卡"——
// 你进门时办一张卡（session_start），工作人员把你的信息记在系统里，
// 之后玩每个项目刷卡就行，不用每次都报姓名和身份证号。
// Cookie 就像盖在你手背上的"印章"——每次进场亮一下手背，
// 服务员看到印章就知道你是付费用户，放你进去。
// 区别在于：会员卡信息存在游乐园的系统里（服务器），印章在你自己手上（浏览器）。

// =============================================
// 第一节：Session详解
// =============================================

echo "【第一节：Session详解】\n\n";

// Session工作原理
echo "Session 工作原理：\n";
echo "  1. 用户访问网站，服务器创建Session\n";
echo "  2. 服务器生成唯一的Session ID\n";
echo "  3. Session ID通过Cookie发送到浏览器\n";
echo "  4. 浏览器每次请求都带上Session ID\n";
echo "  5. 服务器用Session ID查找对应的Session数据\n\n";

// 启动Session
// session_start();

// 存储Session数据
// $_SESSION['user_id'] = 123;
// $_SESSION['username'] = '小明';
// $_SESSION['login_time'] = time();

// 读取Session数据
// $user_id = $_SESSION['user_id'] ?? null;

// 检查Session是否存在
// if (isset($_SESSION['user_id'])) {
//     echo "用户已登录";
// }

echo "Session 常用操作：\n";
echo <<<'CODE'
// 启动Session（必须在输出任何内容之前）
session_start();

// 存储数据
$_SESSION['user_id'] = 123;
$_SESSION['username'] = '小明';
$_SESSION['role'] = 'admin';

// 读取数据
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '访客';

// 删除单个Session变量
unset($_SESSION['username']);

// 删除所有Session变量
$_SESSION = [];

// 销毁Session
session_destroy();

// 重新生成Session ID（防止Session固定攻击）
session_regenerate_id(true);
CODE;
echo "\n\n";


// =============================================
// 第二节：Session安全配置
// =============================================

echo "【第二节：Session安全配置】\n\n";

echo "Session 安全配置：\n";
echo <<<'CODE'
// php.ini 配置
// session.cookie_httponly = 1    // JavaScript无法访问Cookie
// session.cookie_secure = 1      // 只在HTTPS下发送Cookie
// session.use_strict_mode = 1    // 严格模式
// session.use_only_cookies = 1   // 只使用Cookie传递Session ID
// session.cookie_samesite = "Lax" // 防止CSRF

// 代码中设置
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// 启动Session
session_start();

// 重要：登录成功后重新生成Session ID
if (password_verify($password, $user['password'])) {
    session_regenerate_id(true);  // 防止Session固定攻击
    $_SESSION['user_id'] = $user['id'];
}
CODE;
echo "\n\n";


// =============================================
// 第三节：Cookie详解
// =============================================

echo "【第三节：Cookie详解】\n\n";

echo "Cookie 特点：\n";
echo "  - 存储在客户端（浏览器）\n";
echo "  - 可以设置过期时间\n";
echo "  - 每次请求都会发送到服务器\n";
echo "  - 大小限制4KB\n";
echo "  - 可以被用户查看和修改\n\n";

echo "Cookie 使用示例：\n";
echo <<<'CODE'
// 设置Cookie（有效期1小时）
setcookie('username', '小明', [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => 'example.com',
    'secure' => true,      // 只在HTTPS下发送
    'httponly' => true,     // JavaScript无法访问
    'samesite' => 'Lax'    // 防止CSRF
]);

// 设置简单的Cookie
setcookie('theme', 'dark', time() + 86400, '/');

// 读取Cookie
$username = $_COOKIE['username'] ?? '访客';
$theme = $_COOKIE['theme'] ?? 'light';

// 删除Cookie
setcookie('username', '', time() - 3600, '/');
CODE;
echo "\n\n";


// =============================================
// 第四节：密码安全
// =============================================

echo "【第四节：密码安全】\n\n";

echo "密码安全原则：\n";
echo "  1. 永远不要明文存储密码\n";
echo "  2. 使用 password_hash() 和 password_verify()\n";
echo "  3. 使用 bcrypt 或 argon2 算法\n";
echo "  4. 不要自己实现加密算法\n\n";

// 密码哈希
echo "密码哈希示例：\n";

$password = 'my_secure_password';

// 创建密码哈希
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "原始密码：$password\n";
echo "哈希后：$hash\n\n";

// 验证密码
$is_valid = password_verify($password, $hash);
echo "密码验证：" . ($is_valid ? '成功' : '失败') . "\n";

$is_valid = password_verify('wrong_password', $hash);
echo "错误密码验证：" . ($is_valid ? '成功' : '失败') . "\n\n";

// 检查是否需要重新哈希
if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
    echo "需要重新哈希密码\n";
    $hash = password_hash($password, PASSWORD_DEFAULT);
} else {
    echo "密码哈希仍然有效\n";
}
echo "\n";


// =============================================
// 第五节：CSRF防护
// =============================================

echo "【第五节：CSRF防护】\n\n";

echo "什么是CSRF（跨站请求伪造）？\n";
echo "  攻击者诱使用户在已登录的网站上执行恶意操作\n";
echo "  例如：用户登录了银行网站，然后访问恶意网站\n";
echo "  恶意网站自动发送转账请求到银行网站\n\n";

echo "CSRF防护方法：\n";
echo <<<'CODE'
// 生成CSRF Token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 验证CSRF Token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
}

// 在表单中使用
// <form method="POST">
//     <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
//     <!-- 其他表单字段 -->
//     <button type="submit">提交</button>
// </form>

// 处理表单时验证
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        die('CSRF验证失败');
    }
    // 处理表单...
}
CODE;
echo "\n\n";


// =============================================
// 第六节：XSS防护
// =============================================

echo "【第六节：XSS防护】\n\n";

echo "什么是XSS（跨站脚本攻击）？\n";
echo "  攻击者在网页中注入恶意脚本\n";
echo "  当其他用户访问该页面时，脚本会执行\n";
echo "  可以窃取Cookie、会话信息等\n\n";

echo "XSS防护方法：\n";

// 不安全的输出
$user_input = '<script>alert("XSS攻击!")</script>';
echo "不安全输出：$user_input\n";

// 安全的输出
echo "安全输出：" . htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8') . "\n\n";

echo "XSS防护函数：\n";
echo <<<'CODE'
// htmlspecialchars() - 转义HTML特殊字符
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');

// strip_tags() - 移除HTML标签
echo strip_tags($user_input);

// HTML Purifier库（更强大的HTML过滤）
// require_once 'HTMLPurifier.auto.php';
// $purifier = new HTMLPurifier();
// $clean_html = $purifier->purify($dirty_html);
CODE;
echo "\n\n";


// =============================================
// 第七节：SQL注入防护
// =============================================

echo "【第七节：SQL注入防护】\n\n";

echo "SQL注入防护方法：\n";
echo <<<'CODE'
// 不安全的代码
$username = $_POST['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
// 如果 username = "' OR '1'='1"，会返回所有用户

// 安全的代码（预处理语句）
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $_POST['username']]);
// 参数和SQL分离，不会被注入

// 其他防护措施
// 1. 使用PDO或MySQLi的预处理语句
// 2. 验证和过滤用户输入
// 3. 使用最小权限原则
// 4. 不要显示详细的数据库错误信息
CODE;
echo "\n\n";


// =============================================
// 第八节：输入验证和过滤
// =============================================

echo "【第八节：输入验证和过滤】\n\n";

echo "输入验证函数：\n";

// filter_var() 过滤
$email = "test@example.com";
$filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
echo "邮箱验证：$email → " . ($filtered ?: '无效') . "\n";

$url = "https://example.com";
$filtered = filter_var($url, FILTER_VALIDATE_URL);
echo "URL验证：$url → " . ($filtered ?: '无效') . "\n";

$ip = "192.168.1.1";
$filtered = filter_var($ip, FILTER_VALIDATE_IP);
echo "IP验证：$ip → " . ($filtered ?: '无效') . "\n\n";

// 输入验证类
echo "输入验证类示例：\n";
echo <<<'CODE'
class Validator {
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function url($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function ip($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public static function integer($value, $min = null, $max = null) {
        $options = [];
        if ($min !== null) $options['options']['min_range'] = $min;
        if ($max !== null) $options['options']['max_range'] = $max;
        return filter_var($value, FILTER_VALIDATE_INT, $options) !== false;
    }

    public static function alphanumeric($value) {
        return preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    public static function length($value, $min = 0, $max = PHP_INT_MAX) {
        $len = mb_strlen($value);
        return $len >= $min && $len <= $max;
    }
}
CODE;
echo "\n\n";


// =============================================
// 第九节：安全最佳实践
// =============================================

echo "【第九节：安全最佳实践】\n\n";

echo "Web安全清单：\n";
echo "  1. HTTPS：使用SSL/TLS加密传输\n";
echo "  2. 密码安全：使用password_hash()\n";
echo "  3. SQL注入：使用预处理语句\n";
echo "  4. XSS防护：使用htmlspecialchars()\n";
echo "  5. CSRF防护：使用Token验证\n";
echo "  6. 输入验证：验证和过滤所有输入\n";
echo "  7. 输出编码：根据上下文编码输出\n";
echo "  8. 错误处理：不要显示详细错误信息\n";
echo "  9. 日志记录：记录安全相关事件\n";
echo "  10. 定期更新：保持PHP和依赖库更新\n\n";

echo "安全函数总结：\n";
echo "  password_hash() - 密码哈希\n";
echo "  password_verify() - 密码验证\n";
echo "  htmlspecialchars() - HTML转义\n";
echo "  filter_var() - 输入过滤\n";
echo "  prepared statements - SQL注入防护\n";
echo "  random_bytes() - 生成安全随机数\n";
echo "  hash_equals() - 时间安全的字符串比较\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写一个安全的用户登录系统\n";
echo "  提示：用 password_verify() 验证密码，登录成功后 session_regenerate_id(true)\n";
echo "  提示：记录登录失败次数，超过5次锁定15分钟\n\n";

echo "练习2：编写一个CSRF Token生成和验证函数\n";
echo "  提示：生成用 bin2hex(random_bytes(32))，存到 \$_SESSION['csrf_token']\n";
echo "  提示：验证用 hash_equals() 比较（防止时序攻击），并检查Token是否过期\n\n";

echo "练习3：编写一个输入验证类，支持多种验证规则\n";
echo "  提示：支持 required、email、min、max 等规则，用数组存储错误信息\n";
echo "  提示：validate(\$value, \$rules, \$field_name) 遍历规则逐个检查\n\n";

echo "练习4：编写一个安全的文件上传函数\n";
echo "  提示：检查 UPLOAD_ERR_OK、用 finfo 检查MIME类型、检查文件大小\n";
echo "  提示：用 bin2hex(random_bytes(16)) 生成安全文件名，不要用原始文件名\n\n";

echo "练习5：编写一个简单的安全中间件\n";
echo "  提示：设置安全响应头（X-Content-Type-Options、X-Frame-Options等）\n";
echo "  提示：POST 请求时验证 CSRF Token，可选强制 HTTPS 跳转\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";

class SecureLogin {
    private $pdo;
    private $max_attempts = 5;
    private $lockout_time = 900; // 15分钟

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login($username, $password) {
        // 检查登录尝试次数
        if ($this->isLockedOut($username)) {
            return ['success' => false, 'message' => '账户已锁定，请15分钟后重试'];
        }

        // 查找用户
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $this->recordAttempt($username);
            return ['success' => false, 'message' => '用户名或密码错误'];
        }

        // 登录成功
        $this->clearAttempts($username);

        // 重新生成Session ID
        session_regenerate_id(true);

        // 存储Session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['login_time'] = time();

        return ['success' => true, 'user' => $user];
    }

    private function isLockedOut($username) {
        // 检查锁定状态
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM login_attempts
            WHERE username = ? AND attempted_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
        ");
        $stmt->execute([$username, $this->lockout_time]);
        return $stmt->fetchColumn() >= $this->max_attempts;
    }

    private function recordAttempt($username) {
        $stmt = $this->pdo->prepare("INSERT INTO login_attempts (username) VALUES (?)");
        $stmt->execute([$username]);
    }

    private function clearAttempts($username) {
        $stmt = $this->pdo->prepare("DELETE FROM login_attempts WHERE username = ?");
        $stmt->execute([$username]);
    }
}

echo "SecureLogin 类已定义\n\n";

// 练习2
echo "--- 练习2答案 ---\n";

class CSRFProtection {
    public static function generateToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyToken($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        // 检查Token是否过期（1小时）
        if (time() - $_SESSION['csrf_token_time'] > 3600) {
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function getTokenField() {
        return '<input type="hidden" name="csrf_token" value="' . self::generateToken() . '">';
    }
}

echo "CSRFProtection 类已定义\n\n";

// 练习3
echo "--- 练习3答案 ---\n";

class InputValidator {
    private $errors = [];

    public function validate($value, $rules, $field_name) {
        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $this->checkRule($value, $rule, [], $field_name);
            } elseif (is_array($rule)) {
                [$rule_name, $params] = $rule;
                $this->checkRule($value, $rule_name, $params, $field_name);
            }
        }
    }

    private function checkRule($value, $rule, $params, $field_name) {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[] = "$field_name 不能为空";
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[] = "$field_name 格式不正确";
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[] = "$field_name 必须是数字";
                }
                break;
            case 'min':
                if (strlen($value) < $params[0]) {
                    $this->errors[] = "$field_name 最少 {$params[0]} 个字符";
                }
                break;
            case 'max':
                if (strlen($value) > $params[0]) {
                    $this->errors[] = "$field_name 最多 {$params[0]} 个字符";
                }
                break;
        }
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}

echo "InputValidator 类已定义\n\n";

// 练习4
echo "--- 练习4答案 ---\n";

class SecureFileUpload {
    private $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    private $max_size = 2 * 1024 * 1024; // 2MB
    private $upload_dir = 'uploads/';

    public function upload($file) {
        $errors = [];

        // 检查上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'errors' => ['上传错误']];
        }

        // 检查文件大小
        if ($file['size'] > $this->max_size) {
            $errors[] = '文件太大';
        }

        // 检查MIME类型
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($file['tmp_name']);
        if (!in_array($mime_type, $this->allowed_types)) {
            $errors[] = '不支持的文件类型';
        }

        // 检查文件扩展名
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $allowed_extensions)) {
            $errors[] = '不支持的文件扩展名';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // 生成安全文件名
        $safe_name = bin2hex(random_bytes(16)) . '.' . $extension;

        // 创建上传目录
        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0755, true);
        }

        // 移动文件
        $destination = $this->upload_dir . $safe_name;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $safe_name, 'path' => $destination];
        }

        return ['success' => false, 'errors' => ['保存文件失败']];
    }
}

echo "SecureFileUpload 类已定义\n\n";

// 练习5
echo "--- 练习5答案 ---\n";

class SecurityMiddleware {
    public static function handle() {
        // 设置安全头
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // 强制HTTPS
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            if (!headers_sent()) {
                header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit;
            }
        }

        // 启动Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // CSRF验证（POST请求）
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRFProtection::verifyToken($token)) {
                http_response_code(403);
                die('CSRF验证失败');
            }
        }
    }
}

echo "SecurityMiddleware 类已定义\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - Session存储在服务器，Cookie存储在客户端；登录后要session_regenerate_id()
 * - 密码用password_hash()加密、password_verify()验证，永远不要明文存储
 * - CSRF用Token防护，XSS用htmlspecialchars()防护，SQL注入用预处理语句防护
 *
 * 常见陷阱：
 * - session_start()必须在任何输出之前调用，否则会报headers already sent
 * - Cookie设置httponly=true防止JS读取，secure=true只在HTTPS下发送
 * - CSRF Token要绑定Session并设置过期时间，否则可以被重放
 *
 * 下节课预告：
 * - 第16课我们将学习命名空间与类型声明——PHP 8.0+的现代特性
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第15课！\n";
echo "   下节课：命名空间与类型声明 —— PHP 8.0+的现代特性\n";
echo "============================================================\n";
