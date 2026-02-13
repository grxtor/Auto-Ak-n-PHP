<?php
$pageTitle = 'Sipariş Takibi';
$pageDesc = 'Gelen siparişleri onaylayın, kargo bilgilerini güncelleyin veya iptal edin.';
include 'includes/header.php';
?>

<div class="container" style="margin-top: 2rem; padding-bottom: 3rem;">
    
    <!-- Stats Cards -->
    <div class="stats-grid" id="statsGrid" style="display: none;">
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: #f59e0b; font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Bekleyen</div>
                    <div style="font-size: 1.75rem; font-weight: 900; color: var(--secondary);" id="statPending">0</div>
                </div>
            </div>
        </div>
        
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: #16a34a; font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Onaylanan</div>
                    <div style="font-size: 1.75rem; font-weight: 900; color: var(--secondary);" id="statVerified">0</div>
                </div>
            </div>
        </div>
        
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; background: #dbeafe; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-truck" style="color: #2563eb; font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Kargoda</div>
                    <div style="font-size: 1.75rem; font-weight: 900; color: var(--secondary);" id="statShipped">0</div>
                </div>
            </div>
        </div>
        
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 48px; height: 48px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-box-open" style="color: #15803d; font-size: 1.5rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Teslim</div>
                    <div style="font-size: 1.75rem; font-weight: 900; color: var(--secondary);" id="statDelivered">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-bar" style="margin-top: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
            <i class="fas fa-filter" style="color: var(--gray-400);"></i>
            <span style="font-weight: 700; font-size: 0.85rem; color: var(--gray-600);">Filtrele:</span>
        </div>
        <select class="form-select" id="statusFilter" style="max-width: 200px;" onchange="filterOrders()">
            <option value="all">Tüm Siparişler</option>
            <option value="pending">Bekleyen</option>
            <option value="verified">Onaylanan</option>
            <option value="shipped">Kargoda</option>
            <option value="delivered">Teslim Edildi</option>
            <option value="cancelled">İptal Edildi</option>
        </select>
        <input type="text" class="form-input" id="searchInput" placeholder="Sipariş ara..." style="max-width: 250px;" oninput="filterOrders()">
        <button class="btn-outline btn-sm" onclick="loadOrders()" style="gap: 6px;">
            <i class="fas fa-sync-alt"></i>
            <span class="hide-mobile">Yenile</span>
        </button>
    </div>

    <!-- Orders Table/Cards -->
    <div class="card" style="margin-top: 1.5rem;">
        <!-- Loading State -->
        <div id="ordLoading" class="loading" style="padding: 3rem;">
            <span>Siparişler yükleniyor</span>
        </div>
        
        <!-- Empty State -->
        <div id="ordEmpty" class="empty-state" style="display: none; padding: 3rem;">
            <i class="fas fa-inbox" style="font-size: 3rem; color: var(--gray-300); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--gray-500); font-size: 1.1rem; margin-bottom: 0.5rem;">Henüz sipariş yok</h3>
            <p style="color: var(--gray-400); font-size: 0.9rem;">Yeni siparişler geldiğinde burada görünecek.</p>
        </div>

        <!-- Desktop Table View -->
        <div class="table-container desktop-only" id="ordTableContainer" style="display: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sipariş No</th>
                        <th>Müşteri</th>
                        <th>Ürünler</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                        <th>Dekont</th>
                        <th>Tarih</th>
                        <th style="width: 180px;">İşlem</th>
                    </tr>
                </thead>
                <tbody id="ordBody"></tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-only" id="ordCardsContainer" style="display: none;">
            <div id="ordCards"></div>
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div id="orderModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1050; padding: 1rem; overflow-y: auto;">
    <div style="max-width: 600px; margin: 2rem auto; background: white; border-radius: var(--radius); box-shadow: var(--shadow-xl);">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0;">Sipariş Detayları</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--gray-400); cursor: pointer; padding: 0; width: 32px; height: 32px;">&times;</button>
        </div>
        <div id="modalContent" style="padding: 1.5rem;"></div>
    </div>
</div>

<style>
.desktop-only { display: block; }
.mobile-only { display: none; }

