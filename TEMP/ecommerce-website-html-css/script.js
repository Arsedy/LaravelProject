// Focus the cursor on the email-address input on load
const emailField = document.getElementById("email-address-input");
if (emailField) {
  emailField.focus({
    preventScroll: true,
  });
}

// ==========================================
// CART STATE & LOGIC
// ==========================================

// Cart data structure: [{ id, name, price, img, quantity }]
let cart = JSON.parse(localStorage.getItem("ecommerce-cart")) || [];

// DOM Elements
const cartIcon = document.getElementById("cart-icon");
const cartDrawer = document.getElementById("cart-drawer");
const cartOverlay = document.getElementById("cart-overlay");
const cartCloseBtn = document.getElementById("cart-close-btn");
const cartItemsContainer = document.getElementById("cart-drawer-items");
const cartSubtotal = document.getElementById("cart-subtotal");
const cartDrawerCount = document.getElementById("cart-drawer-count");
const cartBadge = document.querySelector(".cart-badge");
const clearCartBtn = document.getElementById("clear-cart-btn");
const checkoutBtn = document.getElementById("checkout-btn");
const toastContainer = document.getElementById("toast-container");
const startShoppingBtn = document.getElementById("start-shopping-btn");

// Toggle Cart Drawer
function openCart() {
  cartDrawer.classList.add("active");
  cartOverlay.classList.add("active");
  document.body.style.overflow = "hidden"; // Prevent scrolling behind drawer
}

function closeCart() {
  cartDrawer.classList.remove("active");
  cartOverlay.classList.remove("active");
  document.body.style.overflow = ""; // Restore scrolling
}

// Save Cart to LocalStorage
function saveCart() {
  localStorage.setItem("ecommerce-cart", JSON.stringify(cart));
}

// Update Cart Badge and Drawer UI
function updateUI() {
  // Calculate total items and subtotal
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

  // Update Badge in Header
  if (totalItems > 0) {
    cartBadge.textContent = totalItems;
    if (!cartBadge.classList.contains("show")) {
      cartBadge.classList.add("show");
    }
    // Add pulse animation
    cartBadge.classList.add("pulse");
    setTimeout(() => {
      cartBadge.classList.remove("pulse");
    }, 300);
  } else {
    cartBadge.classList.remove("show");
  }

  // Update Drawer Title Count
  cartDrawerCount.textContent = totalItems;

  // Update Subtotal Display
  cartSubtotal.textContent = `$${subtotal.toFixed(2)}`;

  // Render Items List
  renderCartItems();
}

