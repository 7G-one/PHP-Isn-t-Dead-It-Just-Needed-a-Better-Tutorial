<?php
// =============================================
// 第 04 课：字符串操作
// =============================================
// 上节课我们学了函数基础。
// 这节课我们要学习字符串操作，这是Web开发中最常用的技能。

echo "============================================================\n";
echo "   第04课：字符串操作\n";
echo "============================================================\n\n";


// =============================================
// 第一节：字符串的定义方式
// =============================================
// 字符串就像一串珠子，每颗珠子是一个字符。

echo "【第一节：字符串的定义方式】\n\n";

// 方式1：单引号字符串
// 单引号中的内容原样输出，不会解析变量
$name = '小明';
echo '单引号：我叫$name\n';  // 输出：我叫$name（没有解析）
echo '单引号中的换行符\n不会生效' . "\n\n";

// 方式2：双引号字符串
// 双引号中会解析变量和转义字符
echo "双引号：我叫$name\n";  // 输出：我叫小明（解析了变量）
echo "双引号中的换行符\n会生效\n\n";

// 方式3：Heredoc语法（适合多行字符串）
// 类似于双引号，会解析变量
$sql = <<<SQL
SELECT *
FROM users
WHERE name = '$name'
AND age > 18
ORDER BY id DESC
SQL;
echo "Heredoc语法：\n$sql\n\n";

// 方式4：Nowdoc语法（PHP 5.3+）
// 类似于单引号，不会解析变量
$html = <<<'HTML'
<div class="container">
    <h1>Hello, $name!</h1>
    <p>This is a paragraph.</p>
</div>
HTML;
echo "Nowdoc语法：\n$html\n\n";


// =============================================
// 第二节：字符串长度与位置
// =============================================

echo "【第二节：字符串长度与位置】\n\n";

$str = "Hello, PHP World!";
echo "字符串：$str\n\n";

// strlen() - 字符串长度（字节数）
echo "strlen()：\n";
echo "长度：" . strlen($str) . " 字节\n";

// mb_strlen() - 多字节字符串长度（中文等）
$chinese = "你好世界";
echo "strlen(\"$chinese\") = " . strlen($chinese) . "（字节数，中文3字节/字）\n";
echo "mb_strlen(\"$chinese\") = " . mb_strlen($chinese, 'UTF-8') . "（字符数）\n\n";

// strpos() - 查找字符串首次出现的位置
echo "strpos()：\n";
echo "\"PHP\" 位置：" . strpos($str, "PHP") . "\n";
echo "\"World\" 位置：" . strpos($str, "World") . "\n";
echo "\"Java\" 位置：" . var_export(strpos($str, "Java"), true) . "（false表示未找到）\n";

// stripos() - 不区分大小写的查找
echo "stripos(\"php\")：" . stripos($str, "php") . "\n\n";

// strrpos() - 查找字符串最后一次出现的位置
$str2 = "Hello PHP, Hello World";
echo "strrpos()：\n";
echo "\"Hello\" 最后位置：" . strrpos($str2, "Hello") . "\n\n";

// strstr() - 获取子字符串
echo "strstr()：\n";
echo "从PHP开始：" . strstr($str, "PHP") . "\n";
echo "从PHP开始（之前部分）：" . strstr($str, "PHP", true) . "\n\n";


// =============================================
// 第三节：字符串截取
// =============================================

echo "【第三节：字符串截取】\n\n";

$str = "Hello, PHP World!";
echo "原字符串：$str\n\n";

// substr() - 截取子字符串
echo "substr()：\n";
echo "从位置7开始：" . substr($str, 7) . "\n";
echo "从位置7开始，取3个字符：" . substr($str, 7, 3) . "\n";
echo "从末尾倒数6个：" . substr($str, -6) . "\n";
echo "从位置0开始，到倒数7个：" . substr($str, 0, -7) . "\n\n";

