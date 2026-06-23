<?php
// =============================================
// 第 08 课：超全局变量
// =============================================
// 上节课我们学了函数，完成了基础篇。
// 这节课我们要学习超全局变量，开始与Web服务器交互。

echo "============================================================\n";
echo "   第08课：超全局变量\n";
echo "============================================================\n\n";

// 【生活类比】
// 超全局变量就像学校的"公告栏"——无论你在哪间教室（函数），
// 走到走廊里都能看到公告栏上的信息，不需要任何人的许可。
// $_GET 就像别人写在纸条上递给你的内容（URL参数），
// $_POST 就像你填好表格交给老师的资料（表单数据），
// $_SESSION 就像老师手里的学生档案夹，记录了你从入学到现在的所有信息。

// =============================================
// 第一节：什么是超全局变量？
// =============================================

echo "【第一节：什么是超全局变量？】\n\n";

// 超全局变量的特点：
// 1. 在任何地方都可以访问（函数内、类内、全局）
// 2. 始终可用，不需要 global 关键字
// 3. 以 $_ 开头（除了$GLOBALS）
// 4. 都是数组

echo "PHP超全局变量列表：\n";
echo "1. \$GLOBALS - 所有全局变量\n";
echo "2. \$_SERVER - 服务器和请求信息\n";
echo "3. \$_GET - URL参数（GET请求）\n";
echo "4. \$_POST - 表单数据（POST请求）\n";
echo "5. \$_REQUEST - GET + POST + COOKIE\n";
echo "6. \$_FILES - 文件上传信息\n";
echo "7. \$_COOKIE - Cookie数据\n";
echo "8. \$_SESSION - Session数据\n";
echo "9. \$_ENV - 环境变量\n\n";


// =============================================
// 第二节：$GLOBALS
// =============================================

echo "【第二节：\$GLOBALS】\n\n";

// $GLOBALS 包含所有全局变量
$global_var = "我是全局变量";
$another_global = 100;

// 这个函数演示如何在函数内部通过 $GLOBALS 访问和修改全局变量
// 注意：普通函数内无法直接访问全局变量，但 $GLOBALS 始终可用
function testGlobals() {
    echo "函数内访问全局变量：\n";
    echo "  \$GLOBALS['global_var'] = " . $GLOBALS['global_var'] . "\n";
    echo "  \$GLOBALS['another_global'] = " . $GLOBALS['another_global'] . "\n";

    // 可以修改全局变量
    $GLOBALS['global_var'] = "被函数修改了";
}

testGlobals();
echo "\n修改后：$global_var\n\n";


// =============================================
// 第三节：$_SERVER
// =============================================

echo "【第三节：\$_SERVER】\n\n";

// $_SERVER 包含服务器和请求信息
echo "常用 \$_SERVER 变量：\n";

// 注意：这些值在CLI模式下可能为空
$server_vars = [
    'SERVER_NAME' => '服务器名称',
    'SERVER_ADDR' => '服务器IP',
    'SERVER_PORT' => '服务器端口',
    'REQUEST_METHOD' => '请求方法',
    'REQUEST_URI' => '请求URI',
    'QUERY_STRING' => '查询字符串',
    'HTTP_HOST' => '主机名',
    'HTTP_USER_AGENT' => '用户代理',
    'REMOTE_ADDR' => '客户端IP',
    'DOCUMENT_ROOT' => '文档根目录',
    'SCRIPT_FILENAME' => '脚本文件名',
    'PHP_SELF' => '当前脚本路径',
];

foreach ($server_vars as $key => $desc) {
    // 这里使用了 ??（null合并运算符），如果左边是 null 就返回右边的默认值
    // 等价于 isset($_SERVER[$key]) ? $_SERVER[$key] : 'N/A'，但更简洁
    $value = $_SERVER[$key] ?? 'N/A';
    echo "  $desc ($key): $value\n";
}
echo "\n";


// =============================================
// 第四节：$_GET
// =============================================

echo "【第四节：\$_GET】\n\n";

// $_GET 用于获取URL参数
// 例如：http://example.com/page.php?name=小明&age=25

echo "\$_GET 用法示例：\n";
echo "URL: page.php?name=小明&age=25&city=北京\n";
echo "代码：\n";
echo "  \$name = \$_GET['name'] ?? '默认值';\n";
echo "  \$age = \$_GET['age'] ?? 0;\n\n";

