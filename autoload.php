<?php

/**
 * @author Andrew S Erwin
 * @link https://github.com/erwininteractive
 *
 * PSR-4 compliant class autoloader
 */

Autoload::init()->loadClasses();

class Autoload
{
    private static ?autoload $instance = null;
    private static array $namespaces = [];

    private function __construct()
    {
        /**
         * Set up your name spaces like this:
         * 'Namespace\\' => ['physical directory'],
         *
         * @var array list of namespaces
         */
        self::$namespaces = [
            'App\\'         => ['../app/'],
            'Framework\\'   => ['../src/']
        ];
    }

    public static function init(): self
    {
        if (self::$instance === null)
        {
            self::$instance = new Autoload;
        }

        return self::$instance;
    }

    public static function loadClasses()
    {
        spl_autoload_register('self::load');
    }

    private static function load(string $ns): bool
    {
        $namespace = $ns;

        while ($pos = strrpos($namespace, '\\')) 
        {
            $namespace = substr($ns, 0, $pos + 1);
            $class = substr($ns, $pos + 1);
            
            if (isset(self::$namespaces[$namespace]))
            {
                foreach (self::$namespaces[$namespace] as $dir) 
                {
                    $file = $dir.str_replace('\\', '/', $class).'.php';
        
                    if (file_exists($file))
                    {
                        require($file);
                        return true;
                    }
                }
            }
    
            $namespace = rtrim($namespace, '\\');
        }

        return false;
    }
}
