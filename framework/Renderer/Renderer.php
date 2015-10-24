<?php
/**
 * Клас являє реалізацію рендера для візуалізації контента
 * @autor Lizogyb Igor
 * @since 1.0
 */

namespace Framework\Renderer;

use \Framework\Exception\FileException;
use \Framework\DI\Service;
use \Framework\Application;

class Renderer {
	private $path = '';
    private $viewFiles = [];
    public $defaultExtension = 'php';
    protected $data = array();
    protected $layout;

    /**
     * Конструктор для ініціалізації Візуалізптора
     * @param string $layout потрібний шаблон
     * @param array $data змінні для передачі в шаблон
     *
     */
    public function __construct($layout, $data)
    {
        $this->data = $data;
        
        if (file_exists($layout)){
            $this->layout = $layout;
        } else {
            $this->path = $_SERVER['DOCUMENT_ROOT'] . Application::VIEW_PATH;
            $this->layout = $this->findView($layout);
        }
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
     * Метод для візуалізації контенту з php файлу
     * @param string $_file ім'я файлу
     * @param array $_params параметри які потрібно передати в шаблон
     * @return string  вміст сторінки
     */
    public function render()
    {  
        $include = function($controller, $action, $par) {
            $controller = new $controller;
            $action = $action . 'Action';
            call_user_func(array($controller, $action), $par['id']);
        };
        
        $getRoute = function($name, $params = array()) {
            return Service::get('router')->build($name, $params);
        };
        
        $generateToken = function(){
            return md5(uniqid(rand(),1));
        };

        $user = Service::get('session')->read('user');

        $flush = (Service::get('session')->read('flush')) ? (Service::get('session')->read('flush')) : array();
        Service::get('session')->del('flush');

        //старт буферизація виводу
        ob_start();
        //вимкнення неявного очищення буфера після кожного виводу
        ob_implicit_flush(false);
        //імпорт змінних з масиву в поточну символьну таблицю
        extract($this->data, EXTR_OVERWRITE);
        //підключаємо головний шаблон
        include $this->layout;
        //Очищаємо буфер виводу
        return ob_get_clean();  
    }     
}
?>
