<?php
// =============================================
// 第 11 课：面向对象编程（上）
// =============================================
// 上节课我们学了文件操作。
// 这节课我们要学习面向对象编程（OOP）的基础：类与对象。

echo "============================================================\n";
echo "   第11课：面向对象编程（上）\n";
echo "============================================================\n\n";


// =============================================
// 第一节：什么是面向对象编程？
// =============================================

echo "【第一节：什么是面向对象编程？】\n\n";

// 面向对象 vs 面向过程
echo "面向过程 vs 面向对象：\n\n";

// 面向过程：关注"怎么做"
echo "面向过程（关注步骤）：\n";
echo "  1. 创建变量存储姓名\n";
echo "  2. 创建变量存储年龄\n";
echo "  3. 定义函数打招呼\n";
echo "  4. 调用函数\n\n";

// 面向对象：关注"谁来做"
echo "面向对象（关注对象）：\n";
echo "  1. 定义一个'人'的类（模板）\n";
echo "  2. 创建一个'人'的对象（实例）\n";
echo "  3. 让这个'人'打招呼\n\n";

// 生活类比
echo "生活类比：\n";
echo "类（Class）= 图纸/模板\n";
echo "对象（Object）= 根据图纸造出来的实物\n";
echo "属性（Property）= 对象的特征（姓名、年龄）\n";
echo "方法（Method）= 对象的行为（说话、走路）\n\n";


// =============================================
// 第二节：定义类和创建对象
// =============================================

echo "【第二节：定义类和创建对象】\n\n";

// 定义一个类：class 关键字 + 类名（大驼峰命名）
// 类就像一个"模板"或"图纸"，描述了一类事物的共同特征
class Person {
    // 属性（成员变量）
    public $name;
    public $age;
    public $city;

    // 方法（成员函数）
    // $this 代表"当前对象自己"，用 -> 访问对象的属性和方法
    public function sayHello() {
        echo "你好，我叫$this->name，今年$this->age岁，来自$this->city\n";
    }

    public function walk() {
        echo "$this->name 正在走路...\n";
    }
}

// new 关键字创建对象（也叫"实例化"）
// -> 是对象操作符，用来访问对象的属性和方法
$person1 = new Person();
$person1->name = "小明";
$person1->age = 25;
$person1->city = "北京";
$person1->sayHello();
$person1->walk();

echo "\n";

$person2 = new Person();
$person2->name = "小红";
$person2->age = 22;
$person2->city = "上海";
$person2->sayHello();

echo "\n";


// =============================================
// 第三节：构造函数和析构函数
// =============================================

echo "【第三节：构造函数和析构函数】\n\n";

class Student {
    // 属性
    public $name;
    public $age;
    public $grade;

    // 构造函数 __construct：用 new 创建对象时自动调用
    // 通常用来初始化属性，给对象一个初始状态
    public function __construct($name, $age, $grade) {
        $this->name = $name;
        $this->age = $age;
        $this->grade = $grade;
        echo "学生 $name 已创建\n";
    }

    // 析构函数 __destruct：对象被销毁时自动调用（脚本结束或 unset）
    // 通常用来释放资源（关闭文件、断开连接等）
    public function __destruct() {
        echo "学生 $this->name 已销毁\n";
    }

    public function introduce() {
        echo "我是$this->name，$this->age岁，$this->grade年级\n";
    }
}

echo "创建学生对象：\n";
$student1 = new Student("小明", 20, "大三");
$student1->introduce();

$student2 = new Student("小红", 21, "大四");
$student2->introduce();

echo "\n对象即将销毁...\n";
// 对象在脚本结束时自动销毁
echo "\n";


// =============================================
// 第四节：访问控制（public, private, protected）
// =============================================

echo "【第四节：访问控制】\n\n";

echo "访问控制修饰符：\n";
echo "  public    - 公开的，任何地方都可以访问\n";
echo "  private   - 私有的，只能在类内部访问\n";
echo "  protected - 受保护的，类内部和子类可以访问\n\n";

// 封装示例：用 private 保护敏感数据，只暴露安全的公开方法
// 就像银行不会让你直接改余额，而是通过存款/取款操作
class BankAccount {
    private $account_number;  // 私有：账号
    private $balance;         // 私有：余额
    protected $owner;         // 受保护：所有者
    public $bank_name;        // 公开：银行名

    public function __construct($account_number, $owner, $initial_balance = 0) {
        $this->account_number = $account_number;
        $this->owner = $owner;
        $this->balance = $initial_balance;
        $this->bank_name = "PHP银行";
    }

