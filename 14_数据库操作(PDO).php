<?php
// =============================================
// 第 14 课：数据库操作（PDO）
// =============================================
// 上节课我们学了错误与异常处理。
// 这节课我们要学习PDO数据库操作，这是Web应用的核心。

echo "============================================================\n";
echo "   第14课：数据库操作（PDO）\n";
echo "============================================================\n\n";


// =============================================
// 第一节：数据库基础
// =============================================

echo "【第一节：数据库基础】\n\n";

echo "什么是数据库？\n";
echo "  数据库是存储和管理数据的系统\n";
echo "  就像一个超级文件柜，可以高效地存储、检索、更新数据\n\n";

echo "常见的数据库：\n";
echo "  关系型数据库（SQL）：\n";
echo "    - MySQL：最流行的开源数据库\n";
echo "    - PostgreSQL：功能强大的开源数据库\n";
echo "    - SQLite：轻量级，文件型数据库\n";
echo "    - SQL Server：微软的商业数据库\n\n";

echo "  非关系型数据库（NoSQL）：\n";
echo "    - MongoDB：文档型数据库\n";
echo "    - Redis：键值存储，用于缓存\n\n";


// =============================================
// 第二节：PDO简介
// =============================================

echo "【第二节：PDO简介】\n\n";

echo "PDO（PHP Data Objects）的优势：\n";
echo "  1. 统一接口：支持多种数据库\n";
echo "  2. 安全性：支持预处理语句，防止SQL注入\n";
echo "  3. 面向对象：更现代的API\n";
echo "  4. 异常处理：更好的错误处理\n\n";

echo "PDO vs MySQLi：\n";
echo "  PDO：支持12种数据库，更灵活\n";
echo "  MySQLi：只支持MySQL，性能略好\n";
echo "  建议：使用PDO\n\n";


// =============================================
// 第三节：连接数据库
// =============================================

echo "【第三节：连接数据库】\n\n";

// 注意：实际运行需要数据库服务器
// 这里演示代码结构

echo "连接MySQL数据库：\n";
echo <<<'CODE'
try {
    // DSN（数据源名称）
    $dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';

    // 连接选项
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // 异常模式
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // 关联数组
        PDO::ATTR_EMULATE_PREPARES => false,  // 禁用模拟预处理
    ];

    // 创建PDO实例
    $pdo = new PDO($dsn, 'username', 'password', $options);

    echo "数据库连接成功！\n";
} catch (PDOException $e) {
    echo "连接失败：" . $e->getMessage() . "\n";
    exit;
}
CODE;
echo "\n\n";

// SQLite连接（无需服务器）
echo "连接SQLite数据库（示例）：\n";
echo <<<'CODE'
try {
    $dsn = 'sqlite:' . __DIR__ . '/database.sqlite';
    $pdo = new PDO($dsn);
    echo "SQLite连接成功！\n";
} catch (PDOException $e) {
    echo "连接失败：" . $e->getMessage() . "\n";
}
CODE;
echo "\n\n";


// =============================================
// 第四节：创建表
// =============================================

echo "【第四节：创建表】\n\n";

echo "创建users表：\n";
echo <<<'CODE'
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    age INT,
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$pdo->exec($sql);
echo "表创建成功！\n";
CODE;
echo "\n\n";


// =============================================
// 第五节：CRUD操作
// =============================================

echo "【第五节：CRUD操作】\n\n";

echo "CRUD = Create, Read, Update, Delete\n\n";

// ---- Create（创建）----
echo "--- Create（创建数据）---\n";
echo <<<'CODE'
// 方法1：直接执行（不安全，不推荐）
$sql = "INSERT INTO users (username, email, age, city)
        VALUES ('小明', 'xiaoming@example.com', 25, '北京')";
$pdo->exec($sql);
echo "插入成功，ID：" . $pdo->lastInsertId() . "\n";

// 方法2：预处理语句（推荐，安全）
$sql = "INSERT INTO users (username, email, age, city)
        VALUES (:username, :email, :age, :city)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':username' => '小红',
    ':email' => 'xiaohong@example.com',
    ':age' => 22,
    ':city' => '上海'
]);
echo "插入成功，ID：" . $pdo->lastInsertId() . "\n";
CODE;
echo "\n\n";

