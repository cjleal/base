<?php

namespace Modules\Admin\Model;

use DB;
use URL;
use Config;
use Session;
use Modules\Admin\Model\Modelo;

class Menu extends Modelo
{
    protected $table = '';
    protected static $urlSitio = '';

    protected static $metodos_excluidos = [
        "__construct",
        "ruta",
        "permisologia",
        "_app",
        "titulo",
        "limit_text",
        "limit_text",
        "middleware",
        "callAction",
        "missingMethod",
        "__call",
        "getMiddleware",
        "getRouter",
        "getRedirectUrl",
        "getValidationFactory"
    ];
    protected static $form_metodos = ['get', 'post', 'any', 'delete', 'put'];
    protected static $routes = [];

    public static function routers()
    {
        $routeCollection = \Route::getRoutes();

        $routes = [];
        $controllers = [];

        $urlBase = \Config::get('admin.prefix') . '/';
        $urlBase = preg_replace('/\/+/', '/', $urlBase);
        $urlBase = trim($urlBase, '/');
        
        $patternUrl = '/^' . $urlBase . '\//i';
        //dd($routeCollection);
        foreach ($routeCollection as $route) {
            $ruta = $route->getPath();
            //echo $ruta . " \n ";
            if ($urlBase != '' && !preg_match($patternUrl, $ruta)) {
                continue;
            }

            $ruta = preg_replace($patternUrl, '', $ruta);
            $ruta = preg_replace('/\/?\{[_a-zA-Z0-9\?]+\}\/?/', '', $ruta);
            $ruta = preg_replace('/\/index$/i', '', $ruta);

            $accion = $route->getActionName();
            $controller = preg_replace('/\@\w+$/', '', $accion);

            /*
            if ($ruta == 'proyectos')
                dd($route);
            */

            if (isset($controllers[$controller])) {
                //continue;
            }
            
            $_metodos = get_class_methods($controller);
            if (is_null($_metodos)) {
                dd($controller);
            }
            $_metodos = array_diff($_metodos, self::$metodos_excluidos);
            $_metodos = array_values($_metodos);
            $metodos = [];
            
            for ($i = 0, $c = count($_metodos); $i < $c; $i++) {
                $metodos[] = $_metodos[$i];
            }

            //$controllers[$controller] = $metodos;
            $routes[] = $ruta;
        }
        //dd($routes);
        return array_unique($routes);
    }

    public static function estructura($metodos = false)
    {
        if ($metodos !== false) {
            $metodos = self::routers();
        }

        self::$routes = $metodos;

        $_menus = Config::get('menu');
        $_menus = array_values($_menus);
        $menu = [];

        foreach ($_menus as $men) {
            $menus = self::_estructura($men);

            if (isset($menus[0])){
            	$menu[] = $menus[0];
            }
        }
        //dd($metodos, $menu);
        
        $data = [[
			'id'       => md5('#'),
			'text'     => 'Todo',
			'li_attr'  => ['data-direccion' => '#'],
			'icon'     => 'fa fa-sitemap',
			'children' => $menu,
			'state'    => [
				'opened' => true
			]
        ]];
        
        return $data;
    }

    protected static function _estructura($menus)
    {
        $data = [];

        foreach ($menus as $menu) {
            $menu['direccion'] = trim($menu['direccion']);

            $dato = [
				'id'      => md5($menu['direccion']),
				'text'    => $menu['nombre'],
				'li_attr' => ['data-direccion' => $menu['direccion']],
				'icon'    => $menu['icono']
            ];
            
            if (isset($menu['menu'])) {
                //$dato['children'] = [];
                $dato['children'] = self::_estructura($menu['menu']);
            } else {
                if (self::$routes === false) {
                    continue;
                }

                foreach (self::$routes as $route) {
                    if ($route != $menu['direccion'] && strpos($route, $menu['direccion']) === 0) {
                        $texto = $route;
                        $pos = strrpos($texto, "/");
                        if ($pos !== false) {
                            $texto = substr($route, $pos);
                        }

                        $texto = trim($texto, '/');
                        $texto = ucwords(str_replace(['_', '-'], ' ', $texto));
                        
                        if (!isset($dato['children'])) {
                            $dato['children'] = [];
                        }

                        $dato['children'][] = [
							'id'      => md5($route),
							'text'    => $texto,
							'li_attr' => ['data-direccion' => $route],
							'icon'    => 'fa fa-code'
                        ];
                    }
                }
            }

            $data[] = $dato;
        }
        
        return $data;
    }
    
    public static function generar_menu($app = '')
    {
        $usuario = auth()->user();
        
        if (!$usuario) {
            return self::_generar_menu([]);
        }

        $permisos = clone($usuario->permisos());
        $grupos = Config::get('admin.grupos_apps');
        $_menu = Config::get('menu');
        
        //limpiar los elementos vacios
        foreach ($_menu as $key => $value) {
            if (empty($value)) {
                unset($_menu[$key]);
            }
        }
        
        if ($app != '' && $usuario->super == 'n') {
            foreach ($_menu as $key => $value) {
                if ($app == 'admin') {
                    continue;
                }
                if (!in_array($key, $grupos[$app])) {
                    unset($_menu[$key]);
                }
            }
        }

        $_menu = array_values($_menu);

        $menu = [];
        for ($i = 0, $c = count($_menu); $i < $c; $i++) {
            for ($j = 0, $cc = count($_menu[$i]); $j < $cc; $j++) {
                $menu[] = $_menu[$i][$j];
            }
        }

        if (self::$urlSitio == '') {
            $url = Config::get('admin.prefix') . '/';
            $url = preg_replace('/\/+/', '/', $url);
            $url = trim($url, '/');
            $url = URL::to($url);

            self::$urlSitio = $url;
        }

        if ($usuario->super != 's') {
            self::permiso_menu($menu, $permisos);
        }

        return self::_generar_menu($menu);
    }

    protected static function permiso_menu(&$menu, $permisos)
    {
        for ($i = 0, $c = count($menu); $i < $c; $i++) {
            if (isset($menu[$i]['menu'])) {
                self::permiso_menu($menu[$i]['menu'], $permisos);

                if (empty($menu[$i]['menu'])) {
                    unset($menu[$i]);
                }
            } elseif ($permisos->search($menu[$i]['direccion']) === false) {
                unset($menu[$i]);
            }
        }
    }

    protected static function _generar_menu($menu, $contador = 0)
    {
        $html = '';
        //for ($i = 0, $c = count($menu); $i < $c; $i++){
        foreach ($menu as $men) {
            if (isset($men['menu'])) {
                if ($contador === 0) {
                    $html .= '
					<li class="menu-dropdown classic-menu-dropdown">
						<a href="javascript:;">
							<i class="' . $men['icono'] . '"></i>
							' . $men['nombre'] . '
							<span class="fa fa-chevron-down"></span>
						</a>
						<ul class="dropdown-menu pull-left">
							' . self::_generar_menu($men['menu'], $contador + 1) . '
						</ul>
					</li>';
                } else {
                    $html .= '
					<li class="dropdown-submenu">
						<a href="javascript:;">
							<i class="' . $men['icono'] . '"></i>
							' . $men['nombre'] . '
						</a>
						<ul class="dropdown-menu">
							' . self::_generar_menu($men['menu'], $contador + 1) . '
						</ul>
					</li>';
                }
            } else {
                $html .= '
				<li>
					<a href="' . self::$urlSitio . '/' . $men['direccion'] . '" class="nav-link">
						<i class="' . $men['icono'] . '"></i> ' . $men['nombre'] . '
					</a>
				</li>';
            }
        }

        return $html;
    }
}
