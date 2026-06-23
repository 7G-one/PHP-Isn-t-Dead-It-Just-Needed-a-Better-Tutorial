# PHP from Scratch: 19 Lessons for Absolute Beginners

<h3 align="center"><a href="README.md">中文</a> | <a href="README_en.md">English</a></h3>

Ever wondered how dynamic websites, login systems, and shopping carts actually work? One answer is PHP. This course starts from "what is PHP" and walks you through building real things that run in a browser.

No programming experience needed. Seriously, none.

## What You'll Actually Learn

Think of this like learning to cook. The basics teach you to chop, heat, and season. The intermediate section gets you cooking full dishes. The practice section has you hosting your own dinner party. That's the rhythm of this PHP course.

### Basics (00-07) -- Learning to Chop

| Lesson | File | What You'll Do | What You'll Pick Up Along the Way |
|--------|------|----------------|-----------------------------------|
| 00 | `00_零基础入门.php` | Write your first PHP program | What programming even is, what PHP can do |
| 01 | `01_变量与数据类型.php` | Store stuff with variables (numbers, text, true/false) | Naming rules, type conversion gotchas |
| 02 | `02_运算符与表达式.php` | Let PHP do math and comparisons for you | Arithmetic, comparison, logical, bitwise -- the full toolkit |
| 03 | `03_函数基础.php` | Pack repeated code into reusable "building blocks" | Parameters, return values, variable scope |
| 04 | `04_字符串操作.php` | Work with text: join, slice, replace | Single vs double quotes, intro to regex |
| 05 | `05_数组详解.php` | Manage a bunch of data with arrays | Indexed, associative, and multi-dimensional arrays |
| 06 | `06_条件语句.php` | Teach your program to make decisions | if/switch/match -- three flavors, each with its place |
| 07 | `07_循环语句.php` | Let the computer do repetitive work for you | for/while/foreach -- when to use which |

### Intermediate (08-16) -- Cooking Full Dishes

| Lesson | File | What You'll Do | What You'll Pick Up Along the Way |
|--------|------|----------------|-----------------------------------|
| 08 | `08_超全局变量.php` | Read user-submitted data and server info | $_GET/$_POST/$_SERVER/$_SESSION |
| 09 | `09_表单处理.php` | Build a form that collects user input | Data validation, SQL injection prevention (safety first!) |
| 10 | `10_文件操作.php` | Read and write files, handle CSV and JSON | Directory operations, file pointers |
| 11 | `11_面向对象编程(上).php` | Use "blueprints" (classes) to create "objects" | Properties, methods, access control |
| 12 | `12_面向对象编程(下).php` | Make classes inherit and follow contracts | Inheritance, polymorphism, interfaces, traits, magic methods |
| 13 | `13_错误与异常处理.php` | Handle errors gracefully instead of crashing | try/catch, custom exceptions, error levels |
| 14 | `14_数据库操作(PDO).php` | Talk to a MySQL database with PHP | CRUD, prepared statements (your anti-injection shield), transactions |
| 15 | `15_会话与安全.php` | Make your site "remember" who the user is | Sessions/Cookies, CSRF/XSS protection |
| 16 | `16_命名空间与类型声明.php` | Organize code for larger projects | Namespaces, autoloading, strict types |

### Practice (17-18) -- Hosting Your Own Dinner Party

| Lesson | File | What You'll Do | What You'll Pick Up Along the Way |
|--------|------|----------------|-----------------------------------|
| 17 | `17_项目实战.php` | Build a complete user registration & login system | Everything from the previous 16 lessons, combined |
| 18 | `18_PHP与Web开发.php` | See how real-world PHP is written | RESTful APIs, Composer, MVC, framework intro |

## Learning Path

Don't skip around -- later lessons build on earlier ones:

```
00 Getting Started → 01 Variables & Types → 02 Operators
    ↓
03 Functions → 04 Strings → 05 Arrays
    ↓
06 Conditionals → 07 Loops → 08 Superglobals
    ↓
09 Forms → 10 File I/O → 11-12 OOP
    ↓
13 Errors & Exceptions → 14 Database → 15 Sessions & Security
    ↓
16 Namespaces → 17 Project → 18 PHP & Web Dev
```

## What Makes This Course Different

### Real-Life Analogies, No Jargon

Programming concepts sound scary, but they're just everyday logic in disguise:

- **Variable** = a box with a label on it
- **Function** = a vending machine -- you press a button (pass a parameter), it gives you something (return value)
- **Array** = a bookshelf, find your book by shelf number
- **Class & Object** = blueprint vs building -- draw once, build many
- **Loop** = washing dishes -- one by one until done
- **Condition** = a traffic light -- red means stop, green means go

### Exercises in Every Lesson

Watching isn't learning. Each lesson ends with 5 practice problems, from easy to challenging, with reference answers. You haven't really learned the lesson until you've done the exercises.

### A Real Project to Finish

Lesson 17 is a complete user registration and login system -- database, form validation, security and all. After building it, you'll know PHP can do real work.

### Comments That Read Like a Conversation

Every `.php` file is packed with comments, but not the "this declares a variable" kind. These explain *why* things are written this way and *what happens* if you do it differently. It's like having a teacher sitting next to you.

## Getting Started

### What You Need

- PHP 8.0 or later
- MySQL 8.0 (needed for Lesson 14)
- A text editor (VS Code recommended)
- A web server (XAMPP, WAMP, or MAMP all work)

### Three Ways to Run

**Option 1: PHP Built-in Server (best for beginners)**
```bash
cd s_php
php -S localhost:8000
```
Open `http://localhost:8000/00_零基础入门.php` in your browser.

**Option 2: Command Line**
```bash
php 00_零基础入门.php
```

**Option 3: XAMPP/WAMP/MAMP**
Drop the files into your `htdocs` folder and visit them in a browser.

## FAQ

**Q: I have zero experience. Can I really learn this?**
Yes. This course was built for absolute beginners. It even covers "what is programming."

**Q: What can I do after finishing?**
You'll be able to build simple dynamic websites, understand how web development works, and have a solid foundation for learning frameworks like Laravel.

**Q: How long will it take?**
About 4-6 weeks at 2-3 hours a day. Take your time -- what matters is doing the exercises for every lesson.

## What's Next

After this course, here are some directions to explore:

1. **Laravel Framework** -- the king of PHP frameworks
2. **Frontend Trio** -- HTML / CSS / JavaScript
3. **Advanced SQL** -- deeper MySQL features
4. **Version Control** -- Git, essential for team work
5. **Deployment** -- Linux, Docker

## License

MIT License

---

> Variables are boxes, functions are building blocks, loops are washing dishes -- programming isn't magic, it's translating everyday logic into code. After these 19 lessons, you'll see PHP for what it really is, and realize you can already build things with it.