// ---- Read（读取）----
echo "--- Read（读取数据）---\n";
echo <<<'CODE'
// 查询所有用户
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
foreach ($users as $user) {
    echo $user['username'] . ' - ' . $user['email'] . "\n";
}

// 条件查询
$sql = "SELECT * FROM users WHERE city = :city AND age > :age";
$stmt = $pdo->prepare($sql);
$stmt->execute([':city' => '北京', ':age' => 20]);
$users = $stmt->fetchAll();

// 查询单条记录
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => 1]);
$user = $stmt->fetch();

// 查询单个值
$sql = "SELECT COUNT(*) FROM users";
$count = $pdo->query($sql)->fetchColumn();
CODE;
echo "\n\n";

// ---- Update（更新）----
echo "--- Update（更新数据）---\n";
echo <<<'CODE'
$sql = "UPDATE users SET age = :age, city = :city WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':age' => 26,
    ':city' => '深圳',
    ':id' => 1
]);
echo "更新了 " . $stmt->rowCount() . " 条记录\n";
CODE;
echo "\n\n";

// ---- Delete（删除）----
echo "--- Delete（删除数据）---\n";
echo <<<'CODE'
$sql = "DELETE FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => 1]);
echo "删除了 " . $stmt->rowCount() . " 条记录\n";
CODE;
echo "\n\n";


// =============================================
// 第六节：预处理语句详解
// =============================================

echo "【第六节：预处理语句详解】\n\n";

echo "预处理语句的工作原理：\n";
echo "  1. 准备阶段：发送SQL模板给数据库\n";
echo "  2. 数据库解析和编译SQL\n";
echo "  3. 绑定参数：发送参数值\n";
echo "  4. 数据库执行查询\n\n";

echo "预处理语句的优势：\n";
echo "  1. 安全性：参数和SQL分离，防止SQL注入\n";
echo "  2. 性能：SQL只编译一次，多次执行\n";
echo "  3. 清晰：代码更易读\n\n";

echo "预处理语句示例：\n";
echo <<<'CODE'
// 命名参数
$sql = "INSERT INTO users (username, email) VALUES (:username, :email)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => 'test', ':email' => 'test@example.com']);

// 位置参数
$sql = "INSERT INTO users (username, email) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute(['test', 'test@example.com']);

// 绑定参数
$sql = "INSERT INTO users (username, email) VALUES (:username, :email)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':email', $email);
$username = 'test';
$email = 'test@example.com';
$stmt->execute();
CODE;
echo "\n\n";


// =============================================
// 第七节：事务处理
// =============================================

echo "【第七节：事务处理】\n\n";

echo "什么是事务？\n";
echo "  事务是一组操作，要么全部成功，要么全部失败\n";
echo "  就像银行转账：A扣钱和B加钱必须同时成功或失败\n\n";

echo "事务示例（银行转账）：\n";
echo <<<'CODE'
try {
    $pdo->beginTransaction();

    // A账户扣钱
    $sql = "UPDATE accounts SET balance = balance - :amount WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':amount' => 100, ':id' => 1]);

    // B账户加钱
    $sql = "UPDATE accounts SET balance = balance + :amount WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':amount' => 100, ':id' => 2]);

    // 提交事务
    $pdo->commit();
    echo "转账成功！\n";
} catch (Exception $e) {
    // 回滚事务
    $pdo->rollBack();
    echo "转账失败：" . $e->getMessage() . "\n";
}
CODE;
echo "\n\n";


// =============================================
// 第八节：PDO封装类
// =============================================

echo "【第八节：PDO封装类】\n\n";

// 数据库封装类
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct($dsn, $user = '', $pass = '', $options = []) {
        $default_options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $options = array_merge($default_options, $options);

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new Exception("数据库连接失败：" . $e->getMessage());
        }
    }

    // 单例模式
    public static function getInstance($dsn = '', $user = '', $pass = '') {
        if (self::$instance === null) {
            self::$instance = new self($dsn, $user, $pass);
        }
        return self::$instance;
    }

    // 查询方法
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // 获取所有记录
    public function findAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    // 获取单条记录
    public function findOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    // 获取单个值
    public function findColumn($sql, $params = []) {
        return $this->query($sql, $params)->fetchColumn();
    }

    // 插入
    public function insert($table, $data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $this->query($sql, $data);

        return $this->pdo->lastInsertId();
    }

    // 更新
    public function update($table, $data, $where, $where_params = []) {
        $set = [];
        foreach (array_keys($data) as $field) {
            $set[] = "$field = :$field";
        }
        $set = implode(', ', $set);

        $sql = "UPDATE $table SET $set WHERE $where";
        $params = array_merge($data, $where_params);

        return $this->query($sql, $params)->rowCount();
    }

    // 删除
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        return $this->query($sql, $params)->rowCount();
    }

    // 事务
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    public function commit() {
        return $this->pdo->commit();
    }

    public function rollBack() {
        return $this->pdo->rollBack();
    }

    // 获取最后插入的ID
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}

