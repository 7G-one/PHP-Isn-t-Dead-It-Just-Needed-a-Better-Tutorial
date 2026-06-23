<?php
// =============================================
// 第 13 课：错误与异常处理
// =============================================
// 上节课我们学了OOP进阶：继承、多态、接口。
// 这节课我们要学习错误与异常处理，让程序更健壮。

echo "============================================================\n";
echo "   第13课：错误与异常处理\n";
echo "============================================================\n\n";

// 【生活类比】
// 错误处理就像开车时的"安全气囊"——
// 你平时开车不会想着会出事故（正常执行代码），
// 但安全气囊随时准备好（try-catch），一旦发生碰撞（异常），
// 它就会立刻弹出来保护你（捕获并处理异常）。
// 没有安全气囊的车你敢开吗？没有错误处理的程序你敢上线吗？
// 不同的气囊应对不同的碰撞（不同类型的Exception），这就是"分类处理"。

// =============================================
// 第一节：PHP错误类型
// =============================================

echo "【第一节：PHP错误类型】\n\n";

echo "PHP错误级别：\n";
echo "┌─────────────────┬────────────────────────────────────┐\n";
echo "│ 错误常量        │ 说明                               │\n";
echo "├─────────────────┼────────────────────────────────────┤\n";
echo "│ E_ERROR         │ 致命错误，脚本停止                 │\n";
echo "│ E_WARNING       │ 警告，脚本继续                     │\n";
echo "│ E_NOTICE        │ 通知，可能是问题                   │\n";
echo "│ E_PARSE         │ 语法解析错误                       │\n";
echo "│ E_DEPRECATED    │ 废弃功能警告                       │\n";
echo "│ E_STRICT        │ 代码兼容性建议                     │\n";
echo "│ E_USER_ERROR    │ 用户触发的致命错误                 │\n";
echo "│ E_USER_WARNING  │ 用户触发的警告                     │\n";
echo "│ E_USER_NOTICE   │ 用户触发的通知                     │\n";
echo "└─────────────────┴────────────────────────────────────┘\n\n";


// =============================================
// 第二节：错误报告设置
// =============================================

echo "【第二节：错误报告设置】\n\n";

// 显示所有错误（开发环境）
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

// 不显示错误（生产环境）
// error_reporting(0);
// ini_set('display_errors', '0');

// 记录错误到日志
// ini_set('log_errors', '1');
// ini_set('error_log', '/path/to/error.log');

echo "错误报告配置：\n";
echo "  开发环境：error_reporting(E_ALL) + display_errors = 1\n";
echo "  生产环境：error_reporting(0) + display_errors = 0 + log_errors = 1\n\n";

// 获取当前错误报告级别
echo "当前错误报告级别：" . error_reporting() . "\n";
echo "display_errors：" . ini_get('display_errors') . "\n";
echo "log_errors：" . ini_get('log_errors') . "\n\n";


// =============================================
// 第三节：自定义错误处理器
// =============================================

echo "【第三节：自定义错误处理器】\n\n";

// 自定义错误处理器函数：PHP 会将触发的错误交给这个函数处理
// 参数分别是：错误级别、错误信息、文件名、行号
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_types = [
        E_ERROR => '致命错误',
        E_WARNING => '警告',
        E_NOTICE => '通知',
        E_PARSE => '解析错误',
        E_DEPRECATED => '废弃警告',
        E_USER_ERROR => '用户错误',
        E_USER_WARNING => '用户警告',
        E_USER_NOTICE => '用户通知'
    ];

    $type = $error_types[$errno] ?? '未知错误';
    $message = "[$type] $errstr in $errfile on line $errline";

    // 记录到日志
    error_log($message);

    // 根据错误类型决定是否显示
    if ($errno === E_ERROR || $errno === E_PARSE) {
        echo "发生严重错误，请联系管理员\n";
    } else {
        echo "发生非致命错误：$errstr\n";
    }

    // 返回 true 表示错误已处理，不再传递给PHP默认处理器
    return true;
}

// 设置错误处理器
set_error_handler('customErrorHandler');

echo "自定义错误处理器已设置\n";

// 触发一些错误
echo "触发警告：\n";
// trigger_error("这是一个警告", E_USER_WARNING);

// 恢复默认错误处理器
restore_error_handler();
echo "\n";


