<?php
// =============================================
// 第 05 课：数组详解
// =============================================
// 上节课我们学了字符串操作。
// 这节课我们要学习数组，PHP中最强大的数据结构。

echo "============================================================\n";
echo "   第05课：数组详解\n";
echo "============================================================\n\n";


// =============================================
// 第一节：什么是数组？
// =============================================

echo "【第一节：什么是数组？】\n\n";

// 数组就像一个书架，可以存放多个值
// 生活类比：
// - 普通变量 = 一个盒子，只能放一个东西
// - 数组 = 一个书架，可以放很多东西

// PHP数组的特点：
// 1. 可以存储任意类型的值
// 2. 可以是索引数组（数字编号）或关联数组（字符串键名）
// 3. 可以是多维数组（数组中包含数组）
// 4. 数组大小可以动态变化

echo "数组是PHP中最灵活的数据结构\n";
echo "可以存储任意类型、任意数量的值\n\n";


// =============================================
// 第二节：创建数组
// =============================================

echo "【第二节：创建数组】\n\n";

// 方式1：array() 函数
$fruits1 = array("苹果", "香蕉", "橙子");
echo "方式1 - array()：\n";
print_r($fruits1);

// 方式2：短数组语法（PHP 5.4+，推荐）
$fruits2 = ["苹果", "香蕉", "橙子"];
echo "方式2 - []：\n";
print_r($fruits2);

// 空数组
$empty1 = [];
$empty2 = array();
echo "空数组：";
print_r($empty1);
echo "\n";


// =============================================
// 第三节：索引数组
// =============================================
// 索引数组就像图书馆的编号系统，用数字来标识每个位置。

echo "【第三节：索引数组】\n\n";

// 创建索引数组
$colors = ["红", "绿", "蓝", "黄", "紫"];
echo "颜色数组：\n";
print_r($colors);

// 访问元素（索引从0开始）
echo "第一个颜色：$colors[0]\n";
echo "第三个颜色：$colors[2]\n";

// 修改元素
$colors[1] = "青";
echo "修改后：";
print_r($colors);

// 添加元素
$colors[] = "白";  // 自动添加到末尾
echo "添加后：";
print_r($colors);

// 获取数组长度
echo "数组长度：" . count($colors) . "\n\n";

// 遍历索引数组
echo "遍历索引数组：\n";
for ($i = 0; $i < count($colors); $i++) {
    echo "  $i: $colors[$i]\n";
}
echo "\n";

// 使用 foreach 遍历
echo "使用 foreach：\n";
foreach ($colors as $index => $color) {
    echo "  $index: $color\n";
}
echo "\n";


// =============================================
// 第四节：关联数组
// =============================================
// 关联数组就像字典，用"键"来查找"值"。

echo "【第四节：关联数组】\n\n";

// 创建关联数组
$person = [
    "name" => "小明",
    "age" => 25,
    "city" => "北京",
    "job" => "程序员"
];
echo "人员信息：\n";
print_r($person);

// 访问元素
echo "姓名：$person[name]\n";
echo "年龄：$person[age]\n";

// 修改元素
$person["age"] = 26;
echo "修改年龄后：$person[age]\n";

// 添加元素
$person["email"] = "xiaoming@example.com";
echo "添加邮箱后：\n";
print_r($person);

// 检查键是否存在
echo "检查name是否存在：" . (isset($person["name"]) ? '是' : '否') . "\n";
echo "检查phone是否存在：" . (isset($person["phone"]) ? '是' : '否') . "\n";

// 遍历关联数组
echo "\n遍历关联数组：\n";
foreach ($person as $key => $value) {
    echo "  $key: $value\n";
}
echo "\n";


// =============================================
// 第五节：多维数组
// =============================================
// 多维数组就像书架上有多层，每层有多本书。

echo "【第五节：多维数组】\n\n";

// 二维数组
$students = [
    ["name" => "小明", "age" => 20, "score" => 95],
    ["name" => "小红", "age" => 21, "score" => 88],
    ["name" => "小刚", "age" => 19, "score" => 92]
];
echo "学生信息：\n";
print_r($students);

