<?php
// =============================================
// 第 01 课：变量与数据类型
// =============================================
// 上节课我们初步认识了变量和数据类型。
// 这节课我们要深入学习变量命名规则、数据类型详解和类型转换。

echo "============================================================\n";
echo "   第01课：变量与数据类型\n";
echo "============================================================\n\n";


// =============================================
// 第一节：变量命名规则
// =============================================
// 变量命名就像给孩子起名字，有规则要遵守。

echo "【第一节：变量命名规则】\n\n";

// 规则1：变量必须以 $ 符号开头
$name = "小明";  // 正确
// name = "小红";  // 错误！没有$符号

// 规则2：$ 后面必须是字母或下划线，不能是数字
$_name = "小红";    // 正确：以下划线开头
$myName = "小刚";   // 正确：驼峰命名法
$my_name = "小李";  // 正确：下划线命名法
// $123name = "错误"; // 错误！不能以数字开头

// 规则3：变量名只能包含字母、数字、下划线
$my_name_2 = "正确";  // 正确
// $my-name = "错误";   // 错误！不能有连字符
// $my name = "错误";   // 错误！不能有空格

// 规则4：变量名区分大小写
$Name = "大写N";
$name = "小写n";
echo "大写N的Name：$Name\n";
echo "小写n的name：$name\n";
echo "它们是不同的变量！\n\n";

// 规则5：不能使用PHP保留字作为变量名
// $class = "错误";    // class是保留字
// $function = "错误"; // function是保留字
// $echo = "错误";     // echo是保留字
$class_name = "正确";  // 加个后缀就好了

// 命名最佳实践
echo "命名最佳实践：\n";
echo "1. 使用有意义的名字（\$user_name 而不是 \$a）\n";
echo "2. 使用驼峰命名法或下划线命名法（保持一致）\n";
echo "3. 避免使用缩写（除非是通用缩写如 \$id, \$url）\n";
echo "4. 布尔变量可以加 is/has/can 前缀\n\n";

// 好的命名示例
$user_age = 25;              // 清晰明了
$is_logged_in = true;        // 布尔值用is前缀
$max_retry_count = 3;        // 常量风格
$database_connection = null; // 下划线命名法
$databaseConnection = null;  // 驼峰命名法

echo "好名字 vs 坏名字：\n";
echo "好：\$user_age → 明确知道是用户年龄\n";
echo "坏：\$a → 完全不知道是什么\n";
echo "好：\$is_active → 明确知道是布尔值\n";
echo "坏：\$flag → 不清楚是什么标志\n\n";


// =============================================
// 第二节：PHP数据类型详解
// =============================================
// PHP有8种数据类型，分为3大类。

echo "【第二节：PHP数据类型详解】\n\n";

// --- 标量类型（Scalar Types）：只能存一个值 ---

echo "--- 标量类型 ---\n\n";

// 1. 整数（integer/int）—— 没有小数点的数字
$age = 25;
$temperature = -10;  // 可以是负数
$big_number = 1000000;
$hex_number = 0xFF;  // 十六进制（255）
$oct_number = 077;   // 八进制（63）
$bin_number = 0b1010; // 二进制（10）

echo "整数示例：\n";
echo "十进制：$age\n";
echo "十六进制0xFF = $hex_number\n";
echo "八进制077 = $oct_number\n";
echo "二进制0b1010 = $bin_number\n";
echo "整数范围：" . PHP_INT_MIN . " 到 " . PHP_INT_MAX . "\n\n";

// 2. 浮点数（float/double）—— 有小数点的数字
$price = 9.99;
$pi = 3.14159265359;
$scientific = 1.5e2;  // 科学计数法：1.5 × 10² = 150

echo "浮点数示例：\n";
echo "价格：$price\n";
echo "圆周率：$pi\n";
echo "科学计数法1.5e2 = $scientific\n";

// 注意：浮点数有精度问题！
echo "精度问题演示：\n";
echo "0.1 + 0.2 = " . (0.1 + 0.2) . "\n";  // 不是0.3，而是0.30000000000000004
echo "为什么？因为计算机用二进制存储小数，有些小数无法精确表示\n\n";

// 3. 字符串（string）—— 一串字符
$single = '单引号字符串';     // 单引号
$double = "双引号字符串";     // 双引号

