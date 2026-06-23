<?php
// =============================================
// 第 09 课：表单处理
// =============================================
// 上节课我们学了超全局变量。
// 这节课我们要学习表单处理，这是Web应用与用户交互的核心。

echo "============================================================\n";
echo "   第09课：表单处理\n";
echo "============================================================\n\n";

// 【生活类比】
// 表单就像去银行"填表格"——你拿到一张表格（HTML表单），
// 填写姓名、金额等信息（用户输入），然后交给柜台（提交到服务器）。
// 银行工作人员会检查你填得对不对（数据验证），
// 如果有问题会让你重新填（返回错误信息），
// 确认无误后才会帮你办理业务（处理数据）。

// =============================================
// 第一节：HTML表单基础
// =============================================

echo "【第一节：HTML表单基础】\n\n";

// HTML表单的基本结构
echo "HTML表单基本结构：\n";
echo <<<'HTML'
<form method="POST" action="process.php">
    <!-- 文本输入 -->
    <label>用户名：</label>
    <input type="text" name="username" required>

    <!-- 密码输入 -->
    <label>密码：</label>
    <input type="password" name="password" required>

    <!-- 邮箱输入 -->
    <label>邮箱：</label>
    <input type="email" name="email" required>

    <!-- 单选按钮 -->
    <label>性别：</label>
    <input type="radio" name="gender" value="male"> 男
    <input type="radio" name="gender" value="female"> 女

    <!-- 复选框 -->
    <label>爱好：</label>
    <input type="checkbox" name="hobbies[]" value="reading"> 阅读
    <input type="checkbox" name="hobbies[]" value="coding"> 编程
    <input type="checkbox" name="hobbies[]" value="gaming"> 游戏

    <!-- 下拉选择 -->
    <label>城市：</label>
    <select name="city">
        <option value="">请选择</option>
        <option value="beijing">北京</option>
        <option value="shanghai">上海</option>
        <option value="guangzhou">广州</option>
    </select>

    <!-- 文本域 -->
    <label>简介：</label>
    <textarea name="bio" rows="4"></textarea>

    <!-- 隐藏字段 -->
    <input type="hidden" name="form_id" value="register">

    <!-- 提交按钮 -->
    <button type="submit">注册</button>
</form>
HTML;
echo "\n\n";


// =============================================
// 第二节：GET vs POST
// =============================================

echo "【第二节：GET vs POST】\n\n";

echo "GET 和 POST 的区别：\n";
echo "┌─────────────┬─────────────────┬─────────────────┐\n";
echo "│ 特点        │ GET             │ POST            │\n";
echo "├─────────────┼─────────────────┼─────────────────┤\n";
echo "│ 数据位置    │ URL参数         │ 请求体          │\n";
echo "│ 数据长度    │ 有限制(2KB)     │ 无限制          │\n";
echo "│ 安全性      │ 低(可见)        │ 较高(不可见)    │\n";
echo "│ 缓存        │ 可被缓存        │ 不会被缓存      │\n";
echo "│ 书签        │ 可收藏          │ 不可收藏        │\n";
echo "│ 用途        │ 查询、获取数据  │ 提交、修改数据  │\n";
echo "└─────────────┴─────────────────┴─────────────────┘\n\n";

echo "什么时候用 GET？\n";
echo "- 搜索查询：search.php?q=PHP\n";
echo "- 分页：page.php?page=2\n";
echo "- 筛选：products.php?category=electronics\n\n";

echo "什么时候用 POST？\n";
echo "- 用户注册/登录\n";
echo "- 提交评论\n";
echo "- 上传文件\n";
echo "- 修改数据\n\n";


// =============================================
// 第三节：表单处理流程
// =============================================

echo "【第三节：表单处理流程】\n\n";

// 完整的表单处理示例
echo "完整表单处理示例：\n\n";

// 因为在CLI模式下没有真实表单提交，所以手动模拟 $_POST 数据
// 实际Web环境中，这些数据由浏览器通过表单自动发送到服务器
$_POST = [
    'username' => '小明',
    'email' => 'xiaoming@example.com',
    'password' => '123456',
    'age' => '25',
    'gender' => 'male',
    'hobbies' => ['reading', 'coding'],
    'city' => 'beijing',
    'bio' => '我是一个程序员',
    'agree' => 'on'
];

