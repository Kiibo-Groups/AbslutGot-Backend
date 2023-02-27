@php($i = 0)
@foreach($langs as $l)
@php($i++)

<input type="hidden" name="lid[]" value="{{ $l['id'] }}">

<h4 style="color:#fff;">Welcome Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Skip Button</label>
                <input type="text" name="skip_button[]" class="form-control" value="{{ $data->getSData($l['id'],'skip_button') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">App Enter App</label>
                <input type="text" name="enter_button[]" class="form-control" value="{{ $data->getSData($l['id'],'enter_button') }}">
            </div>
        </div>
    </div>
</div>

<!--start-->
<h4>Select City Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Page Title</label>
                <input type="text" name="city_title[]" class="form-control" value="{{ $data->getSData($l['id'],'city_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Search Placeholder</label>
                <input type="text" name="city_search[]" class="form-control" value="{{ $data->getSData($l['id'],'city_search') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Heading</label>
                <input type="text" name="city_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'city_heading') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Button</label>
                <input type="text" name="city_button[]" class="form-control" value="{{ $data->getSData($l['id'],'city_button') }}">
            </div>
        </div>
    </div>
</div>
<!--End-->

<!--start-->
<h4>Home Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Search Placeholder</label>
                <input type="text" name="home_search[]" class="form-control" value="{{ $data->getSData($l['id'],'home_search') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Offer Title Filter</label>
                <input type="text" name="home_offer[]" class="form-control" value="{{ $data->getSData($l['id'],'home_offer') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Fast Delivery Filter</label>
                <input type="text" name="home_fast_delivery[]" class="form-control" value="{{ $data->getSData($l['id'],'home_fast_delivery') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Trending Filter</label>
                <input type="text" name="home_trending[]" class="form-control" value="{{ $data->getSData($l['id'],'home_trending') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">New Arrival Filter</label>
                <input type="text" name="home_new_arrival[]" class="form-control" value="{{ $data->getSData($l['id'],'home_new_arrival') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">By Rating Filter</label>
                <input type="text" name="home_by_rating[]" class="form-control" value="{{ $data->getSData($l['id'],'home_by_rating') }}">
            </div>
            </div>

            <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Coupon Text</label>
                <input type="text" name="home_coupon[]" class="form-control" value="{{ $data->getSData($l['id'],'home_coupon') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Per Person Cost</label>
                <input type="text" name="home_per_person[]" class="form-control" value="{{ $data->getSData($l['id'],'home_per_person') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Tab Home</label>
                <input type="text" name="home_footer_name[]" class="form-control" value="{{ $data->getSData($l['id'],'home_footer_name') }}">
            </div>
            </div>

            <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Tab Nearest</label>
                <input type="text" name="home_nearest[]" class="form-control" value="{{ $data->getSData($l['id'],'home_nearest') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Tab Cart</label>
                <input type="text" name="home_cart[]" class="form-control" value="{{ $data->getSData($l['id'],'home_cart') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Tab Profile</label>
                <input type="text" name="home_profile[]" class="form-control" value="{{ $data->getSData($l['id'],'home_profile') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Tab categroys</label>
                <input type="text" name="home_cats[]" class="form-control" value="{{ $data->getSData($l['id'],'home_cats') }}">
            </div>
        </div>

    </div>
</div>
<!--End-->

<!--start-->
<h4>Side Menu</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Title</label>
                <input type="text" name="menu_title[]" class="form-control" value="{{ $data->getSData($l['id'],'menu_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Pages Title</label>
                <input type="text" name="menu_page_title[]" class="form-control" value="{{ $data->getSData($l['id'],'menu_page_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">App Version</label>
                <input type="text" name="menu_footer[]" class="form-control" value="{{ $data->getSData($l['id'],'menu_footer') }}">
            </div>
        </div>
    </div>
</div>
<!--End-->

<!--start-->
<h4>Item Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">View Info</label>
                <input type="text" name="item_view_info[]" class="form-control" value="{{ $data->getSData($l['id'],'item_view_info') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Veg Only</label>
                <input type="text" name="item_veg_only[]" class="form-control" value="{{ $data->getSData($l['id'],'item_veg_only') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Add To Cart Button</label>
                <input type="text" name="item_add_button[]" class="form-control" value="{{ $data->getSData($l['id'],'item_add_button') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Addon Page Title</label>
                <input type="text" name="item_addon_title[]" class="form-control" value="{{ $data->getSData($l['id'],'item_addon_title') }}">
            </div>
            </div>

            <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Select Size Heading</label>
                <input type="text" name="item_size_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'item_size_heading') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Small</label>
                <input type="text" name="item_small[]" class="form-control" value="{{ $data->getSData($l['id'],'item_small') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Medium</label>
                <input type="text" name="item_m[]" class="form-control" value="{{ $data->getSData($l['id'],'item_m') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Large</label>
                <input type="text" name="item_large[]" class="form-control" value="{{ $data->getSData($l['id'],'item_large') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Addon Add</label>
                <input type="text" name="addon_add_title[]" class="form-control" value="{{ $data->getSData($l['id'],'addon_add_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Addon Heading</label>
                <input type="text" name="item_addon_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'item_addon_heading') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Addon Button</label>
                <input type="text" name="item_addon_button[]" class="form-control" value="{{ $data->getSData($l['id'],'item_addon_button') }}">
            </div>
        </div>
    </div>