.order-card {
    padding: 1.25rem;
    border-bottom: 1px solid var(--gray-100);
    transition: background var(--transition-base);
}

.order-card:hover {
    background: var(--gray-50);
}

.order-card:last-child {
    border-bottom: none;
}

.hide-mobile {
    display: inline;
}

@media (max-width: 900px) {
    .desktop-only { display: none !important; }
    .mobile-only { display: block !important; }
    .hide-mobile { display: none; }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    
    .filter-bar {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .filter-bar > * {
        max-width: 100% !important;
        width: 100%;
    }
}

@media (max-width: 600px) {
    .stats-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
if(!localStorage.getItem('admin_auth')) window.location='<?= BASE_URL ?>/admin/login.php';

let allOrders = [];

const statusMap = {
    pending: {label: 'BEKLEMEDE', cls: 'badge-pending', icon: 'fa-clock'},
    verified: {label: 'ONAYLANDI', cls: 'badge-verified', icon: 'fa-check-circle'},
    shipped: {label: 'KARGODA', cls: 'badge-shipped', icon: 'fa-truck'},
    delivered: {label: 'TESLİM EDİLDİ', cls: 'badge-delivered', icon: 'fa-box-open'},
    cancelled: {label: 'İPTAL EDİLDİ', cls: 'badge-cancelled', icon: 'fa-times-circle'}
};

function loadOrders() {
    document.getElementById('ordLoading').style.display = 'flex';
    document.getElementById('ordEmpty').style.display = 'none';
    document.getElementById('ordTableContainer').style.display = 'none';
    document.getElementById('ordCardsContainer').style.display = 'none';
    
    fetch(API_BASE + '/admin/orders')
        .then(r => r.json())
        .then(orders => {
            document.getElementById('ordLoading').style.display = 'none';
            
            if(!Array.isArray(orders) || orders.length === 0) {
                document.getElementById('ordEmpty').style.display = 'block';
                return;
            }
            
            allOrders = orders;
            updateStats(orders);
            renderOrders(orders);
        })
        .catch(err => {
            console.error('Error loading orders:', err);
            document.getElementById('ordLoading').style.display = 'none';
            document.getElementById('ordEmpty').style.display = 'block';
        });
}

function updateStats(orders) {
    const stats = {
        pending: 0,
        verified: 0,
        shipped: 0,
        delivered: 0
    };
    
    orders.forEach(o => {
        if(stats.hasOwnProperty(o.status)) {
            stats[o.status]++;
        }
    });
    
    document.getElementById('statPending').textContent = stats.pending;
    document.getElementById('statVerified').textContent = stats.verified;
    document.getElementById('statShipped').textContent = stats.shipped;
    document.getElementById('statDelivered').textContent = stats.delivered;
    document.getElementById('statsGrid').style.display = 'grid';
}

function renderOrders(orders) {
    // Desktop Table
    document.getElementById('ordTableContainer').style.display = 'block';
    document.getElementById('ordBody').innerHTML = orders.map(o => {
        const s = statusMap[o.status] || statusMap.pending;
        const orderItems = Array.isArray(o.items) ? o.items : [];
        const itemCount = orderItems.length;
        
        return `<tr>
            <td>
                <span style="font-weight: 800; color: var(--primary);">#${o.order_code}</span>
            </td>
            <td>
                <div style="font-weight: 700;">${o.customer_name}</div>
                <div style="font-size: 0.8rem; color: var(--gray-500);">
                    <i class="fas fa-phone" style="margin-right: 4px;"></i>${o.customer_phone || 'Belirtilmemiş'}
                </div>
            </td>
            <td>
                <button onclick="viewOrderDetails(${o.id})" class="btn-outline btn-xs" style="gap: 4px;">
                    <i class="fas fa-box"></i>
                    ${itemCount} Ürün
                </button>
            </td>
            <td>
                <span style="font-weight: 800; color: var(--secondary);">₺${parseFloat(o.total_amount).toLocaleString('tr-TR', {minimumFractionDigits: 2})}</span>
            </td>
            <td>
                <span class="badge ${s.cls}">
                    <i class="fas ${s.icon}"></i> ${s.label}
                </span>
            </td>
            <td>
                ${o.receipt_url ? `
                    <a href="${o.receipt_url}" target="_blank" class="btn-outline btn-xs" style="gap: 4px;">
                        <i class="fas fa-file-invoice"></i> Görüntüle
                    </a>
                ` : '<small style="color: var(--gray-400);">Yüklenmedi</small>'}
            </td>
            <td>
                <div style="font-size: 0.85rem; color: var(--gray-600);">
                    ${new Date(o.created_at).toLocaleDateString('tr-TR', {day: '2-digit', month: 'short', year: 'numeric'})}
                </div>
                <div style="font-size: 0.75rem; color: var(--gray-400);">
                    ${new Date(o.created_at).toLocaleTimeString('tr-TR', {hour: '2-digit', minute: '2-digit'})}
                </div>
            </td>
            <td>
                <select class="form-select" style="font-size: 0.8rem; padding: 6px 10px;" onchange="updateStatus(${o.id}, this.value)">
                    <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>Beklemede</option>
                    <option value="verified" ${o.status === 'verified' ? 'selected' : ''}>Onaylandı</option>
                    <option value="shipped" ${o.status === 'shipped' ? 'selected' : ''}>Kargoda</option>
                    <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>Teslim Edildi</option>
                    <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>İptal</option>
                </select>
            </td>
        </tr>`;
    }).join('');
    
    // Mobile Cards
    document.getElementById('ordCardsContainer').style.display = 'block';
    document.getElementById('ordCards').innerHTML = orders.map(o => {
        const s = statusMap[o.status] || statusMap.pending;
        const orderItems = Array.isArray(o.items) ? o.items : [];
        
        return `<div class="order-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <div style="font-weight: 800; color: var(--primary); font-size: 1rem;">#${o.order_code}</div>
                    <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 2px;">
                        ${new Date(o.created_at).toLocaleDateString('tr-TR')}
                    </div>
                </div>
                <span class="badge ${s.cls}">
                    <i class="fas ${s.icon}"></i> ${s.label}
                </span>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <div style="font-weight: 700; margin-bottom: 4px;">${o.customer_name}</div>
                <div style="font-size: 0.85rem; color: var(--gray-500);">
                    <i class="fas fa-phone"></i> ${o.customer_phone || 'Belirtilmemiş'}
                </div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0.75rem; background: var(--gray-50); border-radius: 8px;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 2px;">Toplam Tutar</div>
                    <div style="font-weight: 900; font-size: 1.1rem; color: var(--secondary);">₺${parseFloat(o.total_amount).toLocaleString('tr-TR', {minimumFractionDigits: 2})}</div>
                </div>
                <button onclick="viewOrderDetails(${o.id})" class="btn-outline btn-sm">
                    <i class="fas fa-box"></i> ${orderItems.length} Ürün
                </button>
            </div>
            
            ${o.receipt_url ? `
                <a href="${o.receipt_url}" target="_blank" class="btn-outline btn-sm" style="width: 100%; margin-bottom: 0.75rem;">
                    <i class="fas fa-file-invoice"></i> Dekontu Görüntüle
                </a>
            ` : ''}
            
            <select class="form-select" style="width: 100%;" onchange="updateStatus(${o.id}, this.value)">
                <option value="pending" ${o.status === 'pending' ? 'selected' : ''}>Beklemede</option>
                <option value="verified" ${o.status === 'verified' ? 'selected' : ''}>Ödeme Onaylandı</option>
                <option value="shipped" ${o.status === 'shipped' ? 'selected' : ''}>Kargoda</option>
                <option value="delivered" ${o.status === 'delivered' ? 'selected' : ''}>Teslim Edildi</option>
                <option value="cancelled" ${o.status === 'cancelled' ? 'selected' : ''}>İptal Edildi</option>
            </select>
        </div>`;
    }).join('');
}

function filterOrders() {
    const statusFilter = document.getElementById('statusFilter').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    
    let filtered = allOrders;
    
    if(statusFilter !== 'all') {
        filtered = filtered.filter(o => o.status === statusFilter);
    }
    
    if(searchText) {
        filtered = filtered.filter(o => 
            o.order_code.toLowerCase().includes(searchText) ||
            o.customer_name.toLowerCase().includes(searchText) ||
            (o.customer_phone && o.customer_phone.includes(searchText))
        );
    }
    
    renderOrders(filtered);
}

function updateStatus(id, status) {
    if(!confirm('Sipariş durumunu güncellemek istediğinize emin misiniz?')) return;
    
    fetch(API_BASE + '/admin/orders', {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id, status})
    })
    .then(r => r.json())
    .then(() => {
        loadOrders();
        // Show success message (you can implement a toast notification here)
        alert('Sipariş durumu güncellendi!');
    })
    .catch(err => {
        console.error('Error updating status:', err);
        alert('Durum güncellenirken bir hata oluştu.');
    });
}

