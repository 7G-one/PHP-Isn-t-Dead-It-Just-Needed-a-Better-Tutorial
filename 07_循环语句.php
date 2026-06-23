<?php
// =============================================
// 第 07 课：循环语句
// =============================================
// 上节课我们学了条件语句。
// 这节课我们要学习循环语句，让程序能够"重复劳动"。

echo "============================================================\n";
echo "   第07课：循环语句\n";
echo "============================================================\n\n";


// =============================================
// 第一节：for 循环
// =============================================
// for 循环就像一个计数器，你知道要重复多少次。

echo "【第一节：for 循环】\n\n";

// 基本语法
echo "基本 for 循环：\n";
for ($i = 1; $i <= 5; $i++) {
    echo "  第 $i 次循环\n";
}
echo "\n";

// 三个部分详解
echo "for 循环的三个部分：\n";
echo "for (初始化; 条件; 更新) { 循环体 }\n";
echo "  1. 初始化：循环开始前执行一次\n";
echo "  2. 条件：每次循环前检查，为true才继续\n";
echo "  3. 更新：每次循环后执行\n\n";

// 实际应用：计算1到100的和
echo "计算1到100的和：\n";
$sum = 0;
for ($i = 1; $i <= 100; $i++) {
    $sum += $i;
}
echo "1 + 2 + 3 + ... + 100 = $sum\n\n";

// 实际应用：九九乘法表
echo "九九乘法表：\n";
for ($i = 1; $i <= 9; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        printf("%d×%d=%-4d", $j, $i, $i * $j);
    }
    echo "\n";
}
echo "\n";

// 实际应用：输出三角形
echo "输出三角形：\n";
$rows = 5;
for ($i = 1; $i <= $rows; $i++) {
    // 输出空格
    for ($j = 1; $j <= $rows - $i; $j++) {
        echo " ";
    }
    // 输出星号
    for ($k = 1; $k <= 2 * $i - 1; $k++) {
        echo "*";
    }
    echo "\n";
}
echo "\n";


// =============================================
// 第二节：while 循环
// =============================================
// while 循环就像"只要...就..."，条件满足就一直执行。

echo "【第二节：while 循环】\n\n";

// 基本语法
echo "基本 while 循环：\n";
$i = 1;
while ($i <= 5) {
    echo "  第 $i 次循环\n";
    $i++;
}
echo "\n";

// 实际应用：猜数字游戏
echo "猜数字游戏（模拟）：\n";
$target = 7;
$guess = 0;
$attempts = 0;
$max_attempts = 5;

// 模拟猜数字过程
$guesses = [3, 5, 8, 6, 7];  // 预设的猜测
$guess_index = 0;

while ($guess !== $target && $attempts < $max_attempts) {
    $guess = $guesses[$guess_index];
    $attempts++;
    echo "  第 $attempts 次猜测：$guess";

    if ($guess < $target) {
        echo " → 太小了！\n";
    } elseif ($guess > $target) {
        echo " → 太大了！\n";
    } else {
        echo " → 恭喜你猜对了！\n";
    }
    $guess_index++;
}

if ($guess !== $target) {
    echo "  游戏结束，正确答案是 $target\n";
}
echo "\n";

// 实际应用：遍历链表（模拟）
echo "遍历链表（模拟）：\n";
$linked_list = [
    ["value" => "A", "next" => 1],
    ["value" => "B", "next" => 2],
    ["value" => "C", "next" => 3],
    ["value" => "D", "next" => null]
];

$current = 0;
while ($current !== null) {
    echo "  节点：$linked_list[$current][value]\n";
    $current = $linked_list[$current]["next"];
}
echo "\n";


// =============================================
// 第三节：do-while 循环
// =============================================
// do-while 至少执行一次，然后才检查条件。

echo "【第三节：do-while 循环】\n\n";

echo "do-while 至少执行一次：\n";
$i = 10;
do {
    echo "  i = $i\n";
    $i++;
} while ($i <= 5);  // 条件为false，但已经执行了一次
echo "\n";

// 实际应用：菜单系统（模拟）
echo "菜单系统（模拟）：\n";
$choice = 0;
$menu_shown = 0;

do {
    if ($menu_shown < 2) {  // 只显示2次菜单
        echo "  ====== 菜单 ======\n";
        echo "  1. 查看信息\n";
        echo "  2. 修改设置\n";
        echo "  3. 退出\n";
        echo "  ==================\n";
    }

    // 模拟用户选择
    $choices = [1, 2, 3];
    $choice = $choices[$menu_shown] ?? 3;
    echo "  你选择了：$choice\n";

    switch ($choice) {
        case 1:
            echo "  → 显示信息\n";
            break;
        case 2:
            echo "  → 修改设置\n";
            break;
        case 3:
            echo "  → 退出系统\n";
            break;
    }
    $menu_shown++;
} while ($choice !== 3 && $menu_shown < 3);
echo "\n";


// =============================================
// 第四节：foreach 循环
// =============================================
// foreach 是遍历数组的最佳方式。