// =============================================
// 第四节：异常处理基础
// =============================================

echo "【第四节：异常处理基础】\n\n";

// 异常是更结构化的错误处理方式
echo "异常处理的基本结构：\n";
echo "  try {\n";
echo "      // 可能抛出异常的代码\n";
echo "  } catch (ExceptionType1 \$e) {\n";
echo "      // 处理特定类型的异常\n";
echo "  } catch (Exception \$e) {\n";
echo "      // 处理所有异常\n";
echo "  } finally {\n";
echo "      // 无论是否异常都执行\n";
echo "  }\n\n";

// 基本的异常处理
try {
    echo "尝试执行代码...\n";
    // throw new Exception("这是一个异常");
    echo "代码执行成功\n";
} catch (Exception $e) {
    echo "捕获异常：" . $e->getMessage() . "\n";
    echo "异常文件：" . $e->getFile() . "\n";
    echo "异常行号：" . $e->getLine() . "\n";
} finally {
    echo "finally 块总是执行\n";
}
echo "\n";


// =============================================
// 第五节：异常类层次结构
// =============================================

echo "【第五节：异常类层次结构】\n\n";

// PHP内置异常类层次
echo "PHP内置异常类：\n";
echo "  Exception\n";
echo "    ├── LogicException\n";
echo "    │     ├── BadFunctionCallException\n";
echo "    │     ├── DomainException\n";
echo "    │     ├── InvalidArgumentException\n";
echo "    │     └── LengthException\n";
echo "    ├── RuntimeException\n";
echo "    │     ├── OutOfBoundsException\n";
echo "    │     ├── OverflowException\n";
echo "    │     ├── RangeException\n";
echo "    │     ├── UnderflowException\n";
echo "    │     └── UnexpectedValueException\n";
echo "    └── ErrorException\n\n";

// 使用不同的异常类型
echo "使用不同异常类型：\n";

// 除法函数：除数为0时抛出 InvalidArgumentException
function divide($a, $b) {
    if ($b == 0) {
        throw new InvalidArgumentException("除数不能为0");
    }
    return $a / $b;
}

// 数组取值函数：键不存在时抛出 OutOfBoundsException
function getArrayValue($arr, $key) {
    if (!array_key_exists($key, $arr)) {
        throw new OutOfBoundsException("键 '$key' 不存在");
    }
    return $arr[$key];
}

// 测试
try {
    echo divide(10, 0) . "\n";
} catch (InvalidArgumentException $e) {
    echo "参数错误：" . $e->getMessage() . "\n";
}

try {
    $arr = ['a' => 1, 'b' => 2];
    echo getArrayValue($arr, 'c') . "\n";
} catch (OutOfBoundsException $e) {
    echo "越界错误：" . $e->getMessage() . "\n";
}
echo "\n";


// =============================================
// 第六节：自定义异常类
// =============================================

echo "【第六节：自定义异常类】\n\n";

// 表单验证异常类：可以携带多个错误信息（如"姓名不能为空"、"邮箱格式错误"等）
class ValidationException extends Exception {
    private $errors;

    public function __construct($errors, $code = 0, Exception $previous = null) {
        $this->errors = $errors;
        parent::__construct("验证失败", $code, $previous);
    }

    public function getErrors() {
        return $this->errors;
    }
}

// 数据库异常类：携带 SQL 语句信息，方便调试时定位问题
class DatabaseException extends Exception {
    private $query;

    public function __construct($message, $query = '', $code = 0, Exception $previous = null) {
        $this->query = $query;
        parent::__construct($message, $code, $previous);
    }

    public function getQuery() {
        return $this->query;
    }
}

// 使用自定义异常
echo "自定义异常示例：\n";

// 用户验证函数：收集所有验证错误后统一抛出 ValidationException
function validateUser($data) {
    $errors = [];

    if (empty($data['name'])) {
        $errors[] = '姓名不能为空';
    }
    if (empty($data['email'])) {
        $errors[] = '邮箱不能为空';
    }
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = '邮箱格式不正确';
    }

    if (!empty($errors)) {
        throw new ValidationException($errors);
    }

    return true;
}

try {
    validateUser(['name' => '', 'email' => 'invalid']);
} catch (ValidationException $e) {
    echo "验证失败：\n";
    foreach ($e->getErrors() as $error) {
        echo "  - $error\n";
    }
}
echo "\n";