// 第一步：检查请求方法是否为 POST
// $_SERVER['REQUEST_METHOD'] 记录了HTTP请求类型（GET/POST/PUT/DELETE等）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "收到POST请求\n";

    // 2. 获取表单数据
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $hobbies = $_POST['hobbies'] ?? [];
    $city = $_POST['city'] ?? '';
    $bio = $_POST['bio'] ?? '';

    echo "表单数据：\n";
    echo "  用户名：$username\n";
    echo "  邮箱：$email\n";
    echo "  年龄：$age\n";
    echo "  性别：$gender\n";
    echo "  爱好：" . implode(', ', $hobbies) . "\n";
    echo "  城市：$city\n";
    echo "  简介：$bio\n\n";
}
echo "\n";


// =============================================
// 第四节：数据验证
// =============================================

echo "【第四节：数据验证】\n\n";

// 验证规则类：封装了常用的表单验证逻辑
// 使用方法：创建实例后，调用 validate() 方法逐个验证字段
// 最后通过 hasErrors() 和 getErrors() 获取验证结果
class Validator {
    private $errors = [];

    // 验证方法：$value 是待验证的值，$rules 是规则数组，$field_name 是字段名（用于错误提示）
    // 规则可以是字符串如 'required'，也可以是数组如 ['min', 3] 表示带参数的规则
    public function validate($value, $rules, $field_name) {
        foreach ($rules as $rule) {
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
            }

            // 处理带参数的规则，例如 ['min', 3] 表示最少3个字符
            if (is_array($rule)) {
                // 这里使用了数组解构语法 [$rule_name, $param] = $rule
                // 等价于 $rule_name = $rule[0]; $param = $rule[1];
                [$rule_name, $param] = $rule;
                switch ($rule_name) {
                    case 'min':
                        if (strlen($value) < $param) {
                            $this->errors[] = "$field_name 最少 $param 个字符";
                        }
                        break;
                    case 'max':
                        if (strlen($value) > $param) {
                            $this->errors[] = "$field_name 最多 $param 个字符";
                        }
                        break;
                    case 'min_value':
                        if ((float)$value < $param) {
                            $this->errors[] = "$field_name 不能小于 $param";
                        }
                        break;
                    case 'max_value':
                        if ((float)$value > $param) {
                            $this->errors[] = "$field_name 不能大于 $param";
                        }
                        break;
                }
            }
        }
    }

    // 检查是否有验证错误
    public function hasErrors() {
        return !empty($this->errors);
    }

    // 获取所有错误信息数组
    public function getErrors() {
        return $this->errors;
    }
}

// 使用验证器
echo "数据验证示例：\n";
$validator = new Validator();

// 模拟表单数据
$form_data = [
    'username' => 'ab',
    'email' => 'invalid-email',
    'age' => 'abc',
    'bio' => '短'
];

// 验证
$validator->validate($form_data['username'], ['required', ['min', 3], ['max', 20]], '用户名');
$validator->validate($form_data['email'], ['required', 'email'], '邮箱');
$validator->validate($form_data['age'], ['required', 'numeric', ['min_value', 1], ['max_value', 150]], '年龄');
$validator->validate($form_data['bio'], [['min', 10]], '简介');

if ($validator->hasErrors()) {
    echo "验证失败：\n";
    foreach ($validator->getErrors() as $error) {
        echo "  - $error\n";
    }
} else {
    echo "验证通过！\n";
}
echo "\n";


// =============================================
// 第五节：过滤用户输入
// =============================================

echo "【第五节：过滤用户输入】\n\n";

// PHP内置过滤函数
echo "PHP内置过滤函数：\n\n";

// filter_var() - 过滤单个值
echo "filter_var() 示例：\n";

// 过滤邮箱
$email = "test@example.com";
$filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
echo "邮箱过滤：" . ($filtered ?: '无效') . "\n";

// 过滤整数
$age = "25";
$filtered = filter_var($age, FILTER_VALIDATE_INT);
echo "整数过滤：" . ($filtered !== false ? $filtered : '无效') . "\n";

// 过滤URL
$url = "https://example.com";
$filtered = filter_var($url, FILTER_VALIDATE_URL);
echo "URL过滤：" . ($filtered ?: '无效') . "\n";

// 过滤IP
$ip = "192.168.1.1";
$filtered = filter_var($ip, FILTER_VALIDATE_IP);
echo "IP过滤：" . ($filtered ?: '无效') . "\n\n";

