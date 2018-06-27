<?php
/**
 * Request service
 * @package core
 * @version 0.0.2
 */

namespace Mim\Service;

class Request extends \Mim\Service
{
    private $_props;
    
    private $_file_gates;
    private $_file_routes;
    
    public function __construct(string $file_gates='/etc/cache/gates.php', string $file_routes='/etc/cache/routes.php'){
        $this->_file_gates  = $file_gates;
        $this->_file_routes = $file_routes;
        
        $this->_fillProps();
        $this->_fillGate();
        $this->_fillRoute();
    }
    
    private function _fillGate(): void{
        $gates = include BASEPATH . $this->_file_gates;
        if(!$gates)
            return;
        $is_cli = $this->isCLI();
        $r_host = $this->host;
        $r_path = $this->path;
        
        foreach($gates as $gate){
            $m_host = [];
            
            if($is_cli !== ($gate->host->value === 'CLI'))
                continue;
            
            $sep = $is_cli ? ' ' : '.';
            if(!$is_cli && null === ($m_host = $this->_matchFilter($gate->host, $r_host, $sep)))
                continue;
            
            $sep = $is_cli ? ' ' : '/';
            if(null === ($m_path = $this->_matchFilter($gate->path, $r_path, $sep, true)))
                continue;
            
            if($m_host){
                foreach($m_host['params'] as $k => $val)
                    $this->_props['param']->$k = $val;
            }
            
            foreach($m_path['params'] as $k => $val)
                $this->_props['param']->$k = $val;
            
            $this->_props['gate'] = $gate;
            break;
        }
    }
    
    private function _fillProps(): void{
        $this->_props = [
            'accept'    => null,
            'agent'     => $this->getServer('HTTP_USER_AGENT'),
            'body'      => null,
            'gate'      => null,
            'handler'   => null,
            'host'      => $this->getServer('HTTP_HOST'),
            'ip'        => null,
            'length'    => (int)$this->getServer('CONTENT_LENGTH', '0'),
            'method'    => $this->getServer('REQUEST_METHOD', 'GET'),
            'param'     => (object)[],
            'path'      => null,
            'route'     => null,
            'scheme'    => $this->getServer('REQUEST_SCHEME'),
            'type'      => $this->getServer('CONTENT_TYPE'),
            'url'       => $this->getServer('REQUEST_SCHEME')
                . '://' 
                . $this->getServer('HTTP_HOST')
                . $this->getServer('REQUEST_URI')
        ];
        
        // content type
        $type = $this->getServer('CONTENT_TYPE');
        if(strstr($type, ';'))
            $type = explode(';', $type)[0];
        if(strstr($type, '/'))
            $type = explode('/', $type)[1];
        $this->_props['type'] = strtolower($type);
        
        if($this->isCLI()){
            $paths = $_SERVER['argv'] ?? [];
            if(isset($paths[0])){
                if(preg_match('!index.php$!', $paths[0]))
                    array_shift($paths);
            }
            $paths = implode(' ', $paths);
            $this->_props['path'] = trim($paths);
        }else{
            $this->_props['path'] = trim('/' . trim(preg_replace('!\?.+$!', '', $this->getServer('REQUEST_URI')), '/'));
        }
    }
    
    private function _fillRoute(): void{
        if(!$this->gate)
            return;
        
        $use404 = true;
        $routes = [];
        
        $is_cli = $this->isCLI();
        $r_path = $this->path;
        $r_method = $this->method;
        $p_sep  = $is_cli ? ' ' : '/';
        
        $gates = include BASEPATH . $this->_file_routes;
        if($gates && property_exists($gates, $this->gate->name)){
            $routes = $gates->{$this->gate->name};
            foreach($routes as $route){
                if(!$is_cli && !in_array($r_method, $route->_method))
                    continue;
                
                $sep = $this->gate->host->value === 'CLI' ? ' ' : '/';
                if(null === ($m_path = $this->_matchFilter($route->path, $r_path, $sep)))
                    continue;
                
                // set the route
                $this->_props['route'] = $route;
                
                // set the params
                foreach($m_path['params'] as $k => $v)
                    $this->_props['param']->$k = $v;
                
                // set handler
                $this->_props['handler'] = $route->_handlers;
                $use404 = false;
                break;
            }
        }
        
        // find the route
        if($use404){
            $route = $this->gate->errors->{'404'};
            $this->_props['route'] = $route;
            $this->_props['handler'] = $route->_handlers;
        }
    }
    
    private function _getAccept(): object{
        if(!is_null($this->_props['accept']))
            return $this->_props['accept'];
        
        // accept encoding
        $a_e = array_map('trim', explode(',', $this->getServer('HTTP_ACCEPT_ENCODING')));
        
        // accept language
        $a_l = array_map('trim', explode(',', $this->getServer('HTTP_ACCEPT_LANGUAGE')));
        
        // accept type
        $a_t = explode(',', $this->getServer('HTTP_ACCEPT'));
        foreach($a_t as &$at){
            $at = explode(';', trim($at))[0];
            if(strstr($at, '/'))
                $at = explode('/', $at)[1];
        }
        
        $this->_props['accept'] = (object)[
            'encoding' => $a_e,
            'language' => $a_l,
            'type' => $a_t
        ];
        
        return $this->_props['accept'];
    }
    
