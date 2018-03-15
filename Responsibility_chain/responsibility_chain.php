<?php
/**
 * Created by PhpStorm.
 * User: Alan.Guo
 * Date: 2018/3/15
 * Time: 下午5:05
 */

/**
 * 设计模式之责任链模式：
 * 责任链模式为请求创造了一个接收者对象得链，并沿着这条链传递该请求，直到有对象出来为止。
 * 这种模式能够给予的请求类型，对请求的发送者和接收者进行解耦。
 *
 * 为什么使用责任链模式？
 *    1. 将请求的发送者和请求的处理者解耦。责任链上的处理者负责处理请求，客户只需要将请求发送到职责链上即可，无须关心请求的处理细节和请求的传递。
 *    2. 发出这个请求的客户端并不知道链上的哪一个对象最终处理这个请求，这使得系统可以在不影响客户端得请求下动态得组织和分配责任
 *
 */

//抽象处理者
abstract class Handler{

    private $next_handler;  //存放下一处理对象
    private $level = 0;    //处理级别默认为0
    abstract protected function response();   //处理者回应

    //设置处理级别
    public function setHandlerLevel($level){
        $this->level = $level;
    }

    //设置下一个处理者是谁
    public function setNextHandler(Handler $handler){
        $this->next_handler = $handler;
        $this->next_handler->setHandlerLevel($this->level+1);
    }

    //责任链实现的主要方法，判断责任链是否属于该对象，不属于转发给下一级。使用final 不允许重写
    final public function handlerMessage(Request $request){
        if ($this->level == $request->getLever()) {
            $this->response();
        }else{
            if ($this->next_handler != null) {
                $this->next_handler->handlerMessage($request);
            }else{
                echo '哈哈，没人鸟你'.PHP_EOL;
            }
        }
    }
}

//具体处理者
class HeadMan extends Handler{
    protected function response()
    {
        echo "HeadMan" . PHP_EOL;
    }
}

class Director extends Handler{
    protected function response()
    {
        echo "Director" . PHP_EOL;
    }
}

class Manager extends Handler{
    protected function response()
    {
        echo "Manager" . PHP_EOL;
    }
}

//请求类
class Request{
    protected $level = array('请假'=>0,'休假'=>1,'辞职'=>2);  //测试方便 默认写的

    protected $request;

    public function __construct($request){
        $this->request = $request;
    }

    public function getLever(){
        return array_key_exists($this->request,$this->level) ? $this->level[$this->request] : -1;
    }
}

//实例化处理者
$headman = new HeadMan;
$director = new Director;
$manager = new Manager;

//责任链组装
$headman->setNextHandler($director);
$director->setNextHandler($manager);

//传递请求
$headman->handlerMessage(new Request('请假'));
$headman->handlerMessage(new Request('休假'));
$headman->handlerMessage(new Request('辞职'));
$headman->handlerMessage(new Request('加薪'));