// 访问二维数组
echo "第一个学生：$students[0][name]\n";
echo "第二个学生的分数：$students[1][score]\n\n";

// 三维数组
$school = [
    "一班" => [
        ["name" => "小明", "score" => 95],
        ["name" => "小红", "score" => 88]
    ],
    "二班" => [
        ["name" => "小刚", "score" => 92],
        ["name" => "小李", "score" => 85]
    ]
];

echo "学校数据：\n";
echo "一班第一个学生：$school[一班][0][name]\n";
echo "二班第二个学生的分数：$school[二班][1][score]\n\n";

// 遍历二维数组
echo "遍历学生信息：\n";
foreach ($students as $student) {
    echo "  姓名：$student[name]，年龄：$student[age]，分数：$student[score]\n";
}
echo "\n";


// =============================================
// 第六节：数组函数（增删改查）
// =============================================

echo "【第六节：数组函数（增删改查）】\n\n";

// --- 增加元素 ---
echo "--- 增加元素 ---\n";
$arr = [1, 2, 3];

// array_push() - 末尾添加
array_push($arr, 4, 5);
echo "array_push(4,5)：";
print_r($arr);

// array_unshift() - 开头添加
array_unshift($arr, 0);
echo "array_unshift(0)：";
print_r($arr);

// --- 删除元素 ---
echo "--- 删除元素 ---\n";
$arr = [1, 2, 3, 4, 5];

// array_pop() - 删除末尾
$last = array_pop($arr);
echo "array_pop() 删除：$last，剩余：";
print_r($arr);

// array_shift() - 删除开头
$first = array_shift($arr);
echo "array_shift() 删除：$first，剩余：";
print_r($arr);

// unset() - 删除指定元素
unset($arr[1]);
echo "unset(\$arr[1])：";
print_r($arr);

// --- 查找元素 ---
echo "--- 查找元素 ---\n";
$fruits = ["苹果", "香蕉", "橙子", "苹果", "葡萄"];

// in_array() - 检查值是否存在
echo "in_array('苹果')：" . (in_array("苹果", $fruits) ? '存在' : '不存在') . "\n";
echo "in_array('西瓜')：" . (in_array("西瓜", $fruits) ? '存在' : '不存在') . "\n";

// array_search() - 查找值的键
echo "array_search('香蕉')：" . array_search("香蕉", $fruits) . "\n";

// array_keys() - 获取所有键
echo "array_keys()：";
print_r(array_keys($fruits, "苹果"));

// --- 其他常用函数 ---
echo "--- 其他常用函数 ---\n";
$arr = [3, 1, 4, 1, 5, 9, 2, 6];

echo "原数组：";
print_r($arr);

// array_reverse() - 反转
echo "array_reverse()：";
print_r(array_reverse($arr));

// array_unique() - 去重
echo "array_unique()：";
print_r(array_unique($arr));

// array_merge() - 合并
$arr1 = [1, 2, 3];
$arr2 = [4, 5, 6];
echo "array_merge()：";
print_r(array_merge($arr1, $arr2));

// array_slice() - 截取
echo "array_slice(\$arr, 2, 3)：";
print_r(array_slice($arr, 2, 3));

// array_splice() - 删除并替换
$arr3 = [1, 2, 3, 4, 5];
array_splice($arr3, 1, 2, [20, 30]);
echo "array_splice()：";
print_r($arr3);
echo "\n";


// =============================================
// 第七节：数组排序
// =============================================

echo "【第七节：数组排序】\n\n";

// sort() - 升序排序（索引重置）
$arr = [3, 1, 4, 1, 5, 9, 2, 6];
echo "原数组：";
print_r($arr);

sort($arr);
echo "sort() 升序：";
print_r($arr);

// rsort() - 降序排序
rsort($arr);
echo "rsort() 降序：";
print_r($arr);

// asort() - 升序排序（保持键名）
$scores = ["小明" => 95, "小红" => 88, "小刚" => 92];
echo "\n原关联数组：";
print_r($scores);

asort($scores);
echo "asort() 升序：";
print_r($scores);

// arsort() - 降序排序（保持键名）
arsort($scores);
echo "arsort() 降序：";
print_r($scores);

