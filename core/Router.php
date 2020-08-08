<?php
    class Router
    {
        static $routes = array();
        static $prefixes = array();

        static function prefix($url, $prefix)
        {
            self::$prefixes[$url] = $prefix;
        }

        static function parse($url, $request)
        {
            $url = trim($url,'/');
            if(empty($url))
            {
                if (count(Router::$routes) > 0)
                {
                    $url = Router::$routes[0]['url'];
                }
            }
            else
            {
                foreach (Router::$routes as $value)
                {
                    if (preg_match($value['catcher'], $url, $match)){
                        $request->controller= $value['controller'];
                        $request->params=array();
                        foreach ($value['params'] as $key=>$w) {
                            # code...
                            $request->params[$key]=$match[$key];
                        }
                        if(!empty($match['args'])){
                            $request->params += explode('/', trim($match['args']),'/');
                        }
                        return $request;
                    }
                }
            }

            $params = explode('/', $url);
            if(in_array($params[0], array_keys(self::$prefixes))){
                $request->prefix = self::$prefixes[$params[0]];
                array_shift($params);
            }
            $request->controller = $params[0];
            $request->params = array_slice($params, 1);

            return true;
        }
    }