// filter_var_array() 可以一次性过滤多个值，比逐个调用 filter_var() 更高效
// 参数1是待过滤的数据数组，参数2是对应的过滤规则数组
echo "filter_var_array() 示例：\n";
$data = [
    'name' => '  小明  ',
    'email' => 'test@example.com',
    'age' => '25'
];

// 过滤规则可以是简单常量，也可以是带选项的数组
// FILTER_SANITIZE_STRING 会去除HTML标签，FILTER_VALIDATE_INT 验证整数并可设置范围
$filters = [
    'name' => ['filter' => FILTER_SANITIZE_STRING, 'flags' => FILTER_FLAG_STRIP_BACKTICK],
    'email' => FILTER_VALIDATE_EMAIL,
    'age' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1, 'max_range' => 150]]
];

$filtered_data = filter_var_array($data, $filters);
print_r($filtered_data);
echo "\n";


// =============================================
// 第六节：防注入基础
// =============================================

echo "【第六节：防注入基础】\n\n";

// SQL注入示例：这是Web安全中最常见的攻击方式之一
// 攻击者在输入中插入SQL语句片段，改变查询逻辑，绕过验证或窃取数据
$username = "admin' OR '1'='1";
$password = "' OR '1'='1";

// 不安全的SQL
$unsafe_sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
echo "不安全SQL：$unsafe_sql\n";
echo "这会导致查询所有用户！\n\n";

// 安全的做法
echo "安全做法：\n";
echo "1. 使用预处理语句（PDO/MySQLi）\n";
echo "2. 使用 htmlspecialchars() 输出\n";
echo "3. 使用 filter_var() 过滤\n";
echo "4. 使用白名单验证\n\n";

// XSS示例
echo "XSS风险：\n";
$user_input = '<script>alert("XSS攻击!")</script>';
echo "用户输入：$user_input\n";
echo "安全输出：" . htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8') . "\n\n";


// =============================================
// 第七节：完整表单处理示例
// =============================================

echo "【第七节：完整表单处理示例】\n\n";

// 用户注册表单处理
echo "用户注册表单处理：\n\n";

// 模拟POST数据
$_POST = [
    'username' => '小明',
    'email' => 'xiaoming@example.com',
    'password' => 'password123',
    'confirm_password' => 'password123',
    'age' => '25',
    'agree' => 'on'
];

// 这个函数处理用户注册的完整流程：验证 -> 清理 -> 返回结果
// 参数 $data 是表单提交的数据数组，返回包含 success 和 data/errors 的结果数组
function processRegistration($data) {
    $errors = [];

    // 验证用户名
    $username = trim($data['username'] ?? '');
    if (empty($username)) {
        $errors[] = '用户名不能为空';
    } elseif (strlen($username) < 3) {
        $errors[] = '用户名至少3个字符';
    } elseif (strlen($username) > 20) {
        $errors[] = '用户名最多20个字符';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = '用户名只能包含字母、数字、下划线';
    }

    // 验证邮箱
    $email = trim($data['email'] ?? '');
    if (empty($email)) {
        $errors[] = '邮箱不能为空';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '邮箱格式不正确';
    }

    // 验证密码
    $password = $data['password'] ?? '';
    if (empty($password)) {
        $errors[] = '密码不能为空';
    } elseif (strlen($password) < 6) {
        $errors[] = '密码至少6个字符';
    }

    // 确认密码
    $confirm_password = $data['confirm_password'] ?? '';
    if ($password !== $confirm_password) {
        $errors[] = '两次密码不一致';
    }

    // 验证年龄
    $age = $data['age'] ?? '';
    if (!empty($age)) {
        if (!is_numeric($age) || $age < 1 || $age > 150) {
            $errors[] = '年龄不合法';
        }
    }

    // 同意条款
    if (!isset($data['agree'])) {
        $errors[] = '必须同意用户协议';
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // 数据清理：验证通过后，对数据进行安全处理再存储
    // 这是"清理-验证-存储"三步安全流程的最后一步
    $clean_data = [
        'username' => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),  // 转义HTML字符防XSS
        'email' => filter_var($email, FILTER_SANITIZE_EMAIL),  // 清理邮箱中的非法字符
        // password_hash() 使用 bcrypt 算法加密密码，PASSWORD_DEFAULT 会自动选择最安全的算法
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'age' => (int)$age,
        'created_at' => date('Y-m-d H:i:s')
    ];

    return ['success' => true, 'data' => $clean_data];
}