// =============================================
// 第七节：异常处理最佳实践
// =============================================

echo "【第七节：异常处理最佳实践】\n\n";

echo "异常处理最佳实践：\n";
echo "1. 只在异常情况下使用异常，不要用于正常流程控制\n";
echo "2. 捕获具体的异常类型，而不是通用的 Exception\n";
echo "3. 异常信息要清晰，帮助调试\n";
echo "4. 在适当层级处理异常，不要过早捕获\n";
echo "5. 记录异常日志，方便排查问题\n";
echo "6. 向用户显示友好的错误信息\n\n";

// 实际应用示例：演示异常处理的最佳实践
// 捕获具体异常 -> 记录日志 -> 重新抛出或转换为友好提示
class UserService {
    public function createUser($data) {
        try {
            // 验证数据
            $this->validate($data);

            // 创建用户（模拟）
            $user = $this->saveToDatabase($data);

            return $user;
        } catch (ValidationException $e) {
            // 记录日志
            error_log("验证失败：" . implode(', ', $e->getErrors()));
            throw $e;  // 重新抛出，让调用者处理
        } catch (DatabaseException $e) {
            error_log("数据库错误：" . $e->getMessage());
            throw new Exception("创建用户失败，请稍后重试");
        }
    }

    // 私有验证方法：验证失败时抛出异常，由调用者决定如何处理
    private function validate($data) {
        $errors = [];
        if (empty($data['username'])) {
            $errors[] = '用户名不能为空';
        }
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    // 模拟数据库保存：实际项目中这里会操作数据库
    private function saveToDatabase($data) {
        // 模拟数据库操作
        return ['id' => 1, 'username' => $data['username']];
    }
}

echo "异常处理流程示例：\n";
$service = new UserService();
try {
    $user = $service->createUser(['username' => '']);
} catch (ValidationException $e) {
    echo "验证失败：" . implode(', ', $e->getErrors()) . "\n";
} catch (Exception $e) {
    echo "错误：" . $e->getMessage() . "\n";
}
echo "\n";


// =============================================
// 第八节：错误和异常的转换
// =============================================

echo "【第八节：错误和异常的转换】\n\n";

// 自定义异常类：用于将 PHP 错误包装成异常，统一用 try-catch 处理
class ErrorToException extends Exception {}

// 用匿名函数（闭包）作为错误处理器，将所有 PHP 错误转为异常抛出
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // 不处理 @ 抑制的错误
    if (error_reporting() === 0) {
        return false;
    }

    throw new ErrorToException($errstr, $errno);
});

echo "错误转异常示例：\n";
try {
    // 这会触发一个警告，并被转换为异常
    $result = 1 / 0;
} catch (ErrorToException $e) {
    echo "捕获到错误转换的异常：" . $e->getMessage() . "\n";
} finally {
    restore_error_handler();
}
echo "\n";


// =============================================
// 第九节：异常和错误处理的比较
// =============================================

echo "【第九节：异常和错误处理的比较】\n\n";

echo "错误 vs 异常：\n";
echo "┌─────────────┬─────────────────┬─────────────────┐\n";
echo "│ 特点        │ 错误（Error）   │ 异常（Exception）│\n";
echo "├─────────────┼─────────────────┼─────────────────┤\n";
echo "│ 触发方式    │ 自动触发        │ 手动抛出        │\n";
echo "│ 处理方式    │ error_handler   │ try-catch       │\n";
echo "│ 严重程度    │ 通常更严重      │ 可以是预期内的  │\n";
echo "│ 使用场景    │ 编程错误        │ 业务逻辑异常    │\n";
echo "│ 恢复性      │ 通常不可恢复    │ 通常可以恢复    │\n";
echo "└─────────────┴─────────────────┴─────────────────┘\n\n";

echo "PHP 7+ 中，很多错误变成了异常：\n";
echo "  TypeError → 类型错误\n";
echo "  DivisionByZeroError → 除零错误\n";
echo "  ArithmeticError → 算术错误\n";
echo "  ValueError → 值错误\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写一个自定义异常类 FileException，用于文件操作错误\n";
echo "  提示：继承 Exception 类，添加 private \$filename 属性\n";
echo "  提示：构造函数接收 message 和 filename，提供 getFilename() 方法\n\n";

