<?php
// =============================================
// 第 12 课：面向对象编程（下）
// =============================================
// 上节课我们学了OOP基础：类与对象。
// 这节课我们要学习继承、多态、接口等高级OOP特性。

echo "============================================================\n";
echo "   第12课：面向对象编程（下）\n";
echo "============================================================\n\n";

// 【生活类比】
// 继承就像"遗传"——孩子（子类）从父母（父类）那里继承了外貌和血型，
// 但也可以有自己的特点，比如比父母更高、更聪明（重写方法、新增方法）。
// 多态就像"打招呼"这个动作——中国人说"你好"，美国人说"Hello"，
// 同一个动作（接口），不同的人有不同的表现方式（实现）。
// 接口就像"合同"——你签了合同（implements），就必须完成合同上写的所有条款（实现所有方法）。

// =============================================
// 第一节：继承
// =============================================

echo "【第一节：继承】\n\n";

// 父类（基类）
class Animal {
    protected $name;
    protected $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }

    public function eat() {
        echo "$this->name 正在吃东西\n";
    }

    public function sleep() {
        echo "$this->name 正在睡觉\n";
    }

    public function info() {
        echo "名字：$this->name，年龄：$this->age\n";
    }
}

// 子类继承父类
class Dog extends Animal {
    public function bark() {
        echo "$this->name 汪汪叫\n";
    }

    // 重写父类方法
    public function info() {
        echo "【狗】名字：$this->name，年龄：$this->age\n";
    }
}

// 猫类：同样继承 Animal，拥有自己特有的叫声方法
class Cat extends Animal {
    public function meow() {
        echo "$this->name 喵喵叫\n";
    }

    public function info() {
        echo "【猫】名字：$this->name，年龄：$this->age\n";
    }
}

echo "继承示例：\n";
$dog = new Dog("旺财", 3);
$dog->info();     // 调用子类方法
$dog->eat();      // 继承父类方法
$dog->bark();     // 子类特有方法

echo "\n";

$cat = new Cat("咪咪", 2);
$cat->info();
$cat->eat();
$cat->meow();
echo "\n";


// =============================================
// 第二节：方法重写（Override）
// =============================================

echo "【第二节：方法重写】\n\n";

// 交通工具基类：下面将通过子类重写它的方法来演示 Override
class Vehicle {
    protected $brand;
    protected $speed = 0;

    public function __construct($brand) {
        $this->brand = $brand;
    }

    public function accelerate($amount) {
        $this->speed += $amount;
        echo "$this->brand 加速到 {$this->speed}km/h\n";
    }

    public function brake() {
        $this->speed = max(0, $this->speed - 20);
        echo "$this->brand 减速到 {$this->speed}km/h\n";
    }
}

// 跑车子类：重写父类的加速和刹车方法，体现多态特性
class SportsCar extends Vehicle {
    private $turbo = false;

    public function enableTurbo() {
        $this->turbo = true;
        echo "涡轮增压已开启！\n";
    }

    // 重写加速方法
    public function accelerate($amount) {
        if ($this->turbo) {
            $amount *= 2;  // 涡轮增压效果
        }
        parent::accelerate($amount);  // 调用父类方法
    }

    // 重写刹车方法
    public function brake() {
        $this->speed = max(0, $this->speed - 30);  // 跑车刹车更强
        echo "$this->brand 紧急减速到 {$this->speed}km/h\n";
    }
}

echo "方法重写示例：\n";
$car = new SportsCar("法拉利");
$car->accelerate(50);
$car->enableTurbo();
$car->accelerate(50);
$car->brake();
echo "\n";


// =============================================
// 第三节：抽象类
// =============================================

echo "【第三节：抽象类】\n\n";

// 抽象类：不能实例化，只能被继承
abstract class Shape {
    protected $color;

    public function __construct($color) {
        $this->color = $color;
    }

    // 抽象方法：子类必须实现
    abstract public function area();
    abstract public function perimeter();

    // 普通方法：子类可以直接使用
    public function describe() {
        echo "这是一个{$this->color}的图形，";
        echo "面积：" . number_format($this->area(), 2) . "，";
        echo "周长：" . number_format($this->perimeter(), 2) . "\n";
    }
}

