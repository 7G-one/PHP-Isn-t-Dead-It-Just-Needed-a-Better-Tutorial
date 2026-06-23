<?php
// =============================================
// 第 16 课：命名空间与类型声明
// =============================================
// 上节课我们学了会话与安全。
// 这节课我们要学习PHP 8.0+的两个重要现代特性：命名空间和类型声明。
// 这些特性让PHP代码更加规范、可维护，是现代PHP开发的必备技能。

echo "============================================================\n";
echo "   第16课：命名空间与类型声明\n";
echo "============================================================\n\n";

// 【生活类比】
// 命名空间就像"姓氏"——
// 学校里可能有好几个叫"小明"的同学，但加上姓氏就区分开了：
// "张小明"和"李小明"是不同的人。命名空间就是给类、函数加"姓氏"，
// 防止不同作者写的代码撞名字。
//
// 类型声明就像"快递单上的物品类型"——
// 你寄快递时要写清楚是"文件"还是"易碎品"，
// 这样快递员就知道怎么处理。类型声明也是告诉PHP：
// "这个参数必须是整数""这个函数必须返回字符串"，
// 如果传错了类型，PHP会立刻报错，而不是默默出错。


// =============================================
// 第一节：命名空间基础
// =============================================

echo "【第一节：命名空间基础】\n\n";

// 为什么需要命名空间？
echo "为什么需要命名空间？\n";
echo "  想象一下：你的项目用了两个第三方库，\n";
echo "  两个库都定义了一个叫 Logger 的类。\n";
echo "  PHP就不知道你用的是哪个 Logger 了！\n";
echo "  命名空间就是为了解决这个"命名冲突"问题。\n\n";

// 没有命名空间时的问题
echo "没有命名空间时的问题：\n";
echo <<<'CODE'
// 库A定义了一个Logger类
class Logger {
    public function log($msg) {
        echo "库A记录：$msg";
    }
}

// 库B也定义了一个Logger类
class Logger {  // 致命错误！类名重复！
    public function log($msg) {
        echo "库B记录：$msg";
    }
}
CODE;
echo "\n\n";

// 命名空间的基本语法
echo "命名空间的基本语法：\n";
echo <<<'CODE'
<?php
// 用 namespace 关键字声明命名空间
// 注意：必须放在文件的最前面，<?php 之后

namespace App\Database;

class Connection {
    // 这个类的完整名字是 App\Database\Connection
}

namespace App\Logger;

class Logger {
    // 这个类的完整名字是 App\Logger\Logger
}
CODE;
echo "\n\n";

// 命名空间就像文件夹
echo "命名空间的层级关系（就像文件夹）：\n";
echo "  App                  ← 顶级命名空间（就像根目录）\n";
echo "  ├── App\\Database     ← 二级命名空间（就像子文件夹）\n";
echo "  │   └── Connection   ← 类\n";
echo "  ├── App\\Logger       ← 二级命名空间\n";
echo "  │   └── Logger       ← 类\n";
echo "  └── App\\Models       ← 二级命名空间\n";
echo "      ├── User         ← 类\n";
echo "      └── Post         ← 类\n\n";

// 实际演示：定义命名空间
echo "实际演示 —— 定义命名空间：\n";

// 模拟一个命名空间下的类
// 注意：在同一个文件里不能真正切换命名空间（实战中每个文件一个命名空间）
// 这里我们用字符串演示完整类名
echo "  完整类名 = 命名空间 + 类名\n";
echo "  例：App\\Database\\Connection\n";
echo "       └─ 命名空间 ─┘  └类名┘\n\n";

// 子命名空间
echo "子命名空间：\n";
echo "  namespace App\\Http\\Controllers;\n";
echo "  这创建了三层嵌套：App → Http → Controllers\n\n";


// =============================================
// 第二节：命名空间使用（use 和 as）
// =============================================

echo "【第二节：命名空间使用（use 和 as）】\n\n";

echo "使用 use 关键字引入命名空间中的类：\n";
echo <<<'CODE'
<?php
namespace App\Controllers;

// 方式1：用 use 引入完整类名
use App\Database\Connection;
use App\Logger\Logger;

class UserController {
    public function index() {
        $db = new Connection();     // 不用写完整路径了
        $log = new Logger();        // 直接用短名字
    }
}
CODE;
echo "\n\n";

echo "使用 as 给类起别名（当类名冲突时）：\n";
echo <<<'CODE'
<?php
namespace App\Controllers;

// 两个库都有 Logger 类，用 as 起别名区分
use App\Logger\Logger as AppLogger;
use ThirdParty\Logger\Logger as ThirdPartyLogger;

class UserController {
    public function index() {
        $appLog = new AppLogger();           // 用的是App自己的Logger
        $thirdLog = new ThirdPartyLogger();  // 用的是第三方的Logger
    }
}
CODE;
echo "\n\n";

echo "use 的其他用法：\n";
echo <<<'CODE'
<?php
// 引入函数（PHP 5.6+）
use function App\Helpers\formatDate;
use function App\Helpers\generateId as genId;

// 引入常量（PHP 5.6+）
use const App\Config\MAX_UPLOAD_SIZE;
use const App\Config\DEBUG_MODE as IS_DEBUG;

