<?php
// app/Http/Middleware/LogActivity.php

namespace App\Http\Middleware;

use Closure;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class LogActivity
{
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        // Only log for authenticated users
        if (auth()->check()) {
            $user = auth()->user();
            
            // Log based on route name or action
            $route = $request->route();
            
            if ($route) {
                $action = $route->getActionMethod();
                $controller = class_basename($route->getController());
                
                // Skip logging for certain routes (like health checks, etc.)
                if (!in_array($route->uri(), ['_debugbar/*', 'telescope/*'])) {
                    $this->logger
                        ->by($user)
                        ->action($action)
                        ->description("Accessed {$controller}::{$action}")
                        ->withProperties([
                            'route' => $route->uri(),
                            'method' => $request->method(),
                        ])
                        ->log();
                }
            }
        }
    }
}