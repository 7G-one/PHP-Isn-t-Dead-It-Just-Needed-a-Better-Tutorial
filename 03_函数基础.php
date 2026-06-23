<?php
// -*- coding: utf-8 -*-
// =============================================
// 第 03 课：函数基础
// =============================================
// 上节课我们学了运算符与表达式。
// 这节课我们要学习函数——把代码"包起来"的工具。
//
// 学完这节课，你将能够：
// 1. 定义和调用函数
// 2. 使用参数和返回值
// 3. 理解变量作用域
// 4. 使用默认参数和可变参数

// =============================================
// 第一节：什么是函数？
// =============================================
//
// 【生活类比】
// 想象一下你去餐厅点了一份宫保鸡丁。
// 你不需要知道厨师怎么切菜、怎么炒、放多少盐——
// 你只需要"点菜"（调用函数），然后"上菜"（得到结果）。
//
// 函数就是一段"打包好"的代码，你可以随时"点菜"来使用它。
//
// 【工厂类比】
// 工厂里有一台机器，你把原材料（参数）放进去，
// 机器处理完后把成品（返回值）给你。
// 机器内部怎么运作，你不用管——你只管用。
//
// 【为什么需要函数？】
// 假设你要在程序里 10 个地方计算两个数的和：
// 没有函数 → 每个地方都写一遍 $a + $b，共 10 遍
// 有函数 → 写一次 add($a, $b)，调用 10 次
//
// 函数的好处：
// 1. 代码复用 —— 写一次，用很多次
// 2. 逻辑清晰 —— 每个函数只做一件事，起个好名字就能看懂
// 3. 易于调试 —— 出了问题，只需要检查函数内部
// 4. 方便协作 —— 你写函数，别人直接调用

// =============================================
// 第二节：定义函数
// =============================================
//
// 定义函数的语法：
//   function 函数名(参数列表) {
//       // 函数体（要执行的代码）
//   }
//
// - function 是关键字（告诉 PHP "我要定义一个函数"）
// - 函数名的命名规则和变量类似，但不用 $ 符号
// - 参数列表可以为空，也可以有多个参数
// - 花括号 {} 里是函数要执行的代码

// 示例：定义一个打招呼的函数
function sayHello() {
    echo "你好，世界！\n";
}

// 示例：定义一个带参数的函数
function greet($name) {
    echo "你好，$name！\n";
}

// 示例：定义一个计算两数之和的函数
function add($a, $b) {
    $sum = $a + $b;
    return $sum;
}

// 函数名的命名规范：
// 1. 用小写字母和下划线（snake_case）
// 2. 要有描述性，看名字就知道做什么
// 好名字：calculate_area, get_user_name, is_even
// 坏名字：calc, do_it, func1

// =============================================
// 第三节：调用函数
// =============================================
//
// 调用函数的语法：
//   函数名();
//
// 注意：括号 () 不能省！省了就不是调用了！

echo "=== 调用函数 ===\n";

// 调用无参数的函数
sayHello();       // 输出：你好，世界！

// 调用带参数的函数
greet("小明");     // 输出：你好，小明！
greet("小红");     // 输出：你好，小红！

// 调用有返回值的函数
$result = add(3, 5);
echo "3 + 5 = $result\n";   // 输出：3 + 5 = 8

// 也可以直接在表达式中使用函数的返回值
echo "10 + 20 = " . add(10, 20) . "\n";  // 输出：10 + 20 = 30

// 常见错误：忘记括号
// sayHello;    // 这不会调用函数！这只是引用函数本身（高级用法）

// =============================================
// 第四节：参数详解
// =============================================

echo "\n=== 参数详解 ===\n";

// 4.1 位置参数
// 参数按位置对应，第一个实参传给第一个形参，以此类推
function introduce($name, $age) {
    echo "我叫$name，今年$age岁。\n";
}
introduce("小明", 18);   // 我叫小明，今年18岁。
introduce("小红", 20);   // 我叫小红，今年20岁。
// 注意：如果位置反了，结果就错了
introduce(18, "小明");   // 我叫18，今年小明岁。（这不是我们想要的！）

// 4.2 默认参数
// 给参数一个默认值，调用时可以不传这个参数
function is_positive($num) {
    // 判断一个数是否为正数
    // 默认不传参数时，演示用 0
    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}
echo "is_positive(3) = " . var_export(is_positive(3), true) . "\n";    // true
echo "is_positive(-5) = " . var_export(is_positive(-5), true) . "\n";  // false
echo "is_positive(0) = " . var_export(is_positive(0), true) . "\n";    // false