// Render Items inside Cart Drawer
function renderCartItems() {
  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
      <div class="cart-empty-state">
        <i class="fa-solid fa-bag-shopping empty-icon"></i>
        <p>Your cart is empty</p>
        <button class="normal start-shopping-btn" id="start-shopping-btn-inner">Start Shopping</button>
      </div>
    `;
    // Hide footer buttons if cart is empty
    document.getElementById("cart-drawer-footer").style.display = "none";
    
    // Bind click listener to the inner start shopping button
    const innerStartBtn = document.getElementById("start-shopping-btn-inner");
    if (innerStartBtn) {
      innerStartBtn.addEventListener("click", closeCart);
    }
    return;
  }

  // Show footer if cart has items
  document.getElementById("cart-drawer-footer").style.display = "flex";

  let html = "";
  cart.forEach(item => {
    html += `
      <div class="cart-item" data-id="${item.id}">
        <img src="${item.img}" alt="${item.name}" />
        <div class="cart-item-details">
          <h4>${item.name}</h4>
          <span class="price">$${item.price}</span>
          <div class="cart-item-actions">
            <div class="cart-item-quantity">
              <button class="qty-btn minus" data-id="${item.id}"><i class="fa-solid fa-minus"></i></button>
              <span>${item.quantity}</span>
              <button class="qty-btn plus" data-id="${item.id}"><i class="fa-solid fa-plus"></i></button>
            </div>
            <button class="cart-item-remove" data-id="${item.id}" aria-label="Remove item">
              <i class="fa-solid fa-trash-can"></i>
            </button>
          </div>
        </div>
      </div>
    `;
  });

  cartItemsContainer.innerHTML = html;
}

// Show Custom Toast Notification
function showToast(name, img) {
  const toast = document.createElement("div");
  toast.classList.add("toast");
  toast.innerHTML = `
    <img src="${img}" alt="${name}" />
    <div class="toast-body">
      <span class="toast-title">Added to Cart</span>
      <p class="toast-message">${name}</p>
    </div>
    <button class="toast-close"><i class="fa-solid fa-xmark"></i></button>
  `;

  toastContainer.appendChild(toast);

  // Trigger browser reflow to enable transition, then add 'show'
  setTimeout(() => {
    toast.classList.add("show");
  }, 10);

  // Auto remove toast after 3.5 seconds
  const autoRemoveTimer = setTimeout(() => {
    dismissToast(toast);
  }, 3500);

  // Dismiss on close button click
  const closeBtn = toast.querySelector(".toast-close");
  closeBtn.addEventListener("click", () => {
    clearTimeout(autoRemoveTimer);
    dismissToast(toast);
  });
}

function dismissToast(toast) {
  toast.classList.remove("show");
  // Wait for transition to complete before deleting element
  toast.addEventListener("transitionend", () => {
    toast.remove();
  });
}

// Add Item to Cart State
function addToCart(id, name, price, img) {
  const parsedPrice = parseFloat(price);
  const existingItem = cart.find(item => item.id === id);

  if (existingItem) {
    existingItem.quantity += 1;
  } else {
    cart.push({
      id,
      name,
      price: parsedPrice,
      img,
      quantity: 1
    });
  }

  saveCart();
  updateUI();
  showToast(name, img);
}

// Update Item Quantity
function updateQuantity(id, delta) {
  const item = cart.find(item => item.id === id);
  if (!item) return;

  item.quantity += delta;

  if (item.quantity <= 0) {
    cart = cart.filter(item => item.id !== id);
  }

  saveCart();
  updateUI();
}

// Remove Item from Cart
function removeItem(id) {
  cart = cart.filter(item => item.id !== id);
  saveCart();
  updateUI();
}

// Clear all items
function clearCart() {
  if (confirm("Are you sure you want to empty your shopping cart?")) {
    cart = [];
    saveCart();
    updateUI();
  }
}

// Checkout Process
function checkout() {
  if (cart.length === 0) return;
  
  // Show successful checkout notification
  alert("Thank you for your order! Checkout process simulation successful.");
  cart = [];
  saveCart();
  updateUI();
  closeCart();
}

// ==========================================
// EVENT LISTENERS
// ==========================================

// Cart Open/Close events
cartIcon.addEventListener("click", (e) => {
  e.preventDefault();
  openCart();
});

cartCloseBtn.addEventListener("click", closeCart);
cartOverlay.addEventListener("click", closeCart);

if (startShoppingBtn) {
  startShoppingBtn.addEventListener("click", closeCart);
}

// Click Listeners on "Add to Cart" product buttons
const addToCartButtons = document.querySelectorAll(".add-to-cart");
addToCartButtons.forEach(button => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    
    // Find parent product-cart card
    const card = button.closest(".product-cart");
    if (!card) return;

    // Retrieve data attributes
    const id = card.dataset.id;
    const name = card.dataset.name;
    const price = card.dataset.price;
    const img = card.dataset.img;

    addToCart(id, name, price, img);
  });
});

// Event Delegation for clicks inside the Cart Drawer (Plus, Minus, Trash)
cartItemsContainer.addEventListener("click", (e) => {
  const target = e.target;

  // Handle quantity buttons
  const qtyBtn = target.closest(".qty-btn");
  if (qtyBtn) {
    const id = qtyBtn.dataset.id;
    if (qtyBtn.classList.contains("plus")) {
      updateQuantity(id, 1);
    } else if (qtyBtn.classList.contains("minus")) {
      updateQuantity(id, -1);
    }
    return;
  }

  // Handle remove trash button
  const removeBtn = target.closest(".cart-item-remove");
  if (removeBtn) {
    const id = removeBtn.dataset.id;
    removeItem(id);
    return;
  }
});

// Footer action buttons
clearCartBtn.addEventListener("click", clearCart);
checkoutBtn.addEventListener("click", checkout);

// Initial Load UI trigger
updateUI();