// 因为在命令行（CLI）模式下运行，没有真实的URL参数
// 所以这里手动模拟 $_GET 数据，实际Web环境中这些数据由浏览器自动传入
$_GET['name'] = '小明';
$_GET['age'] = 25;
$_GET['city'] = '北京';

echo "模拟获取 GET 参数：\n";
echo "  姓名：" . ($_GET['name'] ?? '未知') . "\n";
echo "  年龄：" . ($_GET['age'] ?? '未知') . "\n";
echo "  城市：" . ($_GET['city'] ?? '未知') . "\n\n";

// 安全注意事项
echo "安全注意事项：\n";
echo "1. 永远不要信任用户输入\n";
echo "2. 使用 isset() 检查变量是否存在\n";
echo "3. 使用 filter_var() 过滤输入\n";
echo "4. 使用 htmlspecialchars() 防止XSS\n\n";


// =============================================
// 第五节：$_POST
// =============================================

echo "【第五节：\$_POST】\n\n";

// $_POST 用于获取表单POST数据
echo "\$_POST 用法示例：\n";
echo "HTML表单：\n";
echo '  <form method="POST" action="page.php">' . "\n";
echo '    <input type="text" name="username">' . "\n";
echo '    <input type="password" name="password">' . "\n";
echo '    <button type="submit">提交</button>' . "\n";
echo '  </form>' . "\n\n";

echo "PHP代码：\n";
echo '  $username = $_POST["username"] ?? "";' . "\n";
echo '  $password = $_POST["password"] ?? "";' . "\n\n";

// 同样在CLI模式下模拟 $_POST 数据
// 在实际Web应用中，这些数据来自 HTML 表单的 method="POST" 提交
$_POST['username'] = 'admin';
$_POST['password'] = '123456';

echo "模拟获取 POST 数据：\n";
echo "  用户名：" . ($_POST['username'] ?? '') . "\n";
echo "  密码：" . ($_POST['password'] ?? '') . "\n\n";


// =============================================
// 第六节：$_REQUEST
// =============================================

echo "【第六节：\$_REQUEST】\n\n";

// $_REQUEST 包含 $_GET, $_POST, $_COOKIE 的数据
echo "\$_REQUEST 特点：\n";
echo "1. 包含 GET、POST、COOKIE 数据\n";
echo "2. 顺序由 php.ini 中 variables_order 决定\n";
echo "3. 如果同名，后面的会覆盖前面的\n";
echo "4. 建议明确使用 \$_GET 或 \$_POST\n\n";

// 模拟同一参数名分别通过 GET、POST、COOKIE 传递
// $_REQUEST 会按 php.ini 中 variables_order 的顺序合并这些数据
$_GET['test'] = 'from_get';
$_POST['test'] = 'from_post';
$_COOKIE['test'] = 'from_cookie';

echo "GET: " . $_GET['test'] . "\n";
echo "POST: " . $_POST['test'] . "\n";
echo "COOKIE: " . $_COOKIE['test'] . "\n";
echo "REQUEST: " . $_REQUEST['test'] . "（取决于 variables_order）\n\n";


// =============================================
// 第七节：$_FILES
// =============================================

echo "【第七节：\$_FILES】\n\n";

// $_FILES 用于文件上传
echo "\$_FILES 结构：\n";
echo '  $_FILES["file"]["name"]     - 原始文件名' . "\n";
echo '  $_FILES["file"]["type"]     - MIME类型' . "\n";
echo '  $_FILES["file"]["tmp_name"] - 临时文件路径' . "\n";
echo '  $_FILES["file"]["error"]    - 错误码' . "\n";
echo '  $_FILES["file"]["size"]     - 文件大小（字节）' . "\n\n";

echo "文件上传HTML：\n";
echo '  <form method="POST" enctype="multipart/form-data">' . "\n";
echo '    <input type="file" name="upload">' . "\n";
echo '    <button type="submit">上传</button>' . "\n";
echo '  </form>' . "\n\n";

echo "文件上传PHP处理：\n";
echo <<<'CODE'
  if (isset($_FILES['upload'])) {
      $file = $_FILES['upload'];

      // 检查错误
      if ($file['error'] === UPLOAD_ERR_OK) {
          // 检查文件类型
          $allowed = ['image/jpeg', 'image/png', 'image/gif'];
          if (in_array($file['type'], $allowed)) {
              // 移动文件
              $destination = 'uploads/' . $file['name'];
              move_uploaded_file($file['tmp_name'], $destination);
          }
      }
  }