// 圆形类：继承抽象类 Shape，必须实现 area() 和 perimeter() 方法
class Circle extends Shape {
    private $radius;

    public function __construct($color, $radius) {
        parent::__construct($color);
        $this->radius = $radius;
    }

    public function area() {
        return M_PI * $this->radius ** 2;
    }

    public function perimeter() {
        return 2 * M_PI * $this->radius;
    }
}

// 矩形类：用宽和高计算面积与周长
class Rectangle extends Shape {
    private $width;
    private $height;

    public function __construct($color, $width, $height) {
        parent::__construct($color);
        $this->width = $width;
        $this->height = $height;
    }

    public function area() {
        return $this->width * $this->height;
    }

    public function perimeter() {
        return 2 * ($this->width + $this->height);
    }
}

echo "抽象类示例：\n";
// $shape = new Shape("红");  // 错误！抽象类不能实例化

$circle = new Circle("红色", 5);
$circle->describe();

$rect = new Rectangle("蓝色", 10, 6);
$rect->describe();
echo "\n";


// =============================================
// 第四节：接口
// =============================================

echo "【第四节：接口】\n\n";

// 接口：定义规范，不实现细节
interface Flyable {
    public function fly();
    public function land();
}

// 可游泳接口：定义游泳和潜水两个行为规范
interface Swimmable {
    public function swim();
    public function dive();
}

// 一个类可以实现多个接口
class Duck extends Animal implements Flyable, Swimmable {
    public function fly() {
        echo "$this->name 正在飞\n";
    }

    public function land() {
        echo "$this->name 着陆了\n";
    }

    public function swim() {
        echo "$this->name 正在游泳\n";
    }

    public function dive() {
        echo "$this->name 潜水了\n";
    }
}

echo "接口示例：\n";
$duck = new Duck("唐老鸭", 5);
$duck->eat();
$duck->fly();
$duck->swim();
$duck->land();
echo "\n";

// 接口类型检查
echo "接口类型检查：\n";
echo "鸭子会飞：" . ($duck instanceof Flyable ? '是' : '否') . "\n";
echo "鸭子会游泳：" . ($duck instanceof Swimmable ? '是' : '否') . "\n\n";


// =============================================
// 第五节：Trait（特征）
// =============================================

echo "【第五节：Trait】\n\n";

// Trait：代码复用机制，解决PHP单继承的限制
trait Loggable {
    public function log($message) {
        echo "[" . date('Y-m-d H:i:s') . "] $message\n";
    }
}

// 缓存特征：提供键值对缓存功能，use 后任何类都能获得缓存能力
trait Cacheable {
    private $cache = [];

    public function cache($key, $value = null) {
        if ($value === null) {
            return $this->cache[$key] ?? null;
        }
        $this->cache[$key] = $value;
    }
}

// 使用 Trait
class UserService {
    use Loggable, Cacheable;

    public function getUser($id) {
        $this->log("获取用户：$id");

        // 检查缓存
        $cached = $this->cache("user_$id");
        if ($cached) {
            $this->log("从缓存获取");
            return $cached;
        }

        // 模拟从数据库获取
        $user = ["id" => $id, "name" => "用户$id"];
        $this->cache("user_$id", $user);
        $this->log("从数据库获取并缓存");

        return $user;
    }
}

echo "Trait 示例：\n";
$service = new UserService();
$user = $service->getUser(1);
print_r($user);
echo "\n";


// =============================================
// 第六节：多态
// =============================================

echo "【第六节：多态】\n\n";

// 多态：同一个接口，不同的实现
interface Payment {
    public function pay($amount);
}

// 支付宝类：实现 Payment 接口，定义自己的 pay() 逻辑
class Alipay implements Payment {
    public function pay($amount) {
        echo "支付宝支付：$amount 元\n";
    }
}

// 微信支付类：同样的接口，不同的实现——这就是多态的精髓
class WechatPay implements Payment {
    public function pay($amount) {
        echo "微信支付：$amount 元\n";
    }
}

// 信用卡支付类：第三个 Payment 实现
class CreditCard implements Payment {
    public function pay($amount) {
        echo "信用卡支付：$amount 元\n";
    }
}