echo "数据库封装类示例：\n";
echo <<<'CODE'
// 使用示例
$db = Database::getInstance('mysql:host=localhost;dbname=test', 'root', '');

// 插入
$id = $db->insert('users', [
    'username' => '小明',
    'email' => 'xiaoming@example.com',
    'age' => 25
]);

// 查询
$user = $db->findOne("SELECT * FROM users WHERE id = :id", ['id' => $id]);
$users = $db->findAll("SELECT * FROM users WHERE city = :city", ['city' => '北京']);
$count = $db->findColumn("SELECT COUNT(*) FROM users");

// 更新
$db->update('users', ['age' => 26], 'id = :id', ['id' => $id]);

// 删除
$db->delete('users', 'id = :id', ['id' => $id]);
CODE;
echo "\n\n";


// =============================================
// 第九节：SQL注入防护
// =============================================

echo "【第九节：SQL注入防护】\n\n";

echo "SQL注入示例：\n";
echo <<<'CODE'
// 不安全的代码
$username = $_POST['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
// 如果 username = "' OR '1'='1"
// SQL变成：SELECT * FROM users WHERE username = '' OR '1'='1'
// 会返回所有用户！

// 安全的代码（预处理语句）
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $_POST['username']]);
// 参数和SQL分离，不会被注入
CODE;
echo "\n";

echo "防止SQL注入的方法：\n";
echo "  1. 使用预处理语句（必须！）\n";
echo "  2. 不要直接拼接用户输入到SQL\n";
echo "  3. 使用PDO的参数绑定\n";
echo "  4. 验证和过滤用户输入\n\n";


// =============================================
// 练习题
// =============================================

echo "============================================================\n";
echo "   练习题\n";
echo "============================================================\n\n";

echo "练习1：设计一个 users 表，包含必要的字段\n";
echo "  提示：至少包含 id(AUTO_INCREMENT)、username、email、password、created_at\n";
echo "  提示：username 和 email 加 UNIQUE 紦束，设置 utf8mb4 字符集\n\n";

echo "练习2：编写一个 UserRepository 类，实现基本的CRUD操作\n";
echo "  提示：构造函数接收 PDO 实例，实现 create、findById、findAll、update、delete 方法\n";
echo "  提示：所有 SQL 都用预处理语句（prepare + execute），不要拼接字符串\n\n";

echo "练习3：编写一个分页查询函数\n";
echo "  提示：先用 SELECT COUNT(*) 获取总数，再用 LIMIT \$per_page OFFSET \$offset 查询\n";
echo "  提示：offset = (\$current_page - 1) * \$per_page，总页数 = ceil(总数 / 每页数)\n\n";

echo "练习4：编写一个数据库迁移工具\n";
echo "  提示：创建 migrations 表记录已执行的迁移\n";
echo "  提示：up() 执行迁移并记录，down() 回滚并删除记录\n\n";

echo "练习5：编写一个简单的查询构建器（Query Builder）\n";
echo "  提示：用链式调用——->table('users')->where('age','>',18)->orderBy('name')->limit(10)\n";
echo "  提示：每个方法返回 \$this 实现链式，build() 最终生成 SQL 和参数数组\n\n";


// =============================================
// 参考答案
// =============================================

echo "============================================================\n";
echo "   参考答案\n";
echo "============================================================\n\n";

// 练习1
echo "--- 练习1答案 ---\n";
echo <<<'SQL'
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    age INT CHECK (age >= 0 AND age <= 150),
    city VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_city (city)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
echo "\n\n";

// 练习2
echo "--- 练习2答案 ---\n";