// 单引号 vs 双引号的重要区别
$name = "小明";
echo "单引号：\$name 不会解析变量，输出：'$name'\n";  // 输出 $name
echo "双引号：\$name 会解析变量，输出：\"$name\"\n";   // 输出 小明

// 单引号中要输出单引号，用转义字符 \'
echo '单引号中输出单引号：\'这是单引号\'\n';
// 双引号中要输出双引号，用转义字符 \"
echo "双引号中输出双引号：\"这是双引号\"\n";
// 换行符、制表符等只在双引号中生效
echo "双引号中的换行：第一行\n第二行\n\n";

// 4. 布尔值（boolean/bool）—— 只有 true 或 false
$is_active = true;
$is_deleted = false;

echo "布尔值示例：\n";
echo "是否激活：$is_active\n";
echo "是否删除：$is_deleted\n";

// 以下值在PHP中被认为是 false：
echo "\n以下值被认为是 false：\n";
echo "false: " . var_export(false, true) . "\n";
echo "0: " . var_export(0, true) . "\n";
echo "0.0: " . var_export(0.0, true) . "\n";
echo "'': " . var_export('', true) . "\n";
echo "'0': " . var_export('0', true) . "\n";
echo "null: " . var_export(null, true) . "\n";
echo "空数组[]: " . var_export([], true) . "\n\n";

// --- 复合类型（Compound Types）：可以存多个值 ---

echo "--- 复合类型 ---\n\n";

// 5. 数组（array）—— 一组有序的数据
$fruits = ["苹果", "香蕉", "橙子"];
$person = ["name" => "小明", "age" => 25, "city" => "北京"];

echo "索引数组：\n";
print_r($fruits);
echo "关联数组：\n";
print_r($person);

// 6. 对象（object）—— 面向对象编程的核心（后面课程详细讲解）
// 7. 可调用（callable）—— 函数或方法（后面课程讲解）

// --- 特殊类型 ---

echo "--- 特殊类型 ---\n\n";

// 8. NULL —— 表示"没有值"
$nothing = null;
echo "NULL值：" . var_dump($nothing) . "\n";

// 9. 资源（resource）—— 外部资源的引用（如文件、数据库连接）
// $file = fopen("test.txt", "r");  // 这是一个资源


// =============================================
// 第三节：类型转换（Type Casting）
// =============================================
// 有时候你需要把一种类型转换成另一种类型，就像把水倒进不同的容器。

echo "\n【第三节：类型转换】\n\n";

// 方法1：自动类型转换（Type Juggling）
// PHP会根据上下文自动转换类型
echo "自动类型转换：\n";

$string = "10";
$number = 5;
$result = $string + $number;  // 字符串"10"自动转为整数10
echo "\"10\" + 5 = $result\n";  // 15

$string2 = "hello";
$result2 = $string2 + $number;  // "hello"转为0
echo "\"hello\" + 5 = $result2\n\n";  // 5

// 方法2：强制类型转换（Type Casting）
echo "强制类型转换：\n";

// 转为整数 (int)
echo "转为整数：\n";
echo "(int)\"123\" = " . (int)"123" . "\n";
echo "(int)\"12.9\" = " . (int)"12.9" . "\n";  // 截断小数部分
echo "(int)true = " . (int)true . "\n";
echo "(int)false = " . (int)false . "\n";
echo "(int)\"hello\" = " . (int)"hello" . "\n\n";  // 0

// 转为浮点数 (float)
echo "转为浮点数：\n";
echo "(float)\"123\" = " . (float)"123" . "\n";
echo "(float)\"12.9\" = " . (float)"12.9" . "\n";
echo "(float)true = " . (float)true . "\n\n";

// 转为字符串 (string)
echo "转为字符串：\n";
echo "(string)123 = '" . (string)123 . "'\n";
echo "(string)true = '" . (string)true . "'\n";   // "1"
echo "(string)false = '" . (string)false . "'\n";  // ""
echo "(string)null = '" . (string)null . "'\n\n";  // ""

// 转为布尔值 (bool)
echo "转为布尔值：\n";
echo "(bool)1 = " . var_export((bool)1, true) . "\n";
echo "(bool)0 = " . var_export((bool)0, true) . "\n";
echo "(bool)\"\" = " . var_export((bool)"", true) . "\n";
echo "(bool)\"hello\" = " . var_export((bool)"hello", true) . "\n\n";