// 统一的支付处理函数——参数类型声明为 Payment 接口，
// 任何实现了 Payment 的类都可以传入，体现了面向接口编程
function processPayment(Payment $payment, $amount) {
    $payment->pay($amount);
}

echo "多态示例：\n";
$alipay = new Alipay();
$wechat = new WechatPay();
$card = new CreditCard();

processPayment($alipay, 100);
processPayment($wechat, 200);
processPayment($card, 300);
echo "\n";


// =============================================
// 第七节：魔术方法
// =============================================

echo "【第七节：魔术方法】\n\n";

// 魔术方法演示类：通过 __get/__set 等"双下划线"方法拦截对象操作
class MagicClass {
    private $data = [];

    // __get：读取不存在的属性时调用
    public function __get($name) {
        echo "获取属性：$name\n";
        return $this->data[$name] ?? null;
    }

    // __set：设置不存在的属性时调用
    public function __set($name, $value) {
        echo "设置属性：$name = $value\n";
        $this->data[$name] = $value;
    }

    // __isset：isset() 检查不存在的属性时调用
    public function __isset($name) {
        echo "检查属性：$name\n";
        return isset($this->data[$name]);
    }

    // __unset：unset() 删除不存在的属性时调用
    public function __unset($name) {
        echo "删除属性：$name\n";
        unset($this->data[$name]);
    }

    // __toString：对象转字符串时调用
    public function __toString() {
        return "MagicClass: " . json_encode($this->data);
    }

    // __call：调用不存在的方法时调用
    public function __call($name, $arguments) {
        echo "调用不存在的方法：$name(" . implode(', ', $arguments) . ")\n";
    }

    // __callStatic：静态调用不存在的方法时调用
    public static function __callStatic($name, $arguments) {
        echo "静态调用不存在的方法：$name(" . implode(', ', $arguments) . ")\n";
    }
}

echo "魔术方法示例：\n";
$obj = new MagicClass();

// __set
$obj->name = "小明";
$obj->age = 25;

// __get
echo "名字：" . $obj->name . "\n";

// __isset
echo "name 存在：" . (isset($obj->name) ? '是' : '否') . "\n";

// __toString
echo "对象：" . $obj . "\n";

// __call
$obj->hello("世界");

// __callStatic
MagicClass::staticHello("世界");
echo "\n";


// =============================================
// 第八节：final 关键字
// =============================================

echo "【第八节：final 关键字】\n\n";

// final 类：不能被继承
final class FinalClass {
    public function sayHello() {
        echo "这是 final 类\n";
    }
}

// class ExtendedFinal extends FinalClass {}  // 错误！final 类不能被继承

// final 方法：不能被重写
class ParentClass {
    final public function cannotOverride() {
        echo "这个方法不能被重写\n";
    }

    public function canOverride() {
        echo "这个方法可以被重写\n";
    }
}

// 子类：可以重写普通方法，但尝试重写 final 方法会报错
class ChildClass extends ParentClass {
    // public function cannotOverride() {}  // 错误！final 方法不能被重写

    public function canOverride() {
        echo "子类重写了这个方法\n";
    }
}

echo "final 关键字示例：\n";
$child = new ChildClass();
$child->cannotOverride();
$child->canOverride();
echo "\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：设计一个"员工"类层次结构：Employee（基类）、Manager（经理）、Developer（开发者）\n";
echo "  提示：Employee 有 name、salary 属性和 work() 方法\n";
echo "  提示：Manager 和 Developer 继承 Employee，重写 work() 方法，各自添加特有方法\n\n";

echo "练习2：设计一个接口 LoggerInterface，实现 FileLogger 和 ConsoleLogger\n";
echo "  提示：接口定义 log()、error()、warning() 方法签名\n";
echo "  提示：FileLogger 写入文件，ConsoleLogger 输出到屏幕，用 implements 实现接口\n\n";

echo "练习3：使用 Trait 实现一个简单的缓存系统\n";
echo "  提示：trait 中定义 cacheGet(\$key)、cacheSet(\$key, \$value, \$ttl) 方法\n";
echo "  提示：用一个 private \$cache 数组存数据，用时间戳判断是否过期\n\n";