// ksort() - 按键名升序
ksort($scores);
echo "ksort() 按键名：";
print_r($scores);

// krsort() - 按键名降序
krsort($scores);
echo "krsort() 按键名降序：";
print_r($scores);

// usort() - 自定义排序
$students = [
    ["name" => "小明", "score" => 95],
    ["name" => "小红", "score" => 88],
    ["name" => "小刚", "score" => 92]
];

usort($students, function($a, $b) {
    return $b["score"] - $a["score"];  // 按分数降序
});

echo "\nusort() 按分数降序：\n";
print_r($students);
echo "\n";


// =============================================
// 第八节：数组遍历方法
// =============================================

echo "【第八节：数组遍历方法】\n\n";

$fruits = ["苹果", "香蕉", "橙子", "葡萄", "西瓜"];

// 方法1：for 循环（索引数组）
echo "方法1 - for循环：\n";
for ($i = 0; $i < count($fruits); $i++) {
    echo "  $i: $fruits[$i]\n";
}
echo "\n";

// 方法2：foreach 循环（推荐）
echo "方法2 - foreach：\n";
foreach ($fruits as $index => $fruit) {
    echo "  $index: $fruit\n";
}
echo "\n";

// 方法3：while + each()（已废弃，不推荐）
echo "方法3 - while + each()（了解即可）：\n";
// while (list($key, $value) = each($fruits)) {
//     echo "  $key: $value\n";
// }
echo "  （each()在PHP 7.2已废弃）\n\n";

// 方法4：array_walk() - 对每个元素执行函数
echo "方法4 - array_walk()：\n";
$numbers = [1, 2, 3, 4, 5];
array_walk($numbers, function(&$value, $key) {
    $value *= 2;  // 每个元素乘以2
});
echo "每个元素乘以2：";
print_r($numbers);

// 方法5：array_map() - 对每个元素执行函数，返回新数组
echo "方法5 - array_map()：\n";
$numbers = [1, 2, 3, 4, 5];
$doubled = array_map(function($n) {
    return $n * 2;
}, $numbers);
echo "每个元素乘以2：";
print_r($doubled);

// 方法6：array_filter() - 过滤元素
echo "方法6 - array_filter()：\n";
$numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$evens = array_filter($numbers, function($n) {
    return $n % 2 === 0;  // 只保留偶数
});
echo "偶数：";
print_r($evens);

// 方法7：array_reduce() - 累积计算
echo "方法7 - array_reduce()：\n";
$numbers = [1, 2, 3, 4, 5];
$sum = array_reduce($numbers, function($carry, $item) {
    return $carry + $item;
}, 0);
echo "总和：$sum\n\n";


// =============================================
// 第九节：数组高级操作
// =============================================

echo "【第九节：数组高级操作】\n\n";

// array_column() - 获取二维数组中的一列
$users = [
    ["id" => 1, "name" => "小明", "age" => 25],
    ["id" => 2, "name" => "小红", "age" => 22],
    ["id" => 3, "name" => "小刚", "age" => 28]
];

$names = array_column($users, "name");
echo "array_column() 姓名：";
print_r($names);

// array_combine() - 合并两个数组
$keys = ["name", "age", "city"];
$values = ["小明", 25, "北京"];
$combined = array_combine($keys, $values);
echo "array_combine()：";
print_r($combined);

// array_flip() - 交换键和值
$original = ["a" => 1, "b" => 2, "c" => 3];
$flipped = array_flip($original);
echo "array_flip()：";
print_r($flipped);

// array_count_values() - 统计值出现次数
$colors = ["红", "绿", "蓝", "红", "绿", "红"];
$counts = array_count_values($colors);
echo "array_count_values()：";
print_r($counts);

// list() - 从数组中赋值
$info = ["小明", 25, "北京"];
list($name, $age, $city) = $info;
echo "list()：姓名=$name，年龄=$age，城市=$city\n";

// extract() - 将关联数组转为变量
$person = ["name" => "小明", "age" => 25, "city" => "北京"];
extract($person);
echo "extract()：姓名=$name，年龄=$age，城市=$city\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：创建一个包含5种水果的数组，添加"草莓"，删除"香蕉"，然后遍历输出\n";
echo "  提示：添加用 array_push() 或 \$arr[]，删除先用 array_search() 找索引再 unset()\n";
echo "  提示：删除后用 array_values() 重建索引，遍历用 foreach\n\n";

