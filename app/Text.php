<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Text extends Authenticatable
{
    protected $table = "text";

    public function addNew($data)
    {
        
        $lid = $data['lid'];

        for($i=0;$i<count($lid);$i++)
        {
            $allData = [

            'skip_button' 				=> $data['skip_button'][$i],
            'enter_button' 			   	=> $data['enter_button'][$i],
            'city_title' 			  	=> $data['city_title'][$i],
            'city_search' 			  	=> $data['city_search'][$i],
            'city_heading' 			  	=> $data['city_heading'][$i],
            'city_button' 			  	=> $data['city_button'][$i],
            'home_search' 			  	=> $data['home_search'][$i],
            'home_offer' 			  	=> $data['home_offer'][$i],
            'home_fast_delivery' 		=> $data['home_fast_delivery'][$i],
            'home_trending' 			=> $data['home_trending'][$i],
            'home_new_arrival' 			=> $data['home_new_arrival'][$i],
            'home_by_rating' 			=> $data['home_by_rating'][$i],
            'home_coupon' 			    => $data['home_coupon'][$i],
            'home_per_person' 			=> $data['home_per_person'][$i],
            'home_footer_name' 			=> $data['home_footer_name'][$i],
            'home_nearest' 			  	=> $data['home_nearest'][$i],
            'home_cart' 			  	=> $data['home_cart'][$i],
            'home_profile' 			  	=> $data['home_profile'][$i],
            'home_cats' 			  	=> $data['home_cats'][$i],
            'menu_title' 			  	=> $data['menu_title'][$i],
            'menu_page_title' 			=> $data['menu_page_title'][$i],
            'menu_footer' 			  	=> $data['menu_footer'][$i],
            'item_view_info'            => $data['item_view_info'][$i],
            'item_veg_only' 			=> $data['item_veg_only'][$i],
            'item_add_button' 			=> $data['item_add_button'][$i],
            'item_addon_title' 			=> $data['item_addon_title'][$i],
            'item_size_heading'         => $data['item_size_heading'][$i],
            'item_small'                => $data['item_small'][$i],
            'item_m'                    => $data['item_m'][$i],
            'item_large'                => $data['item_large'][$i],
            'addon_add_title'           => $data['addon_add_title'][$i],
            'item_addon_heading' 		=> $data['item_addon_heading'][$i],
            'item_addon_button' 		=> $data['item_addon_button'][$i],
            'info_title' 			  	=> $data['info_title'][$i],
            'info_rating_title'         => $data['info_rating_title'][$i],
            'info_open'                 => $data['info_open'][$i],
            'info_close'                => $data['info_close'][$i],
            'info_person'               => $data['info_person'][$i],
            'info_d_time' 		        => $data['info_d_time'][$i],
            'cart_heading' 			  	=> $data['cart_heading'][$i],
            'cart_total' 			 	=> $data['cart_total'][$i],
            'cart_delivery' 			=> $data['cart_delivery'][$i],
            'cart_coupon' 			  	=> $data['cart_coupon'][$i],
            'cart_payable' 			  	=> $data['cart_payable'][$i],
            'cart_button'               => $data['cart_button'][$i],
            'cart_empty'                => $data['cart_empty'][$i],
            'cart_start_order'          => $data['cart_start_order'][$i],
            'cart_price'                => $data['cart_price'][$i],
            'cart_qty'                  => $data['cart_qty'][$i],
            'cart_discount'             => $data['cart_discount'][$i],
            'cart_apply' 			  	=> $data['cart_apply'][$i],
            'coupon_title' 			  	=> $data['coupon_title'][$i],
            'coupon_heading' 			=> $data['coupon_heading'][$i],
            'coupon_button' 			=> $data['coupon_button'][$i],
            'login_title' 			  	=> $data['login_title'][$i],
            'login_heading' 			=> $data['login_heading'][$i],
            'login_button' 			  	=> $data['login_button'][$i],
            'login_forgot_password' 	=> $data['login_forgot_password'][$i],
            'login_reset_password' 		=> $data['login_reset_password'][$i],
            'login_signup' 			  	=> $data['login_signup'][$i],
            'forgot_title' 			  	=> $data['forgot_title'][$i],
            'forgot_heading' 			=> $data['forgot_heading'][$i],
            'forgot_text' 			  	=> $data['forgot_text'][$i],
            'signup_title' 			  	=> $data['signup_title'][$i],
            'signup_heading' 			=> $data['signup_heading'][$i],
            'signup_button' 			=> $data['signup_button'][$i],
            'place_title' 			  	=> $data['place_title'][$i],
            'place_delivery_heading' 	=> $data['place_delivery_heading'][$i],
            'place_add_address'         => $data['place_add_address'][$i],
            'place_address_text' 	    => $data['place_address_text'][$i],
            'place_payment_heading' 	=> $data['place_payment_heading'][$i],
            'place_order_button'        => $data['place_order_button'][$i],
            'add_title'                 => $data['add_title'][$i],
            'add_address'               => $data['add_address'][$i],
            'add_landmark'              => $data['add_landmark'][$i],
            'add_button' 		        => $data['add_button'][$i],
            'confirm_title' 			=> $data['confirm_title'][$i],
            'confirm_heading' 			=> $data['confirm_heading'][$i],
            'confirm_view_order' 		=> $data['confirm_view_order'][$i],
            'confirm_order_id' 			=> $data['confirm_order_id'][$i],
            'confirm_total' 			=> $data['confirm_total'][$i],
            'profile_title' 			=> $data['profile_title'][$i],
            'profile_heading' 			=> $data['profile_heading'][$i],
            'profile_welcome' 			=> $data['profile_welcome'][$i],
            'profile_order_history'   	=> $data['profile_order_history'][$i],
            'profile_setting' 			=> $data['profile_setting'][$i],
            'profile_logout' 			=> $data['profile_logout'][$i],
            'history_title' 			=> $data['history_title'][$i],
            'history_heading' 			=> $data['history_heading'][$i],
            'history_date' 			  	=> $data['history_date'][$i],
            'history_status' 			=> $data['history_status'][$i],
            'history_item' 			  	=> $data['history_item'][$i],
            'history_qty' 			  	=> $data['history_qty'][$i],
            'history_price' 			=> $data['history_price'][$i],
            'history_rating' 			=> $data['history_rating'][$i],
            'history_cancel' 			=> $data['history_cancel'][$i],
            'rating_title' 			  	=> $data['rating_title'][$i],
            'rating_heading' 			=> $data['rating_heading'][$i],
            'rating_msg' 			  	=> $data['rating_msg'][$i],
            'rating_button' 			=> $data['rating_button'][$i],
            'about_title' 			  	=> $data['about_title'][$i],
            'how_title' 			  	=> $data['how_title'][$i],
            'faq_title' 			  	=> $data['faq_title'][$i],
            'contact_title'             => $data['contact_title'][$i],
            'language'                  => $data['language'][$i],
            'home'                      => $data['home'][$i],
            'city'                      => $data['city'][$i],
            'account'                   => $data['account'][$i],
            'order'                     => $data['order'][$i],
            'd_no_order'                => $data['d_no_order'][$i],
            'd_new_order'               => $data['d_new_order'][$i],
            'd_view_detail'             => $data['d_view_detail'][$i],
            'd_user'                    => $data['d_user'][$i],
            'd_phone'                   => $data['d_phone'][$i],
            'd_address'                 => $data['d_address'][$i],
            'd_start_ride'              => $data['d_start_ride'][$i],
            'd_complete_ride'           => $data['d_complete_ride'][$i],
            'd_total_amount'            => $data['d_total_amount'][$i],
            'd_pay'                     => $data['d_pay'][$i],
            'close'                     => $data['close'][$i],
            's_total_order'             => $data['s_total_order'][$i],
            's_complete_order'          => $data['s_complete_order'][$i],
            's_new_order'               => $data['s_new_order'][$i],
            's_new_status'              => $data['s_new_status'][$i],
            's_confirm_order'           => $data['s_confirm_order'][$i],
            's_out_delivery_status'     => $data['s_out_delivery_status'][$i],
            's_complete_status'         => $data['s_complete_status'][$i],
            's_detail_title'            => $data['s_detail_title'][$i],
            's_menu_title'              => $data['s_menu_title'][$i],
            's_order_overview'          => $data['s_order_overview'][$i],
            's_c_order'                 => $data['s_c_order'][$i],
            's_cancel_button'           => $data['s_cancel_button'][$i],
            's_confirm_button'          => $data['s_confirm_button'][$i],
            's_assign_button'           => $data['s_assign_button'][$i],

            ];

            Text::where('lang_id',$lid[$i])->delete();

            $add 			= new Text;
            $add->lang_id   = $lid[$i]; 
            $add->s_data    = serialize($allData); 
            $add->save();
        }
    }

    public function getSData($lid,$index)
    {
    	$res = Text::where('lang_id',$lid)->first();

    	if(isset($res->id))
    	{
    		$sdata = unserialize($res->s_data);

    		return isset($sdata[$index]) ? $sdata[$index] : null;
    	}
    	else
    	{
    		return null;
    	}
    }

    public function getAppData($id)
    {
        $res = Text::where('lang_id',$id)->first();
   
        return unserialize($res->s_data);
    }
}