// 方法3：使用 settype() 函数
echo "使用 settype()：\n";
$value = "123";
echo "转换前：" . var_dump($value);
settype($value, "int");
echo "转换后：" . var_dump($value);

// 方法4：使用 intval(), floatval(), strval()
echo "\n使用专用函数：\n";
echo "intval(\"123\") = " . intval("123") . "\n";
echo "floatval(\"12.5\") = " . floatval("12.5") . "\n";
echo "strval(123) = " . strval(123) . "\n\n";


// =============================================
// 第四节：类型检查函数
// =============================================

echo "【第四节：类型检查函数】\n\n";

$value1 = 42;
$value2 = "Hello";
$value3 = 3.14;
$value4 = true;
$value5 = [1, 2, 3];
$value6 = null;

echo "is_int(42): " . var_export(is_int($value1), true) . "\n";
echo "is_float(3.14): " . var_export(is_float($value3), true) . "\n";
echo "is_string(\"Hello\"): " . var_export(is_string($value2), true) . "\n";
echo "is_bool(true): " . var_export(is_bool($value4), true) . "\n";
echo "is_array([1,2,3]): " . var_export(is_array($value5), true) . "\n";
echo "is_null(null): " . var_export(is_null($value6), true) . "\n";

// gettype() 获取类型
echo "\ngettype() 函数：\n";
echo "gettype(42) = " . gettype($value1) . "\n";
echo "gettype(\"Hello\") = " . gettype($value2) . "\n";
echo "gettype(3.14) = " . gettype($value3) . "\n";
echo "gettype(true) = " . gettype($value4) . "\n";
echo "gettype([1,2,3]) = " . gettype($value5) . "\n";
echo "gettype(null) = " . gettype($value6) . "\n\n";


// =============================================
// 第五节：可变变量（Variable Variables）
// =============================================
// 这是一个高级特性，变量的名字本身也是一个变量。
// 就像你有一个盒子，盒子里放的是另一个盒子的名字。

echo "【第五节：可变变量】\n\n";

$foo = "bar";
$$foo = "baz";  // $bar = "baz"

echo "foo = $foo\n";
echo "bar = $bar\n";
echo "\$foo 的值是 'bar'，所以 \$\$foo 就是 \$bar\n\n";

// 实际应用场景：动态创建变量
$fields = ["name", "age", "city"];
$values = ["小明", 25, "北京"];

// 动态创建 $name, $age, $city
for ($i = 0; $i < count($fields); $i++) {
    ${$fields[$i]} = $values[$i];
}

echo "动态创建的变量：\n";
echo "name = $name\n";
echo "age = $age\n";
echo "city = $city\n\n";

// 注意：可变变量容易造成混乱，不建议在简单场景使用
// 更好的方式是使用数组


// =============================================
// 第六节：常量（Constants）
// =============================================
// 常量就像用钢笔写的标签——一旦写了就不能改。
// 变量是用铅笔写的，可以擦掉重写。

echo "【第六节：常量】\n\n";

// 方法1：使用 define() 函数
define("PI", 3.14159);
define("MAX_SIZE", 100);
define("SITE_NAME", "我的网站");

echo "PI = " . PI . "\n";
echo "MAX_SIZE = " . MAX_SIZE . "\n";
echo "SITE_NAME = " . SITE_NAME . "\n";

// 方法2：使用 const 关键字（PHP 5.3+）
const DB_HOST = "localhost";
const DB_PORT = 3306;

echo "DB_HOST = " . DB_HOST . "\n";
echo "DB_PORT = " . DB_PORT . "\n";

// 常量和变量的区别
echo "\n常量 vs 变量：\n";
echo "1. 常量不用 \$ 符号\n";
echo "2. 常量一旦定义不能修改\n";
echo "3. 常量可以在任何地方访问（全局作用域）\n";
echo "4. 常量只能存标量值（int, float, string, bool）\n\n";