// 批量引入同一命名空间下的多个类
use App\Models\{User, Post, Comment};

// 引入全局命名空间的类（反斜杠开头）
use \PDO;
use \Exception;
CODE;
echo "\n\n";

echo "命名空间中的三种名称：\n";
echo "  1. 类名：    use App\\Models\\User;\n";
echo "  2. 函数名：  use function App\\Helpers\\formatDate;\n";
echo "  3. 常量名：  use const App\\Config\\DB_HOST;\n\n";

echo "访问命名空间中的元素：\n";
echo <<<'CODE'
<?php
// 非限定名称（不带反斜杠）—— 在当前命名空间中查找
$user = new User();

// 限定名称（带部分反斜杠）—— 相对路径
$user = new Models\User();  // 相当于 当前命名空间\Models\User

// 完全限定名称（以反斜杠开头）—— 绝对路径
$user = new \App\Models\User();
$conn = new \PDO('mysql:host=localhost;dbname=test', 'root', '');
CODE;
echo "\n\n";


// =============================================
// 第三节：自动加载（spl_autoload_register）
// =============================================

echo "【第三节：自动加载（spl_autoload_register）】\n\n";

echo "什么是自动加载？\n";
echo "  以前，要用一个类就得 require_once 一次，文件多了很麻烦。\n";
echo "  自动加载就是：当你 use 或 new 一个类时，\n";
echo "  PHP自动去找对应的文件并加载进来，不用手动 require。\n\n";

echo "自动加载的原理：\n";
echo "  1. 你用 new 创建一个类的对象\n";
echo "  2. PHP发现这个类还没有被加载\n";
echo "  3. PHP调用你注册的"加载器函数"\n";
echo "  4. 加载器根据类名找到对应的文件并 include 进来\n\n";

echo "简单的自动加载器：\n";
echo <<<'CODE'
<?php
// 注册自动加载函数
spl_autoload_register(function ($className) {
    // $className 可能是 "App\Models\User"
    // 把命名空间的反斜杠转成目录分隔符
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';

    // 如果文件存在就加载
    if (file_exists($file)) {
        require_once $file;
    }
});

// 现在直接 use 或 new 就行，不用 require 了
use App\Models\User;

$user = new User();  // PHP会自动加载 src/App/Models/User.php
CODE;
echo "\n\n";

echo "PSR-4 自动加载标准：\n";
echo "  PSR-4 是 PHP 社区公认的自动加载规范。\n";
echo "  核心规则：命名空间前缀 对应 根目录\n\n";

echo "PSR-4 示例：\n";
echo "  composer.json 中配置：\n";
echo "  {\n";
echo "      \"autoload\": {\n";
echo "          \"psr-4\": {\n";
echo "              \"App\\\\\": \"src/\"\n";
echo "          }\n";
echo "      }\n";
echo "  }\n\n";

echo "  对应关系：\n";
echo "    App\\Models\\User      →  src/Models/User.php\n";
echo "    App\\Http\\Controller  →  src/Http/Controller.php\n";
echo "    App\\Database\\Connection → src/Database/Connection.php\n\n";

echo "  使用 Composer 自动加载：\n";
echo <<<'CODE'
<?php
// 引入 Composer 的自动加载文件
require 'vendor/autoload.php';

// 之后所有 use / new 都会自动加载
use App\Models\User;

$user = new User();  // 自动找到 src/Models/User.php
CODE;
echo "\n\n";


// =============================================
// 第四节：类型声明 —— 参数类型
// =============================================

echo "【第四节：类型声明 —— 参数类型】\n\n";

echo "什么是类型声明？\n";
echo "  类型声明就是告诉PHP：这个变量必须是什么类型。\n";
echo "  如果传入了错误的类型，PHP会报错。\n";
echo "  这样可以在开发阶段就发现错误，而不是运行时才出问题。\n\n";

// 标量类型声明
echo "【标量类型声明】—— int、string、float、bool：\n";
echo <<<'CODE'
<?php
// 没有类型声明时（PHP 5 时代的写法）
function add($a, $b) {
    return $a + $b;
}
echo add(1, 2);       // 3，正确
echo add("1", "2");   // 3，PHP自动转换，但可能不是你想要的
echo add("abc", 2);   // 2，PHP默默忽略了错误，容易藏bug

// 有了类型声明后（PHP 7+的写法）
function addSafe(int $a, int $b): int {
    return $a + $b;
}
echo addSafe(1, 2);      // 3，正确
// echo addSafe("abc", 2); // TypeError！立刻报错，不会悄悄出错
CODE;
echo "\n\n";

// 实际演示
echo "实际演示 —— 参数类型声明：\n";

function greet(string $name): string {
    return "你好，{$name}！";
}
echo "  greet('小明') = " . greet('小明') . "\n";

function calculateArea(float $width, float $height): float {
    return $width * $height;
}
echo "  calculateArea(3.5, 2.0) = " . calculateArea(3.5, 2.0) . "\n";

