// ============================================
// HAPPY PETS - JAVASCRIPT UNIFICADO
// ============================================

// ============================================
// 1. CAROUSEL
// ============================================

let currentSlideIndex = 0;
const slides = document.querySelector('.slides');
const dots = document.querySelectorAll('.dot');
const totalSlides = document.querySelectorAll('.slide').length;

function showSlide(index) {
  currentSlideIndex = index;
  if (currentSlideIndex >= totalSlides) currentSlideIndex = 0;
  if (currentSlideIndex < 0) currentSlideIndex = totalSlides - 1;

  if (slides) {
    slides.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
  }

  dots.forEach((dot, i) => {
    dot.classList.toggle('active', i === currentSlideIndex);
  });
}

function changeSlide(direction) {
  showSlide(currentSlideIndex + direction);
}

function currentSlide(n) {
  showSlide(n - 1);
}

// Auto-slide cada 5s
function startAutoSlide() {
  setInterval(() => {
    changeSlide(1);
  }, 5000);
}

// ============================================
// 2. CARRITO DE COMPRAS
// ============================================

(function() {
  let cart = [];

  // elementos
  const cartButton = document.getElementById('cart-button');
  const cartCountEl = document.getElementById('cart-count');
  const cartPanel = document.getElementById('cart-panel');
  const cartItemsContainer = document.getElementById('cart-items');
  const cartEmptyEl = document.getElementById('cart-empty');
  const cartTotalEl = document.getElementById('cart-total');
  const cartClose = document.getElementById('cart-close');
  const clearBtn = document.getElementById('cart-clear');
  const checkoutBtn = document.getElementById('checkout-btn');
  const continueBtn = document.getElementById('close-btn-2');

  // util: parsear precio tipo "$85.000"
  function parsePrice(str) {
    if (!str) return 0;
    const cleaned = String(str).replace(/[^\d,\.]/g,'').replace(/\./g,'').replace(/,/g,'.');
    const num = parseFloat(cleaned);
    if (isNaN(num)) return 0;
    return Math.round(num * 100); // guardamos en centavos
  }

  function formatPrice(cents) {
    const value = (cents/100).toFixed(0);
    return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,'.');
  }

  function loadCart() {
    const raw = localStorage.getItem('hp_cart_v1');
    cart = raw ? JSON.parse(raw) : [];
    renderCart();
  }

  function saveCart() {
    localStorage.setItem('hp_cart_v1', JSON.stringify(cart));
  }

  function updateCounter() {
    const qty = cart.reduce((s,i)=> s + i.qty, 0);
    if (cartCountEl) cartCountEl.textContent = qty;
  }

  function renderCart() {
    if (!cartItemsContainer) return;
    cartItemsContainer.innerHTML = '';
    if (cart.length === 0) {
      if (cartEmptyEl) cartEmptyEl.style.display = 'block';
    } else {
      if (cartEmptyEl) cartEmptyEl.style.display = 'none';
    }

    let total = 0;
    cart.forEach((item, idx) => {
      total += item.price * item.qty;
      const node = document.createElement('div');
      node.className = 'cart-item';
      node.innerHTML = `
        <img src="${item.image}" alt="${item.title}">
        <div class="meta">
          <h4>${item.title}</h4>
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div class="qty-controls" data-idx="${idx}">
              <button class="qty-decrease">-</button>
              <span style="min-width:28px; text-align:center;">${item.qty}</span>
              <button class="qty-increase">+</button>
            </div>
            <div style="font-weight:700;">${formatPrice(item.price * item.qty)}</div>
          </div>
        </div>
      `;
      cartItemsContainer.appendChild(node);
    });

    if (cartTotalEl) cartTotalEl.textContent = formatPrice(total);
    updateCounter();
  }

  function toggleCart(open) {
    if (!cartPanel) return;
    const isOpen = cartPanel.classList.contains('open');
    if (typeof open === 'boolean') {
      if (open && !isOpen) cartPanel.classList.add('open');
      if (!open && isOpen) cartPanel.classList.remove('open');
    } else {
      cartPanel.classList.toggle('open');
    }
    cartPanel.setAttribute('aria-hidden', cartPanel.classList.contains('open') ? 'false' : 'true');
  }

  function addProductFromCard(cardEl) {
    try {
      const titleEl = cardEl.querySelector('.content-card-product h3');
      const priceEl = cardEl.querySelector('.content-card-product .price');
      const imgEl = cardEl.querySelector('.container-img img');

      const title = titleEl ? titleEl.textContent.trim() : 'Producto';
      const priceText = priceEl ? priceEl.textContent.trim().split(' ')[0] : '$0';
      const priceCents = parsePrice(priceText);
      const image = imgEl ? imgEl.src : '';

      addToCart({ title, price: priceCents, image });
      toggleCart(true);
    } catch (e) {
      console.error('Error al añadir producto', e);
    }
  }

  function addToCart(product) {
    const found = cart.find(i => i.title === product.title && i.price === product.price);
    if (found) {
      found.qty += 1;
    } else {
      cart.push({ title: product.title, price: product.price, image: product.image, qty: 1 });
    }
    saveCart();
    renderCart();
  }

  function changeQty(idx, delta) {
    if (!cart[idx]) return;
    cart[idx].qty = Math.max(1, cart[idx].qty + delta);
    saveCart();
    renderCart();
  }

  function clearCart() {
    if (!confirm('¿Vaciar carrito?')) return;
    cart = [];
    saveCart();
    renderCart();
  }

  function checkout() {
    if (cart.length === 0) { alert('Tu carrito está vacío'); return; }
    alert('Checkout simulado. Total: ' + cartTotalEl.textContent);
  }

  // eventos
  if (cartButton) cartButton.addEventListener('click', () => toggleCart());
  if (cartClose) cartClose.addEventListener('click', () => toggleCart(false));
  if (continueBtn) continueBtn.addEventListener('click', () => toggleCart(false));
  if (clearBtn) clearBtn.addEventListener('click', clearCart);
  if (checkoutBtn) checkoutBtn.addEventListener('click', checkout);

  if (cartItemsContainer) {
    cartItemsContainer.addEventListener('click', (e) => {
      const decrease = e.target.closest('.qty-decrease');
      const increase = e.target.closest('.qty-increase');
      if (decrease || increase) {
        const parent = e.target.closest('.qty-controls');
        const idx = Number(parent.getAttribute('data-idx'));
        if (decrease) changeQty(idx, -1);
        if (increase) changeQty(idx, +1);
      }
    });
  }

  document.querySelectorAll('.add-cart').forEach(btn => {
    btn.addEventListener('click', function (ev) {
      const card = ev.target.closest('.card-product') || this.closest('.card-product');
      if (card) addProductFromCard(card);
    });
  });

  loadCart();
})();

