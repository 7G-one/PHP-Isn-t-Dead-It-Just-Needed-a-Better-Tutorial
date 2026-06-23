<?php
// =============================================
// 第 18 课：PHP与Web开发
// =============================================
// 上节课我们完成了用户注册登录系统的项目实战。
// 这节课是最后一课，学习RESTful API、Composer、MVC等进阶主题。

echo "============================================================\n";
echo "   第18课：PHP与Web开发\n";
echo "============================================================\n\n";


// =============================================
// 第一节：RESTful API
// =============================================

echo "【第一节：RESTful API】\n\n";

echo "什么是RESTful API？\n";
echo "  REST = Representational State Transfer（表述性状态转移）\n";
echo "  是一种Web服务的设计风格，不是协议\n\n";

echo "RESTful API 设计原则：\n";
echo "  1. 使用HTTP方法表示操作：\n";
echo "     - GET：获取资源\n";
echo "     - POST：创建资源\n";
echo "     - PUT：更新资源（完整更新）\n";
echo "     - PATCH：更新资源（部分更新）\n";
echo "     - DELETE：删除资源\n\n";

echo "  2. 使用URL表示资源：\n";
echo "     - GET /api/users        # 获取用户列表\n";
echo "     - GET /api/users/1      # 获取用户1\n";
echo "     - POST /api/users       # 创建用户\n";
echo "     - PUT /api/users/1      # 更新用户1\n";
echo "     - DELETE /api/users/1   # 删除用户1\n\n";

// 简单的API路由示例
echo "简单API路由示例：\n";
echo <<<'PHP'
<?php
// api.php

// 获取请求方法和路径
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 路由匹配
$routes = [
    'GET /api/users' => 'UserController@index',
    'GET /api/users/{id}' => 'UserController@show',
    'POST /api/users' => 'UserController@store',
    'PUT /api/users/{id}' => 'UserController@update',
    'DELETE /api/users/{id}' => 'UserController@destroy',
];

// 解析路由
foreach ($routes as $route => $handler) {
    [$routeMethod, $routePath] = explode(' ', $route, 2);

    // 转换路由参数为正则
    $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $routePath);
    $pattern = "#^{$pattern}$#";

    if ($method === $routeMethod && preg_match($pattern, $path, $matches)) {
        // 提取参数
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // 调用控制器
        [$controller, $action] = explode('@', $handler);
        $controllerInstance = new $controller();
        $controllerInstance->$action(...array_values($params));
        exit;
    }
}

// 404
http_response_code(404);
echo json_encode(['error' => 'Not Found']);
PHP;
echo "\n\n";


// =============================================
// 第二节：JSON处理
// =============================================

echo "【第二节：JSON处理】\n\n";

// 编码JSON
$data = [
    'name' => '小明',
    'age' => 25,
    'hobbies' => ['编程', '阅读', '游戏'],
    'address' => [
        'city' => '北京',
        'district' => '海淀区'
    ]
];

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "编码JSON：\n$json\n\n";

// 解码JSON
$decoded = json_decode($json, true);
echo "解码JSON：\n";
echo "姓名：" . $decoded['name'] . "\n";
echo "城市：" . $decoded['address']['city'] . "\n\n";

// JSON错误处理
$invalid_json = '{"name": "小明", age: 25}';  // 缺少引号
$result = json_decode($invalid_json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON错误：" . json_last_error_msg() . "\n";
}
echo "\n";

// API响应函数
echo "API响应函数：\n";
echo <<<'PHP'
<?php
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError($message, $status = 400) {
    jsonResponse(['error' => $message], $status);
}

