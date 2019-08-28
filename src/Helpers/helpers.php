<?php

if (! function_exists('blogged_assets')) 
{
    function blogged_assets($path, $secure = null)
    {
        return asset('vendor/jebog/blogged/assets/' . $path, $secure);
    }
}

if (! function_exists('blogged_path')) 
{
    function blogged_path()
    {
        return config('blogged.routes.blog', 'blog');
    }
}


if (! function_exists('blogged_dashboard_path')) 
{
    function blogged_dashboard_path()
    {
        return config('blogged.routes.dashboard', 'blog/dashboard');
    }
}

if (! function_exists('blogged_version')) 
{
    function blogged_version()
    {
        return 'v1.0.0';
    }
}