// 有默认值的参数必须放在没有默认值的参数后面！
// 错误示例：function bad_example($a = 1, $b) { }  // 这会报错

// 4.3 类型声明
// PHP 7+ 支持声明参数的类型，传错类型会报错
function add_numbers(int $a, int $b): int {
    // int $a 表示 $a 必须是整数
    // : int 表示返回值必须是整数
    return $a + $b;
}
echo "add_numbers(3, 4) = " . add_numbers(3, 4) . "\n";  // 7
// echo add_numbers("hello", "world");  // 这会报错！类型不对

// 常见的类型声明：
// int（整数）、float（浮点数）、string（字符串）
// bool（布尔值）、array（数组）、callable（可调用的）
// self（当前类）、parent（父类）

// 4.4 类型声明的实际应用
function is_adult(int $age): bool {
    // 用类型声明保证参数是整数，返回值是布尔
    if ($age >= 18) {
        return true;
    } else {
        return false;
    }
}
echo "is_adult(20) = " . var_export(is_adult(20), true) . "\n";  // true
echo "is_adult(15) = " . var_export(is_adult(15), true) . "\n";  // false

// =============================================
// 第五节：返回值
// =============================================

echo "\n=== 返回值 ===\n";

// 5.1 return 关键字
// return 做两件事：
// 1. 把一个值"送出去"给调用者
// 2. 立即结束函数的执行

function divide($a, $b) {
    if ($b == 0) {
        echo "错误：除数不能为零！\n";
        return null;   // 提前返回，后面的代码不会执行
    }
    return $a / $b;    // 正常返回结果
}
echo "10 / 3 = " . divide(10, 3) . "\n";   // 3.3333...
echo "10 / 0 = ";
divide(10, 0);                              // 输出错误信息

// 5.2 没有 return 的函数返回 null
function just_print($msg) {
    echo "$msg\n";
    // 没有 return 语句
}
$result = just_print("测试");
var_dump($result);   // 输出：NULL

// 5.3 返回值的灵活运用
function get_grade($score) {
    // 根据分数返回等级
    // 展示一个函数可以有多个 return 路径
    if ($score >= 90) {
        return "优秀";
    } elseif ($score >= 80) {
        return "良好";
    } elseif ($score >= 60) {
        return "及格";
    } else {
        return "不及格";
    }
}
echo "95 分：" . get_grade(95) . "\n";  // 优秀
echo "75 分：" . get_grade(75) . "\n";  // 及格
echo "50 分：" . get_grade(50) . "\n";  // 不及格

// 5.4 提前返回（守卫语句）
function check_age($age) {
    // 先检查"不合法"的情况，提前返回
    // 这样后面就不用写在 if 里，代码更清晰
    if ($age < 0) {
        return "年龄不能为负数";
    }
    if ($age > 150) {
        return "年龄不可能超过150岁";
    }
    if ($age < 18) {
        return "未成年";
    }
    return "成年";
}
echo "年龄 15：" . check_age(15) . "\n";
echo "年龄 25：" . check_age(25) . "\n";
echo "年龄 -1：" . check_age(-1) . "\n";

// =============================================
// 第六节：变量作用域
// =============================================

echo "\n=== 变量作用域 ===\n";

// 6.1 局部变量 vs 全局变量
//
// 【生活类比】
// 想象你家有一个储物柜（全局作用域），
// 每个房间也有自己的柜子（函数的局部作用域）。
// 房间柜子里的东西（局部变量），只在那个房间能用。
// 客厅储物柜的东西（全局变量），哪里都能用。

$global_var = "我是全局变量";

function test_scope() {
    $local_var = "我是局部变量";
    echo "在函数内部：\n";
    echo "  局部变量 = $local_var\n";
    // echo "  全局变量 = $global_var\n";  // 报错！函数里看不到全局变量
}
test_scope();
echo "在函数外部：\n";
echo "  全局变量 = $global_var\n";
// echo "  局部变量 = $local_var\n";  // 报错！函数外看不到局部变量

// 6.2 global 关键字
// 如果确实需要在函数里访问全局变量，用 global 关键字
$counter = 0;

function increment_counter() {
    global $counter;   // 声明"我要用外面的 $counter"
    $counter++;
    echo "函数内 counter = $counter\n";
}
increment_counter();   // 1
increment_counter();   // 2
increment_counter();   // 3
echo "函数外 counter = $counter\n";  // 3