function jsonSuccess($data, $message = '成功') {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

// 使用示例
jsonSuccess(['id' => 1, 'name' => '小明'], '创建成功');
jsonError('参数错误', 422);
PHP;
echo "\n\n";


// =============================================
// 第三节：Composer
// =============================================

echo "【第三节：Composer】\n\n";

echo "什么是Composer？\n";
echo "  Composer是PHP的依赖管理工具\n";
echo "  就像Node.js的npm，Python的pip\n\n";

echo "Composer基本命令：\n";
echo <<<'BASH'
# 安装Composer
curl -sS https://getcomposer.org/installer | php

# 初始化项目
composer init

# 安装依赖
composer install

# 添加依赖
composer require monolog/monolog

# 添加开发依赖
composer require --dev phpunit/phpunit

# 更新依赖
composer update

# 自动加载
composer dump-autoload
BASH;
echo "\n\n";

echo "composer.json 示例：\n";
echo <<<'JSON'
{
    "name": "my-project/my-app",
    "description": "My PHP Application",
    "type": "project",
    "require": {
        "php": ">=8.0",
        "monolog/monolog": "^3.0",
        "guzzlehttp/guzzle": "^7.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "phpstan/phpstan": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
JSON;
echo "\n\n";


// =============================================
// 第四节：MVC架构
// =============================================

echo "【第四节：MVC架构】\n\n";

echo "什么是MVC？\n";
echo "  MVC = Model-View-Controller（模型-视图-控制器）\n";
echo "  是一种软件设计模式，将应用分为三层\n\n";

echo "MVC分工：\n";
echo "  Model（模型）：处理数据和业务逻辑\n";
echo "  View（视图）：负责显示（HTML模板）\n";
echo "  Controller（控制器）：接收请求，协调Model和View\n\n";

echo "MVC工作流程：\n";
echo "  用户请求 → 路由 → 控制器 → 模型 → 视图 → 响应\n\n";

// MVC示例
echo "MVC示例：\n";
echo <<<'PHP'
<?php
// Model
class UserModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll() {
        return $this->db->query("SELECT * FROM users")->fetchAll();
    }
}

// View
class View {
    private $data = [];

    public function assign($key, $value) {
        $this->data[$key] = $value;
    }

    public function render($template) {
        extract($this->data);
        ob_start();
        require "views/$template.php";
        return ob_get_clean();
    }
}

// Controller
class UserController {
    private $model;
    private $view;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->model = new UserModel($db);
        $this->view = new View();
    }

    public function index() {
        $users = $this->model->findAll();
        $this->view->assign('users', $users);
        echo $this->view->render('users/index');
    }

    public function show($id) {
        $user = $this->model->findById($id);
        $this->view->assign('user', $user);
        echo $this->view->render('users/show');
    }
}
PHP;
echo "\n\n";


// =============================================
// 第五节：PHP框架简介
// =============================================

echo "【第五节：PHP框架简介】\n\n";

echo "流行的PHP框架：\n";
echo "  1. Laravel：最流行的PHP框架\n";
echo "     - 优雅的语法\n";
echo "     - 强大的生态系统\n";
echo "     - 完善的文档\n";
echo "     - 适合中大型项目\n\n";

echo "  2. Symfony：企业级框架\n";
echo "     - 组件化设计\n";
echo "     - 高度可定制\n";
echo "     - 适合大型项目\n\n";

echo "  3. CodeIgniter：轻量级框架\n";
echo "     - 简单易学\n";
echo "     - 性能优秀\n";
echo "     - 适合小型项目\n\n";

echo "  4. Yii：高性能框架\n";
echo "     - 快速开发\n";
echo "     - 缓存支持好\n";
echo "     - 适合Web应用\n\n";

echo "  5. Slim：微框架\n";
echo "     - 轻量级\n";
echo "     - 适合API开发\n";
echo "     - 灵活自由\n\n";


// =============================================
// 第六节：HTTP客户端
// =============================================

echo "【第六节：HTTP客户端】\n\n";

echo "使用cURL发送HTTP请求：\n";
echo <<<'PHP'
<?php
// GET请求
function httpGet($url, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error) {
        throw new Exception("cURL错误：$error");
    }

    return ['status' => $statusCode, 'body' => $response];
}

// POST请求
function httpPost($url, $data, $headers = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
        'Content-Type: application/json'
    ], $headers));

    $response = curl_exec($ch);
    $error = curl_error($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($error) {
        throw new Exception("cURL错误：$error");
    }

    return ['status' => $statusCode, 'body' => $response];
}