function isAdult(int $age): bool {
    return $age >= 18;
}
echo "  isAdult(20) = " . (isAdult(20) ? 'true' : 'false') . "\n";
echo "  isAdult(15) = " . (isAdult(15) ? 'true' : 'false') . "\n\n";

// 数组和类类型声明
echo "【数组和类类型声明】：\n";
echo <<<'CODE'
<?php
// 数组类型
function getFirst(array $items) {
    return $items[0] ?? null;
}
echo getFirst([1, 2, 3]);           // 1
// echo getFirst("not array");       // TypeError！

// 类类型
function processUser(User $user) {
    echo $user->getName();
}
// 只能传入 User 类的实例

// 接口类型
function logMessage(LoggerInterface $logger, string $msg) {
    $logger->log($msg);
}
// 只要实现了 LoggerInterface 接口的类都可以传入
CODE;
echo "\n\n";

// callable 类型
echo "【callable 和 iterable 类型】：\n";
echo <<<'CODE'
<?php
// callable：可调用的（函数、方法、闭包）
function execute(callable $callback, $data) {
    return $callback($data);
}

// 传匿名函数
execute(function($x) { return $x * 2; }, 5);  // 10

// 传数组方法
execute('strtoupper', 'hello');  // 'HELLO'

// iterable：数组或实现了 Traversable 的对象
function processItems(iterable $items): void {
    foreach ($items as $item) {
        echo $item . "\n";
    }
}

processItems([1, 2, 3]);           // 数组可以
processItems(new ArrayObject([4, 5, 6]));  // 对象也可以
CODE;
echo "\n\n";


// =============================================
// 第五节：返回类型
// =============================================

echo "【第五节：返回类型】\n\n";

echo "返回类型声明的语法 —— 在函数名后面加 :类型：\n";
echo <<<'CODE'
<?php
// 返回整数
function add(int $a, int $b): int {
    return $a + $b;
}

// 返回字符串
function getName(): string {
    return "小明";
}

// 返回数组
function getScores(): array {
    return [90, 85, 92];
}

