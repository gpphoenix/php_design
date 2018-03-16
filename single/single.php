<?php
/**
 * Created by PhpStorm.
 * User: Alan.Guo
 * Date: 2018/3/16
 * Time: 下午2:35
 */

/**
 * 设计模式之单例模式
 * 当需要保证对象只有一个实例得时候，单例模式是非常有用得。它将创建对象得控制权交给一个单一得点上，任何时候应用程序都只会在存在且只会存在一个实例。
 * 单例类不应该在类得外部进行实例化。
 * 一个单例类应该具备以下几个因素：
 *     1。必须拥有一个访问级别为private得构造函数，用于阻止类被随意实例化
 *     2。必须拥有一个保存类得实例得静态变量
 *     3。必须拥有一个访问这个实例得公共静态方法，该方法通常被命名为getInstance()
 *     4。必须拥有一个私有得空得 clone() 方法，防止实例被克隆
 */


class single{

    public static $_instance;

    private function __construct(){

    }

    private function __clone(){
        // TODO: Implement __clone() method.
    }

    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function sayHi(){
        echo "Hi " . PHP_EOL;
    }
}

$single = single::getInstance();
$single->sayHi();