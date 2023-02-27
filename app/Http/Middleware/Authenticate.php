<?php

namespace App\Http\Middleware;


use App\Order;

use Closure;
use View; 
use Auth;
use DB;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
           return redirect(env('user').'/login')->with('error','Please login for access this page');
        }else {
            $orders = Order::where(function($query){
                $query->where('orders.store_id',Auth::user()->id);
                $query->whereIn('orders.status',[0]);
             })->orderBy('orders.id','DESC')
               ->get();
            $orders_count     = $orders->count();

            View::share('orders_count', $orders_count);
            View::share('orders', $orders);
        }
        
        return $next($request);
    }
}
