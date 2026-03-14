<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected $perPage = 15;
    
    protected function logAdminAction($action, $description, $subject = null)
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ])
            ->event($action)
            ->log($description);
    }
}