echo "练习4：设计一个简单的计算器，支持不同的运算（加减乘除），使用多态\n";
echo "  提示：定义一个 Operation 接口，有 calculate(\$a, \$b) 方法\n";
echo "  提示：每种运算（加减乘除）是一个类，Calculator 类接受 Operation 对象\n\n";

echo "练习5：设计一个简单的ORM（对象关系映射）基础类\n";
echo "  提示：基类 Model 用 __get/__set 存取属性，用 static \$table 指定表名\n";
echo "  提示：实现 find(\$id)、all()、save()、delete() 方法，输出SQL语句即可\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";

// 员工基类：所有员工共有的属性和行为
class Employee {
    protected $name;
    protected $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function work() {
        echo "$this->name 正在工作\n";
    }

    public function getSalary() {
        return $this->salary;
    }
}

// 经理子类：继承 Employee 并重写 work()，新增开会方法
class Manager extends Employee {
    private $department;

    public function __construct($name, $salary, $department) {
        parent::__construct($name, $salary);
        $this->department = $department;
    }

    public function work() {
        echo "$this->name 正在管理{$this->department}部门\n";
    }

    public function meeting() {
        echo "$this->name 正在开会\n";
    }
}

// 开发者子类：继承 Employee 并重写 work()，新增调试方法
class Developer extends Employee {
    private $language;

    public function __construct($name, $salary, $language) {
        parent::__construct($name, $salary);
        $this->language = $language;
    }

    public function work() {
        echo "$this->name 正在用{$this->language}编程\n";
    }

    public function debug() {
        echo "$this->name 正在调试代码\n";
    }
}

$manager = new Manager("张经理", 20000, "技术部");
$developer = new Developer("李开发", 15000, "PHP");

$manager->work();
$manager->meeting();
$developer->work();
$developer->debug();
echo "\n";

// 练习2
echo "--- 练习2答案 ---\n";

interface LoggerInterface {
    public function log($message, $level = 'INFO');
    public function error($message);
    public function warning($message);
}

// 文件日志类：将日志写入文件，使用 FILE_APPEND 追加模式
class FileLogger implements LoggerInterface {
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    public function log($message, $level = 'INFO') {
        $entry = "[" . date('Y-m-d H:i:s') . "] [$level] $message\n";
        file_put_contents($this->file, $entry, FILE_APPEND);
    }

    public function error($message) {
        $this->log($message, 'ERROR');
    }

    public function warning($message) {
        $this->log($message, 'WARNING');
    }
}

// 控制台日志类：将日志输出到屏幕，适合开发调试
class ConsoleLogger implements LoggerInterface {
    public function log($message, $level = 'INFO') {
        echo "[$level] $message\n";
    }

    public function error($message) {
        echo "[ERROR] $message\n";
    }

    public function warning($message) {
        echo "[WARNING] $message\n";
    }
}

$logger = new ConsoleLogger();
$logger->log("应用启动");
$logger->warning("内存使用过高");
$logger->error("数据库连接失败");
echo "\n";

// 练习3
echo "--- 练习3答案 ---\n";

trait SimpleCache {
    private $cache = [];
    private $cache_ttl = [];
    private $default_ttl = 3600;

    public function cacheGet($key) {
        if (!isset($this->cache[$key])) {
            return null;
        }

        if (time() > $this->cache_ttl[$key]) {
            unset($this->cache[$key], $this->cache_ttl[$key]);
            return null;
        }

        return $this->cache[$key];
    }

    public function cacheSet($key, $value, $ttl = null) {
        $this->cache[$key] = $value;
        $this->cache_ttl[$key] = time() + ($ttl ?? $this->default_ttl);
    }

    public function cacheDelete($key) {
        unset($this->cache[$key], $this->cache_ttl[$key]);
    }

    public function cacheClear() {
        $this->cache = [];
        $this->cache_ttl = [];
    }
}

class ProductService {
    use SimpleCache;