function viewOrderDetails(orderId) {
    const order = allOrders.find(o => o.id === orderId);
    if(!order) return;
    
    const s = statusMap[order.status] || statusMap.pending;
    const items = Array.isArray(order.items) ? order.items : [];
    
    const modalContent = `
        <div style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--primary);">#${order.order_code}</h4>
                <span class="badge ${s.cls}"><i class="fas ${s.icon}"></i> ${s.label}</span>
            </div>
            
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; margin-bottom: 0.5rem;">Müşteri Bilgileri</div>
                <div style="font-weight: 700; margin-bottom: 4px;">${order.customer_name}</div>
                <div style="font-size: 0.85rem; color: var(--gray-600);">
                    <i class="fas fa-phone"></i> ${order.customer_phone || 'Belirtilmemiş'}
                </div>
                ${order.customer_address ? `
                    <div style="font-size: 0.85rem; color: var(--gray-600); margin-top: 4px;">
                        <i class="fas fa-map-marker-alt"></i> ${order.customer_address}
                    </div>
                ` : ''}
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <h5 style="font-size: 0.9rem; font-weight: 800; margin-bottom: 1rem; color: var(--gray-700);">Sipariş Ürünleri</h5>
            ${items.map(item => `
                <div style="display: flex; gap: 1rem; padding: 0.75rem; background: var(--gray-50); border-radius: 8px; margin-bottom: 0.5rem;">
                    ${item.image_url ? `
                        <img src="${item.image_url}" style="width: 60px; height: 60px; object-fit: contain; background: white; border-radius: 6px; padding: 4px;">
                    ` : ''}
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.9rem; margin-bottom: 2px;">${item.name}</div>
                        <div style="font-size: 0.8rem; color: var(--gray-500);">
                            ${item.quantity} adet × ₺${parseFloat(item.price).toLocaleString('tr-TR', {minimumFractionDigits: 2})}
                        </div>
                    </div>
                    <div style="font-weight: 800; color: var(--secondary); white-space: nowrap;">
                        ₺${(item.quantity * item.price).toLocaleString('tr-TR', {minimumFractionDigits: 2})}
                    </div>
                </div>
            `).join('')}
        </div>
        
        <div style="border-top: 2px solid var(--gray-200); padding-top: 1rem;">
            <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 900;">
                <span>Toplam Tutar:</span>
                <span style="color: var(--primary);">₺${parseFloat(order.total_amount).toLocaleString('tr-TR', {minimumFractionDigits: 2})}</span>
            </div>
        </div>
        
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <label class="form-label">Sipariş Durumu</label>
            <select class="form-select" onchange="updateStatus(${order.id}, this.value); closeModal();">
                <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Beklemede</option>
                <option value="verified" ${order.status === 'verified' ? 'selected' : ''}>Ödeme Onaylandı</option>
                <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>Kargoda</option>
                <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Teslim Edildi</option>
                <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>İptal Edildi</option>
            </select>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = modalContent;
    document.getElementById('orderModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

// Close modal on outside click
document.getElementById('orderModal')?.addEventListener('click', function(e) {
    if(e.target === this) closeModal();
});

// Initial load
loadOrders();

// Auto refresh every 30 seconds
setInterval(loadOrders, 30000);
</script>

</body>
</html>