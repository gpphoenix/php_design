<?php

/**
 *  观察者模式
 */

//抽象主题角色
interface Subject {
    //增加一个观察者对象对象
    public function attach(Observer $observer);

    //删除一个观察者对象
    public function detach(Observer $observer);

    //通知所有注册过的观察者对象
    public function notifyObserver();
}

//具体主题角色
class ConcreteSubject implements Subject{

    private $_observers;

    public function __construct()
    {
        $this->_observers = array();
    }

    //新增一个新的观察者对象
    public function attach(Observer $observer)
    {
        return array_push($this->_observers,$observer);
    }

    //删除一个观察者对象
    public function detach(Observer $splObserver)
    {
        $index = array_search($splObserver,$this->_observers);
        if ($index === false || !array_key_exists($index,$this->_observers)) {
            return false;
        }

        unset($this->_observers[$index]);

        return true;
    }

    //通知所有的观察者
    public function notifyObserver()
    {
        if (!is_array($this->_observers)){
            return false;
        }
        foreach ($this->_observers as $observer) {
            $observer->update();
        }

        return true;
    }
}

/**
 * 抽象观察者角色
 */
interface Observer {
    public function update();
}

class ConcreteObserver implements Observer {

    //观察者名字
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function update()
    {
        echo 'observer' . $this->name . 'had notify' . "\n";
    }
}


$subject = new ConcreteSubject();

//添加第一个观察者
$observer1 = new ConcreteObserver('alan');
$subject->attach($observer1);

echo "\n" . 'the first notify' . "<br />";
$subject->notifyObserver();

//添加第二个观察者
$observer2 = new ConcreteObserver('test');
$subject->attach($observer2);

echo "\n" . 'the second notify' . "<br />";
$subject->notifyObserver();


/* 删除第一个观察者 */
$subject->detach($observer1);

echo '<br /> The Third notify:<br />';
$subject->notifyObserver();


?>