</div>
<!--End-->

<!--start-->
<h4>Info Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Title</label>
                <input type="text" name="info_title[]" class="form-control" value="{{ $data->getSData($l['id'],'info_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Rating & Reviews Title</label>
                <input type="text" name="info_rating_title[]" class="form-control" value="{{ $data->getSData($l['id'],'info_rating_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Opening Time</label>
                <input type="text" name="info_open[]" class="form-control" value="{{ $data->getSData($l['id'],'info_open') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Closing Time</label>
                <input type="text" name="info_close[]" class="form-control" value="{{ $data->getSData($l['id'],'info_close') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Per Person Cost</label>
                <input type="text" name="info_person[]" class="form-control" value="{{ $data->getSData($l['id'],'info_person') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Delivery Time</label>
                <input type="text" name="info_d_time[]" class="form-control" value="{{ $data->getSData($l['id'],'info_d_time') }}">
            </div>
        </div>

    </div>
</div>
<!--End-->

<!--start-->
<h4>Cart Page</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Heading</label>
                <input type="text" name="cart_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_heading') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Total Amount</label>
                <input type="text" name="cart_total[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_total') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Delivery Charges</label>
                <input type="text" name="cart_delivery[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_delivery') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Coupon Text</label>
                <input type="text" name="cart_coupon[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_coupon') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Payable Amount</label>
                <input type="text" name="cart_payable[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_payable') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Place Order Button</label>
                <input type="text" name="cart_button[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_button') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Cart Empty</label>
                <input type="text" name="cart_empty[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_empty') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Start Ordering</label>
                <input type="text" name="cart_start_order[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_start_order') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Cart Price</label>
                <input type="text" name="cart_price[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_price') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Cart Quantity</label>
                <input type="text" name="cart_qty[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_qty') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Discount</label>
                <input type="text" name="cart_discount[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_discount') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Apply Coupon</label>
                <input type="text" name="cart_apply[]" class="form-control" value="{{ $data->getSData($l['id'],'cart_apply') }}">
            </div>
        </div>
    </div>
</div>
<!--End-->

