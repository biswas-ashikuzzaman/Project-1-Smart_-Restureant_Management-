// public/assets/js/app.js
// frontend logic for index.php
let menuData = [];
let cart = [];

async function fetchMenu(q='') {
    const url = '/api/get_menu.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');
    const res = await fetch(url);
    menuData = await res.json();
    renderMenu();
}

function renderMenu() {
    const el = document.getElementById('menuList');
    el.innerHTML = '';
    menuData.forEach(item => {
        const div = document.createElement('div');
        div.className = 'col-6 col-md-4';
        div.innerHTML = `
            <div class="card p-2 h-100">
              <div><strong>${item.name}</strong></div>
              <div>৳ ${parseFloat(item.price).toFixed(2)}</div>
              <div class="mt-2"><button class="btn btn-sm btn-primary w-100" onclick="addToCart(${item.id}, '${escapeHtml(item.name)}', ${item.price})">Add</button></div>
            </div>
        `;
        el.appendChild(div);
    });
}

function escapeHtml(s) { return s.replace(/'/g, "\\'").replace(/"/g, '&quot;'); }

function addToCart(id, name, price) {
    const found = cart.find(c => c.id === id);
    if (found) found.qty++;
    else cart.push({id, name, qty:1, price: parseFloat(price)});
    renderCart();
}

function renderCart() {
    const el = document.getElementById('cartList');
    let html = '';
    let total = 0;
    cart.forEach((c, idx) => {
        html += `<div class="d-flex justify-content-between align-items-center mb-1">
            <div>${c.name} × <input type="number" min="1" value="${c.qty}" data-idx="${idx}" style="width:60px" onchange="changeQty(event)"></div>
            <div>৳ ${(c.price*c.qty).toFixed(2)} <button class="btn btn-sm btn-link text-danger" onclick="removeItem(${idx})">✕</button></div>
        </div>`;
        total += c.price * c.qty;
    });
    el.innerHTML = html || '<div class="text-muted">Cart empty</div>';
    document.getElementById('totalAmount').innerText = total.toFixed(2);
}

function changeQty(e) {
    const idx = parseInt(e.target.dataset.idx);
    const val = Math.max(1, parseInt(e.target.value));
    cart[idx].qty = val;
    renderCart();
}

function removeItem(idx) {
    cart.splice(idx,1);
    renderCart();
}

document.getElementById('search').addEventListener('input', (e)=> fetchMenu(e.target.value));

document.getElementById('btnPlaceOrder').addEventListener('click', async function(){
    if (cart.length === 0) { alert('Cart empty'); return; }
    const tableNo = document.getElementById('tableNo').value || '0';
    const items = cart.map(c => ({ id: c.id, qty: c.qty }));
    const res = await fetch('/api/add_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ table_no: tableNo, items })
    });
    const j = await res.json();
    if (j.success) {
        alert('Order placed. ID: ' + j.order_id);
        // open KOT for printing
        window.open('/public/kot_print.php?order_id=' + j.order_id, '_blank');
        // open receipt
        window.open('/public/receipt.php?order_id=' + j.order_id, '_blank');
        cart = [];
        renderCart();
        fetchKOT();
    } else {
        alert('Order failed');
    }
});

async function fetchKOT() {
    const res = await fetch('/api/kot.php');
    const kot = await res.json();
    const el = document.getElementById('kotList');
    el.innerHTML = '';
    kot.forEach(k => {
        const d = document.createElement('div');
        d.className = 'd-flex justify-content-between align-items-center mb-1';
        d.innerHTML = `<div>#${k.order_id} | Table ${k.table_no} — ${k.item_name} × ${k.qty}</div>
        <div>
          <button class="btn btn-sm btn-success" onclick="updateKOT(${k.id}, 'cooking')">Cooking</button>
          <button class="btn btn-sm btn-primary" onclick="updateKOT(${k.id}, 'ready')">Ready</button>
          <button class="btn btn-sm btn-danger" onclick="updateKOT(${k.id}, 'served')">Served</button>
        </div>`;
        el.appendChild(d);
    });
}

async function updateKOT(id, status) {
    await fetch('/api/kot.php', {
        method: 'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({ id, status })
    });
    fetchKOT();
}

// auto load
fetchMenu();
renderCart();
fetchKOT();
setInterval(fetchKOT, 5000); // poll every 5s
