<?php
// =============================================
// 第 06 课：条件语句
// =============================================
// 上节课我们学了数组详解。
// 这节课我们要学习条件语句，让程序学会"做判断"。

echo "============================================================\n";
echo "   第06课：条件语句\n";
echo "============================================================\n\n";


// =============================================
// 第一节：if 语句
// =============================================
// if 语句就像一个"守门员"，只有条件满足才放行。

echo "【第一节：if 语句】\n\n";

// 基本语法
$age = 20;
echo "年龄：$age\n";

if ($age >= 18) {
    echo "你已经成年了！可以投票了。\n";
}

// 如果只有一行代码，可以省略大括号（不推荐）
if ($age >= 18)
    echo "成年了！\n";

echo "\n";


// =============================================
// 第二节：if-else 语句
// =============================================

echo "【第二节：if-else 语句】\n\n";

$temperature = 30;
echo "温度：$temperature°C\n";

if ($temperature >= 30) {
    echo "天气很热，记得防晒！\n";
} else {
    echo "天气不错，适合出门。\n";
}

// 实际应用：判断风力等级
$wind_speed = 35;  // km/h
echo "\n风速：{$wind_speed}km/h\n";
if ($wind_speed >= 60) {
    echo "大风预警，请注意安全！\n";
} else {
    echo "风力正常，适合出行。\n";
}

// 判断是否及格
$score = 65;
echo "\n分数：$score\n";
if ($score >= 60) {
    echo "恭喜，你及格了！\n";
} else {
    echo "很遗憾，你没有及格，继续努力！\n";
}
echo "\n";


// =============================================
// 第三节：if-elseif-else 语句
// =============================================

echo "【第三节：if-elseif-else 语句】\n\n";

// 成绩等级判断
$score = 85;
echo "分数：$score\n";

if ($score >= 90) {
    echo "等级：A（优秀）\n";
} elseif ($score >= 80) {
    echo "等级：B（良好）\n";
} elseif ($score >= 70) {
    echo "等级：C（中等）\n";
} elseif ($score >= 60) {
    echo "等级：D（及格）\n";
} else {
    echo "等级：F（不及格）\n";
}

// 季节判断
$month = 8;
echo "\n月份：$month\n";
if ($month >= 3 && $month <= 5) {
    echo "季节：春天\n";
} elseif ($month >= 6 && $month <= 8) {
    echo "季节：夏天\n";
} elseif ($month >= 9 && $month <= 11) {
    echo "季节：秋天\n";
} else {
    echo "季节：冬天\n";
}

// BMI计算器
$height = 1.75;  // 米
$weight = 70;    // 公斤
$bmi = $weight / ($height * $height);
echo "\n身高：{$height}m，体重：{$weight}kg\n";
echo "BMI：" . round($bmi, 2) . "\n";

if ($bmi < 18.5) {
    echo "状态：偏瘦\n";
} elseif ($bmi < 24) {
    echo "状态：正常\n";
} elseif ($bmi < 28) {
    echo "状态：偏胖\n";
} else {
    echo "状态：肥胖\n";
}
echo "\n";


// =============================================
// 第四节：switch 语句
// =============================================
// switch 语句就像一个多路开关，根据值选择不同的分支。

echo "【第四节：switch 语句】\n\n";

$day = date("N");  // 1-7，1是周一
echo "今天是星期几的数字：$day\n";

switch ($day) {
    case 1:
        echo "今天是周一，新的一周开始了！\n";
        break;
    case 2:
        echo "今天是周二，继续加油！\n";
        break;
    case 3:
        echo "今天是周三，周中了！\n";
        break;
    case 4:
        echo "今天是周四，快到周末了！\n";
        break;
    case 5:
        echo "今天是周五，明天就是周末！\n";
        break;
    case 6:
        echo "今天是周六，好好休息！\n";
        break;
    case 7:
        echo "今天是周日，明天又要上班了！\n";
        break;
    default:
        echo "无效的日期\n";
}

// 月份天数判断
$month = 2;
$year = 2024;
echo "\n$year年$month月的天数：";

switch ($month) {
    case 2:
        // 闰年判断
        if (($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0)) {
            echo "29天（闰年）\n";
        } else {
            echo "28天\n";
        }
        break;
    case 4:
    case 6:
    case 9:
    case 11:
        echo "30天\n";
        break;
    case 1:
    case 3:
    case 5:
    case 7:
    case 8:
    case 10:
    case 12:
        echo "31天\n";
        break;
    default:
        echo "无效月份\n";
}

// 注意：switch 使用松散比较（==）
echo "\n注意：switch 使用松散比较（==）\n";
$value = "1";
switch ($value) {
    case 1:
        echo "匹配整数1\n";
        break;
    case "1":
        echo "匹配字符串\"1\"\n";
        break;
}
echo "\n";