// mb_substr() - 多字节字符串截取
$chinese = "你好世界，欢迎学习PHP";
echo "mb_substr()：\n";
echo "原字符串：$chinese\n";
echo "截取前4个字：" . mb_substr($chinese, 0, 4, 'UTF-8') . "\n\n";

// substr_replace() - 替换子字符串
echo "substr_replace()：\n";
echo "替换位置7开始的3个字符：" . substr_replace($str, "MySQL", 7, 3) . "\n\n";


// =============================================
// 第四节：字符串查找与替换
// =============================================

echo "【第四节：字符串查找与替换】\n\n";

$str = "Hello PHP, Hello World";
echo "原字符串：$str\n\n";

// str_replace() - 替换所有匹配
echo "str_replace()：\n";
echo "替换Hello为Hi：" . str_replace("Hello", "Hi", $str) . "\n";
echo "替换次数：" . str_replace("Hello", "Hi", $str, $count) . "（替换了$count次）\n\n";

// str_ireplace() - 不区分大小写替换
echo "str_ireplace()：\n";
echo "替换hello（不区分大小写）：" . str_ireplace("hello", "Hi", $str) . "\n\n";

// substr_count() - 统计子字符串出现次数
echo "substr_count()：\n";
echo "\"Hello\" 出现次数：" . substr_count($str, "Hello") . "\n\n";

// str_contains() - 检查是否包含（PHP 8.0+）
echo "str_contains()：\n";
echo "包含PHP：" . (str_contains($str, "PHP") ? '是' : '否') . "\n";
echo "包含Java：" . (str_contains($str, "Java") ? '是' : '否') . "\n\n";

// str_starts_with() 和 str_ends_with()（PHP 8.0+）
echo "str_starts_with() 和 str_ends_with()：\n";
echo "以Hello开头：" . (str_starts_with($str, "Hello") ? '是' : '否') . "\n";
echo "以World结尾：" . (str_ends_with($str, "World") ? '是' : '否') . "\n\n";


// =============================================
// 第五节：字符串大小写转换
// =============================================

echo "【第五节：字符串大小写转换】\n\n";

$str = "Hello PHP World";
echo "原字符串：$str\n";

echo "strtolower()：" . strtolower($str) . "\n";
echo "strtoupper()：" . strtoupper($str) . "\n";
echo "ucfirst()：" . ucfirst(strtolower($str)) . "\n";
echo "lcfirst()：" . lcfirst($str) . "\n";
echo "ucwords()：" . ucwords(strtolower($str)) . "\n\n";


// =============================================
// 第六节：字符串修剪与填充
// =============================================

echo "【第六节：字符串修剪与填充】\n\n";

$str = "  Hello PHP  ";
echo "原字符串：「$str」\n";
echo "trim()：「" . trim($str) . "」\n";
echo "ltrim()：「" . ltrim($str) . "」\n";
echo "rtrim()：「" . rtrim($str) . "」\n\n";

// 指定修剪字符
$str2 = "***Hello***";
echo "原字符串：$str2\n";
echo "trim('*')：" . trim($str2, '*') . "\n\n";

// 填充
$str = "PHP";
echo "str_pad()：\n";
echo "左填充：" . str_pad($str, 10, '-', STR_PAD_LEFT) . "\n";
echo "右填充：" . str_pad($str, 10, '-', STR_PAD_RIGHT) . "\n";
echo "两端填充：" . str_pad($str, 10, '-', STR_PAD_BOTH) . "\n\n";


// =============================================
// 第七节：字符串分割与合并
// =============================================

echo "【第七节：字符串分割与合并】\n\n";

// explode() - 分割字符串为数组
$str = "苹果,香蕉,橙子,葡萄";
echo "原字符串：$str\n";

$fruits = explode(",", $str);
echo "explode(',', ...)：\n";
print_r($fruits);

// limit参数
$fruits2 = explode(",", $str, 2);
echo "explode(',', ..., 2)：\n";
print_r($fruits2);

