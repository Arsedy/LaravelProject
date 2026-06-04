@extends('layouts.home')

@section('title', 'Checkout - Electro')

@section('content')
<div id="breadcrumb" class="section">
    <div class="container">
        <ul class="breadcrumb-tree">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Checkout</li>
        </ul>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="checkout-form" class="clearfix" action="{{ route('place.order') }}" method="POST">
                @csrf
                <div class="col-md-6">
                    <div class="billing-details" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #E4E7ED; margin-bottom: 30px;">
                        <div class="section-title">
                            <h3 class="title">Billing Details</h3>
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="name" style="margin-bottom: 5px; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
                            <input class="input" type="text" name="name" placeholder="Full Name" value="{{ old('name', auth()->user()->name ?? '') }}" required style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="email" style="margin-bottom: 5px; font-weight: 500;">Email Address <span class="text-danger">*</span></label>
                            <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email', auth()->user()->email ?? '') }}" required style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="address" style="margin-bottom: 5px; font-weight: 500;">Address <span class="text-danger">*</span></label>
                            <input class="input" type="text" name="address" placeholder="Address" value="{{ old('address') }}" required style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="city" style="margin-bottom: 5px; font-weight: 500;">City</label>
                            <input class="input" type="text" name="city" placeholder="City" value="{{ old('city') }}" style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="country" style="margin-bottom: 5px; font-weight: 500;">Country</label>
                            <input class="input" type="text" name="country" placeholder="Country" value="{{ old('country') }}" style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="zip_code" style="margin-bottom: 5px; font-weight: 500;">ZIP Code</label>
                            <input class="input" type="text" name="zip_code" placeholder="ZIP Code" value="{{ old('zip_code') }}" style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label class="control-label" for="phone" style="margin-bottom: 5px; font-weight: 500;">Telephone</label>
                            <input class="input" type="tel" name="phone" placeholder="Telephone" value="{{ old('phone') }}" style="width: 100%; height: 40px; padding: 0 15px; border: 1px solid #E4E7ED; border-radius: 4px;">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="shiping-methods" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #E4E7ED; margin-bottom: 30px;">
                        <div class="section-title">
                            <h4 class="title">Shipping Methods</h4>
                        </div>
                        <div class="input-checkbox" style="margin-bottom: 10px;">
                            <input type="radio" name="shipping_method" id="shipping-1" value="Free Shipping" checked>
                            <label for="shipping-1" style="font-weight: 500; margin-left: 5px;">
                                <span></span> Free Shipping - $0.00
                            </label>
                        </div>
                        <div class="input-checkbox" style="margin-bottom: 10px;">
                            <input type="radio" name="shipping_method" id="shipping-2" value="Standard Shipping">
                            <label for="shipping-2" style="font-weight: 500; margin-left: 5px;">
                                <span></span> Standard Shipping - $4.00
                            </label>
                        </div>
                    </div>

                    <div class="payments-methods" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #E4E7ED; margin-bottom: 30px;">
                        <div class="section-title">
                            <h4 class="title">Payment Methods</h4>
                        </div>
                        <div class="input-checkbox" style="margin-bottom: 10px;">
                            <input type="radio" name="payment_method" id="payments-1" value="Direct Bank Transfer" checked>
                            <label for="payments-1" style="font-weight: 500; margin-left: 5px;">
                                <span></span> Direct Bank Transfer
                            </label>
                        </div>
                        <div class="input-checkbox" style="margin-bottom: 10px;">
                            <input type="radio" name="payment_method" id="payments-2" value="Cash on Delivery">
                            <label for="payments-2" style="font-weight: 500; margin-left: 5px;">
                                <span></span> Cash on Delivery
                            </label>
                        </div>
                        <div class="input-checkbox" style="margin-bottom: 10px;">
                            <input type="radio" name="payment_method" id="payments-3" value="Paypal">
                            <label for="payments-3" style="font-weight: 500; margin-left: 5px;">
                                <span></span> Paypal
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="order-summary clearfix" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #E4E7ED;">
                        <div class="section-title">
                            <h3 class="title">Order Review</h3>
                        </div>
                        <table class="shopping-cart-table table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Product</th>
                                    <th style="padding: 15px; border-bottom: 2px solid #E4E7ED;"></th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Price</th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED; width: 100px;">Quantity</th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr style="border-bottom: 1px solid #FBFBFC; vertical-align: middle;">
                                        <td class="thumb" style="padding: 15px; width: 100px;">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->title }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #E4E7ED;">
                                            @else
                                                <img src="{{ asset('frontend-assets/img/product01.png') }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #E4E7ED;">
                                            @endif
                                        </td>
                                        <td class="details" style="padding: 15px;">
                                            <a href="#" style="font-weight: 700; color: #2B2D42;">
                                                {{ $item->product->title ?? 'Product Deleted' }}
                                            </a>
                                        </td>
                                        <td class="price text-center" style="padding: 15px;">
                                            <strong style="color: #2B2D42;">${{ number_format($item->price, 2) }}</strong>
                                        </td>
                                        <td class="qty text-center" style="padding: 15px;">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="total text-center" style="padding: 15px;">
                                            <strong class="primary-color" style="color: #D10024;">${{ number_format($item->price * $item->quantity, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="empty" colspan="2" style="border: none;"></th>
                                    <th colspan="2" style="padding: 15px; border-top: 2px solid #E4E7ED; text-align: left;">SUBTOTAL</th>
                                    <th class="sub-total" style="padding: 15px; border-top: 2px solid #E4E7ED; text-align: right; color: #2B2D42; font-size: 16px;">
                                        ${{ number_format($subtotal, 2) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="empty" colspan="2" style="border: none;"></th>
                                    <th colspan="2" style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: left;">SHIPPING</th>
                                    <td id="shipping-display" style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: right; color: #8D99AE;">
                                        Free Shipping ($0.00)
                                    </td>
                                </tr>
                                <tr>
                                    <th class="empty" colspan="2" style="border: none;"></th>
                                    <th colspan="2" style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: left;">TOTAL</th>
                                    <th class="total" id="grand-total-display" style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: right; color: #D10024; font-size: 24px; font-weight: 700;">
                                        ${{ number_format($total, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right" style="margin-top: 20px;">
                            <button type="submit" class="primary-btn order-submit" style="border-radius: 40px; padding: 12px 30px; font-weight: 700; text-transform: uppercase; border: none;">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