// 处理注册
$result = processRegistration($_POST);

if ($result['success']) {
    echo "注册成功！\n";
    echo "用户信息：\n";
    foreach ($result['data'] as $key => $value) {
        if ($key !== 'password') {
            echo "  $key: $value\n";
        } else {
            echo "  $key: [已加密]\n";
        }
    }
} else {
    echo "注册失败：\n";
    foreach ($result['errors'] as $error) {
        echo "  - $error\n";
    }
}
echo "\n";


// =============================================
// 第八节：表单安全最佳实践
// =============================================

echo "【第八节：表单安全最佳实践】\n\n";

echo "表单安全清单：\n";
echo "1. 永远不要信任客户端数据\n";
echo "2. 服务端验证是必须的（JS验证只是用户体验）\n";
echo "3. 使用 prepared statements 防止SQL注入\n";
echo "4. 使用 htmlspecialchars() 防止XSS\n";
echo "5. 使用 CSRF Token 防止跨站请求伪造\n";
echo "6. 密码使用 password_hash() 和 password_verify()\n";
echo "7. 敏感操作需要重新验证密码\n";
echo "8. 设置合理的输入长度限制\n";
echo "9. 文件上传要验证类型和大小\n";
echo "10. 使用 HTTPS 传输敏感数据\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写一个函数，验证中国手机号格式（1开头，11位数字）\n";
echo "  提示：正则 /^1[3-9]\\d{9}$/ —— 第一位是1，第二位3-9，后面9位数字\n";
echo "  提示：用 preg_match() 返回 1 表示匹配成功\n\n";

echo "练习2：编写一个函数，验证身份证号格式（18位，最后一位可以是X）\n";
echo "  提示：正则 /^\\d{17}[\\dX]$/ —— 17位数字加1位数字或X\n";
echo "  提示：先用 strtoupper() 统一大写再验证\n\n";

echo "练习3：编写一个登录表单处理函数，验证用户名和密码\n";
echo "  提示：检查用户名和密码是否为空，返回错误数组\n";
echo "  提示：实际项目中用 password_verify() 验证密码哈希\n\n";

echo "练习4：编写一个函数，生成CSRF Token并验证\n";
echo "  提示：生成用 bin2hex(random_bytes(32))，存到 \$_SESSION 中\n";
echo "  提示：验证用 hash_equals(\$stored_token, \$submitted_token) 防止时序攻击\n\n";

echo "练习5：编写一个文件上传处理函数，验证文件类型和大小\n";
echo "  提示：用 finfo(FILEINFO_MIME_TYPE) 检查真实MIME类型，不要只信扩展名\n";
echo "  提示：检查 \$_FILES['upload']['error'] === UPLOAD_ERR_OK\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
// 这个函数用于验证中国大陆手机号格式
// 正则 /^1[3-9]\d{9}$/ 的含义：1开头，第二位3-9（排除旧号段），后面跟9位数字
function validateChinesePhone($phone) {
    return preg_match('/^1[3-9]\d{9}$/', $phone) === 1;
}

$phones = ['13812345678', '12345678901', '1381234567', '138123456789'];
foreach ($phones as $phone) {
    echo "$phone: " . (validateChinesePhone($phone) ? '有效' : '无效') . "\n";
}
echo "\n";

// 练习2
echo "--- 练习2答案 ---\n";
// 这个函数用于验证18位身份证号格式
// strtoupper() 统一大写处理，因为最后一位可能是 X（校验码）
function validateIDCard($id) {
    return preg_match('/^\d{17}[\dX]$/', strtoupper($id)) === 1;
}