echo "【第四节：foreach 循环】\n\n";

// 遍历索引数组
echo "遍历索引数组：\n";
$fruits = ["苹果", "香蕉", "橙子", "葡萄", "西瓜"];
foreach ($fruits as $index => $fruit) {
    echo "  $index: $fruit\n";
}
echo "\n";

// 遍历关联数组
echo "遍历关联数组：\n";
$person = [
    "name" => "小明",
    "age" => 25,
    "city" => "北京",
    "job" => "程序员"
];
foreach ($person as $key => $value) {
    echo "  $key: $value\n";
}
echo "\n";

// 遍历二维数组
echo "遍历二维数组：\n";
$students = [
    ["name" => "小明", "score" => 95],
    ["name" => "小红", "score" => 88],
    ["name" => "小刚", "score" => 92]
];
foreach ($students as $student) {
    echo "  $student[name]: $student[score] 分\n";
}
echo "\n";

// 使用引用修改数组
echo "使用引用修改数组：\n";
$numbers = [1, 2, 3, 4, 5];
echo "原数组：" . implode(", ", $numbers) . "\n";

foreach ($numbers as &$number) {
    $number *= 2;
}
unset($number);  // 重要：取消引用，避免意外修改
echo "翻倍后：" . implode(", ", $numbers) . "\n\n";


// =============================================
// 第五节：break 和 continue
// =============================================

echo "【第五节：break 和 continue】\n\n";

// break - 立即跳出循环
echo "break 示例（找到第一个偶数就停止）：\n";
$numbers = [1, 3, 5, 8, 9, 10];
foreach ($numbers as $number) {
    if ($number % 2 === 0) {
        echo "  找到偶数：$number\n";
        break;
    }
    echo "  检查：$number（奇数）\n";
}
echo "\n";

// continue - 跳过当前迭代，继续下一次
echo "continue 示例（只输出奇数）：\n";
for ($i = 1; $i <= 10; $i++) {
    if ($i % 2 === 0) {
        continue;  // 跳过偶数
    }
    echo "  $i ";
}
echo "\n\n";

// break 和 continue 的数字参数
echo "break/continue 数字参数（跳出多层循环）：\n";
for ($i = 1; $i <= 3; $i++) {
    echo "外层循环 $i：";
    for ($j = 1; $j <= 5; $j++) {
        if ($j === 3) {
            break 1;  // 跳出当前循环（默认）
        }
        echo "$j ";
    }
    echo "\n";
}
echo "\n";

// 跳出多层循环
echo "跳出多层循环：\n";
$found = false;
for ($i = 1; $i <= 5 && !$found; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        if ($i * $j === 12) {
            echo "  找到：$i × $j = 12\n";
            $found = true;
            break;
        }
    }
}
echo "\n";


// =============================================
// 第六节：嵌套循环
// =============================================

echo "【第六节：嵌套循环】\n\n";

// 打印矩阵
echo "打印 5x5 矩阵：\n";
for ($i = 1; $i <= 5; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        printf("%3d", $i * $j);
    }
    echo "\n";
}
echo "\n";

// 生成棋盘
echo "生成 8x8 棋盘：\n";
for ($i = 0; $i < 8; $i++) {
    for ($j = 0; $j < 8; $j++) {
        if (($i + $j) % 2 === 0) {
            echo "□ ";
        } else {
            echo "■ ";
        }
    }
    echo "\n";
}
echo "\n";


// =============================================
// 第七节：循环控制技巧
// =============================================

echo "【第七节：循环控制技巧】\n\n";

// 技巧1：使用 flag 控制循环
echo "技巧1 - 使用 flag：\n";
$numbers = [1, 3, 5, 7, 9];
$is_all_odd = true;

foreach ($numbers as $number) {
    if ($number % 2 === 0) {
        $is_all_odd = false;
        break;
    }
}

echo "所有数字都是奇数：" . ($is_all_odd ? '是' : '否') . "\n\n";

// 技巧2：使用计数器
echo "技巧2 - 使用计数器：\n";
$text = "Hello PHP World PHP PHP";
$count = 0;
$pos = 0;

while (($pos = strpos($text, "PHP", $pos)) !== false) {
    $count++;
    $pos += 3;
}
echo "\"PHP\" 出现了 $count 次\n\n";

// 技巧3：使用 array 函数替代循环
echo "技巧3 - 使用 array 函数：\n";
$numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

// 传统方式
$sum1 = 0;
foreach ($numbers as $n) {
    if ($n % 2 === 0) {
        $sum1 += $n;
    }
}
echo "偶数和（传统）：$sum1\n";

// 函数式方式
$sum2 = array_sum(array_filter($numbers, fn($n) => $n % 2 === 0));
echo "偶数和（函数式）：$sum2\n\n";


// =============================================
// 第八节：生成器（Generator）
// =============================================

echo "【第八节：生成器（Generator）】\n\n";

// 生成器是一种特殊的函数，可以暂停和恢复
// 适合处理大数据集，不会占用太多内存