    // 公开方法：存款
    public function deposit($amount) {
        if ($amount <= 0) {
            echo "存款金额必须大于0\n";
            return false;
        }
        $this->balance += $amount;
        echo "存款成功，余额：" . $this->getBalance() . " 元\n";
        return true;
    }

    // 公开方法：取款
    public function withdraw($amount) {
        if ($amount <= 0) {
            echo "取款金额必须大于0\n";
            return false;
        }
        if ($amount > $this->balance) {
            echo "余额不足\n";
            return false;
        }
        $this->balance -= $amount;
        echo "取款成功，余额：" . $this->getBalance() . " 元\n";
        return true;
    }

    // 公开方法：获取余额（封装私有属性）
    public function getBalance() {
        return number_format($this->balance, 2);
    }

    // 公开方法：显示账户信息
    public function showInfo() {
        echo "账户：$this->account_number\n";
        echo "所有者：$this->owner\n";
        echo "余额：" . $this->getBalance() . " 元\n";
    }
}

echo "银行账户示例：\n";
$account = new BankAccount("6222001234567890", "小明", 10000);
$account->showInfo();
echo "\n";

$account->deposit(5000);
$account->withdraw(3000);
$account->withdraw(20000);  // 余额不足
echo "\n";

// 尝试访问私有属性（会报错）
// echo $account->balance;  // 错误！
// echo $account->account_number;  // 错误！

echo "访问控制总结：\n";
echo "  私有属性通过公开方法访问（封装）\n";
echo "  这样可以保护数据，防止非法修改\n\n";


// =============================================
// 第五节：Getter 和 Setter
// =============================================

echo "【第五节：Getter 和 Setter】\n\n";

// Getter/Setter 模式：通过方法控制属性的读写
// 好处：可以在 Setter 中加入验证逻辑，防止非法数据
class User {
    private $name;
    private $email;
    private $age;

    public function __construct($name, $email, $age) {
        $this->setName($name);
        $this->setEmail($email);
        $this->setAge($age);
    }

    // Getter：获取属性值
    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAge() {
        return $this->age;
    }

    // Setter：设置属性值（带验证）
    public function setName($name) {
        if (empty($name)) {
            throw new Exception("姓名不能为空");
        }
        $this->name = $name;
    }

    // filter_var() 是 PHP 内置的验证过滤函数
    // FILTER_VALIDATE_EMAIL 验证邮箱格式是否合法
    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("邮箱格式不正确");
        }
        $this->email = $email;
    }

    public function setAge($age) {
        if ($age < 0 || $age > 150) {
            throw new Exception("年龄不合法");
        }
        $this->age = $age;
    }

    // __toString 是魔术方法：当对象被当作字符串使用时自动调用
    // 例如 echo $user; 或 "用户：$user"
    public function __toString() {
        return "User[name=$this->name, email=$this->email, age=$this->age]";
    }
}

echo "Getter/Setter 示例：\n";
$user = new User("小明", "xiaoming@example.com", 25);
echo $user . "\n";
echo "姓名：" . $user->getName() . "\n";
echo "邮箱：" . $user->getEmail() . "\n";

// 修改属性
$user->setAge(26);
echo "修改年龄后：" . $user->getAge() . "\n\n";


// =============================================
// 第六节：静态属性和方法
// =============================================

echo "【第六节：静态属性和方法】\n\n";

// 静态成员：属于类本身，不属于任何对象
// 用 类名::属性/方法 访问，不需要创建对象
class Counter {
    // 静态属性：属于类，不属于对象
    private static $count = 0;

    // 静态方法：通过类名调用
    // self:: 代表"当前类"，类似于 $this 代表"当前对象"
    // 静态方法中不能使用 $this，因为没有对象实例
    public static function increment() {
        self::$count++;
    }

    public static function getCount() {
        return self::$count;
    }

    // 静态方法中不能使用 $this
    public static function reset() {
        self::$count = 0;
    }
}

echo "静态属性和方法：\n";
echo "初始计数：" . Counter::getCount() . "\n";

Counter::increment();
Counter::increment();
Counter::increment();
echo "递增3次后：" . Counter::getCount() . "\n";

Counter::reset();
echo "重置后：" . Counter::getCount() . "\n\n";