    public function getProduct($id) {
        $cached = $this->cacheGet("product_$id");
        if ($cached) {
            echo "从缓存获取产品 $id\n";
            return $cached;
        }

        // 模拟数据库查询
        $product = ["id" => $id, "name" => "产品$id", "price" => 100];
        $this->cacheSet("product_$id", $product, 60);
        echo "从数据库获取产品 $id 并缓存\n";

        return $product;
    }
}

$ps = new ProductService();
$ps->getProduct(1);
$ps->getProduct(1);  // 从缓存获取
echo "\n";

// 练习4
echo "--- 练习4答案 ---\n";

interface Operation {
    public function calculate($a, $b);
    public function getSymbol();
}

class Addition implements Operation {
    public function calculate($a, $b) { return $a + $b; }
    public function getSymbol() { return '+'; }
}

class Subtraction implements Operation {
    public function calculate($a, $b) { return $a - $b; }
    public function getSymbol() { return '-'; }
}

class Multiplication implements Operation {
    public function calculate($a, $b) { return $a * $b; }
    public function getSymbol() { return '*'; }
}

class Division implements Operation {
    public function calculate($a, $b) {
        if ($b == 0) throw new Exception("除数不能为0");
        return $a / $b;
    }
    public function getSymbol() { return '/'; }
}

// 计算器类：通过注册不同的 Operation 对象来实现运算，策略模式的体现
class Calculator {
    private $operations = [];

    public function addOperation(Operation $op) {
        $this->operations[$op->getSymbol()] = $op;
    }

    public function calculate($a, $symbol, $b) {
        if (!isset($this->operations[$symbol])) {
            throw new Exception("未知运算符：$symbol");
        }
        return $this->operations[$symbol]->calculate($a, $b);
    }
}

$calc = new Calculator();
$calc->addOperation(new Addition());
$calc->addOperation(new Subtraction());
$calc->addOperation(new Multiplication());
$calc->addOperation(new Division());

echo "10 + 5 = " . $calc->calculate(10, '+', 5) . "\n";
echo "10 - 5 = " . $calc->calculate(10, '-', 5) . "\n";
echo "10 * 5 = " . $calc->calculate(10, '*', 5) . "\n";
echo "10 / 5 = " . $calc->calculate(10, '/', 5) . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";

// ORM 基类：用魔术方法 __get/__set 实现属性存取，
// static::$table 利用后期静态绑定让子类各自指定表名
class Model {
    protected static $table = '';
    protected $attributes = [];

    public function __construct($attributes = []) {
        $this->attributes = $attributes;
    }

    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    public static function find($id) {
        echo "SELECT * FROM " . static::$table . " WHERE id = $id\n";
        // 模拟返回
        return new static(['id' => $id, 'name' => '模拟数据']);
    }

    public static function all() {
        echo "SELECT * FROM " . static::$table . "\n";
        return [];
    }

    public function save() {
        if (isset($this->attributes['id'])) {
            echo "UPDATE " . static::$table . " SET ... WHERE id = {$this->attributes['id']}\n";
        } else {
            echo "INSERT INTO " . static::$table . " ...\n";
        }
    }

    public function delete() {
        echo "DELETE FROM " . static::$table . " WHERE id = {$this->attributes['id']}\n";
    }
}

class User extends Model {
    protected static $table = 'users';
}

class Product extends Model {
    protected static $table = 'products';
}

echo "简单ORM示例：\n";
$user = User::find(1);
echo "用户名：" . $user->name . "\n";

$product = new Product(['name' => '手机', 'price' => 2999]);
$product->save();


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 继承用extends，接口用implements，Trait用use
 * - 抽象类定义规范不能实例化，接口只定义行为规范
 * - 多态让同一接口有不同的实现，是OOP灵活性的核心
 *
 * 常见陷阱：
 * - PHP是单继承，一个类只能extends一个父类，但可以implements多个接口
 * - 子类重写方法时，访问范围不能比父类更严格（public不能改成private）
 * - 魔术方法__get/__set会降低性能，不要滥用
 *
 * 下节课预告：
 * - 第13课我们将学习错误与异常处理，让程序更健壮
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第12课！\n";
echo "   下节课：错误与异常处理 —— 让程序更健壮\n";
echo "============================================================\n";