// 返回布尔值
function isValid(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 返回浮点数
function divide(float $a, float $b): float {
    if ($b == 0) {
        throw new Exception("除数不能为0");
    }
    return $a / $b;
}
CODE;
echo "\n\n";

// void 返回类型
echo "【void 返回类型】—— 不返回任何值：\n";
echo <<<'CODE'
<?php
// void 表示函数不返回任何值（连 return 都可以不写）
function logMessage(string $msg): void {
    file_put_contents('app.log', $msg . "\n", FILE_APPEND);
    // 没有 return 语句
}

// 如果一定要写 return，只能写 return; 不能 return 值
function printLine(string $text): void {
    echo $text . "\n";
    return;     // 可以，但没必要
    // return true;  // 错误！void 函数不能返回值
}
CODE;
echo "\n\n";

// 实际演示
echo "实际演示 —— 返回类型：\n";

function formatPrice(float $price): string {
    return "¥" . number_format($price, 2);
}
echo "  formatPrice(99.5) = " . formatPrice(99.5) . "\n";

function getEvenNumbers(int $limit): array {
    $result = [];
    for ($i = 2; $i <= $limit; $i += 2) {
        $result[] = $i;
    }
    return $result;
}
echo "  getEvenNumbers(10) = " . implode(', ', getEvenNumbers(10)) . "\n\n";


// =============================================
// 第六节：联合类型与可空类型
// =============================================

echo "【第六节：联合类型与可空类型】\n\n";

// 可空类型
echo "【可空类型 ?type】—— 参数可以是某种类型或 null：\n";
echo <<<'CODE'
<?php
// ?string 表示参数可以是 string 或 null
function findUser(?string $name): ?array {
    if ($name === null) {
        return null;  // 找不到，返回 null
    }
    // 模拟查找
    return ['name' => $name, 'age' => 25];
}

$user1 = findUser('小明');   // ['name' => '小明', 'age' => 25]
$user2 = findUser(null);     // null
// findUser(123);             // TypeError！int 不是 string 也不是 null
CODE;
echo "\n\n";

// 实际演示
echo "实际演示 —— 可空类型：\n";

function getMiddleName(?string $fullName): ?string {
    $parts = explode(' ', $fullName ?? '');
    return $parts[1] ?? null;
}
echo "  getMiddleName('张 三 丰') = " . var_export(getMiddleName('张 三 丰'), true) . "\n";
echo "  getMiddleName('张三') = " . var_export(getMiddleName('张三'), true) . "\n\n";

// 联合类型（PHP 8.0+）
echo "【联合类型 int|string】—— 参数可以是多种类型之一（PHP 8.0+）：\n";
echo <<<'CODE'
<?php
// int|string 表示可以传入整数或字符串
function formatId(int|string $id): string {
    return "ID-" . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
}

echo formatId(42);       // "ID-000042"
echo formatId("ABC123"); // "ID-ABC123"
// formatId(3.14);        // TypeError！float 不在 int|string 里

// 多种类型的联合
function setValue(int|float|bool|string $value): void {
    // 可以接收四种类型中的任意一种
}

setValue(1);        // int，可以
setValue(3.14);     // float，可以
setValue(true);     // bool，可以
setValue("hello");  // string，可以
// setValue([]);    // TypeError！array 不在联合类型里
CODE;
echo "\n\n";

// 实际演示
echo "实际演示 —— 联合类型：\n";

function parseNumber(int|string $value): float {
    if (is_int($value)) {
        return (float)$value;
    }
    return (float)str_replace(',', '', $value);
}
echo "  parseNumber(42) = " . parseNumber(42) . "\n";
echo "  parseNumber('3,500.50') = " . parseNumber('3,500.50') . "\n\n";

// never 和 mixed 类型（PHP 8.0+ / 8.1+）
echo "【其他返回类型】：\n";
echo <<<'CODE'
<?php
// never：函数永远不会正常返回（总是抛异常或终止程序）
function throwError(string $msg): never {
    throw new Exception($msg);
}

// mixed：可以是任意类型（等价于 array|bool|int|float|null|object|string|callable）
// 相当于"我不限制类型"，尽量少用
function dump(mixed $value): void {
    var_dump($value);
}
CODE;
echo "\n\n";


// =============================================
// 第七节：严格模式
// =============================================

echo "【第七节：严格模式（strict_types）】\n\n";

echo "什么是严格模式？\n";
echo "  默认情况下，PHP会"偷偷"帮你做类型转换：\n";
echo "  你传了一个字符串 \"42\" 给 int 参数，PHP会自动转成 42。\n";
echo "  这叫"强制模式"（coercive mode），有时候很方便，但容易隐藏bug。\n\n";

echo "  开启严格模式后，PHP就不会自动转换了：\n";
echo "  传错类型就直接报 TypeError，绝不含糊。\n\n";

echo "开启方式 —— 在文件最顶部声明：\n";
echo <<<'CODE'
<?php
declare(strict_types=1);

// 必须放在文件的最顶部，<?php 之后，任何代码之前
// 只对当前文件生效，不影响其他文件
CODE;
echo "\n\n";

echo "强制模式 vs 严格模式的区别：\n";
echo <<<'CODE'
<?php
// === 强制模式（默认，不声明 strict_types）===
function add(int $a, int $b): int {
    return $a + $b;
}

echo add("42", "8");   // 50  PHP自动把字符串转成数字
echo add("hello", 1);  // 1   PHP把"hello"转成了0，默默忽略了错误！

// === 严格模式（declare(strict_types=1)）===
declare(strict_types=1);

function addStrict(int $a, int $b): int {
    return $a + $b;
}

// echo addStrict("42", "8");  // Fatal error: TypeError！
// 严格模式下，字符串 "42" 不等于整数 42，必须传整数

echo addStrict(42, 8);  // 50  只能传真正的整数
CODE;
echo "\n\n";

echo "建议：\n";
echo "  在所有新项目中都开启 strict_types=1。\n";
echo "  虽然写代码时要多注意类型，但能避免很多隐藏的bug。\n";
echo "  现代PHP框架（如Laravel、Symfony）默认都开启严格模式。\n\n";


// =============================================
// 第八节：实战示例
// =============================================

echo "【第八节：实战示例 —— 用命名空间组织小型项目】\n\n";

echo "项目结构：\n";
echo "  my-shop/\n";
echo "  ├── composer.json         # Composer配置（含PSR-4自动加载）\n";
echo "  ├── vendor/               # Composer自动生成\n";
echo "  ├── src/\n";
echo "  │   ├── Models/\n";
echo "  │   │   ├── Product.php   # namespace App\\Models\n";
echo "  │   │   └── Cart.php      # namespace App\\Models\n";
echo "  │   └── Services/\n";
echo "  │       ├── PriceCalculator.php  # namespace App\\Services\n";
echo "  │       └── Logger.php           # namespace App\\Services\n";
echo "  └── index.php             # 入口文件\n\n";

echo "composer.json：\n";
echo <<<'JSON'
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
JSON;
echo "\n\n";

echo "src/Models/Product.php：\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

namespace App\Models;

class Product {
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $stock = 0
    ) {}

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function isInStock(): bool {
        return $this->stock > 0;
    }

    public function reduceStock(int $quantity): void {
        if ($quantity > $this->stock) {
            throw new \Exception("库存不足：需要 {$quantity}，仅有 {$this->stock}");
        }
        $this->stock -= $quantity;
    }
}
PHP;
echo "\n\n";

echo "src/Models/Cart.php：\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

namespace App\Models;

use App\Services\PriceCalculator;
use App\Services\Logger;

class Cart {
    private array $items = [];

    public function __construct(
        private PriceCalculator $calculator,
        private Logger $logger
    ) {}

    public function addProduct(Product $product, int $quantity = 1): void {
        $id = $product->getId();

        if (!$product->isInStock()) {
            $this->logger->warning("商品缺货", ['product' => $product->getName()]);
            return;
        }

        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] += $quantity;
        } else {
            $this->items[$id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        $this->logger->info("加入购物车", [
            'product' => $product->getName(),
            'quantity' => $quantity,
        ]);
    }

    public function getTotal(): float {
        return $this->calculator->calculateTotal($this->items);
    }

    public function getItems(): array {
        return $this->items;
    }

    public function isEmpty(): bool {
        return empty($this->items);
    }
}
PHP;
echo "\n\n";

