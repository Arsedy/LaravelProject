// Electro - Shopping Cart & Checkout Javascript Functionality
// Client-side implementation using localStorage

$(document).ready(function() {
    // -------------------------------------------------------------
    // 1. CORE CART STATE LOGIC (localStorage)
    // -------------------------------------------------------------
    
    const CART_KEY = 'electro_shopping_cart';
    const WISHLIST_KEY = 'electro_wishlist_cart';

    // Get wishlist items from localStorage
    function getWishlist() {
        try {
            const wishlistJSON = localStorage.getItem(WISHLIST_KEY);
            return wishlistJSON ? JSON.parse(wishlistJSON) : [];
        } catch (e) {
            console.error('Error reading wishlist from localStorage:', e);
            return [];
        }
    }

    // Save wishlist items to localStorage
    function saveWishlist(wishlist) {
        try {
            localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));
            // Trigger custom event to sync header UI if wishlist changes on the same page
            $(document).trigger('wishlistUpdated', [wishlist]);
        } catch (e) {
            console.error('Error saving wishlist to localStorage:', e);
        }
    }

    // Add item to wishlist
    function addToWishlist(product) {
        let wishlist = getWishlist();
        const exists = wishlist.some(item => item.name === product.name);
        
        if (exists) {
            showToast(product.name, product.img, 'Already in your wishlist!', 'info');
            return;
        }

        wishlist.push(product);
        saveWishlist(wishlist);
        showToast(product.name, product.img, 'Added to your wishlist!');
    }

    // Remove item from wishlist
    function removeFromWishlist(name) {
        let wishlist = getWishlist();
        wishlist = wishlist.filter(item => item.name !== name);
        saveWishlist(wishlist);
        showToast('Removed from Wishlist', './img/logo.png', `${name} has been removed.`, 'info');
    }

    // Get cart items from localStorage
    function getCart() {
        try {
            const cartJSON = localStorage.getItem(CART_KEY);
            return cartJSON ? JSON.parse(cartJSON) : [];
        } catch (e) {
            console.error('Error reading cart from localStorage:', e);
            return [];
        }
    }

    // Save cart items to localStorage
    function saveCart(cart) {
        try {
            localStorage.setItem(CART_KEY, JSON.stringify(cart));
            // Trigger custom event to sync header UI if cart changes on the same page
            $(document).trigger('cartUpdated', [cart]);
        } catch (e) {
            console.error('Error saving cart to localStorage:', e);
        }
    }

    // Add item to cart
    function addToCart(product) {
        let cart = getCart();
        
        // Check if item already exists with same options (size and color)
        const existingItemIndex = cart.findIndex(item => 
            item.name === product.name && 
            item.size === product.size && 
            item.color === product.color
        );

        if (existingItemIndex > -1) {
            // Update quantity
            cart[existingItemIndex].quantity += product.quantity;
        } else {
            // Add new item
            cart.push(product);
        }
        
        saveCart(cart);
        showToast(product.name, product.img, `Added ${product.quantity} to shopping cart`);
    }

    // Remove item from cart
    function removeFromCart(name, size, color) {
        let cart = getCart();
        cart = cart.filter(item => !(item.name === name && item.size === size && item.color === color));
        saveCart(cart);
        showToast('Item Removed', './img/logo.png', `${name} has been removed.`, 'info');
    }

    // Clear cart
    function clearCart() {
        saveCart([]);
    }

    // -------------------------------------------------------------
    // 2. HEADER CART UI RENDERING
    // -------------------------------------------------------------
    
    function renderHeaderCart() {
        const cart = getCart();
        const $cartDropdown = $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-dropdown');
        const $cartList = $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-list');
        const $cartSummary = $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-summary');
        const $cartQtyBadge = $('.header-ctn .dropdown:has(.fa-shopping-cart) .qty');
        const $cartTitleSpan = $('.header-ctn .dropdown:has(.fa-shopping-cart) .dropdown-toggle span');
        
        // Calculate totals
        let totalItems = 0;
        let subtotal = 0;

        // Clear existing list
        $cartList.empty();

        if (cart.length === 0) {
            $cartQtyBadge.text(0);
            $cartTitleSpan.text('Your Cart');
            
            $cartList.html(`
                <div class="empty-cart-message">
                    <i class="fa fa-shopping-cart" style="font-size: 40px; color: #E4E7ED; margin-bottom: 10px; display: block; text-align: center;"></i>
                    <p style="text-align: center; color: #8D99AE; margin: 0;">Your cart is empty</p>
                </div>
            `);
            
            $cartSummary.html(`
                <small>0 Item(s) selected</small>
                <h5>SUBTOTAL: $0.00</h5>
            `);
            
            // Disable Checkout buttons
            $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-btns a').attr('href', '#').css({
                'pointer-events': 'none',
                'opacity': '0.5'
            });
        } else {
            // Render cart items
            cart.forEach(item => {
                totalItems += item.quantity;
                subtotal += item.price * item.quantity;

                // Create item element
                const $itemWidget = $(`
                    <div class="product-widget">
                        <div class="product-img">
                            <img src="${item.img}" alt="${item.name}">
                        </div>
                        <div class="product-body">
                            <h3 class="product-name"><a href="#">${item.name}</a></h3>
                            <h4 class="product-price">
                                <span class="qty">${item.quantity}x</span>$${item.price.toFixed(2)}
                                ${item.size ? `<br><small style="color:#8D99AE">Size: ${item.size}</small>` : ''}
                                ${item.color ? `<small style="color:#8D99AE"> | Color: ${item.color}</small>` : ''}
                            </h4>
                        </div>
                        <button class="delete-cart-item delete" data-name="${item.name}" data-size="${item.size || ''}" data-color="${item.color || ''}"><i class="fa fa-close"></i></button>
                    </div>
                `);
                
                $cartList.append($itemWidget);
            });

            // Update badge & summary
            $cartQtyBadge.text(totalItems);
            $cartTitleSpan.text('Your Cart');
            
            $cartSummary.html(`
                <small>${totalItems} Item(s) selected</small>
                <h5>SUBTOTAL: $${subtotal.toFixed(2)}</h5>
            `);
            
            // Enable Checkout & View Cart links
            $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-btns a').css({
                'pointer-events': 'auto',
                'opacity': '1'
            });
            // Point the buttons to correct routes
            const checkoutUrl = window.Laravel ? window.Laravel.routes.checkout : 'checkout';
            const storeUrl = window.Laravel ? window.Laravel.routes.store : 'store';
            $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-btns a:first-child').attr('href', storeUrl); // View Cart goes to store
            $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-btns a:last-child').attr('href', checkoutUrl);  // Checkout
        }
    }

    // Render Wishlist Dropdown
    function renderHeaderWishlist() {
        const wishlist = getWishlist();
        const $wishlistList = $('#wishlist-list');
        const $wishlistQty = $('#wishlist-qty');

        if ($wishlistList.length === 0) return;

        $wishlistList.empty();

        if (wishlist.length === 0) {
            $wishlistQty.text(0);
            $wishlistList.html(`
                <div class="empty-cart-message">
                    <i class="fa fa-heart-o" style="font-size: 40px; color: #E4E7ED; margin-bottom: 10px; display: block; text-align: center;"></i>
                    <p style="text-align: center; color: #8D99AE; margin: 0;">Your wishlist is empty</p>
                </div>
            `);
            $('#clear-wishlist').css({
                'pointer-events': 'none',
                'opacity': '0.5'
            });
        } else {
            $wishlistQty.text(wishlist.length);
            $('#clear-wishlist').css({
                'pointer-events': 'auto',
                'opacity': '1'
            });

            wishlist.forEach(item => {
                const $itemWidget = $(`
                    <div class="product-widget" style="padding: 10px 0; border-bottom: 1px solid #FBFBFC; position: relative;">
                        <div class="product-img" style="position: absolute; left: 0px; top: 10px;">
                            <img src="${item.img}" alt="${item.name}">
                        </div>
                        <div class="product-body" style="padding-left: 75px; padding-right: 35px; min-height: 60px;">
                            <h3 class="product-name" style="margin-bottom: 5px; font-size: 12px;"><a href="#">${item.name}</a></h3>
                            <h4 class="product-price" style="font-size: 12px; color: #2B2D42; font-weight: 700;">$${item.price.toFixed(2)}</h4>
                            <button class="add-wishlist-to-cart primary-btn" data-name="${item.name}" style="padding: 3px 8px; font-size: 10px; border-radius: 4px; margin-top: 5px; height: auto;">
                                <i class="fa fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                        <button class="delete-wishlist-item delete" data-name="${item.name}" style="position: absolute; right: 0; top: 10px; background: none; border: none; color: #B9BABC;"><i class="fa fa-close"></i></button>
                    </div>
                `);
                $wishlistList.append($itemWidget);
            });
        }
    }

    // -------------------------------------------------------------
    // 3. TOAST NOTIFICATIONS SYSTEM
    // -------------------------------------------------------------
    
    function showToast(title, imgUrl, message, type = 'success') {
        let $container = $('#toast-container');
        if ($container.length === 0) {
            $('body').append('<div id="toast-container"></div>');
            $container = $('#toast-container');
        }

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div class="toast-notification ${type}" id="${toastId}">
                <img src="${imgUrl}" alt="${title}" onerror="this.src='./img/logo.png'">
                <div class="toast-content">
                    <div class="toast-title">${title}</div>
                    <div class="toast-desc">${message}</div>
                </div>
                <button class="toast-close">&times;</button>
                <div class="toast-progress"></div>
            </div>
        `;

        $container.append(toastHtml);
        
        const $toast = $('#' + toastId);
        
        // Animate slide-in
        setTimeout(() => $toast.addClass('show'), 50);

        // Click close
        $toast.find('.toast-close').on('click', function() {
            closeToast($toast);
        });

        // Auto remove
        setTimeout(() => {
            closeToast($toast);
        }, 3000);
    }

    function closeToast($toast) {
        if ($toast.hasClass('show')) {
            $toast.removeClass('show');
            setTimeout(() => $toast.remove(), 300);
        }
    }

    // -------------------------------------------------------------
    // 4. EVENT BINDING FOR ADD TO CART
    // -------------------------------------------------------------
    
    // Add to Cart from Index and Store page (Product Grids)
    $(document).on('click', '.add-to-cart .add-to-cart-btn', function(e) {
        e.preventDefault();
        
        const $product = $(this).closest('.product');
        if ($product.length === 0) return;

        // Extract Product details
        const name = $product.find('.product-name a').text().trim() || $product.find('.product-name').text().trim();
        
        // Remove old price <del> tags before reading text
        const $priceClone = $product.find('.product-price').clone();
        $priceClone.find('del').remove();
        const priceStr = $priceClone.text().replace(/[^0-9.]/g, '');
        const price = parseFloat(priceStr) || 0;
        
        const img = $product.find('.product-img img').attr('src') || './img/logo.png';
        const category = $product.find('.product-category').text().trim();

        const product = {
            name: name,
            price: price,
            img: img,
            category: category,
            quantity: 1,
            size: '',
            color: ''
        };

        addToCart(product);
    });

    // Add to Cart from Single Product Detail Page
    $(document).on('click', '.product-details .add-to-cart-btn', function(e) {
        e.preventDefault();
        
        const $details = $(this).closest('.product-details');
        if ($details.length === 0) return;

        const name = $details.find('.product-name').text().trim();
        
        const $priceClone = $details.find('.product-price').clone();
        $priceClone.find('del').remove();
        const priceStr = $priceClone.text().replace(/[^0-9.]/g, '');
        const price = parseFloat(priceStr) || 0;

        // Find selected options
        let size = '';
        let color = '';
        
        $details.find('.product-options label').each(function() {
            const labelText = $(this).text().trim();
            const selectedVal = $(this).find('select').val() || $(this).find('select option:selected').text().trim();
            
            if (labelText.toLowerCase().includes('size')) {
                size = selectedVal;
            } else if (labelText.toLowerCase().includes('color')) {
                color = selectedVal;
            }
        });

        // Get quantity
        const qtyVal = parseInt($details.find('.input-number input[type="number"]').val());
        const quantity = isNaN(qtyVal) || qtyVal <= 0 ? 1 : qtyVal;

        // Image extraction
        let img = './img/logo.png';
        const $slickActiveImg = $('#product-main-img .product-preview.slick-active img');
        if ($slickActiveImg.length > 0) {
            img = $slickActiveImg.attr('src');
        } else {
            const $anyProductImg = $('#product-main-img img').first();
            if ($anyProductImg.length > 0) {
                img = $anyProductImg.attr('src');
            }
        }

        const product = {
            name: name,
            price: price,
            img: img,
            quantity: quantity,
            size: size,
            color: color
        };

        addToCart(product);
    });

    // Delete item from cart (dropdown click listener & checkout page)
    $(document).on('click', '.delete-cart-item', function(e) {
        e.preventDefault();
        const name = $(this).attr('data-name');
        const size = $(this).attr('data-size');
        const color = $(this).attr('data-color');
        removeFromCart(name, size, color);
    });

    // Specifically handle clicks inside cart-dropdown since main.js calls e.stopPropagation()
    $('.header-ctn .dropdown:has(.fa-shopping-cart) .cart-dropdown').on('click', '.delete-cart-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const name = $(this).attr('data-name');
        const size = $(this).attr('data-size');
        const color = $(this).attr('data-color');
        removeFromCart(name, size, color);
    });

    // Handle clicks inside order products section on checkout page
    $('.order-products').on('click', '.delete-cart-item', function(e) {
        e.preventDefault();
        const name = $(this).attr('data-name');
        const size = $(this).attr('data-size');
        const color = $(this).attr('data-color');
        removeFromCart(name, size, color);
    });

    // -------------------------------------------------------------
    // 4B. EVENT BINDING FOR FAVORITES / WISHLIST
    // -------------------------------------------------------------

    // Add to Wishlist from Product Grid Cards
    $(document).on('click', '.product-btns button.add-to-wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $product = $(this).closest('.product');
        if ($product.length === 0) return;

        const name = $product.find('.product-name a').text().trim() || $product.find('.product-name').text().trim();
        
        const $priceClone = $product.find('.product-price').clone();
        $priceClone.find('del').remove();
        const priceStr = $priceClone.text().replace(/[^0-9.]/g, '');
        const price = parseFloat(priceStr) || 0;

        const img = $product.find('.product-img img').attr('src') || './img/logo.png';
        const category = $product.find('.product-category').text().trim();

        const product = {
            name: name,
            price: price,
            img: img,
            category: category,
            quantity: 1,
            size: '',
            color: ''
        };

        addToWishlist(product);
    });

    // Add to Wishlist from Single Product Detail Page
    $(document).on('click', '.product-details .product-btns a:has(.fa-heart-o), .product-details .product-btns a:contains("add to wishlist")', function(e) {
        e.preventDefault();

        const $details = $(this).closest('.product-details');
        if ($details.length === 0) return;

        const name = $details.find('.product-name').text().trim();
        
        const $priceClone = $details.find('.product-price').clone();
        $priceClone.find('del').remove();
        const priceStr = $priceClone.text().replace(/[^0-9.]/g, '');
        const price = parseFloat(priceStr) || 0;

        let img = './img/logo.png';
        const $anyProductImg = $('#product-main-img img').first();
        if ($anyProductImg.length > 0) {
            img = $anyProductImg.attr('src');
        }

        const product = {
            name: name,
            price: price,
            img: img,
            quantity: 1,
            size: '',
            color: ''
        };

        addToWishlist(product);
    });

    // Delete item from Wishlist Dropdown
    // Delete item from Wishlist
    $(document).on('click', '.delete-wishlist-item', function(e) {
        e.preventDefault();
        const name = $(this).attr('data-name');
        removeFromWishlist(name);
    });

    // Specifically handle delete inside wishlist dropdown container
    $('#wishlist-dropdown').on('click', '.delete-wishlist-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const name = $(this).attr('data-name');
        removeFromWishlist(name);
    });

    // Clear Wishlist button click inside container
    $('#wishlist-dropdown').on('click', '#clear-wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();
        saveWishlist([]);
        showToast('Wishlist Cleared', './img/logo.png', 'Your wishlist has been cleared.', 'info');
    });

    // Add to Cart from Wishlist Dropdown inside container
    $('#wishlist-dropdown').on('click', '.add-wishlist-to-cart', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const name = $(this).attr('data-name');
        const wishlist = getWishlist();
        const item = wishlist.find(p => p.name === name);

        if (item) {
            // Add item to cart
            addToCart({
                name: item.name,
                price: item.price,
                img: item.img,
                quantity: 1,
                size: '',
                color: ''
            });
            // Remove item from wishlist
            removeFromWishlist(name);
        }
    });

    // -------------------------------------------------------------
    // 5. CHECKOUT PAGE LOGIC & BINDING
    // -------------------------------------------------------------
    
    function renderCheckoutPage() {
        const $orderProducts = $('.order-products');
        const $orderTotal = $('.order-total');
        const cart = getCart();

        if ($orderProducts.length === 0) return; // Not on checkout page

        // Clear existing product list placeholders
        $orderProducts.empty();

        if (cart.length === 0) {
            const storeUrl = window.Laravel ? window.Laravel.routes.store : 'store';
            // Display empty cart notice on checkout page
            $orderProducts.html(`
                <div class="empty-cart-message">
                    <i class="fa fa-shopping-cart"></i>
                    <h4>Your Cart is Empty</h4>
                    <p>Add some products to your cart before checking out.</p>
                    <a href="${storeUrl}" class="primary-btn">Go to Store</a>
                </div>
            `);
            
            $orderTotal.text('$0.00');
            
            // Disable checkout submit
            $('.order-submit').addClass('disabled').css({
                'pointer-events': 'none',
                'opacity': '0.5'
            });
            return;
        }

        // Enable checkout submit
        $('.order-submit').removeClass('disabled').css({
            'pointer-events': 'auto',
            'opacity': '1'
        });

        let total = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            const itemHtml = `
                <div class="order-col" style="align-items: center; display: flex; justify-content: space-between; margin-bottom: 12px;">
                    <div style="flex: 1;">
                        <strong>${item.quantity}x</strong> ${item.name}
                        ${item.size ? `<br><small style="color:#8D99AE">Size: ${item.size}</small>` : ''}
                        ${item.color ? `<small style="color:#8D99AE"> | Color: ${item.color}</small>` : ''}
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div>$${itemTotal.toFixed(2)}</div>
                        <button class="delete-cart-item text-danger" data-name="${item.name}" data-size="${item.size || ''}" data-color="${item.color || ''}" style="background: none; border: none; cursor: pointer; padding: 0 5px; font-size: 14px;"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;
            
            $orderProducts.append(itemHtml);
        });

        // Update shipping (e.g., free shipping)
        // Let's keep it FREE as defined in HTML, or add shipping fee if total < 500
        const shippingFee = 0; 
        const grandTotal = total + shippingFee;

        $orderTotal.text(`$${grandTotal.toFixed(2)}`);
    }

    // Process Form Validation on checkout
    function validateCheckoutForm() {
        let isValid = true;
        
        // Get all required billing fields
        const $billingDetails = $('.billing-details');
        if ($billingDetails.length === 0) return false;

        const fieldsToValidate = [
            { name: 'first-name', label: 'First Name' },
            { name: 'last-name', label: 'Last Name' },
            { name: 'email', label: 'Email Address', isEmail: true },
            { name: 'address', label: 'Address' },
            { name: 'city', label: 'City' },
            { name: 'country', label: 'Country' },
            { name: 'zip-code', label: 'ZIP Code' },
            { name: 'tel', label: 'Telephone Number' }
        ];

        fieldsToValidate.forEach(field => {
            const $input = $billingDetails.find(`input[name="${field.name}"]`);
            const val = $input.val().trim();
            const $parent = $input.closest('.form-group');

            // Clear previous errors
            $parent.removeClass('has-error has-success');
            $parent.find('.error-msg').remove();

            if (val === '') {
                isValid = false;
                $parent.addClass('has-error');
                $input.after(`<span class="error-msg">${field.label} is required.</span>`);
            } else if (field.isEmail && !validateEmail(val)) {
                isValid = false;
                $parent.addClass('has-error');
                $input.after('<span class="error-msg">Please enter a valid email address.</span>');
            } else {
                $parent.addClass('has-success');
            }
        });

        // Validate terms and conditions
        const $termsCheckbox = $('#terms');
        const $termsParent = $termsCheckbox.closest('.input-checkbox');
        $termsParent.find('.error-msg').remove();

        if (!$termsCheckbox.is(':checked')) {
            isValid = false;
            $termsParent.append('<span class="error-msg" style="display: block; margin-top: 5px;">You must accept the terms & conditions.</span>');
        }

        return isValid;
    }

    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // Checkout form Place Order handler
    $(document).on('click', '.order-submit', function(e) {
        e.preventDefault();

        // Perform validation
        if (!validateCheckoutForm()) {
            showToast('Validation Error', './img/logo.png', 'Please check your billing details and accept the terms.', 'info');
            // Scroll to billing details
            $('html, body').animate({
                scrollTop: $('.billing-details').offset().top - 100
            }, 500);
            return;
        }

        // Gather order details
        const cart = getCart();
        let total = 0;
        cart.forEach(item => total += item.price * item.quantity);

        const $billing = $('.billing-details');
        const customerName = `${$billing.find('input[name="first-name"]').val()} ${$billing.find('input[name="last-name"]').val()}`;
        const address = $billing.find('input[name="address"]').val();
        const city = $billing.find('input[name="city"]').val();

        const orderData = {
            name: customerName,
            address: address,
            city: city,
            total: total.toFixed(2)
        };

        // Show animated checkout success modal
        showOrderSuccessModal(orderData);

        // Clear cart
        clearCart();
    });

    function showOrderSuccessModal(orderData) {
        const orderNum = 'EL' + Math.floor(100000 + Math.random() * 900000);
        
        const modalHtml = `
            <div class="modal-overlay" id="order-success-overlay">
                <div class="order-success-modal">
                    <div class="success-icon-container">
                        <i class="fa fa-check"></i>
                    </div>
                    <h3>Order Placed Successfully!</h3>
                    <p>Thank you for your order. Your purchase has been confirmed and we will process it shortly.</p>
                    <div class="order-details-summary">
                        <div>
                            <span>Order Number:</span>
                            <strong>${orderNum}</strong>
                        </div>
                        <div>
                            <span>Customer Name:</span>
                            <strong>${orderData.name}</strong>
                        </div>
                        <div>
                            <span>Shipping Address:</span>
                            <strong>${orderData.address}, ${orderData.city}</strong>
                        </div>
                        <div>
                            <span>Total Paid:</span>
                            <strong>$${orderData.total}</strong>
                        </div>
                    </div>
                    <button class="modal-btn" id="btn-continue-shopping">Continue Shopping</button>
                    <div class="modal-redirect-info">You will be redirected to the homepage in <span id="redirect-countdown">5</span> seconds...</div>
                </div>
            </div>
        `;

        $('body').append(modalHtml);

        // Slide modal up
        setTimeout(() => {
            $('#order-success-overlay').addClass('show');
        }, 50);

        const homeUrl = window.Laravel ? window.Laravel.routes.home : '/home';

        // Redirect countdown
        let seconds = 5;
        const intervalId = setInterval(() => {
            seconds--;
            $('#redirect-countdown').text(seconds);
            if (seconds <= 0) {
                clearInterval(intervalId);
                window.location.href = homeUrl;
            }
        }, 1000);

        // Button redirect
        $('#btn-continue-shopping').on('click', function() {
            clearInterval(intervalId);
            window.location.href = homeUrl;
        });
    }

    // Real-time input check during typing/validation helper
    $(document).on('blur', '.billing-details input', function() {
        const name = $(this).attr('name');
        if (!name) return;

        const val = $(this).val().trim();
        const $parent = $(this).closest('.form-group');

        $parent.removeClass('has-error has-success');
        $parent.find('.error-msg').remove();

        if (val === '') {
            // Ignore showing error instantly on blur unless it was validated once, but let's give immediate visual feedback
            $parent.addClass('has-error');
        } else if (name === 'email' && !validateEmail(val)) {
            $parent.addClass('has-error');
        } else {
            $parent.addClass('has-success');
        }
    });

    // -------------------------------------------------------------
    // 6. INITIALIZATION & CROSS-TAB SYNC
    // -------------------------------------------------------------
    
    // Initial UI render
    renderHeaderCart();
    renderHeaderWishlist();
    renderCheckoutPage();

    // Listen for storage events (updates in other tabs)
    window.addEventListener('storage', function(e) {
        if (e.key === CART_KEY) {
            renderHeaderCart();
            renderCheckoutPage();
        } else if (e.key === WISHLIST_KEY) {
            renderHeaderWishlist();
        }
    });

    // Listen for local cart update events on same tab
    $(document).on('cartUpdated', function() {
        renderHeaderCart();
        renderCheckoutPage();
    });

    // Listen for local wishlist update events on same tab
    $(document).on('wishlistUpdated', function() {
        renderHeaderWishlist();
    });
});
