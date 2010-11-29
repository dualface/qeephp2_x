<?php

class QGenerator_Controller extends QGenerator_Abstract
{
    /**
     * 生成指定名称的控制器
     *
     * @param string $controller_name
     * @param string $ns
     *
     * @return QGenerator_Controller
     */
    function generate($controller_name, $ns = null)
    {
        if (strpos($controller_name, '::'))
        {
            list($ns, $controller_name) = explode('::', $controller_name);
        }

        if ($ns)
        {
            $class_name = "Controller_{$ns}_{$controller_name}";
        }
        else
        {
            $class_name = "Controller_{$controller_name}";
        }
        $class_name = $this->_normalizeClassName($class_name);
        $path = $this->_classFilePath($this->_module->moduleDir(), $class_name, '_controller.php');

        $this->_logClean();
        if (file_exists($path))
        {
            throw new Q_ClassFileExistsException($class_name, $path);
        }

        // 创建控制器文件
        $data = array(
            'class_name' => $class_name,
            'ns'  => $ns,
        );

        $content = $this->_parseTemplate('controller', $data);
        $this->_createFile($path, $content);

        // 建立视图目录
        $dir = rtrim($this->_module->moduleDir(), '/\\') . '/view';
        if ($ns)
        {
            $dir .= "/{$ns}";
        }

        $this->_createDirs($dir . '/_layouts');
        $this->_createDirs($dir . "/{$controller_name}");
        return $this;
    }

}