echo "练习2：创建一个学生成绩的关联数组，计算平均分、最高分、最低分\n";
echo "  提示：平均分 = array_sum(\$scores) / count(\$scores)\n";
echo "  提示：最高分用 max()，最低分用 min()\n\n";

echo "练习3：创建一个二维数组存储3个学生的信息（姓名、年龄、成绩），按成绩降序排序\n";
echo "  提示：用 usort(\$students, function(\$a, \$b) { return \$b[\"score\"] - \$a[\"score\"]; })\n";
echo "  提示：回调函数返回负数表示 \$a 排前面，正数表示 \$b 排前面\n\n";

echo "练习4：使用 array_filter() 从数组 [1,2,3,4,5,6,7,8,9,10] 中筛选出所有奇数\n";
echo "  提示：array_filter(\$arr, function(\$n) { return \$n % 2 !== 0; })\n";
echo "  提示：回调函数返回 true 保留元素，返回 false 过滤掉\n\n";

echo "练习5：合并两个数组 $arr1 = [1,2,3] 和 $arr2 = [3,4,5]，并去除重复值\n";
echo "  提示：先用 array_merge() 合并，再用 array_unique() 去重\n";
echo "  提示：array_unique() 保持键名，可能需要 array_values() 重建索引\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
$fruits = ["苹果", "香蕉", "橙子", "葡萄", "西瓜"];
echo "原数组：";
print_r($fruits);

array_push($fruits, "草莓");
echo "添加草莓后：";
print_r($fruits);

$index = array_search("香蕉", $fruits);
if ($index !== false) {
    unset($fruits[$index]);
    $fruits = array_values($fruits);  // 重新索引
}
echo "删除香蕉后：";
print_r($fruits);

echo "遍历输出：\n";
foreach ($fruits as $i => $fruit) {
    echo "  $i: $fruit\n";
}
echo "\n";

// 练习2
echo "--- 练习2答案 ---\n";
$scores = [
    "小明" => 95,
    "小红" => 88,
    "小刚" => 92,
    "小李" => 78,
    "小王" => 90
];
echo "成绩：";
print_r($scores);
echo "平均分：" . (array_sum($scores) / count($scores)) . "\n";
echo "最高分：" . max($scores) . "\n";
echo "最低分：" . min($scores) . "\n\n";

// 练习3
echo "--- 练习3答案 ---\n";
$students = [
    ["name" => "小明", "age" => 20, "score" => 95],
    ["name" => "小红", "age" => 21, "score" => 88],
    ["name" => "小刚", "age" => 19, "score" => 92]
];

usort($students, function($a, $b) {
    return $b["score"] - $a["score"];
});

echo "按成绩降序：\n";
foreach ($students as $student) {
    echo "  $student[name] - 年龄：$student[age] - 分数：$student[score]\n";
}
echo "\n";

// 练习4
echo "--- 练习4答案 ---\n";
$numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$odds = array_filter($numbers, function($n) {
    return $n % 2 !== 0;
});
echo "奇数：";
print_r($odds);

// 练习5
echo "--- 练习5答案 ---\n";
$arr1 = [1, 2, 3];
$arr2 = [3, 4, 5];
$merged = array_unique(array_merge($arr1, $arr2));
echo "合并去重：";
print_r($merged);


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 索引数组用数字编号，关联数组用字符串键名，多维数组嵌套使用
 * - 增删改查：array_push/array_pop/unset/直接赋值
 * - 遍历首选foreach，排序用sort/asort/usort
 *
 * 常见陷阱：
 * - unset()删除元素后不会重新索引，需要用array_values()重建
 * - foreach中用&引用修改数组后，记得unset($var)取消引用
 * - in_array()是松散比较，第三个参数true可改为严格比较
 *
 * 下节课预告：
 * - 第06课我们将学习条件语句，让程序能够"做判断"
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第05课！\n";
echo "   下节课：条件语句 —— 让程序学会做判断\n";
echo "============================================================\n";