// 使用示例
// $result = httpGet('https://api.example.com/users');
// $result = httpPost('https://api.example.com/users', ['name' => '小明']);
PHP;
echo "\n\n";


// =============================================
// 第七节：中间件
// =============================================

echo "【第七节：中间件】\n\n";

echo "什么是中间件？\n";
echo "  中间件是请求处理管道中的一个环节\n";
echo "  可以在请求到达控制器之前或之后执行操作\n";
echo "  例如：认证、日志、CORS等\n\n";

echo "中间件示例：\n";
echo <<<'PHP'
<?php
interface Middleware {
    public function handle($request, $next);
}

class AuthMiddleware implements Middleware {
    public function handle($request, $next) {
        // 检查是否登录
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        // 继续处理请求
        return $next($request);
    }
}

class CorsMiddleware implements Middleware {
    public function handle($request, $next) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        return $next($request);
    }
}

class LogMiddleware implements Middleware {
    public function handle($request, $next) {
        $start = microtime(true);

        $result = $next($request);

        $time = microtime(true) - $start;
        error_log(sprintf(
            "%s %s %s %.2fms",
            date('Y-m-d H:i:s'),
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $time * 1000
        ));

        return $result;
    }
}

// 中间件管道
class Pipeline {
    private $middlewares = [];

    public function pipe(Middleware $middleware) {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function run($request, $handler) {
        $next = function($request) use ($handler) {
            return $handler($request);
        };

        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = function($request) use ($middleware, $next) {
                return $middleware->handle($request, $next);
            };
        }

        return $next($request);
    }
}
PHP;
echo "\n\n";


// =============================================
// 第八节：缓存
// =============================================

echo "【第八节：缓存】\n\n";

echo "缓存策略：\n";
echo "  1. 文件缓存：简单，适合小型项目\n";
echo "  2. Memcached：高性能，分布式\n";
echo "  3. Redis：功能强大，支持多种数据结构\n";
echo "  4. APCu：进程内缓存，性能最好\n\n";

echo "文件缓存示例：\n";
echo <<<'PHP'
<?php
class FileCache {
    private $cacheDir;

    public function __construct($cacheDir = 'cache/') {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function get($key) {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return null;
        }

        $data = unserialize(file_get_contents($file));

        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function set($key, $value, $ttl = 3600) {
        $file = $this->getFilePath($key);

        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];

        return file_put_contents($file, serialize($data)) !== false;
    }

    public function delete($key) {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }

    private function getFilePath($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
}

// 使用示例
$cache = new FileCache();
$cache->set('user_1', ['name' => '小明'], 3600);
$user = $cache->get('user_1');
PHP;
echo "\n\n";


// =============================================
// 第九节：队列
// =============================================

echo "【第九节：队列】\n\n";

echo "什么是队列？\n";
echo "  队列用于处理耗时任务，例如：\n";
echo "  - 发送邮件\n";
echo "  - 生成报告\n";
echo "  - 图片处理\n";
echo "  - 数据同步\n\n";

echo "队列的好处：\n";
echo "  1. 异步处理：不阻塞用户请求\n";
echo "  2. 削峰填谷：处理突发流量\n";
echo "  3. 解耦：生产者和消费者分离\n\n";

echo "简单队列示例：\n";
echo <<<'PHP'
<?php
class Queue {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->createTable();
    }

    private function createTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS jobs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            queue VARCHAR(50) NOT NULL DEFAULT 'default',
            payload TEXT NOT NULL,
            attempts INT DEFAULT 0,
            reserved_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_queue_reserved (queue, reserved_at)
        )");
    }

    public function push($job, $data = [], $queue = 'default') {
        $payload = json_encode([
            'job' => $job,
            'data' => $data
        ]);

        $stmt = $this->db->prepare("INSERT INTO jobs (queue, payload) VALUES (?, ?)");
        $stmt->execute([$queue, $payload]);

        return $this->db->lastInsertId();
    }

    public function pop($queue = 'default') {
        $stmt = $this->db->prepare("
            SELECT * FROM jobs
            WHERE queue = ? AND reserved_at IS NULL
            ORDER BY id ASC
            LIMIT 1
            FOR UPDATE
        ");
        $stmt->execute([$queue]);
        $job = $stmt->fetch();

        if ($job) {
            $this->db->prepare("UPDATE jobs SET reserved_at = NOW() WHERE id = ?")
                     ->execute([$job['id']]);
        }

        return $job;
    }

    public function delete($id) {
        $this->db->prepare("DELETE FROM jobs WHERE id = ?")->execute([$id]);
    }
}