// 基本生成器
function simpleGenerator() {
    yield "第一个值";
    yield "第二个值";
    yield "第三个值";
}

echo "基本生成器：\n";
foreach (simpleGenerator() as $value) {
    echo "  $value\n";
}
echo "\n";

// 范围生成器（替代 range() 函数）
function myRange($start, $end, $step = 1) {
    for ($i = $start; $i <= $end; $i += $step) {
        yield $i;
    }
}

echo "范围生成器 1-10：\n";
foreach (myRange(1, 10) as $number) {
    echo " $number";
}
echo "\n\n";

// 斐波那契数列生成器
function fibonacci() {
    $a = 0;
    $b = 1;
    while (true) {
        yield $a;
        [$a, $b] = [$b, $a + $b];
    }
}

echo "斐波那契数列前10个：\n";
$count = 0;
foreach (fibonacci() as $number) {
    echo " $number";
    $count++;
    if ($count >= 10) break;
}
echo "\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：使用 for 循环计算 1! + 2! + 3! + ... + 10! 的和\n";
echo "  提示：先写一个计算阶乘的函数，再用 for 循环累加每次的阶乘结果\n";
echo "  提示：n! = 1 × 2 × 3 × ... × n，可以用一个变量从1乘到n\n\n";

echo "练习2：使用 while 循环，将一个数字反转（如 12345 → 54321）\n";
echo "  提示：用 \$num % 10 取最后一位，用 intdiv(\$num, 10) 去掉最后一位\n";
echo "  提示：每次把结果乘以10再加上取出的数字\n\n";

echo "练习3：使用嵌套循环，输出以下图形：\n";
echo "   *\n";
echo "   **\n";
echo "   ***\n";
echo "   ****\n";
echo "   *****\n";
echo "  提示：外层循环控制行数（1到5），内层循环控制每行的星号数\n";
echo "  提示：第 i 行输出 i 个星号\n\n";

echo "练习4：使用 foreach 循环，找出数组中的最大值和最小值（不使用 max/min 函数）\n";
echo "  提示：先假设第一个元素既是最大值也是最小值\n";
echo "  提示：遍历数组，遇到更大的就更新最大值，遇到更小的就更新最小值\n";
echo "  提示：用 foreach(\$arr as \$value) 遍历，用 if 比较更新 \$max 和 \$min\n\n";

echo "练习5：编写一个函数，使用生成器生成指定范围内的所有素数\n";
echo "  提示：素数是只能被1和自身整除的大于1的自然数\n";
echo "  提示：用 yield 逐个返回素数，判断是否素数只需检查到 sqrt(\$num)\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
function factorial($n) {
    $result = 1;
    for ($i = 1; $i <= $n; $i++) {
        $result *= $i;
    }
    return $result;
}

$sum = 0;
for ($i = 1; $i <= 10; $i++) {
    $fact = factorial($i);
    $sum += $fact;
    echo "  $i! = $fact\n";
}
echo "  总和 = $sum\n\n";

// 练习2
echo "--- 练习2答案 ---\n";
function reverseNumber($num) {
    $reversed = 0;
    while ($num > 0) {
        $reversed = $reversed * 10 + ($num % 10);
        $num = intdiv($num, 10);
    }
    return $reversed;
}

$numbers = [12345, 100, 9876];
foreach ($numbers as $num) {
    echo "  $num → " . reverseNumber($num) . "\n";
}
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";
$rows = 5;
for ($i = 1; $i <= $rows; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo "*";
    }
    echo "\n";
}
echo "\n";

// 练习4
echo "--- 练习4答案 ---\n";
$numbers = [34, 12, 56, 78, 23, 9, 45];

$max = $numbers[0];
$min = $numbers[0];

foreach ($numbers as $number) {
    if ($number > $max) {
        $max = $number;
    }
    if ($number < $min) {
        $min = $number;
    }
}
echo "数组：" . implode(", ", $numbers) . "\n";
echo "最大值：$max\n";
echo "最小值：$min\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
function primeGenerator($start, $end) {
    for ($num = $start; $num <= $end; $num++) {
        if ($num < 2) continue;
        $is_prime = true;
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i === 0) {
                $is_prime = false;
                break;
            }
        }
        if ($is_prime) {
            yield $num;
        }
    }
}

echo "2-50的素数：\n";
foreach (primeGenerator(2, 50) as $prime) {
    echo " $prime";
}
echo "\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - for适合已知次数，while适合未知次数，do-while至少执行一次
 * - foreach是遍历数组的首选方式
 * - break跳出循环，continue跳过当前迭代，可带数字参数跳出多层
 *
 * 常见陷阱：
 * - foreach中用&引用后必须unset($var)，否则下次循环会修改原数组
 * - while循环忘记更新条件变量会导致死循环
 * - break/continue的数字参数写错会跳出错误的层数
 *
 * 下节课预告：
 * - 第08课我们将学习超全局变量，开始与Web服务器交互
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第07课！\n";
echo "   下节课：超全局变量 —— 与Web服务器交互\n";
echo "============================================================\n";
