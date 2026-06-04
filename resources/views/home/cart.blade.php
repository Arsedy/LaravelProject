@extends('layouts.home')

@section('title', 'My Cart - Electro')

@section('content')
<div id="breadcrumb" class="section">
    <div class="container">
        <ul class="breadcrumb-tree">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">My Cart</li>
        </ul>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="order-summary clearfix" style="background: #fff; padding: 30px; border-radius: 8px; border: 1px solid #E4E7ED;">
                    <div class="section-title">
                        <h3 class="title">My Cart</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($cartItems->count() > 0)
                        <table class="shopping-cart-table table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Product</th>
                                    <th style="padding: 15px; border-bottom: 2px solid #E4E7ED;"></th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Price</th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED; width: 180px;">Quantity</th>
                                    <th class="text-center" style="padding: 15px; border-bottom: 2px solid #E4E7ED;">Total</th>
                                    <th class="text-right" style="padding: 15px; border-bottom: 2px solid #E4E7ED; width: 80px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $subtotal = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php
                                        $lineTotal = $item->price * $item->quantity;
                                        $subtotal += $lineTotal;
                                    @endphp
                                    <tr style="border-bottom: 1px solid #FBFBFC; vertical-align: middle;">
                                        <td class="thumb" style="padding: 15px; width: 100px;">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->title }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #E4E7ED;">
                                            @else
                                                <img src="{{ asset('frontend-assets/img/product01.png') }}" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #E4E7ED;">
                                            @endif
                                        </td>
                                        <td class="details" style="padding: 15px; vertical-align: middle;">
                                            <a href="#" style="font-weight: 700; color: #2B2D42; font-size: 15px;">
                                                {{ $item->product->title ?? 'Product Deleted' }}
                                            </a>
                                        </td>
                                        <td class="price text-center" style="padding: 15px; vertical-align: middle;">
                                            <strong style="color: #2B2D42;">${{ number_format($item->price, 2) }}</strong>
                                        </td>
                                        <td class="qty text-center" style="padding: 15px; vertical-align: middle;">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                                @csrf
                                                <input class="input" type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 70px; height: 40px; text-align: center; border: 1px solid #E4E7ED; border-radius: 4px;">
                                                <button type="submit" class="primary-btn" style="height: 40px; padding: 0 15px; border-radius: 4px; border: none; font-size: 12px; text-transform: uppercase;">Update</button>
                                            </form>
                                        </td>
                                        <td class="total text-center" style="padding: 15px; vertical-align: middle;">
                                            <strong class="primary-color" style="color: #D10024; font-size: 16px;">${{ number_format($lineTotal, 2) }}</strong>
                                        </td>
                                        <td class="text-right" style="padding: 15px; vertical-align: middle;">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Remove this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="primary-btn" style="background: #2B2D42; border: none; border-radius: 4px; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" title="Remove Item">
                                                    <i class="fa fa-close" style="color: #fff; font-size: 16px;"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="empty" colspan="3" style="border: none;"></th>
                                    <th style="padding: 15px; border-top: 2px solid #E4E7ED; text-align: left;">SUBTOTAL</th>
                                    <th colspan="2" class="sub-total" style="padding: 15px; border-top: 2px solid #E4E7ED; text-align: right; font-size: 18px; color: #2B2D42;">
                                        ${{ number_format($subtotal, 2) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="empty" colspan="3" style="border: none;"></th>
                                    <th style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: left;">TOTAL</th>
                                    <th colspan="2" class="total" style="padding: 15px; border-top: 1px solid #E4E7ED; text-align: right; font-size: 24px; color: #D10024; font-weight: 700;">
                                        ${{ number_format($subtotal, 2) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="pull-right" style="margin-top: 20px;">
                            <a href="{{ route('checkout') }}" class="primary-btn" style="border-radius: 40px; padding: 12px 30px; font-weight: 700; text-transform: uppercase;">
                                Checkout <i class="fa fa-arrow-circle-right" style="margin-left: 5px;"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center" style="padding: 50px 0;">
                            <i class="fa fa-shopping-cart" style="font-size: 80px; color: #E4E7ED; margin-bottom: 20px; display: block;"></i>
                            <h4 style="color: #2B2D42; margin-bottom: 10px;">Your cart is empty.</h4>
                            <p style="color: #8D99AE; margin-bottom: 25px;">Add some products to your cart before checking out.</p>
                            <a href="{{ route('store') }}" class="primary-btn" style="border-radius: 40px; padding: 12px 30px; text-transform: uppercase;">Go to Store</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
