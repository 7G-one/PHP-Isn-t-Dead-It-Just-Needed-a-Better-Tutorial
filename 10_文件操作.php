<?php
// =============================================
// 第 10 课：文件操作
// =============================================
// 上节课我们学了表单处理。
// 这节课我们要学习文件操作，让程序能够读写文件和目录。

echo "============================================================\n";
echo "   第10课：文件操作\n";
echo "============================================================\n\n";

// 【生活类比】
// 文件操作就像"读写笔记本"——
// fopen() 就是打开笔记本，fclose() 就是合上笔记本，
// fread() 就是读里面的内容，fwrite() 就是在上面写字，
// file_get_contents() 就是把整本笔记一口气看完，
// file_put_contents() 就是直接把内容写到新一页。
// 打开模式（r/w/a）就像你告诉别人：我只想看、我要重写、还是我只想追加。

// =============================================
// 第一节：文件读取
// =============================================

echo "【第一节：文件读取】\n\n";

// 创建测试文件
// file_put_contents(文件路径, 内容) 是最简单的写文件方式
// 它会自动创建文件（如果不存在），自动打开/写入/关闭，一步到位
$test_file = 'test.txt';
file_put_contents($test_file, "Hello, PHP!\n这是第二行\n这是第三行\n");

// 方式1：file_get_contents() - 读取整个文件
echo "方式1 - file_get_contents()：\n";
$content = file_get_contents($test_file);
echo $content . "\n";

// 方式2：fopen/fread/fclose - 逐块读取
echo "方式2 - fopen/fread/fclose()：\n";
// fopen() 返回一个"文件句柄"（类似文件的遥控器）
// 如果打开失败（文件不存在等），返回 false，所以要先检查
$handle = fopen($test_file, 'r');
if ($handle) {
    // feof() 检查是否到达文件末尾（End Of File）
    while (!feof($handle)) {
        // fgets() 每次读取一行内容
        $line = fgets($handle);
        echo "  读取：$line";
    }
    fclose($handle);
}
echo "\n";

// 方式3：file() - 按行读取为数组
echo "方式3 - file()：\n";
// file() 把文件按行拆分成数组
// FILE_IGNORE_NEW_LINES 表示去掉每行末尾的换行符 \n
$lines = file($test_file, FILE_IGNORE_NEW_LINES);
foreach ($lines as $line_num => $line) {
    echo "  行 $line_num: $line\n";
}
echo "\n";

// 方式4：readfile() - 直接输出文件
echo "方式4 - readfile()：\n";
echo "  ";
readfile($test_file);
echo "\n";


// =============================================
// 第二节：文件写入
// =============================================

echo "【第二节：文件写入】\n\n";

// 方式1：file_put_contents() - 写入整个文件
echo "方式1 - file_put_contents()：\n";
file_put_contents($test_file, "新内容写入\n");
echo "  写入完成\n";

// 追加内容
// FILE_APPEND 表示"追加模式"——新内容写到文件末尾，不覆盖原有内容
file_put_contents($test_file, "追加的内容\n", FILE_APPEND);
echo "  追加完成\n";

// 方式2：fwrite() - 逐块写入
echo "方式2 - fwrite()：\n";
$handle = fopen($test_file, 'w');  // 'w' 模式会覆盖
if ($handle) {
    fwrite($handle, "第一行\n");
    fwrite($handle, "第二行\n");
    fclose($handle);
    echo "  写入完成\n";
}
echo "\n";

// 文件打开模式
echo "文件打开模式：\n";
echo "  'r'   - 只读，从开头\n";
echo "  'r+'  - 读写，从开头\n";
echo "  'w'   - 只写，清空文件\n";
echo "  'w+'  - 读写，清空文件\n";
echo "  'a'   - 只写，追加到末尾\n";
echo "  'a+'  - 读写，追加到末尾\n";
echo "  'x'   - 只写，文件必须不存在\n";
echo "  'c'   - 只写，不清空，创建如果不存在\n\n";


// =============================================
// 第三节：文件信息
// =============================================

echo "【第三节：文件信息】\n\n";

$file = $test_file;