<!--start-->
<h4>Coupon Page</h4>
<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="coupon_title[]" class="form-control" value="{{ $data->getSData($l['id'],'coupon_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="coupon_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'coupon_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Button</label>
<input type="text" name="coupon_button[]" class="form-control" value="{{ $data->getSData($l['id'],'coupon_button') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Login Page</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="login_title[]" class="form-control" value="{{ $data->getSData($l['id'],'login_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="login_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'login_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Login Button</label>
<input type="text" name="login_button[]" class="form-control" value="{{ $data->getSData($l['id'],'login_button') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Forgot Password</label>
<input type="text" name="login_forgot_password[]" class="form-control" value="{{ $data->getSData($l['id'],'login_forgot_password') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Reset Password</label>
<input type="text" name="login_reset_password[]" class="form-control" value="{{ $data->getSData($l['id'],'login_reset_password') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Signup Button</label>
<input type="text" name="login_signup[]" class="form-control" value="{{ $data->getSData($l['id'],'login_signup') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Forgot Password</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="forgot_title[]" class="form-control" value="{{ $data->getSData($l['id'],'forgot_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="forgot_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'forgot_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Text</label>
<input type="text" name="forgot_text[]" class="form-control" value="{{ $data->getSData($l['id'],'forgot_text') }}">
</div>
</div>


</div>
</div>
<!--End-->

<!--start-->
<h4>Signup</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="signup_title[]" class="form-control" value="{{ $data->getSData($l['id'],'signup_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="signup_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'signup_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Button</label>
<input type="text" name="signup_button[]" class="form-control" value="{{ $data->getSData($l['id'],'signup_button') }}">
</div>
</div>


</div>
</div>
<!--End-->

<!--start-->
<h4>Place Order</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="place_title[]" class="form-control" value="{{ $data->getSData($l['id'],'place_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Delivery Address Heading</label>
<input type="text" name="place_delivery_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'place_delivery_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Add New Address</label>
<input type="text" name="place_add_address[]" class="form-control" value="{{ $data->getSData($l['id'],'place_add_address') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Extra notes</label>
<input type="text" name="place_address_text[]" class="form-control" value="{{ $data->getSData($l['id'],'place_address_text') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Payment Heading</label>
<input type="text" name="place_payment_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'place_payment_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Place Order Button</label>
<input type="text" name="place_order_button[]" class="form-control" value="{{ $data->getSData($l['id'],'place_order_button') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Add New Address</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="add_title[]" class="form-control" value="{{ $data->getSData($l['id'],'add_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Address</label>
<input type="text" name="add_address[]" class="form-control" value="{{ $data->getSData($l['id'],'add_address') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Landmark</label>
<input type="text" name="add_landmark[]" class="form-control" value="{{ $data->getSData($l['id'],'add_landmark') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Button</label>
<input type="text" name="add_button[]" class="form-control" value="{{ $data->getSData($l['id'],'add_button') }}">
</div>
</div>

</div>
</div>
<!--End-->


<!--start-->
<h4>Order Confirm Page</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="confirm_title[]" class="form-control" value="{{ $data->getSData($l['id'],'confirm_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="confirm_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'confirm_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">View Order Details</label>
<input type="text" name="confirm_view_order[]" class="form-control" value="{{ $data->getSData($l['id'],'confirm_view_order') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Order ID</label>
<input type="text" name="confirm_order_id[]" class="form-control" value="{{ $data->getSData($l['id'],'confirm_order_id') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Total</label>
<input type="text" name="confirm_total[]" class="form-control" value="{{ $data->getSData($l['id'],'confirm_total') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>My Account</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="profile_title[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="profile_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Welcome Text</label>
<input type="text" name="profile_welcome[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_welcome') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Order History</label>
<input type="text" name="profile_order_history[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_order_history') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Settings</label>
<input type="text" name="profile_setting[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_setting') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Logout</label>
<input type="text" name="profile_logout[]" class="form-control" value="{{ $data->getSData($l['id'],'profile_logout') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Order History</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="history_title[]" class="form-control" value="{{ $data->getSData($l['id'],'history_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="history_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'history_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Date</label>
<input type="text" name="history_date[]" class="form-control" value="{{ $data->getSData($l['id'],'history_date') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Status</label>
<input type="text" name="history_status[]" class="form-control" value="{{ $data->getSData($l['id'],'history_status') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Item</label>
<input type="text" name="history_item[]" class="form-control" value="{{ $data->getSData($l['id'],'history_item') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Quantity</label>
<input type="text" name="history_qty[]" class="form-control" value="{{ $data->getSData($l['id'],'history_qty') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Price</label>
<input type="text" name="history_price[]" class="form-control" value="{{ $data->getSData($l['id'],'history_price') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Rating Title</label>
<input type="text" name="history_rating[]" class="form-control" value="{{ $data->getSData($l['id'],'history_rating') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Cancel Order</label>
<input type="text" name="history_cancel[]" class="form-control" value="{{ $data->getSData($l['id'],'history_cancel') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Rating & Info Pages</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Title</label>
<input type="text" name="rating_title[]" class="form-control" value="{{ $data->getSData($l['id'],'rating_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Heading</label>
<input type="text" name="rating_heading[]" class="form-control" value="{{ $data->getSData($l['id'],'rating_heading') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Rating Msg</label>
<input type="text" name="rating_msg[]" class="form-control" value="{{ $data->getSData($l['id'],'rating_msg') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Button</label>
<input type="text" name="rating_button[]" class="form-control" value="{{ $data->getSData($l['id'],'rating_button') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">About Us Title</label>
<input type="text" name="about_title[]" class="form-control" value="{{ $data->getSData($l['id'],'about_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">How Works Title</label>
<input type="text" name="how_title[]" class="form-control" value="{{ $data->getSData($l['id'],'how_title') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Faq's Title</label>
<input type="text" name="faq_title[]" class="form-control" value="{{ $data->getSData($l['id'],'faq_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Contact Title</label>
<input type="text" name="contact_title[]" class="form-control" value="{{ $data->getSData($l['id'],'contact_title') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Language</label>
<input type="text" name="language[]" class="form-control" value="{{ $data->getSData($l['id'],'language') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Home</label>
<input type="text" name="home[]" class="form-control" value="{{ $data->getSData($l['id'],'home') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">City</label>
<input type="text" name="city[]" class="form-control" value="{{ $data->getSData($l['id'],'city') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Account</label>
<input type="text" name="account[]" class="form-control" value="{{ $data->getSData($l['id'],'account') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Order</label>
<input type="text" name="order[]" class="form-control" value="{{ $data->getSData($l['id'],'order') }}">
</div>

</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Delivery App</h4>

<div class="card py-3 m-b-30">
<div class="card-body">

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">No Order Found</label>
<input type="text" name="d_no_order[]" class="form-control" value="{{ $data->getSData($l['id'],'d_no_order') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">New Orders</label>
<input type="text" name="d_new_order[]" class="form-control" value="{{ $data->getSData($l['id'],'d_new_order') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">View Detail</label>
<input type="text" name="d_view_detail[]" class="form-control" value="{{ $data->getSData($l['id'],'d_view_detail') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">User</label>
<input type="text" name="d_user[]" class="form-control" value="{{ $data->getSData($l['id'],'d_user') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Phone</label>
<input type="text" name="d_phone[]" class="form-control" value="{{ $data->getSData($l['id'],'d_phone') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Address</label>
<input type="text" name="d_address[]" class="form-control" value="{{ $data->getSData($l['id'],'d_address') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Start Ride</label>
<input type="text" name="d_start_ride[]" class="form-control" value="{{ $data->getSData($l['id'],'d_start_ride') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Complete Ride</label>
<input type="text" name="d_complete_ride[]" class="form-control" value="{{ $data->getSData($l['id'],'d_complete_ride') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Total Amount</label>
<input type="text" name="d_total_amount[]" class="form-control" value="{{ $data->getSData($l['id'],'d_total_amount') }}">
</div>
</div>

<div class="row mb-3">
<div class="col-md-4">
<label for="inputEmail6">Payment Method</label>
<input type="text" name="d_pay[]" class="form-control" value="{{ $data->getSData($l['id'],'d_pay') }}">
</div>

<div class="col-md-4">
<label for="inputEmail6">Store Close</label>
<input type="text" name="close[]" class="form-control" value="{{ $data->getSData($l['id'],'close') }}">
</div>
</div>

</div>
</div>
<!--End-->

<!--start-->
<h4>Store App</h4>
<div class="card py-3 m-b-30">
    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Total Order</label>
                <input type="text" name="s_total_order[]" class="form-control" value="{{ $data->getSData($l['id'],'s_total_order') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Completed Orders</label>
                <input type="text" name="s_complete_order[]" class="form-control" value="{{ $data->getSData($l['id'],'s_complete_order') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">New Orders</label>
                <input type="text" name="s_new_order[]" class="form-control" value="{{ $data->getSData($l['id'],'s_new_order') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">New Order Status</label>
                <input type="text" name="s_new_status[]" class="form-control" value="{{ $data->getSData($l['id'],'s_new_status') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Confirmed Status</label>
                <input type="text" name="s_confirm_order[]" class="form-control" value="{{ $data->getSData($l['id'],'s_confirm_order') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Delivery Assign Status</label>
                <input type="text" name="s_assign_status[]" class="form-control" value="{{ $data->getSData($l['id'],'s_assign_status') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Out For Delivery Status</label>
                <input type="text" name="s_out_delivery_status[]" class="form-control" value="{{ $data->getSData($l['id'],'s_out_delivery_status') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Complete Status</label>
                <input type="text" name="s_complete_status[]" class="form-control" value="{{ $data->getSData($l['id'],'s_complete_status') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Order Detail Title</label>
                <input type="text" name="s_detail_title[]" class="form-control" value="{{ $data->getSData($l['id'],'s_detail_title') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Menu Item Title</label>
                <input type="text" name="s_menu_title[]" class="form-control" value="{{ $data->getSData($l['id'],'s_menu_title') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Orders Overview</label>
                <input type="text" name="s_order_overview[]" class="form-control" value="{{ $data->getSData($l['id'],'s_order_overview') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Complete Order</label>
                <input type="text" name="s_c_order[]" class="form-control" value="{{ $data->getSData($l['id'],'s_c_order') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inputEmail6">Order Cancel Button</label>
                <input type="text" name="s_cancel_button[]" class="form-control" value="{{ $data->getSData($l['id'],'s_cancel_button') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Order Confirm Button</label>
                <input type="text" name="s_confirm_button[]" class="form-control" value="{{ $data->getSData($l['id'],'s_confirm_button') }}">
            </div>

            <div class="col-md-4">
                <label for="inputEmail6">Assign Delivery</label>
                <input type="text" name="s_assign_button[]" class="form-control" value="{{ $data->getSData($l['id'],'s_assign_button') }}">
            </div>
        </div>

    </div>
</div>
 
@endforeach
 

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button><br><br>