echo "练习2：编写一个文件读取函数，使用异常处理各种错误情况\n";
echo "  提示：检查文件是否存在（FileNotFoundException）、是否可读（FilePermissionException）\n";
echo "  提示：用 try-catch 捕获不同的异常类型，给出友好的错误信息\n\n";

echo "练习3：编写一个配置文件加载器，使用异常处理配置错误\n";
echo "  提示：检查文件是否存在，用 json_decode() 解析，用 json_last_error() 检查格式\n";
echo "  提示：配置项不存在时返回默认值，而不是抛异常\n\n";

echo "练习4：编写一个简单的错误日志系统，支持不同的日志级别\n";
echo "  提示：定义 DEBUG=0, INFO=1, WARNING=2, ERROR=3 四个级别常量\n";
echo "  提示：低于设定级别的日志不写入，用 file_put_contents(FILE_APPEND) 写日志\n\n";

echo "练习5：编写一个数据库操作类，使用异常处理数据库错误\n";
echo "  提示：连接失败抛 DatabaseConnectionException，查询失败抛 DatabaseQueryException\n";
echo "  提示：自定义异常类可以携带 SQL 语句和参数信息，方便调试\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";

// 文件异常基类：携带文件名信息，子类可以进一步细分错误类型
class FileException extends Exception {
    private $filename;

    public function __construct($message, $filename = '', $code = 0, Exception $previous = null) {
        $this->filename = $filename;
        parent::__construct($message, $code, $previous);
    }

    public function getFilename() {
        return $this->filename;
    }
}

class FileNotFoundException extends FileException {}
class FilePermissionException extends FileException {}
class FileFormatException extends FileException {}

echo "自定义文件异常类已定义\n\n";

// 练习2
echo "--- 练习2答案 ---\n";

// 安全读取文件：逐步检查文件是否存在、是否可读，每步抛出不同异常
function readFileSafe($filename) {
    if (!file_exists($filename)) {
        throw new FileNotFoundException("文件不存在", $filename);
    }

    if (!is_readable($filename)) {
        throw new FilePermissionException("文件不可读", $filename);
    }

    $content = file_get_contents($filename);
    if ($content === false) {
        throw new FileException("读取文件失败", $filename);
    }

    return $content;
}

// 测试
try {
    $content = readFileSafe('nonexistent.txt');
} catch (FileNotFoundException $e) {
    echo "文件未找到：" . $e->getMessage() . " ({$e->getFilename()})\n";
} catch (FilePermissionException $e) {
    echo "权限错误：" . $e->getMessage() . "\n";
} catch (FileException $e) {
    echo "文件错误：" . $e->getMessage() . "\n";
}
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";

class ConfigException extends Exception {}

// 配置加载器类：加载 JSON 配置文件，支持默认值回退
class Config {
    private $data = [];

    public function load($file) {
        if (!file_exists($file)) {
            throw new ConfigException("配置文件不存在：$file");
        }

        $content = file_get_contents($file);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConfigException("配置文件格式错误：" . json_last_error_msg());
        }

        $this->data = $data;
    }

    public function get($key, $default = null) {
        return $this->data[$key] ?? $default;
    }
}

echo "配置加载器已定义\n\n";

// 练习4
echo "--- 练习4答案 ---\n";

// 日志类：支持 DEBUG/INFO/WARNING/ERROR 四个级别，低于设定级别的日志会被忽略
class Logger {
    const DEBUG = 0;
    const INFO = 1;
    const WARNING = 2;
    const ERROR = 3;

    private $file;
    private $level;

    private $levels = [
        self::DEBUG => 'DEBUG',
        self::INFO => 'INFO',
        self::WARNING => 'WARNING',
        self::ERROR => 'ERROR'
    ];

    public function __construct($file, $level = self::DEBUG) {
        $this->file = $file;
        $this->level = $level;
    }

    public function log($message, $level = self::INFO) {
        if ($level < $this->level) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $levelName = $this->levels[$level];
        $entry = "[$timestamp] [$levelName] $message" . PHP_EOL;

        file_put_contents($this->file, $entry, FILE_APPEND);
    }

    public function debug($message) { $this->log($message, self::DEBUG); }
    public function info($message) { $this->log($message, self::INFO); }
    public function warning($message) { $this->log($message, self::WARNING); }
    public function error($message) { $this->log($message, self::ERROR); }
}