echo "文件信息：\n";
echo "  文件存在：" . (file_exists($file) ? '是' : '否') . "\n";
echo "  是否是文件：" . (is_file($file) ? '是' : '否') . "\n";
echo "  是否可读：" . (is_readable($file) ? '是' : '否') . "\n";
echo "  是否可写：" . (is_writable($file) ? '是' : '否') . "\n";
echo "  文件大小：" . filesize($file) . " 字节\n";
echo "  最后修改时间：" . date('Y-m-d H:i:s', filemtime($file)) . "\n";
echo "  最后访问时间：" . date('Y-m-d H:i:s', fileatime($file)) . "\n";

// pathinfo() 把文件路径拆分成：目录名、文件名、扩展名、不带扩展名的文件名
// 返回一个关联数组，非常方便提取路径的各个部分
$path_info = pathinfo($file);
echo "\n路径信息：\n";
echo "  目录名：" . ($path_info['dirname'] ?? 'N/A') . "\n";
echo "  文件名：" . ($path_info['basename'] ?? 'N/A') . "\n";
echo "  扩展名：" . ($path_info['extension'] ?? 'N/A') . "\n";
echo "  文件名（无扩展名）：" . ($path_info['filename'] ?? 'N/A') . "\n\n";


// =============================================
// 第四节：目录操作
// =============================================

echo "【第四节：目录操作】\n\n";

// 创建目录
$test_dir = 'test_directory';
if (!is_dir($test_dir)) {
    mkdir($test_dir, 0755, true);  // true 表示递归创建
    echo "创建目录：$test_dir\n";
}

// 创建子目录
$sub_dir = $test_dir . '/sub1/sub2';
if (!is_dir($sub_dir)) {
    mkdir($sub_dir, 0755, true);
    echo "创建子目录：$sub_dir\n";
}

// 在目录中创建文件
file_put_contents("$test_dir/file1.txt", "文件1内容\n");
file_put_contents("$test_dir/file2.txt", "文件2内容\n");
file_put_contents("$test_dir/sub1/file3.txt", "文件3内容\n");

// 读取目录内容
echo "\n读取目录内容：\n";
// scandir() 返回目录下的所有文件和子目录
// 注意：结果中会包含 '.'（当前目录）和 '..'（上级目录），需要过滤掉
$items = scandir($test_dir);
foreach ($items as $item) {
    if ($item === '.' || $item === '..') continue;
    $type = is_dir("$test_dir/$item") ? '目录' : '文件';
    echo "  $item ($type)\n";
}

// 递归读取目录
echo "\n递归读取目录：\n";
// 递归扫描目录：遇到子目录就递归调用自身，实现树形遍历
// $prefix 用于缩进显示，让层级关系一目了然
function scanDirectory($dir, $prefix = '') {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = "$dir/$item";
        $type = is_dir($path) ? '📁' : '📄';
        echo "$prefix$type $item\n";

        if (is_dir($path)) {
            scanDirectory($path, $prefix . '  ');
        }
    }
}

scanDirectory($test_dir);
echo "\n";


// =============================================
// 第五节：文件操作函数
// =============================================

echo "【第五节：文件操作函数】\n\n";

// 复制文件
$src = "$test_dir/file1.txt";
$dst = "$test_dir/file1_copy.txt";
if (copy($src, $dst)) {
    echo "复制文件：$src → $dst\n";
}

// 重命名/移动文件
$new_name = "$test_dir/file1_renamed.txt";
if (rename($dst, $new_name)) {
    echo "重命名：$dst → $new_name\n";
}

// 删除文件
if (file_exists($new_name)) {
    unlink($new_name);
    echo "删除文件：$new_name\n";
}

// 删除目录（必须为空）
$sub2_dir = "$test_dir/sub1/sub2";
if (is_dir($sub2_dir)) {
    rmdir($sub2_dir);
    echo "删除目录：$sub2_dir\n";
}

// 递归删除目录：PHP 没有内置的递归删除函数，需要自己实现
// 策略：先删除目录里的所有文件，再删除空目录
function deleteDirectory($dir) {
    if (!is_dir($dir)) return;

    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $path = "$dir/$item";
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($dir);
}