// ============================================
// 3. FILTROS DE PRODUCTOS
// ============================================

function initProductFilters() {
  const filterButtons = document.querySelectorAll('.container-options span');
  filterButtons.forEach(button => {
    button.addEventListener('click', function() {
      filterButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      const filterText = this.textContent.toLowerCase();
      filterProducts(filterText);
    });
  });
}

function filterProducts(filter) {
  const products = document.querySelectorAll('.card-product');
  // Definir categorías por página
  const categoriasGatos = {
    'alimentación': ['alimento', 'snacks'],
    'juguetes': ['juguete', 'ratón', 'túnel'],
    'accesorios': ['collar', 'casa', 'transportadora', 'fuente', 'cepillo'],
    'arena y limpieza': ['arena', 'cepillo'],
  };
  const categoriasPerros = {
    'alimentación': ['alimento', 'snacks'],
    'juguetes': ['juguete'],
    'accesorios': ['collar', 'correa', 'transportadora', 'cama'],
    'cuidado': ['kit', 'aseo', 'cama'],
  };

  // Detectar página
  const isGatos = !!document.getElementById('productos-gatos');
  const isPerros = !!document.getElementById('productos-perros');
  let categorias = isGatos ? categoriasGatos : categoriasPerros;

  products.forEach(product => {
    if (filter === 'todos') {
      product.style.display = 'block';
    } else {
      const nombre = product.querySelector('h3').textContent.toLowerCase();
      // Buscar si el nombre contiene alguna palabra clave de la categoría
      const palabrasClave = categorias[filter] || [];
      const mostrar = palabrasClave.some(palabra => nombre.includes(palabra));
      product.style.display = mostrar ? 'block' : 'none';
    }
  });
}

// ============================================
// 4. MENÚ MÓVIL
// ============================================

function initMobileMenu() {
  const menuToggle = document.querySelector('.menu-toggle');
  if (menuToggle) {
    menuToggle.addEventListener('click', function() {
      document.body.classList.toggle('nav-open');
    });
  }
  document.querySelectorAll('.navbar a').forEach(link => {
    link.addEventListener('click', () => {
      document.body.classList.remove('nav-open');
    });
  });
}

// ============================================
// 5. SMOOTH SCROLL
// ============================================

function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
}

// ============================================
// 6. BOTONES DE SERVICIOS
// ============================================

function initServiceButtons() {
  document.querySelectorAll('.service-button').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const serviceCard = this.closest('.service-card');
      const serviceTitle = serviceCard.querySelector('.service-title').textContent;
      const originalText = this.textContent;
      this.textContent = '✓ Contactado';
      this.style.background = '#10b981';
      setTimeout(() => {
        alert(`¡Gracias por tu interés en ${serviceTitle}!\nTe contactaremos pronto.`);
        this.textContent = originalText;
        this.style.background = '';
      }, 1000);
    });
  });
}

// ============================================
// 7. ANIMACIONES Y EFECTOS
// ============================================

function initAnimations() {
  document.querySelectorAll('.gallery img').forEach(img => {
    img.addEventListener('click', function() {
      console.log('Imagen clickeada:', this.src);
    });
  });
  document.querySelectorAll('.social-icons span').forEach(social => {
    social.addEventListener('click', function() {
      alert(`Síguenos en ${this.className}!`);
    });
  });
}

// ============================================
// 8. BOTÓN "VER PRODUCTOS"
// ============================================

function initViewProductsButton() {
  const viewProductsBtn = document.querySelector('.button');
  if (viewProductsBtn) {
    viewProductsBtn.addEventListener('click', function() {
      const productsSection = document.getElementById('productos');
      if (productsSection) {
        productsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }
}

// ============================================
// 9. INICIALIZACIÓN PRINCIPAL
// ============================================

document.addEventListener('DOMContentLoaded', function() {
  console.log('🐾 Happy Pets cargado correctamente');
  initProductFilters();
  initMobileMenu();
  initSmoothScroll();
  initServiceButtons();
  initAnimations();
  initViewProductsButton();
  if (document.querySelector('.carousel')) {
    startAutoSlide();
  }
});

// ============================================
// 10. MANEJO DE ERRORES
// ============================================

window.addEventListener('error', function(e) {
  console.error('Error en Happy Pets:', e.error);
});

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
      this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiPjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjZjNmNGY2Ii8+PC9zdmc+';
      this.alt = 'Imagen no disponible';
    });
  });
});