// 使用示例
$queue = new Queue($pdo);
$queue->push('SendEmail', ['to' => 'test@example.com', 'subject' => '欢迎']);
PHP;
echo "\n\n";


// =============================================
// 第十节：学习路线图
// =============================================

echo "【第十节：学习路线图】\n\n";

echo "PHP开发者学习路线：\n\n";

echo "初级阶段（你现在在这里）：\n";
echo "  ✅ PHP基础语法\n";
echo "  ✅ 面向对象编程\n";
echo "  ✅ 数据库操作（PDO）\n";
echo "  ✅ 表单处理\n";
echo "  ✅ Session和Cookie\n";
echo "  ✅ 安全基础\n\n";

echo "中级阶段：\n";
echo "  □ 学习一个框架（推荐Laravel）\n";
echo "  □ RESTful API开发\n";
echo "  □ Composer和包管理\n";
echo "  □ 单元测试（PHPUnit）\n";
echo "  □ 设计模式\n";
echo "  □ 缓存（Redis）\n";
echo "  □ 队列\n\n";

echo "高级阶段：\n";
echo "  □ 微服务架构\n";
echo "  □ Docker和容器化\n";
echo "  □ CI/CD流水线\n";
echo "  □ 性能优化\n";
echo "  □ 高可用架构\n";
echo "  □ 源码阅读\n\n";

echo "推荐学习资源：\n";
echo "  1. PHP官方文档：https://www.php.net/manual/zh/\n";
echo "  2. Laravel官方文档：https://laravel.com/docs\n";
echo "  3. PHP-FIG（PSR标准）：https://www.php-fig.org/\n";
echo "  4. Packagist（包仓库）：https://packagist.org/\n";
echo "  5. GitHub上的开源项目\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：设计一个简单的RESTful API，实现用户的CRUD操作\n";
echo "  提示：GET /api/users 获取列表，POST 创建，PUT 更新，DELETE 删除\n";
echo "  提示：用 \$_SERVER['REQUEST_METHOD'] 获取请求方法，json_encode() 返回JSON\n\n";

echo "练习2：使用Composer创建一个项目，安装monolog日志库\n";
echo "  提示：运行 composer init 初始化，composer require monolog/monolog 安装\n";
echo "  提示：用 require 'vendor/autoload.php' 引入自动加载\n\n";

echo "练习3：实现一个简单的MVC框架\n";
echo "  提示：Model 负责数据操作，View 负责模板渲染，Controller 负责协调\n";
echo "  提示：用路由解析请求URL，调用对应的Controller方法\n\n";

echo "练习4：实现一个文件缓存系统\n";
echo "  提示：get() 检查文件是否存在且未过期，set() 写入序列化数据和过期时间\n";
echo "  提示：用 md5(\$key) 作为文件名，用 serialize/unserialize 存取数据\n\n";

echo "练习5：实现一个简单的任务队列\n";
echo "  提示：用数据库存任务（jobs表），push() 插入任务，pop() 取出待处理任务\n";
echo "  提示：用 reserved_at 字段标记任务已被取出，防止重复处理\n\n";


// =============================================
// 练习参考答案
// =============================================
echo "\n" . str_repeat("=", 50) . "\n";
echo "练习参考答案\n";
echo str_repeat("=", 50) . "\n";


// 练习1参考：设计一个简单的RESTful API，实现用户的CRUD操作
echo "\n--- 练习1 参考：RESTful API 用户CRUD ---\n";
echo <<<'PHP'
<?php
// ===== api/users.php =====
// 简单的RESTful API路由和控制器

// 设置JSON响应头
header('Content-Type: application/json; charset=utf-8');

// 获取请求方法和ID
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