// 6.3 超全局变量（例外）
// PHP 有一些特殊的变量，在任何地方都能访问：
// $_GET、$_POST、$_SERVER、$_COOKIE 等
// 这些叫"超全局变量"，不需要 global 关键字
// 后面学 Web 开发时会详细讲

// 6.4 静态变量（static）
// 普通的局部变量，函数结束后就"销毁"了
// 用 static 声明的变量，函数结束后"保留"，下次调用还在
function counter() {
    static $count = 0;   // 只在第一次调用时初始化
    $count++;
    echo "调用次数：$count\n";
}
counter();   // 1
counter();   // 2
counter();   // 3
// $count 是局部变量，外面访问不到
// 但它在函数调用之间"活着"

// =============================================
// 第七节：PHP 特有功能
// =============================================

echo "\n=== PHP 特有功能 ===\n";

// 7.1 可变函数
// 把函数名存在变量里，用变量来调用函数
function say_hi() {
    echo "Hi!\n";
}
function say_bye() {
    echo "Bye!\n";
}
$action = "say_hi";    // 变量里存了函数名
$action();              // 等同于 say_hi()，输出：Hi!

$action = "say_bye";
$action();              // 等同于 say_bye()，输出：Bye!

// 7.2 匿名函数（lambda）
// 没有名字的函数，通常用来"临时"使用
$square = function($x) {
    return $x * $x;
};
echo "5 的平方 = " . $square(5) . "\n";  // 25

// 匿名函数的典型用法：临时定义一个简单逻辑
$is_even = function($n) {
    if ($n % 2 == 0) {
        return true;
    } else {
        return false;
    }
};
echo "4 是偶数？" . var_export($is_even(4), true) . "\n";  // true
echo "7 是偶数？" . var_export($is_even(7), true) . "\n";  // false

// 7.3 回调函数
// 把函数作为参数传给另一个函数
function apply_operation($a, $b, $operation) {
    // $operation 是一个"函数型"参数
    return $operation($a, $b);
}

$add_func = function($a, $b) { return $a + $b; };
$mul_func = function($a, $b) { return $a * $b; };

echo "apply_operation(3, 4, 加法) = " . apply_operation(3, 4, $add_func) . "\n";
echo "apply_operation(3, 4, 乘法) = " . apply_operation(3, 4, $mul_func) . "\n";

// 回调函数的典型用法：自定义判断逻辑
function check_value($num, $checker) {
    // $checker 是一个回调函数，决定怎么检查
    return $checker($num);
}
$is_positive_func = function($n) {
    if ($n > 0) {
        return "正数";
    } else {
        return "非正数";
    }
};
echo "check_value(5, is_positive) = " . check_value(5, $is_positive_func) . "\n";  // 正数
echo "check_value(-3, is_positive) = " . check_value(-3, $is_positive_func) . "\n"; // 非正数

// 7.4 箭头函数（PHP 7.4+）
// 更简短的匿名函数写法
$double = fn($x) => $x * 2;
echo "double(7) = " . $double(7) . "\n";  // 14

// 箭头函数自动"捕获"外部变量（不需要 use）
$factor = 3;
$triple = fn($x) => $x * $factor;
echo "triple(5) = " . $triple(5) . "\n";  // 15

// =============================================
// 第八节：函数的好处（总结）
// =============================================
//
// 1. 代码复用
//    - 写一次，到处用
//    - 修改一处，所有调用都生效
//
// 2. 逻辑清晰
//    - 给函数起个好名字，代码就像"说明书"
//    - calculate_tax($income) 比 一堆代码 更容易理解
//
// 3. 易于调试
//    - 出了 bug，定位到具体函数就行
//    - 可以单独测试每个函数
//
// 4. 方便协作
//    - 你写函数，别人不需要知道内部怎么实现
//    - 只要知道"传什么参数，拿什么返回值"就行

// =============================================
// 实战项目：计算器函数
// =============================================

echo "\n========================================\n";
echo "实战项目：计算器函数\n";
echo "========================================\n";

// 我们来写一个完整的计算器
// 用函数来组织各种运算

// 四则运算函数
function calc_add($a, $b) {
    return $a + $b;
}

function calc_subtract($a, $b) {
    return $a - $b;
}

function calc_multiply($a, $b) {
    return $a * $b;
}

function calc_divide($a, $b) {
    if ($b == 0) {
        echo "  [错误] 除数不能为零！\n";
        return null;
    }
    return $a / $b;
}

