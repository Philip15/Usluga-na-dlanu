<?php

namespace App\Libraries;

class HeaderLib
{
    public static function menuItems($user)
    {
        $res='';
        $res = $res.self::menuItem(base_url('UserController/requests'),lang('App.requests'),$user->requestNotifications());
        $res = $res.self::menuItem(base_url('UserController/reviews'),lang('App.reviews'),$user->reviewNotifications());
        if($user->role()=='provider')
        {
            $res = $res.self::menuItem(base_url('ProviderController/requests'),lang('App.incomingRequests'),$user->incomingNotifications());
            $res = $res.self::menuItem(base_url('ProviderController/acceptedRequests'),lang('App.acceptedRequests'),$user->acceptedNotifications());
        }
        $res = $res.self::menuItem(base_url('UserController/editProfile'),lang('App.editProfile'));
        if($user->role()=='admin')
        {
            $res = $res.self::menuItem(base_url('AdminController/categories'),lang('App.editCategories'));
            $res = $res.self::menuItem(base_url('AdminController/accountRequests'),lang('App.accountRequests'),$user->accountNotifications());
        }
        $res = $res.self::menuDivider();
        $res = $res.self::menuItem(base_url('UserController/OPLogout'),lang('App.logout'));
        return $res;
    }

    public static function menuItem($href,$string,$notifCount=null)
    {
        $res = '<li><a class="dropdown-item d-flex justify-content-between" href="'.esc($href).'">'.esc($string);
        if($notifCount!==null && $notifCount!=0)
        {
            $res = $res.' <span class="badge bg-danger ms-2">'.esc($notifCount).'</span>';
        }
        $res = $res.'</a></li>';
        return $res;
    }

    public static function menuDivider()
    {
        return '<li><hr class="dropdown-divider"></li>';
    }
}