$ids = ['11010119900101001X', '11010119900101001', '11010119900101001Y'];
foreach ($ids as $id) {
    echo "$id: " . (validateIDCard($id) ? '有效' : '无效') . "\n";
}
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";
// 这个函数处理用户登录验证
// 实际项目中，还需要查询数据库验证用户名和密码
function processLogin($data) {
    $errors = [];

    $username = trim($data['username'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($username)) {
        $errors[] = '用户名不能为空';
    }

    if (empty($password)) {
        $errors[] = '密码不能为空';
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // 这里应该查询数据库验证
    // $user = DB::findByUsername($username);
    // if ($user && password_verify($password, $user['password'])) {
    //     return ['success' => true, 'user' => $user];
    // }

    return ['success' => false, 'errors' => ['用户名或密码错误']];
}

// 测试
$login_data = ['username' => 'admin', 'password' => '123456'];
$result = processLogin($login_data);
echo "登录结果：" . ($result['success'] ? '成功' : '失败') . "\n";
if (!$result['success']) {
    foreach ($result['errors'] as $error) {
        echo "  - $error\n";
    }
}
echo "\n";

// 练习4
echo "--- 练习4答案 ---\n";
// 这个函数生成 CSRF Token（跨站请求伪造令牌）
// CSRF 攻击：诱导已登录用户在不知情的情况下发送恶意请求
// Token 存储在 Session 中，表单提交时验证 Token 是否匹配
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// 这个函数验证提交的 CSRF Token 是否与 Session 中存储的一致
function verifyCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // hash_equals() 用于安全比较两个字符串，防止"时序攻击"
    // 普通的 == 比较会在第一个不同字符处停止，攻击者可以通过响应时间推测Token
    return isset($_SESSION['csrf_token']) &&
           hash_equals($_SESSION['csrf_token'], $token);
}

$token = generateCSRFToken();
echo "生成Token：$token\n";
echo "验证Token：" . (verifyCSRFToken($token) ? '成功' : '失败') . "\n";
echo "验证错误Token：" . (verifyCSRFToken('wrong') ? '成功' : '失败') . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
// 这个函数处理文件上传的安全验证
// 参数：$file 是 $_FILES 中的文件数组，$allowed_types 是允许的MIME类型，$max_size 是最大字节数
// 返回包含验证结果和安全文件名的数组
function processFileUpload($file, $allowed_types = ['image/jpeg', 'image/png'], $max_size = 2 * 1024 * 1024) {
    $errors = [];

    // 检查上传错误
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => '文件太大（服务器限制）',
            UPLOAD_ERR_FORM_SIZE => '文件太大（表单限制）',
            UPLOAD_ERR_PARTIAL => '文件只上传了一部分',
            UPLOAD_ERR_NO_FILE => '没有文件被上传',
            UPLOAD_ERR_NO_TMP_DIR => '临时文件夹丢失',
            UPLOAD_ERR_CANT_WRITE => '写入磁盘失败'
        ];
        $errors[] = $error_messages[$file['error']] ?? '上传错误';
        return ['success' => false, 'errors' => $errors];
    }

    // 检查文件大小
    if ($file['size'] > $max_size) {
        $errors[] = '文件大小超过限制（' . ($max_size / 1024 / 1024) . 'MB）';
    }

    // 检查文件类型：使用 finfo 类检测真实MIME类型
    // 这比检查文件扩展名更安全，因为攻击者可以伪造扩展名
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);
    if (!in_array($mime_type, $allowed_types)) {
        $errors[] = '不支持的文件类型：' . $mime_type;
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // 生成安全文件名：使用随机字符串替代用户上传的原始文件名
    // 这样可以防止文件名注入攻击和中文文件名编码问题
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // random_bytes(16) 生成16字节的加密安全随机数，bin2hex() 转为32位十六进制字符串
    $safe_name = bin2hex(random_bytes(16)) . '.' . $extension;

    return [
        'success' => true,
        'filename' => $safe_name,
        'original_name' => $file['name'],
        'mime_type' => $mime_type,
        'size' => $file['size']
    ];
}

// 模拟文件上传
echo "文件上传验证函数已定义\n";
echo "支持的参数：\n";
echo "  - file: \$_FILES['upload']\n";
echo "  - allowed_types: 允许的MIME类型数组\n";
echo "  - max_size: 最大文件大小（字节）\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - GET用于查询/获取，POST用于提交/修改，表单处理要经过验证-过滤-存储流程
 * - filter_var()验证邮箱/URL/IP等，正则验证自定义格式
 * - password_hash()加密存储密码，password_verify()验证密码
 *
 * 常见陷阱：
 * - 只做前端JS验证是不够的，服务端验证是必须的
 * - SQL注入：永远用预处理语句，不要拼接用户输入到SQL
 * - XSS：输出用户内容时必须用htmlspecialchars()转义
 *
 * 下节课预告：
 * - 第10课我们将学习文件操作，让程序能够读写文件和目录
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第09课！\n";
echo "   下节课：文件操作 —— 让PHP读写文件\n";
echo "============================================================\n";