// 使用示例
$logger = new Logger('app.log', Logger::INFO);
$logger->debug("这条不会记录");  // 低于INFO级别
$logger->info("应用启动");
$logger->warning("内存使用过高");
$logger->error("数据库连接失败");
echo "日志已写入\n\n";

// 练习5
echo "--- 练习5答案 ---\n";

class DatabaseException extends Exception {
    private $operation;
    private $params;

    public function __construct($message, $operation = '', $params = [], $code = 0, Exception $previous = null) {
        $this->operation = $operation;
        $this->params = $params;
        parent::__construct($message, $code, $previous);
    }

    public function getOperation() { return $this->operation; }
    public function getParams() { return $this->params; }
}

class DatabaseConnectionException extends DatabaseException {}
class DatabaseQueryException extends DatabaseException {}

// 数据库操作类（基于文件模拟）：连接失败抛 DatabaseConnectionException，
// 查询失败抛 DatabaseQueryException，携带操作和参数信息
class Database {
    private $dataFile;
    private $connected = false;
    private $data = [];

    public function __construct($dataFile) {
        $dir = dirname($dataFile);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new DatabaseConnectionException(
                "数据库连接失败：无法创建目录 {$dir}",
                'connect',
                ['file' => $dataFile]
            );
        }

        $this->dataFile = $dataFile;

        // 加载已有数据
        if (file_exists($dataFile)) {
            $content = file_get_contents($dataFile);
            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new DatabaseConnectionException(
                    "数据库连接失败：文件格式损坏 — " . json_last_error_msg(),
                    'connect',
                    ['file' => $dataFile]
                );
            }
            $this->data = $decoded;
        }

        $this->connected = true;
    }

    public function insert($table, $record) {
        if (!$this->connected) {
            throw new DatabaseQueryException("未连接到数据库", 'insert', ['table' => $table]);
        }

        if (!is_array($record) || empty($record)) {
            throw new DatabaseQueryException("插入失败：记录不能为空", 'insert', ['table' => $table]);
        }

        if (!isset($this->data[$table])) {
            $this->data[$table] = [];
        }

        $this->data[$table][] = $record;
        $this->save();

        return true;
    }

    public function findAll($table) {
        if (!$this->connected) {
            throw new DatabaseQueryException("未连接到数据库", 'findAll', ['table' => $table]);
        }

        if (!isset($this->data[$table])) {
            throw new DatabaseQueryException("查询失败：表 '{$table}' 不存在", 'findAll', ['table' => $table]);
        }

        return $this->data[$table];
    }

    private function save() {
        $json = json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($this->dataFile, $json) === false) {
            throw new DatabaseQueryException("保存失败：无法写入文件", 'save', ['file' => $this->dataFile]);
        }
    }
}

// 测试
try {
    $db = new Database(__DIR__ . '/test_db.json');
    $db->insert('users', ['name' => '小明', 'age' => 25]);
    $db->insert('users', ['name' => '小红', 'age' => 22]);
    $users = $db->findAll('users');
    echo "查询到 " . count($users) . " 条记录\n";
    // 清理测试文件
    @unlink(__DIR__ . '/test_db.json');
} catch (DatabaseConnectionException $e) {
    echo "连接错误：" . $e->getMessage() . " (操作：{$e->getOperation()})\n";
} catch (DatabaseQueryException $e) {
    echo "查询错误：" . $e->getMessage() . " (操作：{$e->getOperation()})\n";
}

echo "数据库异常处理类已定义\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - try-catch-finally是异常处理的标准结构，catch要捕获具体异常类型
 * - 自定义异常类继承Exception，可以携带额外信息（如错误列表、SQL语句）
 * - set_error_handler()可以将PHP错误转换为异常统一处理
 *
 * 常见陷阱：
 * - 不要用异常做正常流程控制，异常只用于"异常情况"
 * - catch(Exception $e)太宽泛，应该catch具体的异常类型
 * - 生产环境不要display_errors=1，要用log_errors记录到日志文件
 *
 * 下节课预告：
 * - 第14课我们将学习数据库操作（PDO），掌握Web应用的数据存储
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第13课！\n";
echo "   下节课：数据库操作（PDO）—— 数据的仓库\n";
echo "============================================================\n";