echo "src/Services/PriceCalculator.php：\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

namespace App\Services;

class PriceCalculator {
    public function __construct(
        private float $taxRate = 0.13,
        private float $discountRate = 0.0
    ) {}

    public function calculateTotal(array $items): float {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $price = $item['product']->getPrice();
            $quantity = $item['quantity'];
            $subtotal += $price * $quantity;
        }

        // 应用折扣
        $afterDiscount = $subtotal * (1 - $this->discountRate);

        // 加税
        $total = $afterDiscount * (1 + $this->taxRate);

        return round($total, 2);
    }

    public function setDiscount(float $rate): void {
        if ($rate < 0 || $rate > 1) {
            throw new \Exception("折扣率必须在0到1之间");
        }
        $this->discountRate = $rate;
    }
}
PHP;
echo "\n\n";

echo "src/Services/Logger.php：\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

namespace App\Services;

class Logger {
    public function info(string $message, array $context = []): void {
        $this->write('INFO', $message, $context);
    }

    public function warning(string $message, array $context = []): void {
        $this->write('WARNING', $message, $context);
    }

    public function error(string $message, array $context = []): void {
        $this->write('ERROR', $message, $context);
    }

    private function write(string $level, string $message, array $context): void {
        $time = date('Y-m-d H:i:s');
        $extra = !empty($context) ? ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        echo "  [{$time}] [{$level}] {$message}{$extra}\n";
    }
}
PHP;
echo "\n\n";

echo "index.php（入口文件）—— 演示如何使用：\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

// 引入 Composer 自动加载
require 'vendor/autoload.php';

// 用 use 引入需要的类
use App\Models\Product;
use App\Models\Cart;
use App\Services\PriceCalculator;
use App\Services\Logger;

// 创建服务实例
$logger = new Logger();
$calculator = new PriceCalculator(taxRate: 0.13);

// 创建商品
$phone = new Product(1, '智能手机', 2999.00, stock: 50);
$case = new Product(2, '手机壳', 49.90, stock: 200);
$charger = new Product(3, '充电器', 89.00, stock: 0);  // 缺货

// 创建购物车
$cart = new Cart($calculator, $logger);

// 添加商品
$cart->addProduct($phone, 1);    // 成功
$cart->addProduct($case, 2);     // 成功
$cart->addProduct($charger, 1);  // 缺货，会记录警告日志

// 计算总价
echo "  购物车总价：¥" . $cart->getTotal() . "\n";
PHP;
echo "\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1（基础）：创建一个命名空间，定义两个类\n";
echo "  提示：创建命名空间 App\\Shapes，定义 Circle 和 Rectangle 两个类\n";
echo "  提示：Circle 有 $radius 属性和 area() 方法，Rectangle 有 $width/$height 和 area()\n";
echo "  提示：面积公式：圆 = π * r²，矩形 = 宽 * 高\n\n";

echo "练习2（应用）：实现一个简单的自动加载器\n";
echo "  提示：用 spl_autoload_register() 注册一个闭包\n";
echo "  提示：把类名中的 \\ 替换成 /，拼上目录前缀和 .php 后缀\n";
echo "  提示：用 file_exists() 检查文件是否存在再 require_once\n\n";

echo "练习3（进阶）：给现有函数添加完整的类型声明\n";
echo "  提示：每个参数都加上类型，返回值也声明类型\n";
echo "  提示：考虑哪些参数可以为 null（用 ?type），哪些应该用联合类型\n";
echo "  提示：在文件顶部加 declare(strict_types=1) 开启严格模式\n\n";

echo "练习4（应用）：用命名空间组织一个简单的工具库\n";
echo "  提示：创建命名空间 App\\Utils，定义 MathUtils 和 StringUtils 两个类\n";
echo "  提示：MathUtils 包含 add()、multiply()、factorial() 等静态方法\n";
echo "  提示：StringUtils 包含 slugify()、truncate()、camelCase() 等静态方法\n";
echo "  提示：用 use App\\Utils\\MathUtils; 引入后直接 MathUtils::add(1, 2) 调用\n\n";

echo "练习5（进阶）：给一个现有函数添加完整的类型声明\n";
echo "  提示：选一个没有类型声明的函数，给每个参数加上类型，返回值也声明\n";
echo "  提示：考虑 nullable 类型（?string）和联合类型（int|string）\n";
echo "  提示：在文件顶部加 declare(strict_types=1)，测试传入错误类型会怎样\n\n";


// =============================================
// 练习参考答案
// =============================================
echo "\n" . str_repeat("=", 50) . "\n";
echo "练习参考答案\n";
echo str_repeat("=", 50) . "\n";


// 练习1参考：创建一个命名空间，定义两个类
echo "\n--- 练习1 参考：命名空间 + 几何图形类 ---\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

// 文件：src/Shapes/Circle.php
namespace App\Shapes;

class Circle {
    // 构造函数：接收半径
    public function __construct(
        private float $radius
    ) {
        if ($radius <= 0) {
            throw new \ValueError("半径必须大于0");
        }
    }