// 综合计算函数：根据运算符调用对应函数
function calculate($a, $op, $b) {
    if ($op == '+') {
        return calc_add($a, $b);
    } elseif ($op == '-') {
        return calc_subtract($a, $b);
    } elseif ($op == '*') {
        return calc_multiply($a, $b);
    } elseif ($op == '/') {
        if ($b == 0) {
            echo "  [错误] 除数不能为零！\n";
            return null;
        }
        return $a / $b;
    } else {
        echo "  [错误] 不支持的运算符：$op\n";
        return null;
    }
}

// 辅助函数：格式化输出
function show_calculation($a, $op, $b) {
    $result = calculate($a, $op, $b);
    if ($result !== null) {
        echo "  $a $op $b = $result\n";
    }
}

// --- 主程序：演示各种调用方式 ---

echo "\n--- 基本四则运算 ---\n";
show_calculation(10, '+', 5);    // 10 + 5 = 15
show_calculation(10, '-', 5);    // 10 - 5 = 5
show_calculation(10, '*', 5);    // 10 * 5 = 50
show_calculation(10, '/', 5);    // 10 / 5 = 2

echo "\n--- 特殊情况 ---\n";
show_calculation(10, '/', 0);    // 除数为零
show_calculation(10, '%', 3);    // 不支持的运算符
show_calculation(3.5, '+', 2.1); // 浮点数运算

echo "\n--- 直接调用各个函数 ---\n";
echo "  calc_add(100, 200) = " . calc_add(100, 200) . "\n";
echo "  calc_multiply(7, 8) = " . calc_multiply(7, 8) . "\n";

echo "\n--- 用函数组合复杂计算 ---\n";
// 计算 (3 + 5) * 2
$step1 = calc_add(3, 5);        // 8
$step2 = calc_multiply($step1, 2); // 16
echo "  (3 + 5) * 2 = $step2\n";

// 计算 (10 - 3) / (2 + 5)
$numerator = calc_subtract(10, 3);   // 7
$denominator = calc_add(2, 5);       // 7
$result = calc_divide($numerator, $denominator);
echo "  (10 - 3) / (2 + 5) = $result\n";

echo "\n--- 用可变函数调用不同运算 ---\n";
// 把函数名存在变量里，用变量来调用
$op_name = "calc_add";
echo "  用变量调用 calc_add(6, 7) = " . $op_name(6, 7) . "\n";

echo "\n--- 用回调函数扩展计算器 ---\n";
// 用回调的方式给计算器"加功能"
function calc_custom($a, $b, callable $func) {
    return $func($a, $b);
}
$mod_func = function($a, $b) {
    if ($b == 0) {
        echo "  [错误] 除数不能为零！\n";
        return null;
    }
    return $a % $b;
};
echo "  10 % 3 = " . calc_custom(10, 3, $mod_func) . "\n";
echo "  7 % 2 = " . calc_custom(7, 2, $mod_func) . "\n";

// =============================================
// 练习题
// =============================================

echo "\n========================================\n";
echo "练习题\n";
echo "========================================\n";

// 练习 1：基础
// 题目：写一个 greet_exercise($name) 函数，输出"你好，$name！"
//
// 提示：
// - 函数接收一个参数 $name
// - 用 echo 输出，记得加换行符 \n
// - 字符串里可以直接放变量
// - 注意：这里用 greet_exercise 避免和前面的 greet 函数重名

echo "\n--- 练习 1 参考答案 ---\n";

function greet_exercise($name) {
    echo "你好，$name！\n";
}
greet_exercise("小明");
greet_exercise("PHP 初学者");
greet_exercise("未来的程序员");

// 练习 2：应用
// 题目：写一个 max_of_three($a, $b, $c) 函数，返回三个数中的最大值
//
// 提示：
// - 方法一：用 if/elseif 比较三个数
// - 方法二：先用 max() 比较前两个，再和第三个比
// - 方法三：直接用 PHP 内置的 max($a, $b, $c)
// - 记得用 return 返回结果，不是 echo

echo "\n--- 练习 2 参考答案 ---\n";

// 方法一：if/elseif 比较
function max_of_three_v1($a, $b, $c) {
    if ($a >= $b && $a >= $c) {
        return $a;
    } elseif ($b >= $a && $b >= $c) {
        return $b;
    } else {
        return $c;
    }
}

// 方法二：借助 max() 函数
function max_of_three_v2($a, $b, $c) {
    return max(max($a, $b), $c);
}

// 方法三：直接用内置 max()
function max_of_three_v3($a, $b, $c) {
    return max($a, $b, $c);
}