// define() 和 const 的区别
echo "define() vs const：\n";
echo "1. define() 可以在运行时定义，const 在编译时定义\n";
echo "2. define() 可以定义条件常量，const 不行\n";
echo "3. const 可以定义类常量，define() 不行\n";
echo "4. const 性能略好\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：判断以下变量名哪些是合法的，哪些是不合法的：\n";
echo "   \$myVar, \$123abc, \$_name, \$my-var, \$class, \$my var\n";
echo "  提示：变量名规则——必须以字母或下划线开头，不能有连字符和空格，不能是保留字\n\n";

echo "练习2：创建以下变量并用 var_dump() 输出类型：\n";
echo "   - 整数 42\n";
echo "   - 浮点数 3.14\n";
echo "   - 字符串 \"Hello PHP\"\n";
echo "   - 布尔值 true\n";
echo "   - NULL\n";
echo "  提示：var_dump(\$variable) 会输出变量的类型和值\n";
echo "  提示：NULL 用 \$var = null; 赋值\n\n";

echo "练习3：预测以下代码的输出结果：\n";
echo "   \$a = \"10\";\n";
echo "   \$b = \"20\";\n";
echo "   echo \$a + \$b;\n";
echo "  提示：PHP做加法时会自动把字符串转为数字，想想 \"10\" + \"20\" 等于多少？\n\n";

echo "练习4：将字符串 \"3.14\" 转换为浮点数，将浮点数 3.14 转换为整数，将整数 1 转换为布尔值\n";
echo "  提示：用 (float)\"3.14\"、(int)3.14、(bool)1 进行强制类型转换\n";
echo "  提示：(int)转换会截断小数部分，(bool)只有 0、\"\"、null 等才是 false\n\n";

echo "练习5：定义一个常量 MAX_USERS = 100，定义一个变量 \$current_users = 75，计算剩余可用用户数\n";
echo "  提示：常量用 define(\"MAX_USERS\", 100) 或 const MAX_USERS = 100 定义\n";
echo "  提示：剩余 = MAX_USERS - \$current_users，常量不用 \$ 符号\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
echo "\$myVar   → 合法（驼峰命名法）\n";
echo "\$123abc  → 不合法（不能以数字开头）\n";
echo "\$_name   → 合法（以下划线开头）\n";
echo "\$my-var  → 不合法（不能有连字符）\n";
echo "\$class   → 不合法（保留字）\n";
echo "\$my var  → 不合法（不能有空格）\n\n";

// 练习2
echo "--- 练习2答案 ---\n";
$ex_int = 42;
$ex_float = 3.14;
$ex_string = "Hello PHP";
$ex_bool = true;
$ex_null = null;

echo "整数42: ";
var_dump($ex_int);
echo "浮点数3.14: ";
var_dump($ex_float);
echo "字符串\"Hello PHP\": ";
var_dump($ex_string);
echo "布尔值true: ";
var_dump($ex_bool);
echo "NULL: ";
var_dump($ex_null);
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";
$a = "10";
$b = "20";
echo "\"10\" + \"20\" = " . ($a + $b) . "\n";
echo "PHP会自动将字符串转为数字进行计算\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
$str = "3.14";
$float = (float)$str;
$int = (int)3.14;
$bool = (bool)1;

echo "\"3.14\" → float: " . var_export($float, true) . "\n";
echo "3.14 → int: " . $int . "\n";
echo "1 → bool: " . var_export($bool, true) . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
define("MAX_USERS", 100);
$current_users = 75;
$remaining = MAX_USERS - $current_users;
echo "最大用户数：" . MAX_USERS . "\n";
echo "当前用户数：$current_users\n";
echo "剩余可用：$remaining\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 变量命名必须以$开头，只能包含字母数字下划线，区分大小写
 * - PHP有8种数据类型：int, float, string, bool, array, object, callable, resource
 * - 类型转换有三种方式：自动转换、强制转换(int)、settype()
 *
 * 常见陷阱：
 * - 浮点数有精度问题：0.1 + 0.2 !== 0.3，不要用 == 比较浮点数
 * - 布尔值的 false 有多种表示：0, 0.0, "", "0", null, [] 都是false
 * - 常量一旦定义不能修改，define() 和 const 有细微区别
 *
 * 下节课预告：
 * - 第02课我们将学习运算符与表达式，让PHP真正"计算"起来
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第01课！\n";
echo "   下节课：运算符与表达式 —— 让PHP真正计算起来\n";
echo "============================================================\n";