CODE;
echo "\n\n";


// =============================================
// 第八节：$_SESSION
// =============================================

echo "【第八节：\$_SESSION】\n\n";

// Session 用于存储用户会话数据
echo "Session 工作原理：\n";
echo "1. 用户访问网站，服务器创建 Session ID\n";
echo "2. Session ID 存储在 Cookie 中\n";
echo "3. 服务器用 Session ID 存取用户数据\n";
echo "4. 用户关闭浏览器，Session 失效\n\n";

echo "Session 使用示例：\n";
echo <<<'CODE'
  // 启动 Session
  session_start();

  // 存储数据
  $_SESSION['username'] = '小明';
  $_SESSION['login_time'] = date('Y-m-d H:i:s');

  // 读取数据
  $username = $_SESSION['username'] ?? '访客';

  // 删除 Session 数据
  unset($_SESSION['username']);

  // 销毁整个 Session
  session_destroy();
CODE;
echo "\n\n";


// =============================================
// 第九节：$_COOKIE
// =============================================

echo "【第九节：\$_COOKIE】\n\n";

// Cookie 用于在客户端存储数据
echo "Cookie 特点：\n";
echo "1. 存储在用户浏览器中\n";
echo "2. 可以设置过期时间\n";
echo "3. 每次请求都会发送到服务器\n";
echo "4. 大小限制 4KB\n\n";

echo "Cookie 使用示例：\n";
echo <<<'CODE'
  // 设置 Cookie（有效期1小时）
  setcookie('username', '小明', time() + 3600, '/');

  // 设置 HttpOnly Cookie（更安全）
  setcookie('token', 'abc123', time() + 3600, '/', '', true, true);

  // 读取 Cookie
  $username = $_COOKIE['username'] ?? '访客';

  // 删除 Cookie
  setcookie('username', '', time() - 3600, '/');
CODE;
echo "\n\n";


// =============================================
// 第十节：$_ENV
// =============================================

echo "【第十节：\$_ENV】\n\n";

// $_ENV 包含环境变量
echo "\$_ENV 示例：\n";
// $_ENV 存储环境变量，但在很多服务器配置中可能为空
// 实际项目中建议使用 getenv() 函数或 .env 文件来管理环境变量
foreach ($_ENV as $key => $value) {
    // 只显示部分，避免输出敏感信息
    if (in_array($key, ['PATH', 'HOME', 'USER'])) {
        echo "  $key: $value\n";
    }
}
echo "\n";


// =============================================
// 第十一节：安全最佳实践
// =============================================

echo "【第十一节：安全最佳实践】\n\n";

echo "超全局变量安全规则：\n";
echo "1. 永远不要信任用户输入\n";
echo "2. 使用 isset() 或 ?? 检查变量\n";
echo "3. 使用 filter_var() 过滤输入\n";
echo "4. 使用 htmlspecialchars() 输出到HTML\n";
echo "5. 使用 prepared statements 防止SQL注入\n";
echo "6. 敏感数据不要放在 GET 参数中\n";
echo "7. Cookie 设置 HttpOnly 和 Secure 标志\n\n";

// 输入过滤示例
echo "输入过滤示例：\n";

// 下面演示 XSS（跨站脚本攻击）的防护方法
// XSS 攻击：攻击者在页面中注入恶意 JavaScript 代码，窃取用户信息
$user_input = '<script>alert("XSS")</script>';

// 不安全的输出
echo "不安全输出：$user_input\n";

// 安全的输出
echo "安全输出：" . htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8') . "\n\n";

// 邮箱验证
echo "邮箱验证：\n";
$email = "test@example.com";
// filter_var() 是PHP内置的过滤函数，FILTER_VALIDATE_EMAIL 是邮箱验证过滤器
// 验证成功返回邮箱字符串，失败返回 false
$filtered = filter_var($email, FILTER_VALIDATE_EMAIL);
echo "原始：$email\n";
echo "过滤后：" . ($filtered ?: '无效邮箱') . "\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写代码，安全地获取 \$_GET['page'] 参数，默认值为 1\n";
echo "  提示：用 filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) 过滤并验证\n";
echo "  提示：用 ?? 1 设置默认值，用 max(1, \$page) 确保大于0\n\n";