// 模拟数据库（实际项目用PDO连接MySQL）
$users = [
    1 => ['id' => 1, 'name' => '小明', 'email' => 'xiaoming@test.com'],
    2 => ['id' => 2, 'name' => '小红', 'email' => 'xiaohong@test.com'],
];

// 路由分发
switch ($method) {
    case 'GET':
        if ($id) {
            // 获取单个用户
            if (isset($users[$id])) {
                echo json_encode([
                    'success' => true,
                    'data' => $users[$id]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(['error' => '用户不存在']);
            }
        } else {
            // 获取用户列表
            echo json_encode([
                'success' => true,
                'data' => array_values($users)
            ], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        // 创建用户
        $input = json_decode(file_get_contents('php://input'), true);

        // 验证必填字段
        if (empty($input['name']) || empty($input['email'])) {
            http_response_code(422);
            echo json_encode(['error' => 'name和email为必填字段']);
            break;
        }

        $newUser = [
            'id' => count($users) + 1,
            'name' => $input['name'],
            'email' => $input['email']
        ];

        http_response_code(201);  // 201 Created
        echo json_encode([
            'success' => true,
            'message' => '创建成功',
            'data' => $newUser
        ], JSON_UNESCAPED_UNICODE);
        break;

    case 'PUT':
        // 更新用户
        if (!$id || !isset($users[$id])) {
            http_response_code(404);
            echo json_encode(['error' => '用户不存在']);
            break;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $users[$id] = array_merge($users[$id], $input);

        echo json_encode([
            'success' => true,
            'message' => '更新成功',
            'data' => $users[$id]
        ], JSON_UNESCAPED_UNICODE);
        break;

    case 'DELETE':
        // 删除用户
        if (!$id || !isset($users[$id])) {
            http_response_code(404);
            echo json_encode(['error' => '用户不存在']);
            break;
        }

        unset($users[$id]);
        http_response_code(204);  // 204 No Content
        break;

    default:
        http_response_code(405);  // Method Not Allowed
        echo json_encode(['error' => '不支持的请求方法']);
}
PHP;
echo "\n";


// 练习2参考：使用Composer创建一个项目，安装monolog日志库
echo "\n--- 练习2 参考：Composer项目 + Monolog ---\n";
echo <<<'PHP'
<?php
// 步骤1：初始化项目
// mkdir my-logger && cd my-logger
// composer init    （按提示填写项目信息）

// 步骤2：安装monolog
// composer require monolog/monolog

// 步骤3：使用monolog写日志 =====
// 使用前先引入自动加载
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 创建日志通道
$log = new Logger('app');

// 添加文件日志处理器（INFO级别及以上写入文件）
$log->pushHandler(new StreamHandler('logs/app.log', Logger::INFO));

// 添加控制台处理器（DEBUG级别及以上输出到控制台）
$log->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));

// 使用示例
$log->info('用户登录成功', ['user_id' => 1, 'ip' => '127.0.0.1']);
$log->warning('磁盘空间不足', ['free_space' => '500MB']);
$log->error('数据库连接失败', ['host' => 'localhost']);

// composer.json 自动生成如下：
// {
//     "require": {
//         "monolog/monolog": "^3.0"
//     },
//     "autoload": {
//         "psr-4": {
//             "App\\": "src/"
//         }
//     }
// }

// 目录结构：
// my-logger/
// ├── composer.json
// ├── composer.lock
// ├── vendor/          (自动生成)
// │   └── autoload.php
// ├── logs/
// │   └── app.log     (日志文件)
// └── index.php
PHP;
echo "\n";


// 练习3参考：实现一个简单的MVC框架
echo "\n--- 练习3 参考：简易MVC框架 ---\n";
echo <<<'PHP'
<?php
// ===== 简易MVC框架 =====

// --- Router（路由器）---
class Router {
    private $routes = [];

    // 注册路由：GET /users -> UserController@index
    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    // 匹配并执行路由
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // 遍历注册的路由
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            // 支持 {id} 这样的路由参数
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = "#^{$pattern}$#";

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$controller, $action] = explode('@', $handler);
                return (new $controller())->$action(...array_values($params));
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}

// --- Model（模型基类）---
class Model {
    protected $db;
    protected $table;

    public function __construct() {
        // 连接数据库（实际项目用配置文件）
        $this->db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // 查询所有
    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 按ID查询
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// --- View（视图）---
class View {
    // 渲染模板并传入数据
    public function render($template, $data = []) {
        extract($data);  // 将数组转为变量
        ob_start();
        require "views/{$template}.php";
        return ob_get_clean();
    }
}

// --- 使用示例 ---
// 路由配置
$router = new Router();
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');
// $router->dispatch();  // 在入口文件调用

// UserController 示例
// class UserController {
//     public function index() {
//         $model = new UserModel();
//         $users = $model->all();
//         $view = new View();
//         echo $view->render('users', ['users' => $users]);
//     }
// }
PHP;
echo "\n";


// 练习4参考：实现一个文件缓存系统
echo "\n--- 练习4 参考：文件缓存系统 ---\n";
echo <<<'PHP'
<?php
class FileCache {
    private $cacheDir;

    // 初始化缓存目录
    public function __construct($cacheDir = 'cache/') {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    // 获取缓存
    public function get($key) {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return null;  // 缓存不存在
        }

        $data = unserialize(file_get_contents($file));

        // 检查是否过期
        if ($data['expires'] < time()) {
            unlink($file);    // 删除过期缓存
            return null;
        }

        return $data['value'];
    }

    // 设置缓存
    public function set($key, $value, $ttl = 3600) {
        $file = $this->getFilePath($key);

        $data = [
            'value'   => $value,
            'expires' => time() + $ttl   // 过期时间戳
        ];

        return file_put_contents($file, serialize($data)) !== false;
    }

    // 删除缓存
    public function delete($key) {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }

    // 清空所有缓存
    public function clear() {
        $files = glob($this->cacheDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    // 检查缓存是否存在且未过期
    public function has($key) {
        return $this->get($key) !== null;
    }

    // 获取缓存文件路径（用key的MD5做文件名）
    private function getFilePath($key) {
        return $this->cacheDir . md5($key) . '.cache';
    }
}

// 使用示例
$cache = new FileCache('cache/');

// 写入缓存，有效期1小时
$cache->set('user_list', ['小明', '小红', '小刚'], 3600);

// 读取缓存
$users = $cache->get('user_list');
if ($users !== null) {
    echo "缓存命中：\n";
    print_r($users);
} else {
    echo "缓存不存在或已过期\n";
}

// 检查是否存在
echo $cache->has('user_list') ? "存在\n" : "不存在\n";

// 删除缓存
$cache->delete('user_list');
PHP;
echo "\n";


// 练习5参考：实现一个简单的任务队列
echo "\n--- 练习5 参考：简单任务队列 ---\n";
echo <<<'PHP'
<?php
class SimpleQueue {
    private $db;

    // 初始化：连接数据库并创建jobs表
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->db->exec("CREATE TABLE IF NOT EXISTS jobs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            queue VARCHAR(50) NOT NULL DEFAULT 'default',
            payload TEXT NOT NULL,
            status ENUM('pending', 'reserved', 'done', 'failed') DEFAULT 'pending',
            attempts INT DEFAULT 0,
            max_attempts INT DEFAULT 3,
            reserved_at TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_status (status)
        )");
    }

    // 推送任务到队列
    public function push($jobName, $data = [], $queue = 'default') {
        $payload = json_encode([
            'job'  => $jobName,    // 任务名称
            'data' => $data        // 任务参数
        ]);

        $stmt = $this->db->prepare(
            "INSERT INTO jobs (queue, payload) VALUES (?, ?)"
        );
        $stmt->execute([$queue, $payload]);

        return $this->db->lastInsertId();
    }

    // 从队列取出一个待处理任务
    public function pop($queue = 'default') {
        // 用事务保证原子性，防止并发冲突
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("
            SELECT * FROM jobs
            WHERE queue = ? AND status = 'pending'
            ORDER BY id ASC LIMIT 1 FOR UPDATE
        ");
        $stmt->execute([$queue]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($job) {
            // 标记为处理中
            $this->db->prepare("
                UPDATE jobs SET status = 'reserved', reserved_at = NOW(), attempts = attempts + 1
                WHERE id = ?
            ")->execute([$job['id']]);
        }

        $this->db->commit();
        return $job;
    }

    // 标记任务完成
    public function markDone($id) {
        $this->db->prepare("UPDATE jobs SET status = 'done' WHERE id = ?")
                 ->execute([$id]);
    }

    // 标记任务失败（可重试则重新设为pending）
    public function markFailed($id) {
        $job = $this->db->prepare("SELECT * FROM jobs WHERE id = ?");
        $job->execute([$id]);
        $job = $job->fetch(PDO::FETCH_ASSOC);

        if ($job['attempts'] < $job['max_attempts']) {
            // 还有重试机会，重新排队
            $this->db->prepare("UPDATE jobs SET status = 'pending', reserved_at = NULL WHERE id = ?")
                     ->execute([$id]);
        } else {
            // 超过最大重试次数，永久失败
            $this->db->prepare("UPDATE jobs SET status = 'failed' WHERE id = ?")
                     ->execute([$id]);
        }
    }

    // 获取队列统计
    public function stats($queue = 'default') {
        $stmt = $this->db->prepare("
            SELECT status, COUNT(*) as count FROM jobs
            WHERE queue = ? GROUP BY status
        ");
        $stmt->execute([$queue]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}

// 使用示例
// $queue = new SimpleQueue($pdo);
//
// // 推送任务
// $queue->push('SendEmail', ['to' => 'test@test.com', 'subject' => '欢迎']);
// $queue->push('GenerateReport', ['user_id' => 1]);
//
// // Worker进程循环处理
// while ($job = $queue->pop()) {
//     try {
//         $payload = json_decode($job['payload'], true);
//         // 根据job名称分发到不同处理函数
//         switch ($payload['job']) {
//             case 'SendEmail':
//                 // 发送邮件逻辑
//                 break;
//             case 'GenerateReport':
//                 // 生成报告逻辑
//                 break;
//         }
//         $queue->markDone($job['id']);
//     } catch (Exception $e) {
//         $queue->markFailed($job['id']);
//     }
// }
PHP;
echo "\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - RESTful API用HTTP方法表示操作（GET/POST/PUT/DELETE），用URL表示资源
 * - Composer是PHP的依赖管理工具，MVC将应用分为模型-视图-控制器三层
 * - 中间件可以在请求到达控制器前/后执行操作（认证、日志、CORS等）
 *
 * 常见陷阱：
 * - API返回JSON时必须设置Content-Type: application/json，否则前端解析会出错
 * - composer install和composer update的区别：install按lock文件安装，update更新依赖
 * - MVC中不要在Model里写HTML，不要在View里写业务逻辑
 *
 * 下一步建议：
 * - 学习一个PHP框架（推荐Laravel）
 * - 多写代码，多做项目，阅读优秀开源项目的源码
 * - 保持学习，技术在不断进步
 */

// =============================================
// 学习路线图
// =============================================

/*
 * PHP 学习路线图：
 *
 * 入门（00-07）：PHP 基础语法
 *   → 变量、函数、数组、条件、循环
 *
 * 进阶（08-13）：Web 开发核心
 *   → 超全局变量、表单、文件、OOP、异常
 *
 * 高级（14-16）：现代 PHP 开发
 *   → 数据库、会话安全、命名空间、类型声明
 *
 * 实战（17-18）：项目实战
 *   → 用户系统、RESTful API、框架入门
 *
 * 下一步学习建议：
 * 1. Laravel 框架 — PHP 最流行的框架
 * 2. Composer — PHP 包管理器
 * 3. PHPUnit — 单元测试
 * 4. Docker — 容器化部署
 * 5. Redis/Mysql — 生产环境数据库
 */

echo "\n============================================================\n";
echo "   恭喜你完成了全部19课！\n";
echo "   从Hello World到RESTful API，你已经是一个合格的PHP开发者了。\n";
echo "   继续前进，构建你自己的Web应用吧！\n";
echo "============================================================\n";