    // 计算面积：π * r²
    public function area(): float {
        return M_PI * $this->radius ** 2;
    }

    // 计算周长：2 * π * r
    public function perimeter(): float {
        return 2 * M_PI * $this->radius;
    }

    public function getRadius(): float {
        return $this->radius;
    }

    // 格式化输出
    public function describe(): string {
        return "圆形（半径={$this->radius}）：面积=" . number_format($this->area(), 2);
    }
}

// 文件：src/Shapes/Rectangle.php
namespace App\Shapes;

class Rectangle {
    public function __construct(
        private float $width,
        private float $height
    ) {
        if ($width <= 0 || $height <= 0) {
            throw new \ValueError("宽和高必须大于0");
        }
    }

    // 计算面积：宽 * 高
    public function area(): float {
        return $this->width * $this->height;
    }

    // 计算周长：2 * (宽 + 高)
    public function perimeter(): float {
        return 2 * ($this->width + $this->height);
    }

    public function getWidth(): float {
        return $this->width;
    }

    public function getHeight(): float {
        return $this->height;
    }

    public function describe(): string {
        return "矩形（{$this->width}x{$this->height}）：面积=" . number_format($this->area(), 2);
    }
}

// 文件：src/Shapes/Square.php（继承 Rectangle 的特殊矩形）
namespace App\Shapes;

class Square extends Rectangle {
    public function __construct(float $side) {
        parent::__construct($side, $side);
    }

    public function describe(): string {
        return "正方形（边长={$this->getWidth()}）：面积=" . number_format($this->area(), 2);
    }
}

// 使用示例：
// require 'vendor/autoload.php';
// use App\Shapes\{Circle, Rectangle, Square};
//
// $circle = new Circle(5);
// echo $circle->describe();   // 圆形（半径=5）：面积=78.54
//
// $rect = new Rectangle(4, 6);
// echo $rect->describe();     // 矩形（4x6）：面积=24.00
//
// $square = new Square(3);
// echo $square->describe();   // 正方形（边长=3）：面积=9.00
PHP;
echo "\n";


// 练习2参考：实现一个简单的自动加载器
echo "\n--- 练习2 参考：简单自动加载器 ---\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

// ===== 方式1：基础自动加载器 =====
// 根据命名空间映射到目录结构
spl_autoload_register(function (string $className): void {
    // 定义命名空间前缀和目录的映射
    $mappings = [
        'App\\'        => __DIR__ . '/src/',
        'App\\Models\\' => __DIR__ . '/src/Models/',
        'Lib\\'        => __DIR__ . '/lib/',
    ];

    foreach ($mappings as $prefix => $dir) {
        // 检查类名是否以这个前缀开头
        if (str_starts_with($className, $prefix)) {
            // 去掉前缀，得到相对类名
            $relativeClass = substr($className, strlen($prefix));
            // 把反斜杠替换成目录分隔符
            $file = $dir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});


// ===== 方式2：PSR-4 标准自动加载器 =====
spl_autoload_register(function (string $className): void {
    // PSR-4 映射：App\ -> src/
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/src/';

    // 检查类名是否有指定前缀
    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        return;  // 不是这个前缀，跳过
    }

    // 获取相对类名
    $relativeClass = substr($className, $len);

    // 把命名空间分隔符转成目录分隔符，拼上基础目录
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    // 如果文件存在则加载
    if (file_exists($file)) {
        require_once $file;
    }
});


// ===== 使用示例 =====
// 项目结构：
// my-project/
// ├── autoload.php    （上面的自动加载代码）
// ├── src/
// │   ├── Models/
// │   │   └── User.php       （namespace App\Models;）
// │   └── Services/
// │       └── Logger.php     （namespace App\Services;）
// └── index.php
//
// index.php:
// require 'autoload.php';
// use App\Models\User;
// use App\Services\Logger;
// $user = new User();      // 自动加载 src/Models/User.php
// $log = new Logger();     // 自动加载 src/Services/Logger.php
PHP;
echo "\n";


// 练习3参考：给现有函数添加完整的类型声明
echo "\n--- 练习3 参考：添加类型声明 ---\n";
echo <<<'PHP'
<?php
declare(strict_types=1);

// ===== 改造前（没有类型声明）=====
// function createUser($name, $email, $age, $role, $active) {
//     if (empty($name)) return false;
//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
//     $user = [
//         'name' => $name,
//         'email' => $email,
//         'age' => $age,
//         'role' => $role,
//         'active' => $active,
//         'created_at' => date('Y-m-d H:i:s'),
//     ];
//     return $user;
// }
//
// 改造前的问题：
// - $name 可以传入数字、数组、任何东西
// - $age 可以传入负数、字符串
// - $role 没有限制，可以传入任意值
// - 返回值有时是 false，有时是数组，调用者很难处理


// ===== 改造后（完整类型声明）=====
function createUser(
    string $name,                    // 必须是字符串
    string $email,                   // 必须是字符串
    int $age,                        // 必须是整数
    string $role = 'user',           // 字符串，有默认值
    bool $active = true              // 布尔值，有默认值
): array {                           // 返回数组
    // 验证用户名
    if (trim($name) === '') {
        throw new \ValueError("用户名不能为空");
    }

    // 验证邮箱
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new \ValueError("邮箱格式不正确：{$email}");
    }

    // 验证年龄
    if ($age < 0 || $age > 150) {
        throw new \ValueError("年龄必须在0到150之间");
    }

    // 验证角色
    $validRoles = ['user', 'editor', 'admin'];
    if (!in_array($role, $validRoles, true)) {
        throw new \ValueError("无效的角色：{$role}，可选值：" . implode(', ', $validRoles));
    }

    return [
        'name' => trim($name),
        'email' => strtolower($email),
        'age' => $age,
        'role' => $role,
        'active' => $active,
        'created_at' => date('Y-m-d H:i:s'),
    ];
}