// 单例模式（Singleton）：确保一个类只能创建一个对象
// 适用场景：数据库连接、日志记录器等全局只需要一份的资源
echo "单例模式示例：\n";
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // 私有构造函数，防止外部创建
        $this->connection = "数据库连接已建立";
        echo "数据库连接创建\n";
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql) {
        echo "执行SQL：$sql\n";
    }
}

// 只会创建一个连接
$db1 = Database::getInstance();
$db2 = Database::getInstance();
echo "db1 和 db2 是同一个对象：" . ($db1 === $db2 ? '是' : '否') . "\n\n";


// =============================================
// 第七节：常量
// =============================================

echo "【第七节：类常量】\n\n";

// 类常量：用 const 定义，值不可修改
// 用 类名::常量名 访问，不需要 $ 符号
class MathConstants {
    // 类常量：用 const 定义
    const PI = 3.14159265359;
    const E = 2.71828182846;

    // 常量用 self:: 访问
    public static function circleArea($radius) {
        return self::PI * $radius * $radius;
    }
}

echo "类常量：\n";
echo "PI = " . MathConstants::PI . "\n";
echo "E = " . MathConstants::E . "\n";
echo "圆面积（半径5）：" . MathConstants::circleArea(5) . "\n\n";


// =============================================
// 第八节：对象比较和类型检查
// =============================================

echo "【第八节：对象比较和类型检查】\n\n";

class Point {
    public $x;
    public $y;

    public function __construct($x, $y) {
        $this->x = $x;
        $this->y = $y;
    }
}

// 演示对象比较的两种方式
$p1 = new Point(1, 2);
$p2 = new Point(1, 2);  // 和 p1 值相同，但是不同的对象
$p3 = $p1;              // p3 和 p1 指向同一个对象

// == 只比较属性值是否相同（值比较）
echo "对象比较：\n";
echo "p1 == p2：" . var_export($p1 == $p2, true) . "\n";  // true

// === 比较是否是同一个对象实例（引用比较）
echo "p1 === p2：" . var_export($p1 === $p2, true) . "\n";  // false
echo "p1 === p3：" . var_export($p1 === $p3, true) . "\n";  // true

// instanceof 检查对象是否属于某个类（或其子类）
// 返回 true 或 false，常用于条件判断
echo "\n类型检查：\n";
echo "p1 是 Point：" . var_export($p1 instanceof Point, true) . "\n";
echo "p1 是 stdClass：" . var_export($p1 instanceof stdClass, true) . "\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：定义一个 Car 类，包含属性（品牌、颜色、价格）和方法（启动、停止、显示信息）\n";
echo "  提示：用 public \$brand 等定义属性，用 public function start() 定义方法\n";
echo "  提示：用一个 private \$is_running 属性记录车辆状态\n\n";

echo "练习2：定义一个 Rectangle 类，包含计算面积和周长的方法\n";
echo "  提示：构造函数接收宽和高，存为 private 属性\n";
echo "  提示：面积 = 宽 × 高，周长 = 2 × (宽 + 高)\n\n";

echo "练习3：定义一个 Temperature 类，支持摄氏度和华氏度的转换\n";
echo "  提示：存一个 \$celsius 属性，getFahrenheit() 返回 \$celsius * 9/5 + 32\n";
echo "  提示：可以加一个静态方法 fromFahrenheit(\$f) 从华氏度创建对象\n\n";

echo "练习4：定义一个 Stack 类（栈），实现 push、pop、peek、isEmpty 方法\n";
echo "  提示：用一个 private \$items = [] 数组存储栈元素\n";
echo "  提示：push 用 \$items[] = \$item，pop 用 array_pop()，peek 用 end()\n\n";

echo "练习5：定义一个 ShoppingCart 类，实现添加商品、删除商品、计算总价、显示购物车功能\n";
echo "  提示：用关联数组存商品，键是商品名，值包含 price 和 quantity\n";
echo "  提示：添加时检查是否已存在，存在则增加数量；总价用 foreach 累加\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
class Car {
    public $brand;
    public $color;
    public $price;
    private $is_running = false;

    public function __construct($brand, $color, $price) {
        $this->brand = $brand;
        $this->color = $color;
        $this->price = $price;
    }

    public function start() {
        if ($this->is_running) {
            echo "{$this->brand} 已经在运行了\n";
        } else {
            $this->is_running = true;
            echo "{$this->brand} 启动了\n";
        }
    }

    public function stop() {
        if ($this->is_running) {
            $this->is_running = false;
            echo "{$this->brand} 停止了\n";
        } else {
            echo "{$this->brand} 已经停止了\n";
        }
    }

