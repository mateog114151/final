
		let currentSlideIndex = 0;
		const slides = document.querySelector('.slides');
		const dots = document.querySelectorAll('.dot');
		const totalSlides = document.querySelectorAll('.slide').length;

		function showSlide(index) {
			currentSlideIndex = index;
			if (currentSlideIndex >= totalSlides) currentSlideIndex = 0;
			if (currentSlideIndex < 0) currentSlideIndex = totalSlides - 1;

			slides.style.transform = `translateX(-${currentSlideIndex * 100}%)`;

			dots.forEach((dot, i) => {
				dot.classList.toggle('active', i === currentSlideIndex);
			});
		}

		function changeSlide(direction) {
			showSlide(currentSlideIndex + direction);
		}

		function currentSlide(index) {
			showSlide(index - 1);
		}

		// Auto-slide every 5 seconds
		setInterval(() => {
			changeSlide(1);
		}, 5000);

		// Product options functionality
		document.querySelectorAll('.container-options span').forEach(option => {
			option.addEventListener('click', function () {
				document.querySelectorAll('.container-options span').forEach(opt => {
					opt.classList.remove('active');
				});
				this.classList.add('active');
			});
		});

		// Smooth scrolling for anchor links
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function (e) {
				e.preventDefault();
				const target = document.querySelector(this.getAttribute('href'));
				if (target) {
					target.scrollIntoView({
						behavior: 'smooth',
						block: 'start'
					});
				}
			});
		});

		// Add to cart animation (existing)
		document.querySelectorAll('.add-cart').forEach(button => {
			button.addEventListener('click', function () {
				this.style.transform = 'scale(0.8)';
				this.style.backgroundColor = '#28a745';
				this.innerHTML = '<i class="fa-solid fa-check"></i>';

				setTimeout(() => {
					this.style.transform = 'scale(1)';
					this.style.backgroundColor = 'var(--accent)';
					this.innerHTML = '<i class="fa-solid fa-basket-shopping"></i>';
				}, 1000);
			});
		});

        /* CART: funcionalidad añadida (no elimina tu código anterior) */
        (function() {
          // estado del carrito
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

          // util: parsear precio tipo "$85.000" -> número 85000
          function parsePrice(str) {
            if (!str) return 0;
            // quitar símbolos y espacios, reemplazar comas por puntos, quitar puntos miles
            const cleaned = String(str).replace(/[^\d,\.]/g,'').replace(/\./g,'').replace(/,/g,'.');
            const num = parseFloat(cleaned);
            if (isNaN(num)) return 0;
            // si decimal was used, keep; otherwise multiply by 1
            return Math.round(num * 100); // guardamos en centavos
          }

          // formatear precio desde centavos
          function formatPrice(cents) {
            const value = (cents/100).toFixed(0); // sin decimales según formato original
            // añadir separador de miles con punto
            return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g,'.');
          }

          // cargar carrito desde localStorage
          function loadCart() {
            const raw = localStorage.getItem('hp_cart_v1');
            cart = raw ? JSON.parse(raw) : [];
            renderCart();
          }

          // guardar carrito
          function saveCart() {
            localStorage.setItem('hp_cart_v1', JSON.stringify(cart));
          }

          // actualizar contador y total
          function updateCounter() {
            const qty = cart.reduce((s,i)=> s + i.qty, 0);
            cartCountEl.textContent = qty;
          }

          // renderizar items en panel
          function renderCart() {
            cartItemsContainer.innerHTML = '';
            if (cart.length === 0) {
              cartEmptyEl.style.display = 'block';
            } else {
              cartEmptyEl.style.display = 'none';
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
                      <button class="qty-decrease" title="Disminuir">-</button>
                      <span style="min-width:28px; text-align:center;">${item.qty}</span>
                      <button class="qty-increase" title="Aumentar">+</button>
                    </div>
                    <div style="font-weight:700;">${formatPrice(item.price * item.qty)}</div>
                  </div>
                </div>
              `;
              cartItemsContainer.appendChild(node);
            });

            cartTotalEl.textContent = formatPrice(total);
            updateCounter();
          }

          // abrir / cerrar panel
          function toggleCart(open) {
            const isOpen = cartPanel.classList.contains('open');
            if (typeof open === 'boolean') {
              if (open && !isOpen) cartPanel.classList.add('open');
              if (!open && isOpen) cartPanel.classList.remove('open');
            } else {
              cartPanel.classList.toggle('open');
            }
            cartPanel.setAttribute('aria-hidden', cartPanel.classList.contains('open') ? 'false' : 'true');
          }

          // añadir producto (busca info a partir de la tarjeta)
          function addProductFromCard(cardEl) {
            try {
              const titleEl = cardEl.querySelector('.content-card-product h3');
              const priceEl = cardEl.querySelector('.content-card-product .price');
              const imgEl = cardEl.querySelector('.container-img img');

              const title = titleEl ? titleEl.textContent.trim() : 'Producto';
              const priceText = priceEl ? priceEl.textContent.trim().split(' ')[0] : '$0';
              const priceCents = parsePrice(priceText); // centavos
              const image = imgEl ? imgEl.src : '';

              addToCart({ title, price: priceCents, image });
              // abrir panel al añadir
              toggleCart(true);
            } catch (e) {
              console.error('Error al añadir producto', e);
            }
          }

          // añadir al carrito (obj con title, price (centavos), image)
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

          // quitar item por indice
          function removeItem(idx) {
            cart.splice(idx, 1);
            saveCart();
            renderCart();
          }

          // cambiar cantidad
          function changeQty(idx, delta) {
            if (!cart[idx]) return;
            cart[idx].qty = Math.max(1, cart[idx].qty + delta);
            saveCart();
            renderCart();
          }

          // vaciar carrito
          function clearCart() {
            if (!confirm('¿Vaciar carrito?')) return;
            cart = [];
            saveCart();
            renderCart();
          }

          // checkout (placeholder)
          function checkout() {
            if (cart.length === 0) { alert('Tu carrito está vacío'); return; }
            // aquí podrías llevar a pago o enviar datos al servidor
            alert('Checkout simulado. Total: ' + cartTotalEl.textContent);
            // opcional: vaciar carrito
            // cart = []; saveCart(); renderCart();
          }

          // eventos
          cartButton.addEventListener('click', () => toggleCart());
          cartClose.addEventListener('click', () => toggleCart(false));
          continueBtn.addEventListener('click', () => toggleCart(false));
          clearBtn.addEventListener('click', clearCart);
          checkoutBtn.addEventListener('click', checkout);

          // delegación en el panel para botones de qty
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

          // añadir desde cada tarjeta: escuchadores en botones .add-cart
          document.querySelectorAll('.add-cart').forEach(btn => {
            btn.addEventListener('click', function (ev) {
              // buscar tarjeta padre
              const card = ev.target.closest('.card-product') || ev.target.closest('.card-product') || this.closest('.card-product');
              if (card) addProductFromCard(card);
            });
          });

          // también permitir agregar al hacer click en botones dentro de container (por si hay iconos)
          document.querySelectorAll('.container-products .card-product').forEach(card => {
            // si el usuario hace click en el icono del carrito (span.add-cart), ya está manejado.
            // nada extra aquí.
          });

          // iniciar
          loadCart();

        })();