// 查找用户（返回可空类型）
function findUserById(int $id, array $users): ?array {
    return $users[$id] ?? null;
}

// 格式化用户信息
function formatUser(array $user): string {
    $status = $user['active'] ? '活跃' : '禁用';
    return "{$user['name']} ({$user['email']}) - 角色:{$user['role']} 状态:{$status}";
}

// 批量创建用户
function createUsers(array $dataList): array {
    $users = [];
    $errors = [];

    foreach ($dataList as $index => $data) {
        try {
            $users[] = createUser(
                name: $data['name'] ?? '',
                email: $data['email'] ?? '',
                age: (int)($data['age'] ?? 0),
                role: $data['role'] ?? 'user',
                active: (bool)($data['active'] ?? true)
            );
        } catch (\ValueError $e) {
            $errors[$index] = $e->getMessage();
        }
    }

    return ['users' => $users, 'errors' => $errors];
}

// ===== 使用示例 =====
try {
    $user = createUser('小明', 'xiaoming@test.com', 25, 'admin');
    echo "创建成功：" . formatUser($user) . "\n";

    // 以下会抛出异常：
    // createUser('', 'test@test.com', 25);      // 用户名为空
    // createUser('小明', 'invalid-email', 25);   // 邮箱格式错误
    // createUser('小明', 'test@test.com', -5);   // 年龄不合法
    // createUser('小明', 'test@test.com', 25, 'superadmin'); // 无效角色

} catch (\ValueError $e) {
    echo "错误：" . $e->getMessage() . "\n";
}

// 批量创建
$results = createUsers([
    ['name' => '小红', 'email' => 'xiaohong@test.com', 'age' => 22],
    ['name' => '', 'email' => 'bad', 'age' => -1],  // 会出错
    ['name' => '小刚', 'email' => 'xiaogang@test.com', 'age' => 30],
]);

echo "成功创建 " . count($results['users']) . " 个用户\n";
echo "失败 " . count($results['errors']) . " 个\n";
PHP;
echo "\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 命名空间用 namespace 声明，用 use 引入，用 as 起别名
 * - PSR-4 自动加载：命名空间前缀对应目录，Composer 自动处理
 * - 参数类型声明：int、string、float、bool、array、callable、类名
 * - 返回类型声明：在函数名后加 :类型，void 表示无返回值
 * - 可空类型 ?string 表示可以是 string 或 null
 * - 联合类型 int|string（PHP 8.0+）表示多种类型之一
 * - strict_types=1 开启严格模式，禁止隐式类型转换
 *
 * 常见陷阱：
 * - namespace 声明必须在文件最顶部（<?php 之后，其他代码之前）
 * - 同一个文件中不应该有多个 namespace 声明（虽然语法允许）
 * - use 引入的是类名，不是文件路径；类名和文件路径的映射靠自动加载器
 * - 严格模式下字符串 "42" 不等于整数 42，必须传真正的整数
 * - void 函数里 return 值会报错，只能写 return; 或不写
 *
 * 下节课预告：
 * - 第17课我们将学习项目实战 —— 用前面所有知识构建完整项目。
 */

// =============================================
// 第九节：PHP 8.0+ 新特性
// =============================================

echo "\n【第九节：PHP 8.0+ 新特性】\n\n";

// 【enum 枚举（PHP 8.1）】
// 枚举是一种特殊的类型，只能有预定义的值
// 就像交通信号灯，只能是红、绿、黄之一

echo "【enum 枚举（PHP 8.1）】\n";
echo <<<'PHP'
<?php
// 枚举的基本定义
enum Status {
    case Pending;
    case Active;
    case Disabled;
}

// 使用枚举
$status = Status::Active;
echo "状态：{$status->name}\n";  // 输出：状态：Active

// 枚举用于类型声明
function updateUserStatus(Status $status): void {
    echo "更新状态为：{$status->name}\n";
}
updateUserStatus(Status::Pending);   // 正确
// updateUserStatus('pending');       // 错误！字符串不是 Status 枚举
PHP;
echo "\n\n";

// 实际演示
echo "实际演示 —— enum 枚举：\n";

// 模拟枚举效果（当前PHP版本可能不支持enum）
echo "  枚举让代码更安全：\n";
echo "  - 只能传入预定义的值，不会传入错误的字符串\n";
echo "  - IDE 可以自动补全枚举值\n";
echo "  - 重构时修改枚举，所有使用处都会更新\n\n";