    public function showInfo() {
        echo "品牌：{$this->brand}，颜色：{$this->color}，价格：{$this->price}万\n";
    }
}

$car = new Car("宝马", "黑色", 50);
$car->showInfo();
$car->start();
$car->stop();
echo "\n";

// 练习2
echo "--- 练习2答案 ---\n";
class Rectangle {
    private $width;
    private $height;

    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    public function area() {
        return $this->width * $this->height;
    }

    public function perimeter() {
        return 2 * ($this->width + $this->height);
    }

    public function __toString() {
        return "Rectangle[width={$this->width}, height={$this->height}]";
    }
}

$rect = new Rectangle(10, 5);
echo "$rect\n";
echo "面积：" . $rect->area() . "\n";
echo "周长：" . $rect->perimeter() . "\n\n";

// 练习3
echo "--- 练习3答案 ---\n";
class Temperature {
    private $celsius;

    public function __construct($celsius) {
        $this->celsius = $celsius;
    }

    public function getCelsius() {
        return $this->celsius;
    }

    public function getFahrenheit() {
        return $this->celsius * 9 / 5 + 32;
    }

    // 静态工厂方法：用类名直接调用，返回一个新的对象实例
    // new self() 代表创建当前类的实例
    public static function fromFahrenheit($fahrenheit) {
        return new self(($fahrenheit - 32) * 5 / 9);
    }

    public function __toString() {
        return number_format($this->celsius, 1) . "°C / " .
               number_format($this->getFahrenheit(), 1) . "°F";
    }
}

$temp = new Temperature(100);
echo "100°C = " . $temp . "\n";

$temp2 = Temperature::fromFahrenheit(212);
echo "212°F = " . $temp2 . "\n\n";

// 练习4
echo "--- 练习4答案 ---\n";
// 栈（Stack）：后进先出（LIFO）的数据结构
// 像叠盘子一样：最后放的最先取走
class Stack {
    private $items = [];

    public function push($item) {
        $this->items[] = $item;
    }

    public function pop() {
        if ($this->isEmpty()) {
            return null;
        }
        return array_pop($this->items);
    }

    public function peek() {
        if ($this->isEmpty()) {
            return null;
        }
        return end($this->items);
    }

    public function isEmpty() {
        return empty($this->items);
    }

    public function size() {
        return count($this->items);
    }
}

$stack = new Stack();
$stack->push(1);
$stack->push(2);
$stack->push(3);
echo "栈顶：" . $stack->peek() . "\n";
echo "弹出：" . $stack->pop() . "\n";
echo "栈顶：" . $stack->peek() . "\n";
echo "大小：" . $stack->size() . "\n\n";

// 练习5
echo "--- 练习5答案 ---\n";
// 购物车类：展示 OOP 在实际业务中的应用
// 把数据（商品列表）和操作（增删计算）封装在一起
class ShoppingCart {
    private $items = [];

    public function addItem($name, $price, $quantity = 1) {
        if (isset($this->items[$name])) {
            $this->items[$name]['quantity'] += $quantity;
        } else {
            $this->items[$name] = [
                'price' => $price,
                'quantity' => $quantity
            ];
        }
        echo "已添加：$name × $quantity\n";
    }

    public function removeItem($name) {
        if (isset($this->items[$name])) {
            unset($this->items[$name]);
            echo "已删除：$name\n";
        }
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function showCart() {
        echo "购物车：\n";
        foreach ($this->items as $name => $item) {
            echo "  $name: ¥{$item['price']} × {$item['quantity']}\n";
        }
        echo "  总价：¥" . $this->getTotal() . "\n";
    }
}

$cart = new ShoppingCart();
$cart->addItem("苹果", 5.5, 3);
$cart->addItem("香蕉", 3.0, 5);
$cart->addItem("橙子", 4.0, 2);
$cart->showCart();


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - 类是模板，对象是实例；构造函数__construct初始化，析构函数__destruct清理
 * - 访问控制：public公开、private私有、protected受保护
 * - 静态成员用static定义，通过类名::调用，不属于任何对象
 *
 * 常见陷阱：
 * - private属性不能在类外部直接访问，必须通过public方法（封装）
 * - 构造函数参数和属性同名时，必须用$this->区分
 * - 静态方法中不能使用$this，只能访问静态成员
 *
 * 下节课预告：
 * - 第12课我们将学习继承、多态、接口等高级OOP特性
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第11课！\n";
echo "   下节课：面向对象编程（下）—— 继承、多态、接口\n";
echo "============================================================\n";