echo "  max_of_three(3, 7, 5) = " . max_of_three_v1(3, 7, 5) . "\n";
echo "  max_of_three(10, 2, 8) = " . max_of_three_v2(10, 2, 8) . "\n";
echo "  max_of_three(1, 1, 1) = " . max_of_three_v3(1, 1, 1) . "\n";

// 练习 3：进阶
// 题目：写一个 classify_number($n) 函数，判断正数/负数/零
//
// 提示：
// - 用 if/elseif/else 判断三种情况
// - 用 return 返回字符串结果
// - 注意：0 既不是正数也不是负数

echo "\n--- 练习 3 参考答案 ---\n";

function classify_number($n) {
    // 判断一个数是正数、负数还是零
    if ($n > 0) {
        return "正数";
    } elseif ($n < 0) {
        return "负数";
    } else {
        return "零";
    }
}

// 测试
echo "  classify_number(5) = " . classify_number(5) . "\n";    // 正数
echo "  classify_number(-3) = " . classify_number(-3) . "\n";  // 负数
echo "  classify_number(0) = " . classify_number(0) . "\n";    // 零

// 验证边界情况
echo "  classify_number(0.001) = " . classify_number(0.001) . "\n";  // 正数
echo "  classify_number(-100) = " . classify_number(-100) . "\n";    // 负数

// 练习 4：温度转换
// 题目：编写一个函数 celsius_to_fahrenheit($celsius)，将摄氏温度转换为华氏温度
//
// 公式：F = C × 9/5 + 32
// 提示：
// - 用 return 返回计算结果，不要用 echo
// - 注意运算优先级，先乘后加

echo "\n--- 练习 4 参考答案 ---\n";

function celsius_to_fahrenheit($celsius) {
    // 摄氏温度转华氏温度
    // 公式：F = C × 9/5 + 32
    return $celsius * 9 / 5 + 32;
}

// 测试
echo "  celsius_to_fahrenheit(0) = " . celsius_to_fahrenheit(0) . "\n";      // 32（水的冰点）
echo "  celsius_to_fahrenheit(100) = " . celsius_to_fahrenheit(100) . "\n";  // 212（水的沸点）
echo "  celsius_to_fahrenheit(37) = " . celsius_to_fahrenheit(37) . "\n";    // 98.6（人体体温）
echo "  celsius_to_fahrenheit(-40) = " . celsius_to_fahrenheit(-40) . "\n";  // -40（摄氏和华氏的交汇点）

// 练习 5：数组最大值
// 题目：编写一个函数 find_max($arr)，返回数组中的最大值
//
// 提示：
// - 用循环遍历数组，用变量记录当前最大值
// - 注意：不要用内置函数 max()，自己实现
// - 先假设第一个元素是最大值，然后逐个比较

echo "\n--- 练习 5 参考答案 ---\n";

function find_max($arr) {
    // 返回数组中的最大值（不用内置 max()）
    if (empty($arr)) {
        return null;  // 空数组返回 null
    }

    // 假设第一个元素是最大值
    $max = $arr[0];

    // 从第二个元素开始逐个比较
    for ($i = 1; $i < count($arr); $i++) {
        if ($arr[$i] > $max) {
            $max = $arr[$i];  // 发现更大的，更新
        }
    }

    return $max;
}

// 测试
echo "  find_max([3, 7, 2, 9, 5]) = " . find_max([3, 7, 2, 9, 5]) . "\n";         // 9
echo "  find_max([100, 200, 50]) = " . find_max([100, 200, 50]) . "\n";             // 200
echo "  find_max([42]) = " . find_max([42]) . "\n";                                 // 42（只有一个元素）
echo "  find_max([-5, -2, -8]) = " . find_max([-5, -2, -8]) . "\n";                // -2（负数取最大）
echo "  find_max([1, 1, 1]) = " . find_max([1, 1, 1]) . "\n";                      // 1（全部相同）

// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 函数用 function 定义，用 函数名() 调用
 * - 参数让函数更灵活，return 让函数有返回值
 * - 作用域决定变量在哪里能用
 *
 * 常见陷阱：
 * - 忘记写花括号 {} 导致语法错误
 * - 忘记调用函数（只写函数名不加括号）
 * - 在函数外访问局部变量会报错
 *
 * 下节课预告：
 * - 下节课学字符串操作，用函数处理文本
 */

// =============================================
// 恭喜完成
// =============================================
echo "\n恭喜你完成了第 03 课：函数基础！\n";
echo "你已经学会了定义函数、使用参数和返回值、理解作用域。\n";
echo "下节课我们将学习字符串操作——用函数处理文本。\n";