// 带值的枚举
echo "带值的枚举（backed enum）：\n";
echo <<<'PHP'
<?php
// 带字符串值的枚举
enum Color: string {
    case Red = '#FF0000';
    case Green = '#00FF00';
    case Blue = '#0000FF';
}

echo Color::Red->value;    // '#FF0000'
echo Color::Red->name;     // 'Red'

// 从值反向查找枚举
$color = Color::from('#00FF00');        // Color::Green
$color = Color::tryFrom('#FFFFFF');     // null（不存在则返回null，不报错）
PHP;
echo "\n\n";


// 【readonly 属性（PHP 8.1）】
// 只读属性，初始化后不能修改

echo "【readonly 属性（PHP 8.1）】\n";
echo <<<'PHP'
<?php
class User {
    public function __construct(
        public readonly string $name,
        public readonly int $age,
    ) {}
}

$user = new User("小明", 18);
echo "姓名：{$user->name}\n";   // 输出：姓名：小明
echo "年龄：{$user->age}\n";    // 输出：年龄：18

// $user->name = "小红";  // 错误！readonly 属性不能修改
// 试图修改会抛出 Error: Cannot modify readonly property User::$name
PHP;
echo "\n\n";

// 实际演示
echo "实际演示 —— readonly 属性：\n";

echo "  readonly 的典型用法：\n";
echo "  - 数据传输对象（DTO）：一旦创建就不能修改\n";
echo "  - 值对象：如 Money、DateRange 等不可变对象\n";
echo "  - 配置对象：配置项初始化后不应被意外修改\n\n";

echo "readonly vs const 的区别：\n";
echo "  - const 是类级别的常量，所有实例共享同一个值\n";
echo "  - readonly 是实例级别的属性，每个实例可以有不同的值\n";
echo "  - const 只能是字面量（数字、字符串等），readonly 可以是任何类型\n\n";


// 【?-> nullsafe 运算符（PHP 8.0）】
// 如果对象为 null，直接返回 null，不会报错

echo "【?-> nullsafe 运算符（PHP 8.0）】\n";
echo <<<'PHP'
<?php
class Address {
    public string $city = "北京";
}

class Person {
    public ?Address $address = null;
}

$person = new Person();

// 不用 nullsafe 的写法（繁琐）
if ($person->address !== null) {
    echo $person->address->city;
} else {
    echo '未知';
}

// 用 nullsafe 的写法（简洁）
echo $person->address?->city ?? '未知';
// 如果 address 为 null，直接返回 null，不会报错
// ?? '未知' 处理最终的 null 值
PHP;
echo "\n\n";

// 实际演示
echo "实际演示 —— nullsafe 运算符：\n";

echo "  nullsafe 的好处：\n";
echo "  - 避免 'Trying to access property of null' 错误\n";
echo "  - 简化深层嵌套的 null 检查\n";
echo "  - 链式调用更安全：\$user?->getAddress()?->getCity()\n\n";

echo "  对比传统写法：\n";
echo "  // 传统写法（需要多层判断）\n";
echo "  if (\$user && \$user->getAddress() && \$user->getAddress()->getCity()) {\n";
echo "      echo \$user->getAddress()->getCity();\n";
echo "  }\n\n";
echo "  // nullsafe 写法（一行搞定）\n";
echo "  echo \$user?->getAddress()?->getCity() ?? '未知';\n\n";


// =============================================
// 课程总结（更新）
// =============================================
/*
 * 核心收获：
 * - 命名空间用 namespace 声明，用 use 引入，用 as 起别名
 * - PSR-4 自动加载：命名空间前缀对应目录，Composer 自动处理
 * - 参数类型声明：int、string、float、bool、array、callable、类名
 * - 返回类型声明：在函数名后加 :类型，void 表示无返回值
 * - 可空类型 ?string 表示可以是 string 或 null
 * - 联合类型 int|string（PHP 8.0+）表示多种类型之一
 * - strict_types=1 开启严格模式，禁止隐式类型转换
 * - enum 枚举（PHP 8.1）：限制变量只能取预定义的值
 * - readonly 属性（PHP 8.1）：初始化后不能修改，适合不可变对象
 * - ?-> nullsafe 运算符（PHP 8.0）：安全访问可能为 null 的对象属性
 *
 * 常见陷阱：
 * - namespace 声明必须在文件最顶部（<?php 之后，其他代码之前）
 * - 同一个文件中不应该有多个 namespace 声明（虽然语法允许）
 * - use 引入的是类名，不是文件路径；类名和文件路径的映射靠自动加载器
 * - 严格模式下字符串 "42" 不等于整数 42，必须传真正的整数
 * - void 函数里 return 值会报错，只能写 return; 或不写
 * - enum 的 case 不用 $ 符号，也不用引号
 * - readonly 属性只能在构造函数中初始化一次
 *
 * 下节课预告：
 * - 第17课我们将学习项目实战 —— 用前面所有知识构建完整项目。
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第16课！\n";
echo "   你已经掌握了命名空间和类型声明这两个现代PHP的核心特性。\n";
echo "   继续加油！\n";
echo "============================================================\n";