// =============================================
// 第五节：match 表达式（PHP 8.0+）
// =============================================

echo "【第五节：match 表达式（PHP 8.0+）】\n\n";

// match 比 switch 更简洁，使用严格比较（===）
$statusCode = 404;

// 传统写法
echo "传统 switch 写法：\n";
switch ($statusCode) {
    case 200:
        $message = "OK";
        break;
    case 301:
        $message = "Moved Permanently";
        break;
    case 404:
        $message = "Not Found";
        break;
    case 500:
        $message = "Internal Server Error";
        break;
    default:
        $message = "Unknown";
}
echo "状态码 $statusCode: $message\n";

// match 写法（更简洁）
echo "\nmatch 写法：\n";
$message = match($statusCode) {
    200 => "OK",
    301 => "Moved Permanently",
    404 => "Not Found",
    500 => "Internal Server Error",
    default => "Unknown"
};
echo "状态码 $statusCode: $message\n";

// match 支持多个值匹配同一结果
$lang = "zh";
$greeting = match($lang) {
    "en" => "Hello",
    "zh", "cn" => "你好",  // 多个值匹配同一结果
    "ja" => "こんにちは",
    default => "Hi"
};
echo "\n语言 $lang: $greeting\n";

// match 支持表达式
$age = 25;
$category = match(true) {
    $age < 13 => "儿童",
    $age < 18 => "青少年",
    $age < 65 => "成年人",
    default => "老年人"
};
echo "年龄 $age: $category\n\n";


// =============================================
// 第六节：三元运算符
// =============================================

echo "【第六节：三元运算符】\n\n";

// 基本三元运算符
$age = 20;
$status = ($age >= 18) ? "成年人" : "未成年人";
echo "年龄 $age: $status\n";

// 嵌套三元运算符（不推荐，影响可读性）
$score = 85;
$grade = ($score >= 90) ? "A" : (($score >= 80) ? "B" : (($score >= 70) ? "C" : "D"));
echo "分数 $score: 等级 $grade\n";

// 空合并运算符（PHP 7.0+）
$username = null;
$display = $username ?? "匿名用户";
echo "用户名：$display\n";

// 链式空合并
$config = ["name" => "MyApp"];
$name = $config["name"] ?? $config["app_name"] ?? "默认应用";
echo "应用名：$name\n";

// 空合并赋值运算符（PHP 7.4+）
$data = [];
$data["count"] ??= 0;  // 如果不存在则赋值
echo "计数：$data[count]\n\n";


// =============================================
// 第七节：条件语句的实际应用
// =============================================

echo "【第七节：条件语句的实际应用】\n\n";

// 应用1：用户权限检查
echo "应用1 - 用户权限检查：\n";
$user = [
    "name" => "小明",
    "role" => "admin",
    "is_active" => true
];

if (!$user["is_active"]) {
    echo "账户已禁用\n";
} elseif ($user["role"] === "admin") {
    echo "欢迎管理员：$user[name]\n";
} elseif ($user["role"] === "editor") {
    echo "欢迎编辑：$user[name]\n";
} else {
    echo "欢迎用户：$user[name]\n";
}

// 应用2：购物车折扣计算
echo "\n应用2 - 购物车折扣：\n";
$total = 250;
$member_level = "gold";  // bronze, silver, gold, platinum

$discount = match($member_level) {
    "bronze" => 0.05,
    "silver" => 0.10,
    "gold" => 0.15,
    "platinum" => 0.20,
    default => 0
};

// 满减活动
$extra_discount = 0;
if ($total >= 200) {
    $extra_discount = 20;
}

$final = $total * (1 - $discount) - $extra_discount;
echo "原价：$total 元\n";
echo "会员折扣：" . ($discount * 100) . "%\n";
echo "满减优惠：$extra_discount 元\n";
echo "实付：" . round($final, 2) . " 元\n";

// 应用3：表单验证
echo "\n应用3 - 表单验证：\n";
$form_data = [
    "username" => "xiaoming",
    "email" => "test@example.com",
    "password" => "123456",
    "age" => 25
];

$errors = [];

// 用户名验证
if (empty($form_data["username"])) {
    $errors[] = "用户名不能为空";
} elseif (strlen($form_data["username"]) < 3) {
    $errors[] = "用户名至少3个字符";
}