// 清理测试目录
deleteDirectory($test_dir);
echo "递归删除目录：$test_dir\n\n";


// =============================================
// 第六节：CSV文件处理
// =============================================

echo "【第六节：CSV文件处理】\n\n";

// 写入CSV
$csv_file = 'test.csv';
$data = [
    ['姓名', '年龄', '城市'],
    ['小明', 25, '北京'],
    ['小红', 22, '上海'],
    ['小刚', 28, '广州']
];

// fputcsv() 把数组写成一行 CSV 格式（自动加逗号分隔和换行符）
$handle = fopen($csv_file, 'w');
foreach ($data as $row) {
    fputcsv($handle, $row);
}
fclose($handle);
echo "CSV文件已创建\n";

// 读取CSV
echo "\n读取CSV：\n";
// fgetcsv() 逐行读取 CSV，自动按逗号拆分成数组
// 读到文件末尾时返回 false，所以用 !== false 判断
$handle = fopen($csv_file, 'r');
while (($row = fgetcsv($handle)) !== false) {
    echo "  " . implode(', ', $row) . "\n";
}
fclose($handle);

// 另一种读取方式：先整体读入，再用 str_getcsv 逐行解析
// array_map 对数组每个元素应用函数，explode 按换行拆分
echo "\n使用 array 方式读取：\n";
$csv_content = file_get_contents($csv_file);
$rows = array_map('str_getcsv', explode("\n", trim($csv_content)));
print_r($rows);
echo "\n";


// =============================================
// 第七节：JSON文件处理
// =============================================

echo "【第七节：JSON文件处理】\n\n";

// 创建数据
$data = [
    'name' => '小明',
    'age' => 25,
    'hobbies' => ['编程', '阅读', '游戏'],
    'address' => [
        'city' => '北京',
        'district' => '海淀区'
    ]
];

// 写入JSON
$json_file = 'test.json';
// json_encode 把 PHP 数组转为 JSON 字符串
// JSON_PRETTY_PRINT 格式化输出（带缩进），JSON_UNESCAPED_UNICODE 不转义中文
$json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($json_file, $json_content);
echo "JSON文件已创建\n";
echo $json_content . "\n\n";

// json_decode 把 JSON 字符串转回 PHP 数组
// 第二个参数 true 表示返回关联数组（而不是 stdClass 对象）
$json_data = json_decode(file_get_contents($json_file), true);
echo "读取JSON：\n";
echo "  姓名：" . $json_data['name'] . "\n";
echo "  年龄：" . $json_data['age'] . "\n";
echo "  爱好：" . implode(', ', $json_data['hobbies']) . "\n";
echo "  城市：" . $json_data['address']['city'] . "\n\n";


// =============================================
// 第八节：文件上传处理
// =============================================

echo "【第八节：文件上传处理】\n\n";

// Heredoc 语法：<<<'标签名' 开始，单独一行写标签名结束
// 适合输出大段文本（HTML、代码等），比拼接字符串更清晰
echo "文件上传HTML表单：\n";
echo <<<'HTML'
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="upload">
    <button type="submit">上传</button>
</form>
HTML;
echo "\n\n";

echo "文件上传PHP处理：\n";
echo <<<'CODE'
if (isset($_FILES['upload'])) {
    $file = $_FILES['upload'];

    // 检查错误
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('上传错误');
    }

    // 检查文件类型
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($file['tmp_name']);

    if (!in_array($mime_type, $allowed_types)) {
        die('不支持的文件类型');
    }

    // 检查文件大小（2MB）
    if ($file['size'] > 2 * 1024 * 1024) {
        die('文件太大');
    }

    // 生成安全文件名
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safe_name = bin2hex(random_bytes(16)) . '.' . $extension;

    // 移动文件
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    move_uploaded_file($file['tmp_name'], $upload_dir . $safe_name);
}
CODE;
echo "\n\n";


// =============================================
// 第九节：实际应用
// =============================================

echo "【第九节：实际应用】\n\n";