// implode() - 合并数组为字符串
echo "implode('-', ...)：" . implode("-", $fruits) . "\n\n";

// str_split() - 按字符分割
$str = "Hello";
echo "str_split('$str')：\n";
print_r(str_split($str));
echo "str_split('$str', 2)：\n";
print_r(str_split($str, 2));
echo "\n";


// =============================================
// 第八节：字符串格式化
// =============================================

echo "【第八节：字符串格式化】\n\n";

// printf() - 格式化输出
echo "printf()：\n";
printf("字符串：%s\n", "Hello");
printf("整数：%d\n", 42);
printf("浮点数：%.2f\n", 3.14159);
printf("十进制：%d, 十六进制：%x, 八进制：%o\n", 255, 255, 255);
printf("科学计数法：%e\n", 1234.56);
printf("百分号：%%\n\n");

// sprintf() - 格式化字符串
echo "sprintf()：\n";
$formatted = sprintf("姓名：%s，年龄：%d岁", "小明", 25);
echo $formatted . "\n\n";

// number_format() - 数字格式化
echo "number_format()：\n";
$number = 1234567.891;
echo "原数字：$number\n";
echo "千分位：" . number_format($number) . "\n";
echo "保留2位小数：" . number_format($number, 2) . "\n";
echo "自定义分隔符：" . number_format($number, 2, '.', ',') . "\n\n";


// =============================================
// 第九节：正则表达式入门
// =============================================
// 正则表达式是字符串处理的"瑞士军刀"，功能强大但语法复杂。
// 这里只介绍最基础的用法。

echo "【第九节：正则表达式入门】\n\n";

// 基本语法
echo "正则表达式基本语法：\n";
echo ".  匹配任意字符（除换行符）\n";
echo "\\d 匹配数字\n";
echo "\\w 匹配字母、数字、下划线\n";
echo "\\s 匹配空白字符\n";
echo "^  匹配开头\n";
echo "$  匹配结尾\n";
echo "*  匹配0次或多次\n";
echo "+  匹配1次或多次\n";
echo "?  匹配0次或1次\n";
echo "{n} 匹配n次\n";
echo "[abc] 匹配a、b或c\n";
echo "[^abc] 匹配除a、b、c外的字符\n\n";

// preg_match() - 正则匹配
echo "preg_match()：\n";
$str = "我的电话是13812345678";
$pattern = '/\d{11}/';
if (preg_match($pattern, $str, $matches)) {
    echo "找到手机号：" . $matches[0] . "\n";
}

// preg_match_all() - 匹配所有
$str = "价格：100元，200元，300元";
$pattern = '/\d+/';
preg_match_all($pattern, $str, $matches);
echo "所有价格：" . implode(", ", $matches[0]) . "\n\n";

// preg_replace() - 正则替换
$str = "Hello 123 World 456 PHP 789";
$pattern = '/\d+/';
echo "替换数字：" . preg_replace($pattern, '***', $str) . "\n\n";

// 实际应用：验证邮箱格式
echo "实际应用 - 验证邮箱：\n";
$emails = ["test@example.com", "invalid-email", "user@domain", "admin@site.org"];
$pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
foreach ($emails as $email) {
    $valid = preg_match($pattern, $email) ? '有效' : '无效';
    echo "$email → $valid\n";
}
echo "\n";


// =============================================
// 第十节：其他常用字符串函数
// =============================================

echo "【第十节：其他常用字符串函数】\n\n";

// str_repeat() - 重复字符串
echo "str_repeat('-', 20)：" . str_repeat('-', 20) . "\n";

// strrev() - 反转字符串
echo "strrev('Hello')：" . strrev('Hello') . "\n";

// wordwrap() - 自动换行
$text = "这是一段很长的文本，需要自动换行处理，每行不超过20个字符。";
echo "wordwrap()：\n" . wordwrap($text, 20, "\n", true) . "\n\n";