    private function _matchFilter(object $filter, string $target, string $sep='/', bool $next=false): ?array{
        $result = [
            'params' => []
        ];
        
        if($filter->_type === 'text'){
            $value_len  = strlen($filter->value);
            $target_len = strlen($target);
            
            if($next && $value_len !== $target_len){
                if(substr($target, 0, $value_len) !== $filter->value)
                    return null;
                
                // match the next character
                if(substr($filter->value, -1) !== $sep){
                    $filter_value_n = ltrim($filter->value . $sep);
                    $filter_value_len = strlen($filter_value_n);
                    if(substr($target, 0, $filter_value_len) !== $filter_value_n)
                        return null;
                }
            }else{
                if($filter->value != $target)
                    return null;
            }
        }elseif($filter->_type === 'regex'){
            if(!preg_match($filter->_value, $target, $match))
                return null;
            foreach($filter->params as $par => $type){
                $result['params'][$par] = $match[$par];
                if($type === 'rest')
                    $result['params'][$par] = explode($sep, $result['params'][$par]);
            }
        }else{
            return null;
        }
        
        return $result;
    }
    
    public function __get(string $name){
        $result = null;
        switch($name){
        case 'accept':
            $result = $this->_getAccept();
            break;
        case 'body':
            $result = $this->getBody();
            break;
        case 'ip':
            $result = $this->getIP();
            break;
        default:
            if(isset($this->_props[$name]))
            $result = $this->_props[$name];
        }
        
        return $result;
    }
    
    public function forward(string $name, array $params=[]): void{
        $c_gates  = include BASEPATH . $this->_file_gates;
        $c_routes = include BASEPATH . $this->_file_routes;
        
        $gate_by_route = $c_routes->_gateof;
        $gate_name = $gate_by_route->$name;
        
        // set the gate
        foreach($c_gates as $gate){
            if($gate->name === $gate_name){
                $this->_props['gate'] = $gate;
                break;
            }
        }
        
        // set the route
        $route = $c_routes->$gate_name->$name;
        $this->_props['route'] = $route;
        
        // set the params
        $this->_props['param'] = (object)[];
        foreach($params as $k => $v)
            $this->_props['param']->$k = $v;
        
        // set handler
        $this->_props['handler'] = $route->_handlers;
        
        // next
        $this->next();
    }
    
    public function get(string $name=null, $def=null){
        if(!is_null($name)){
            return $this->getQuery($name)
                ?? $this->getPost($name)
                ?? $this->getFile($name)
                ?? $this->getBody($name)
                ?? $def;
        }
        
        $result = [];
        
        $body = $this->getBody();
        if(is_array($body) || is_object($body)){
            foreach($body as $k => $v)
                $result[$k] = $v;
        }
        
        foreach($_FILES as $k => $v)
            $result[$k] = $v;
        
        foreach($_POST as $k => $v)
            $result[$k] = $v;
        
        foreach($_GET as $k => $v)
            $result[$k] = $v;
        
        return $result;
    }
    
    public function getBody(string $name=null, $def=null){
        if(is_null($this->_props['body'])){
            $body = file_get_contents('php://input');
            if($this->type == 'json')
                $body = json_decode($body);
            $this->_props['body'] = $body;
        }
        
        if(is_null($name))
            return $this->_props['body'];
        if(!is_object($this->_props['body']))
            return $def;
        return $this->_props['body']->$name ?? $def;
    }
    
    public function getCookie(string $name, string $def=null): ?string{
        return $_COOKIE[$name] ?? $def;
    }
    
    public function getFile(string $name): ?array{
        return $_FILES[$name] ?? null;
    }
    
    public function getIP(): string{
        if(!is_null($this->_props['ip']))
            return $this->_props['ip'];
        
        $this->_props['ip'] =
            $_SERVER['HTTP_CLIENT_IP'] ??
            $_SERVER['HTTP_X_FORWARDED_FOR'] ??
            $_SERVER['HTTP_X_FORWARDED'] ??
            $_SERVER['HTTP_FORWARDED_FOR'] ??
            $_SERVER['HTTP_FORWARDED'] ??
            $_SERVER['REMOTE_ADDR'] ??
            'UNKNOWN';
        return $this->_props['ip'];
    }
    
    public function getPost(string $name=null, $def=null){
        if(is_null($name))
            return $_POST;
        return $_POST[$name] ?? $def;
    }
    
    public function getQuery(string $name=null, $def=null){
        if(is_null($name))
            return $_GET;
        return $_GET[$name] ?? $def;
    }
    
    public function getServer(string $name, string $def=null): ?string{
        return $_SERVER[$name] ?? $def;
    }
    
    public function isAjax(): bool{
        $header = $this->getServer('HTTP_X_REQUESTED_WITH');
        return $header && strtolower($header) === 'xmlhttprequest';
    }
    
    public function isCLI(): bool{
        return php_sapi_name() === 'cli';
    }
    
    public function next(): void{
        $handlers = $this->_props['handler'];
        if(!$handlers)
            return;
        $handler = null;
        foreach($handlers as $index => $hand){
            if($hand->solved)
                continue;
            $handler = $hand;
            $hand->solved = true;
            $handlers[$index] = $hand;
            $this->_props['handler'] = $handlers;
        }
        
        if(!$handler){
            Mim::$app->res->send();
            return;
        }
        
        $class = $handler->class;
        $method= $handler->method;
        
        $hdr = new $class();
        $hdr->$method();
    }
    
    public function setProp(string $name, $value): void{
        // readonly props
        $readonly = [
            'accept',
            'agent',
            'body',
            'gate',
            'host',
            'ip',
            'length',
            'method',
            'param',
            'path',
            'route',
            'scheme',
            'type',
            'url'
        ];
        
        if(in_array($name, $readonly)){
            trigger_error('Property ' . $name . ' is readonly');
            return;
        }
        
        $this->_props[$name] = $value;
    }
}