// 应用1：简单的日志系统
echo "应用1 - 日志系统：\n";

// 日志函数：往文件末尾追加一条日志记录
// PHP_EOL 是跨平台的换行符（Windows=\r\n, Linux=\n）
// LOCK_EX 加排他锁，防止多进程同时写入导致日志混乱
function writeLog($message, $level = 'INFO') {
    $log_file = 'app.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

writeLog('应用启动');
writeLog('用户登录成功', 'INFO');
writeLog('数据库连接失败', 'ERROR');
writeLog('缓存过期', 'WARNING');

echo "日志已写入 app.log\n";
echo file_get_contents('app.log') . "\n";

// 应用2：配置文件管理
echo "应用2 - 配置文件管理：\n";

// 加载配置文件：从 JSON 文件读取配置，返回关联数组
// 文件不存在时返回空数组，避免报错
function loadConfig($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?? [];
}

// 保存配置文件：把数组写成格式化的 JSON（方便人类阅读和编辑）
// LOCK_EX 确保写入过程中不会被其他进程干扰
function saveConfig($file, $config) {
    $json = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $json, LOCK_EX);
}

// 使用示例
$config_file = 'config.json';
$config = loadConfig($config_file);
$config['database'] = [
    'host' => 'localhost',
    'name' => 'myapp',
    'user' => 'root',
    'pass' => ''
];
saveConfig($config_file, $config);

echo "配置文件内容：\n";
echo file_get_contents($config_file) . "\n";

// 清理测试文件
unlink($test_file);
unlink($csv_file);
unlink($json_file);
unlink('app.log');
unlink($config_file);
echo "测试文件已清理\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写一个函数，统计文件的行数、单词数、字符数\n";
echo "  提示：用 file_get_contents() 读取整个文件内容\n";
echo "  提示：行数用 substr_count(\$content, \"\\n\")，单词数用 str_word_count()，字符数用 strlen()\n\n";

echo "练习2：编写一个函数，读取CSV文件并返回关联数组\n";
echo "  提示：用 fopen() 打开，fgetcsv() 逐行读取\n";
echo "  提示：第一行作为键名（header），用 array_combine(\$headers, \$row) 组合\n\n";

echo "练习3：编写一个函数，备份文件（复制并添加时间戳）\n";
echo "  提示：用 pathinfo() 获取文件名和扩展名\n";
echo "  提示：用 date('Y-m-d_H-i-s') 生成时间戳，用 copy() 复制文件\n\n";

echo "练习4：编写一个简单的文件管理器，支持列出、创建、删除文件\n";
echo "  提示：列出用 scandir()，创建用 file_put_contents()，删除用 unlink()\n";
echo "  提示：用 is_dir() 和 is_file() 区分目录和文件\n\n";

echo "练习5：编写一个函数，将数组存储为PHP文件（可直接include）\n";
echo "  提示：用 var_export(\$data, true) 把数组转为可执行的PHP代码字符串\n";
echo "  提示：写入 \"<?php\\n\\n\\$array_name = \" . var_export(\$data, true) . \";\\n\"\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
// 统计文件信息：返回行数、单词数、字符数
// 注意行数 +1 是因为最后一行可能没有换行符
function fileStats($file) {
    if (!file_exists($file)) {
        return false;
    }

    $content = file_get_contents($file);
    $lines = substr_count($content, "\n") + 1;
    $words = str_word_count($content);
    $chars = strlen($content);

    return [
        'lines' => $lines,
        'words' => $words,
        'chars' => $chars
    ];
}

// 创建测试文件
$test = 'test_stats.txt';
file_put_contents($test, "Hello World\nThis is a test\nPHP is great\n");
$stats = fileStats($test);
echo "文件统计：\n";
echo "  行数：$stats[lines]\n";
echo "  单词数：$stats[words]\n";
echo "  字符数：$stats[chars]\n\n";