// 邮箱验证
if (empty($form_data["email"])) {
    $errors[] = "邮箱不能为空";
} elseif (!filter_var($form_data["email"], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "邮箱格式不正确";
}

// 密码验证
if (empty($form_data["password"])) {
    $errors[] = "密码不能为空";
} elseif (strlen($form_data["password"]) < 6) {
    $errors[] = "密码至少6个字符";
}

// 年龄验证
if ($form_data["age"] < 0 || $form_data["age"] > 150) {
    $errors[] = "年龄不合法";
}

if (empty($errors)) {
    echo "验证通过！\n";
} else {
    echo "验证失败：\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
}
echo "\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：编写一个程序，输入年份，判断是否是闰年\n";
echo "   规则：能被4整除但不能被100整除，或者能被400整除\n";
echo "  提示：用 && 和 || 组合条件——(\$y%4===0 && \$y%100!==0) || \$y%400===0\n";
echo "  提示：可以用 if-else 也可以封装成函数，返回 true 或 false\n\n";

echo "练习2：编写一个简单的计算器，输入两个数和运算符，输出结果\n";
echo "  提示：用 match(\$operator) 匹配 +、-、*、/ 等运算符\n";
echo "  提示：除法前要检查除数是否为0\n\n";

echo "练习3：使用 match 表达式，将数字 1-7 转换为中文星期几\n";
echo "  提示：match(\$num) { 1 => \"星期一\", 2 => \"星期二\", ... }\n";
echo "  提示：default 分支处理无效数字\n\n";

echo "练习4：编写一个成绩评定系统：\n";
echo "   输入分数（0-100），输出等级（A/B/C/D/F）和评语\n";
echo "  提示：用 match(true) { \$score>=90 => ..., \$score>=80 => ... } 的写法\n";
echo "  提示：先检查分数是否在 0-100 范围内\n\n";

echo "练习5：编写一个程序，判断一个日期是当年的第几天\n";
echo "  提示：用一个数组存每个月的天数 [0,31,28,31,30,...]\n";
echo "  提示：闰年2月要改成29天，然后把前面几个月的天数加起来加上当天\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
function isLeapYear($year) {
    return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
}

$years = [2000, 2020, 2021, 2024, 1900];
foreach ($years as $year) {
    echo "$year年：" . (isLeapYear($year) ? "是" : "不是") . "闰年\n";
}
echo "\n";

// 练习2
echo "--- 练习2答案 ---\n";
$num1 = 10;
$num2 = 3;
$operator = "+";

$result = match($operator) {
    "+" => $num1 + $num2,
    "-" => $num1 - $num2,
    "*" => $num1 * $num2,
    "/" => $num2 != 0 ? $num1 / $num2 : "除数不能为0",
    "%" => $num2 != 0 ? $num1 % $num2 : "除数不能为0",
    default => "无效运算符"
};
echo "$num1 $operator $num2 = $result\n\n";

// 练习3
echo "--- 练习3答案 ---\n";
$dayNum = 3;
$dayName = match($dayNum) {
    1 => "星期一",
    2 => "星期二",
    3 => "星期三",
    4 => "星期四",
    5 => "星期五",
    6 => "星期六",
    7 => "星期日",
    default => "无效"
};
echo "$dayNum = $dayName\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
$score = 78;

if ($score < 0 || $score > 100) {
    echo "分数无效\n";
} else {
    [$grade, $comment] = match(true) {
        $score >= 90 => ["A", "优秀，继续保持！"],
        $score >= 80 => ["B", "良好，还有提升空间"],
        $score >= 70 => ["C", "中等，需要更加努力"],
        $score >= 60 => ["D", "及格，要加油了"],
        default => ["F", "不及格，需要补考"]
    };
    echo "分数：$score，等级：$grade，评语：$comment\n";
}
echo "\n";

// 练习5
echo "--- 练习5答案 ---\n";
function dayOfYear($year, $month, $day) {
    $days_in_month = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    // 闰年2月有29天
    if (($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0)) {
        $days_in_month[2] = 29;
    }

    $total = 0;
    for ($i = 1; $i < $month; $i++) {
        $total += $days_in_month[$i];
    }
    $total += $day;

    return $total;
}

echo "2024年3月1日是第" . dayOfYear(2024, 3, 1) . "天\n";
echo "2024年12月31日是第" . dayOfYear(2024, 12, 31) . "天\n";
echo "2023年12月31日是第" . dayOfYear(2023, 12, 31) . "天\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - if/elseif/else 处理多分支条件，switch/match 处理值匹配
 * - match 表达式（PHP 8.0+）比 switch 更简洁，使用严格比较===
 * - 三元运算符 ?: 和空合并运算符 ?? 让代码更简洁
 *
 * 常见陷阱：
 * - switch 使用松散比较（==），容易出现类型混淆的bug
 * - 嵌套三元运算符可读性极差，超过一层就用if-else
 * - 忘记在switch的case后面写break，会导致"穿透"到下一个case
 *
 * 下节课预告：
 * - 第07课我们将学习循环语句，让程序能够"重复劳动"
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第06课！\n";
echo "   下节课：循环语句 —— 让程序学会重复劳动\n";
echo "============================================================\n";
