<?php

/**
 *  设计模式-观察者模式
 *  概念：观察者模式是一种比较容易理解的一种模式，它是一个事件系统，意味着这一模式允许某个类观察另一个类的状态，当被观察的类发
 *       生状态变化时，观察者类可以收到通知并且作出相应的动作；观察者模式提供了避免组件之间紧密耦合得另一种方法
 *  关键点：
 *       1。被观察者-》追加观察者-》一处观察者-》满足条件的所有观察者-》观察条件
 *       2。观察者-》接受观察方法
 *  应用：
 *       邮件订阅，用户注册（验证邮件，用户信息激活），购物网站下单邮件／短信通知
 *  php内部的支持：
 *       SplSubject 接口，代表着被观察的对象
 *       结构：
 *          interface SplSubject {
 *              public function attach(SplObserver $observer);
 *              public function detach(SplObserver $observer);
 *              public function notify();
 *          }
 *
 *        SplObserver 接口，它代表着充当观察者的对象，
 *        其结构：
 *          interface SplObserver
 *          {
 *               public function update(SplSubject $subject);
 *          }
 *
 */

//观察者接口
interface IObserver {
    public function onSendMsg( $sender , $msg);
    public function getName();
}

//被观察者接口
interface IObservable {
    //提供注册观察者接口方法
    public function addObserver($observer);
    //提供移除观察者的方法
    public function removeObserver($observer);
}

class UserList implements IObservable {

    protected $observers = array();

    public function addObserver($observer)
    {
        return $this->observers[] = $observer;
    }

    public function removeObserver($observer){

        $index = array_search($observer,$this->observers);
        if ($index === false || !array_key_exists($index,$this->observers)) {
            return false;
        }

        unset($this->observers[$index]);

        return true;
    }

    public function sendMsg($msg){
        foreach ($this->observers as $observer) {
            $observer->onSendMsg($this,$msg);
        }
    }
}

class UserListLogger implements IObserver {

    public function onSendMsg($sender, $msg)
    {
        echo $msg . 'user-list-logger' . "\n";
    }

    public function getName()
    {
        return 'userListLogger';
    }
}

class OtherObserver implements IObserver {
    public function onSendMsg($sender, $msg)
    {
        echo $msg . 'user-other-logger' . "\n";
    }

    public function getName()
    {
        return 'OtherObserver';
    }
}

//被观察者
$user = new UserList();

$user->addObserver( new UserListLogger() );//增加观察者
$user->addObserver( new OtherObserver() );//增加观察者

$user->sendMsg('test');


#===================定义观察者、被观察者接口============

interface ITicketObserver //观察者接口
{
    function onBuyTicketOver($sender, $args); //得到通知后调用的方法
}
/**
 *
 * 主题接口
 *
 */
interface ITicketObservable //被观察对象接口
{
    function addObserver($observer); //提供注册观察者方法
}
#====================主题类实现========================
/**
 *
 * 主题类（购票）
 *
 */
class HipiaoBuy implements ITicketObservable { //实现主题接口（被观察者）
    private $_observers = array (); //通知数组（观察者）
    public function buyTicket($ticket) //购票核心类，处理购票流程
    {
        // TODO 购票逻辑
        //循环通知，调用其onBuyTicketOver实现不同业务逻辑
        foreach ( $this->_observers as $obs )
            $obs->onBuyTicketOver ( $this, $ticket ); //$this 可用来获取主题类句柄，在通知中使用
    }
    //添加通知
    public function addObserver($observer) //添加N个通知
    {
        $this->_observers [] = $observer;
    }
}
#=========================定义多个通知====================
//短信日志通知
class HipiaoMSM implements ITicketObserver {
    public function onBuyTicketOver($sender, $ticket) {
        echo (date ( 'Y-m-d H:i:s' ) . " 短信日志记录：购票成功:$ticket<br>");
    }
}
//文本日志通知
class HipiaoTxt implements ITicketObserver {
    public function onBuyTicketOver($sender, $ticket) {
        echo (date ( 'Y-m-d H:i:s' ) . " 文本日志记录：购票成功:$ticket<br>");
    }
}
//抵扣卷赠送通知
class HipiaoDiKou implements ITicketObserver {
    public function onBuyTicketOver($sender, $ticket) {
        echo (date ( 'Y-m-d H:i:s' ) . " 赠送抵扣卷：购票成功:$ticket 赠送10元抵扣卷1张。<br>");
    }
}
#============================用户购票====================
$buy = new HipiaoBuy ();
$buy->addObserver ( new HipiaoMSM () ); //根据不同业务逻辑加入各种通知
$buy->addObserver ( new HipiaoTxt () );
$buy->addObserver ( new HipiaoDiKou () );
//购票
$buy->buyTicket ( "一排一号" );