<?php
/**
 * Клас являє реалізацію рендера для візуалізації контента
 * @autor Lizogyb Igor
 * @since 1.0
 */

namespace Framework\Renderer;

use \Framework\Exception\FileException;
use Framework\DI\Service;

class Render {
	private $path = '';
    private $viewFiles = [];
    public $defaultExtension = 'php';
    protected $content;
    protected $mainLayout = 'layout.html';

    public function __construct($path, $content=null)
    {
        $this->path = $path;
        $this->content = $content;
    }
    
    /**
     * Метод для отримання масиву з даними 
     * про шляхи шаблонів та їх назви
     * @param string $templatePath рядок із шляхом
     * до папки з шаблонами
     * @return array string інформація про шаблони та шляхи до них
     */
    protected function getTemplatesInfo($templatePath) 
    {
       // $templatePath = str_replace("\\", DIRECTORY_SEPARATOR, $templatePath);
        $res = scandir($templatePath);
        $files = array();
	
        foreach($res as $value) {
            if(!in_array($value, array('.', '..'))) {
                if(!is_dir($templatePath . DIRECTORY_SEPARATOR . $value)) {
                    $files[$value] = $templatePath . '/' .$value;
                } else {
                    $path = $templatePath . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR;
                    $files[$value] = $this->getTemplatesInfo($path);
                }
            }
        }
        return $files;
    }
    
    /**
     * Метод для отримання списку наявних шаблонів із їхнім шляхом 
     * @param array string $files масив з даними про шляхи та назви шаблонів
     * @return array string список з назвами шаблонів та шляхами до них
     */
    public function getTemplateFilesList() 
    {
        $files = $this->getTemplatesInfo($this->path);
        $templFiles  = array();
        foreach($files as $key => $value) {
            if(!is_array($value)) {
                $templFiles[str_replace(".php", "", $key)] = $value;       
            } else {
                foreach($value as $key=>$value) {
                    $templFiles[str_replace(".php", "", $key)] = str_replace(["\\", "\/"], DIRECTORY_SEPARATOR, $value);
                }        
            }
        }
        $this->viewFiles =  $templFiles;
    }
    
    /**
     * Метод пошуку потрібного шаблону
     * @param string $view назва шаблону 
     * @return string шлях до потрібного шаблону
     * @throw FileException якщо потрібного шаблону не знайдено
     */
    public function findView($view)
    {
        //Отримуємо список шаблонів з їхніми шляхами
        $this->getTemplateFilesList();
        
        if(isset($this->viewFiles[$view])) {
            return $this->viewFiles[$view]; 
        } else {
            throw new FileException("File with template <b>$view</b> not Found", 505, true);
        }
    }
    
    /**
     * Метод для візуалізації статичних сторінок
     * @param string $FileName ім'я статичної сторінки
     * @return string вміст сторінки
     */
    public function renderStaticPage($fileName)
    {
        echo file_get_contents($this->path . DIRECTORY_SEPARATOR . $fileName);    
    }

    /**
     * Метод для візуалізації контенту
     * @param string $view ім'я шаблону
     * @param array $params змінні для шаблону
     * @return string вміст сторінки
     */
    public function render($view, $params = [])
    {
        $viewFile = $this->findView($view);
        return $this->renderFile($viewFile, $params);
    }

    /**
     * Метод для візуалізації контенту з файла шаблона
     * @param string $viewFile ім'я файлу з шаблоном
     * @param array $_params параметри які потрібно передати в шаблон
     * @return string  вміст сторінки
     */
    public function renderFile($viewFile, $params)
    { 
        $output = '';
        $ext = pathinfo($viewFile, PATHINFO_EXTENSION);

        if($ext === 'php' ) {
            $output = $this->renderPhpFile($viewFile, $params);
        }

        return $output;
    }
     
    /**
     * Метод для візуалізації контенту з php файлу
     * @param string $_file ім'я файлу
     * @param array $_params параметри які потрібно передати в шаблон
     * @return string  вміст сторінки
     */
    public function renderPhpFile($_file, $_params = [])
    {  
        $include = function($controller, $action, $par) {
            $controller = new $controller;
            $action = $action . 'Action';
            call_user_func(array($controller, $action), $par['id']);
        };
        
        $getRoute = function($name) {
            return Service::get('router')->build($name);
        };
        
        $generateToken = function(){
            return "123";
        };

        $user = Service::get('session')->read('user');
        $flush = "Test flush";
        
        //старт буферизація виводу
        ob_start();
        //вимкнення неявного очищення буфера після кожного виводу
        ob_implicit_flush(false);
        //імпорт змінних з масиву в поточну символьну таблицю
        if(!empty($_params)) {
            extract($_params, EXTR_OVERWRITE);
        } else {
            $_params = $this->content;
        }
        //підключаємо головний шаблон
        include($this->findView($this->mainLayout));
        //підключаємо потрібну заготовку
        include($_file);
        //Очищаємо буфер виводу
        //return ob_get_clean();       
    }
}
?>