// nl2br() - 换行符转HTML
$text = "第一行\n第二行\n第三行";
echo "nl2br()：\n" . nl2br($text) . "\n";

// htmlspecialchars() - HTML特殊字符转义
echo "\htmlspecialchars()：\n";
$html = '<script>alert("XSS")</script>';
echo "原始：$html\n";
echo "转义后：" . htmlspecialchars($html, ENT_QUOTES, 'UTF-8') . "\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：统计字符串 \"Hello PHP World PHP\" 中 \"PHP\" 出现的次数\n";
echo "  提示：用 substr_count(\$str, \"PHP\") 函数直接统计子串出现次数\n";
echo "  提示：该函数区分大小写，如果要忽略大小写可以先 strtolower() 转换\n\n";

echo "练习2：将字符串 \"hello world\" 转换为 \"Hello World\"（每个单词首字母大写）\n";
echo "  提示：用 ucwords(\$str) 函数，它会把每个单词的首字母转为大写\n";
echo "  提示：如果只想让整个字符串第一个字母大写，用 ucfirst() 函数\n\n";

echo "练习3：从字符串 \"user@example.com\" 中提取用户名和域名\n";
echo "  提示：用 explode(\"@\", \$email) 按 @ 符号分割字符串为数组\n";
echo "  提示：分割后 \$parts[0] 是用户名，\$parts[1] 是域名\n\n";

echo "练习4：编写一个函数，将手机号中间4位替换为 ****（如 138****5678）\n";
echo "  提示：用 substr(\$phone, 0, 3) 取前3位，substr(\$phone, -4) 取后4位\n";
echo "  提示：中间用 \"****\" 替代，然后用点号拼接三部分\n\n";

echo "练习5：使用正则表达式验证一个字符串是否是中国手机号（1开头，11位数字）\n";
echo "  提示：正则模式 /^1[3-9]\\d{9}$/ ——以1开头，第二位3-9，后面9位数字\n";
echo "  提示：用 preg_match(\$pattern, \$phone) === 1 判断是否匹配\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
$str = "Hello PHP World PHP";
$count = substr_count($str, "PHP");
echo "\"$str\" 中 PHP 出现 $count 次\n\n";

// 练习2
echo "--- 练习2答案 ---\n";
$str = "hello world";
$result = ucwords($str);
echo "ucwords(\"$str\") = $result\n\n";

// 练习3
echo "--- 练习3答案 ---\n";
$email = "user@example.com";
$parts = explode("@", $email);
echo "用户名：" . $parts[0] . "\n";
echo "域名：" . $parts[1] . "\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
function maskPhone($phone) {
    return substr($phone, 0, 3) . '****' . substr($phone, -4);
}
echo maskPhone("13812345678") . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
function isValidChinesePhone($phone) {
    return preg_match('/^1[3-9]\d{9}$/', $phone) === 1;
}

$phones = ["13812345678", "12345678901", "1381234567", "138123456789"];
foreach ($phones as $phone) {
    $valid = isValidChinesePhone($phone) ? '有效' : '无效';
    echo "$phone → $valid\n";
}


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 字符串有4种定义方式：单引号、双引号、Heredoc、Nowdoc
 * - 常用函数：strlen/strpos/substr/str_replace/explode/implode
 * - 正则表达式：preg_match()匹配、preg_replace()替换
 *
 * 常见陷阱：
 * - 中文字符串必须用 mb_ 系列函数（mb_strlen/mb_substr），否则会乱码
 * - 单引号不解析变量和转义符，双引号会解析，搞混了会出bug
 * - strpos() 返回0时表示在位置0找到，要用 !== false 判断
 *
 * 下节课预告：
 * - 第05课我们将学习数组详解，掌握PHP中最强大的数据结构
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第04课！\n";
echo "   下节课：数组详解 —— 数据的书架\n";
echo "============================================================\n";