class UserRepository {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create($data) {
        $sql = "INSERT INTO users (username, email, password, first_name, last_name, age, city)
                VALUES (:username, :email, :password, :first_name, :last_name, :age, :city)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':first_name' => $data['first_name'] ?? null,
            ':last_name' => $data['last_name'] ?? null,
            ':age' => $data['age'] ?? null,
            ':city' => $data['city'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function findAll($conditions = [], $order = 'id DESC', $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM users";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = :$key";
                $params[":$key"] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY $order LIMIT $limit OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

echo "UserRepository 类已定义\n\n";

// 练习3
echo "--- 练习3答案 ---\n";

class Paginator {
    private $db;
    private $table;
    private $per_page;
    private $current_page;

    public function __construct(PDO $db, $table, $per_page = 10) {
        $this->db = $db;
        $this->table = $table;
        $this->per_page = $per_page;
        $this->current_page = max(1, intval($_GET['page'] ?? 1));
    }

    public function paginate($where = '', $params = []) {
        // 获取总数
        $count_sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) {
            $count_sql .= " WHERE $where";
        }
        $stmt = $this->db->prepare($count_sql);
        $stmt->execute($params);
        $total = $stmt->fetchColumn();

        // 计算分页
        $total_pages = ceil($total / $this->per_page);
        $offset = ($this->current_page - 1) * $this->per_page;

        // 获取数据
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE $where";
        }
        $sql .= " LIMIT {$this->per_page} OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $this->per_page,
            'current_page' => $this->current_page,
            'total_pages' => $total_pages
        ];
    }
}

echo "Paginator 类已定义\n\n";

// 练习4
echo "--- 练习4答案 ---\n";

class Migration {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->db->exec($sql);
    }

    public function up($name, $sql) {
        // 检查是否已执行
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
        $stmt->execute([$name]);
        if ($stmt->fetchColumn() > 0) {
            echo "迁移 $name 已执行，跳过\n";
            return;
        }

        // 执行迁移
        $this->db->exec($sql);

        // 记录迁移
        $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$name]);

        echo "迁移 $name 执行成功\n";
    }

    public function down($name, $sql) {
        $this->db->exec($sql);
        $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$name]);
        echo "迁移 $name 回滚成功\n";
    }
}

echo "Migration 类已定义\n\n";

// 练习5
echo "--- 练习5答案 ---\n";

class QueryBuilder {
    private $table;
    private $conditions = [];
    private $params = [];
    private $order = '';
    private $limit = '';
    private $select = '*';

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($fields) {
        $this->select = is_array($fields) ? implode(', ', $fields) : $fields;
        return $this;
    }

    public function where($field, $operator, $value) {
        $param = ':' . str_replace('.', '_', $field) . count($this->params);
        $this->conditions[] = "$field $operator $param";
        $this->params[$param] = $value;
        return $this;
    }

    public function orderBy($field, $direction = 'ASC') {
        $this->order = "ORDER BY $field $direction";
        return $this;
    }

    public function limit($limit) {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function build() {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $sql .= " {$this->order} {$this->limit}";

        return ['sql' => $sql, 'params' => $this->params];
    }
}

// 使用示例
$query = (new QueryBuilder())
    ->table('users')
    ->select(['id', 'username', 'email'])
    ->where('city', '=', '北京')
    ->where('age', '>=', 18)
    ->orderBy('username')
    ->limit(10);

$result = $query->build();
echo "生成的SQL：\n";
echo "  SQL: " . $result['sql'] . "\n";
echo "  参数: " . json_encode($result['params']) . "\n";


// =============================================
// 课程总结
// =============================================
/*
 * 核心收获：
 * - PDO是PHP操作数据库的标准方式，支持预处理语句防止SQL注入
 * - CRUD操作：prepare()准备SQL -> execute()执行 -> fetch/fetchAll获取结果
 * - 事务：beginTransaction() -> 执行操作 -> commit()或rollBack()
 *
 * 常见陷阱：
 * - 永远用预处理语句（prepare+execute），不要拼接用户输入到SQL
 * - 设置PDO::ATTR_ERRMODE为ERRMODE_EXCEPTION，不要忽略数据库错误
 * - FETCH_ASSOC返回关联数组，FETCH_OBJ返回对象，搞混了访问方式会报错
 *
 * 下节课预告：
 * - 第15课我们将学习会话与安全，保护你的Web应用
 */

echo "\n============================================================\n";
echo "   恭喜你完成了第14课！\n";
echo "   下节课：会话与安全 —— 保护你的Web应用\n";
echo "============================================================\n";
