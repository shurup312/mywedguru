<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 07.07.2015
 * Time: 16:21
 */
namespace system\core\base;

class Session extends Object
{
    const FLASH_BASE_NAME = 'flash';

    public function __construct()
    {
        $this->start();
        $this->init();
    }

    /**
     * Real session start if it not active yet
     *
     * @throws \Exception
     */
    public function start()
    {
        if ($this->isActive()) {
            return ;
        }
        if (!session_start()) {
            throw new \Exception('Session start fail');
        }
    }

    public function isActive()
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function destroy()
    {
//        session_destroy();
        //session_unset();
    }

    /**
     * Setting session variable
     *
     * @use App::session()->set(user.id, 100)
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $s = function ($arr) use (&$s) {
            $name = array_shift($arr);

            return (count($arr) > 1) ? [$name => $s($arr)] : [$name => $arr[0]];
        };
        $_SESSION = array_replace_recursive($_SESSION, $s(array_merge(explode('.', $name), [$value])));

        return $this;
    }

    /**
     * Getting session variable
     *
     * @use App::session()->get(user.id)
     * @param null $name
     * @return mixed Session variable value
     * @throws \Exception If variable not exists
     */
    public function get($name = null)
    {
        if ($name === null) {
            return $_SESSION;
        }
        $value = $_SESSION;
        $nameArray = $this->getNameFromAlias($name);
        foreach ($nameArray as $name) {
            $value = $this->getValueByName($name, $value);
        }
        return $value;
    }

    private function getNameFromAlias($name)
    {
        return explode('.',$name);
    }

    private function getValueByName($name, $arrayValues)
    {
        if(!isset($arrayValues[$name])){
            throw new \Exception(sprintf("Session variable '%s' not exists", $name));
        }
        return $arrayValues[$name];
    }

    public function has($name)
    {
        $h = function ($arr, $session) use (&$h) {
            $k = array_shift($arr);

            if (!isset($session[$k])) {
                return false;
            }

            return count($arr) ? $h($arr, $session[$k]) : true;
        };

        return $h(array_merge(explode('.', $name)), $_SESSION);
    }

    public function delete($name = null)
    {
        if ($name === null) {
            $_SESSION = [];
        }

        $d = function ($arr, &$session) use (&$d) {
            $k = array_shift($arr);
            $result = null;
            if (isset($session[$k])) {
                if (count($arr)) {
                    $d($arr, $session[$k]);
                } else {
                    unset($session[$k]);
                }
            }
        };

        $d(array_merge(explode('.', $name)), $_SESSION);
    }

    public function getFlash($name)
    {
        $key = self::FLASH_BASE_NAME . '.' . $name;

        if (!$this->has($key)) {
            return null;
        }

        $flash = $this->get($key);
        $this->delete($key);

        return $flash;
    }

    public function hasFlash($name)
    {
        return $this->has(self::FLASH_BASE_NAME . '.' . $name);
    }

    public function setFlash($name, $content)
    {
        $this->set(self::FLASH_BASE_NAME . '.' . $name, $content);
    }

    private function init()
    {
//        $this->delete(self::FLASH_BASE_NAME);
    }


}