echo "练习2：编写代码，检查用户是否已登录（检查 \$_SESSION['user_id']）\n";
echo "  提示：用 isset(\$_SESSION['user_id']) && !empty(\$_SESSION['user_id']) 判断\n";
echo "  提示：可以封装成一个函数 isLoggedIn() 返回布尔值\n\n";

echo "练习3：编写代码，获取客户端IP地址\n";
echo "  提示：\$_SERVER['REMOTE_ADDR'] 是基本方法\n";
echo "  提示：如果有代理，检查 HTTP_CLIENT_IP 和 HTTP_X_FORWARDED_FOR 头\n\n";

echo "练习4：编写一个函数，安全地输出HTML内容（防止XSS）\n";
echo "  提示：用 htmlspecialchars(\$content, ENT_QUOTES, 'UTF-8') 转义特殊字符\n";
echo "  提示：ENT_QUOTES 会同时转义单引号和双引号\n\n";

echo "练习5：编写代码，设置一个有效期为7天的"记住我"Cookie\n";
echo "  提示：setcookie('remember', \$value, time() + 7*24*60*60, '/')\n";
echo "  提示：安全起见加上 secure=true 和 httponly=true 参数\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
// filter_input() 从外部输入源获取数据并过滤，比直接用 $_GET 更安全
// INPUT_GET 表示从 GET 参数获取，FILTER_VALIDATE_INT 验证是否为整数
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
$page = max(1, $page);  // 确保大于0
echo "当前页：$page\n\n";

// 练习2
echo "--- 练习2答案 ---\n";
// session_start();  // 实际使用时需要启动
// 这个函数用于检查用户是否已登录，通过判断 session 中是否有 user_id
// 常用于需要登录才能访问的页面做权限检查
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// 模拟
$_SESSION['user_id'] = 123;
echo "用户已登录：" . (isLoggedIn() ? '是' : '否') . "\n\n";

// 练习3
echo "--- 练习3答案 ---\n";
// 这个函数用于获取客户端真实IP地址
// 因为用户可能通过代理服务器访问，所以需要按优先级检查不同的HTTP头
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

echo "客户端IP：" . getClientIP() . "\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
// 这个函数用于安全地输出HTML内容，防止XSS攻击
// htmlspecialchars() 将特殊字符转换为HTML实体：< 变成 &lt;，> 变成 &gt; 等
function safeHtml($content) {
    return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
}

$dangerous = '<img src="x" onerror="alert(1)">';
echo "原始：$dangerous\n";
echo "安全：" . safeHtml($dangerous) . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
// 这个函数用于设置"记住我"功能的Cookie
// 设置两个Cookie：用户ID和一个随机token，用于无密码自动登录
// secure=true 表示只在HTTPS下发送，httponly=true 防止JavaScript读取
function setRememberMe($user_id, $token) {
    $expire = time() + (7 * 24 * 60 * 60);  // 7天
    setcookie('remember_user', $user_id, $expire, '/', '', true, true);
    setcookie('remember_token', $token, $expire, '/', '', true, true);
}

echo "设置记住我Cookie：\n";
echo "  setcookie('remember_user', \$user_id, 7天后过期, '/', '', secure, httpOnly)\n";
echo "  setcookie('remember_token', \$token, 7天后过期, '/', '', secure, httpOnly)\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 超全局变量在任何地方都可访问：$_GET/$_POST/$_SESSION/$_COOKIE/$_SERVER
 * - $_GET用于URL参数，$_POST用于表单数据，$_SESSION存储在服务器
 * - filter_var()可以过滤和验证用户输入
 *
 * 常见陷阱：
 * - 永远不要信任用户输入，$_GET/$_POST的数据必须验证和过滤
 * - $_REQUEST包含GET+POST+COOKIE，同名时会覆盖，建议明确用$_GET或$_POST
 * - Session存储在服务器安全，Cookie存储在客户端可被篡改
 *
 * 下节课预告：
 * - 第09课我们将学习表单处理，构建真正的用户交互功能
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第08课！\n";
echo "   下节课：表单处理 —— 用户交互的桥梁\n";
echo "============================================================\n";
