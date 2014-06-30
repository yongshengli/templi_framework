<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 14-6-29
 * Time: 下午7:23
 */
trait Singleton
{
    /** @var array 实例化对象存储数组*/
    private static $_instance = [];
    private $_className= null;

    /**
     * 获取类的 实例
     * @return mixed
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset($_instance[$class])) {
            self::$_instance[$class] = new $class();
            self::$_instance[$class]->_className = $class;
        }
        return $_instance[$class];
    }
    /**
     * 销毁当前实例
     */
    public function destroy(){
        unset(self::$_instance[$this->_className], $this);
    }

    /**
     * 禁止 clone
     */
    final public function __clone()
    {
        trigger_error( 'Clone is not allowed.', E_USER_ERROR );
    }
}