// 练习2
echo "--- 练习2答案 ---\n";
// CSV 转关联数组：第一行作为键名，后续行作为数据
// array_combine($headers, $row) 把两个等长数组合并为关联数组
function csvToArray($file, $has_header = true) {
    if (!file_exists($file)) {
        return false;
    }

    $rows = [];
    $headers = null;

    $handle = fopen($file, 'r');
    while (($row = fgetcsv($handle)) !== false) {
        if ($has_header && $headers === null) {
            $headers = $row;
            continue;
        }

        if ($headers) {
            $rows[] = array_combine($headers, $row);
        } else {
            $rows[] = $row;
        }
    }
    fclose($handle);

    return $rows;
}

// 创建测试CSV
$csv = 'test_csv.csv';
file_put_contents($csv, "姓名,年龄,城市\n小明,25,北京\n小红,22,上海\n");
$result = csvToArray($csv);
echo "CSV转数组：\n";
print_r($result);
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";
// 文件备份：复制原文件，在文件名中加上时间戳防止重名
// 例如 data.txt -> data_2025-01-15_14-30-00.txt
function backupFile($file) {
    if (!file_exists($file)) {
        return false;
    }

    $path_info = pathinfo($file);
    $timestamp = date('Y-m-d_H-i-s');
    $backup_name = "{$path_info['filename']}_{$timestamp}.{$path_info['extension']}";
    $backup_path = $path_info['dirname'] . '/' . $backup_name;

    return copy($file, $backup_path);
}

echo "备份文件：";
echo (backupFile($test) ? '成功' : '失败') . "\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
class SimpleFileManager {
    private $dir;

    public function __construct($dir) {
        $this->dir = rtrim($dir, '/');
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0755, true);
        }
    }

    public function listFiles() {
        $files = [];
        $items = scandir($this->dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = "$this->dir/$item";
            $files[] = [
                'name' => $item,
                'type' => is_dir($path) ? '目录' : '文件',
                'size' => is_file($path) ? filesize($path) : 0,
                'modified' => date('Y-m-d H:i:s', filemtime($path))
            ];
        }
        return $files;
    }

    public function createFile($name, $content = '') {
        return file_put_contents("$this->dir/$name", $content) !== false;
    }

    public function deleteFile($name) {
        $path = "$this->dir/$name";
        if (file_exists($path) && is_file($path)) {
            return unlink($path);
        }
        return false;
    }
}

$fm = new SimpleFileManager('test_fm');
$fm->createFile('test1.txt', '内容1');
$fm->createFile('test2.txt', '内容2');
echo "文件列表：\n";
foreach ($fm->listFiles() as $file) {
    echo "  {$file['name']} ({$file['type']}, {$file['size']} bytes)\n";
}
echo "\n";

// 练习5
echo "--- 练习5答案 ---\n";
// 把数组保存为 PHP 文件：var_export() 生成可执行的 PHP 代码字符串
// 这样生成的配置文件可以直接用 include 加载，比 JSON 更快
function arrayToPhpFile($file, $array_name, $data) {
    $export = var_export($data, true);
    $content = "<?php\n\n\$$array_name = $export;\n";
    return file_put_contents($file, $content) !== false;
}

$php_file = 'config.php';
arrayToPhpFile($php_file, 'config', [
    'database' => ['host' => 'localhost', 'name' => 'myapp'],
    'app' => ['name' => 'MyApp', 'version' => '1.0']
]);

echo "PHP配置文件已创建：\n";
echo file_get_contents($php_file);

// 清理
unlink($test);
unlink($csv);
unlink($php_file);
deleteDirectory('test_fm');


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - file_get_contents/file_put_contents是最简单的文件读写方式
 * - 目录操作用scandir/mkdir/rmdir，递归删除需要自定义函数
 * - JSON用json_encode/json_decode，CSV用fputcsv/fgetcsv
 *
 * 常见陷阱：
 * - 文件操作前必须检查file_exists()和is_readable()/is_writable()
 * - 写入时用LOCK_EX防止并发写入导致数据丢失
 * - 文件上传必须验证MIME类型和大小，不能只信扩展名
 *
 * 下节课预告：
 * - 第11课我们将学习面向对象编程（OOP），这是现代编程的核心思想
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第10课！\n";
echo "   下节课：面向对象编程（上）—— 类与对象\n";
echo "============================================================\n";
