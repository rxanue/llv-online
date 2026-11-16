<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'restaurant';
$title = 'Restaurant';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .restaurant-container { padding: 40px 10%; background: #f9f9f9; min-height: 70vh; }
    .restaurant-header { text-align: center; margin-bottom: 20px; }
    .restaurant-header h1 { color: #14243d; margin: 0 0 10px 0; font-size: 36px; }
    .restaurant-header p { color: #666; font-size: 16px; }

    .filter-section { display: flex; justify-content: center; gap: 15px; margin-bottom: 40px; flex-wrap: wrap; }
    .filter-input { padding: 12px 20px; border: 1px solid #ccc; border-radius: 25px; font-size: 16px; width: 100%; max-width: 400px; outline: none; transition: 0.3s; }
    .filter-input:focus { border-color: #ff7900; box-shadow: 0 0 5px rgba(255,121,0,0.3); }
    .filter-select { padding: 12px 20px; border: 1px solid #ccc; border-radius: 25px; font-size: 16px; outline: none; cursor: pointer; background: white; }

    .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
    
    .menu-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; display: flex; flex-direction: column; padding: 25px; }
    .menu-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    
    .menu-category { color: #ff7900; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: block; }
    .menu-card h3 { margin: 0 0 10px 0; color: #14243d; font-size: 22px; }
    .menu-desc { color: #666; font-size: 14px; margin-bottom: 20px; flex-grow: 1; }
    
    .menu-meta { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 15px; margin-bottom: 20px; }
    .menu-price { font-size: 24px; font-weight: bold; color: #07559b; }
    
    .btn-order { display: block; text-align: center; background: #ff7900; color: white; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: 0.3s; border:none; cursor:pointer; width:100%; font-family:inherit; font-size:16px;}
    .btn-order:hover { background: #e66d00; }
    
    .empty { text-align: center; color: #888; padding: 40px; font-size: 18px; background: white; border-radius: 12px; grid-column: 1 / -1;}

    .order-form { display: flex; gap: 10px; margin-top: 10px; }
    .order-form input[type="number"] { width: 70px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; text-align: center; font-family: inherit; font-size: 16px; }

    .floating-cart {
        position: fixed;
        top: 100px;
        right: 40px;
        background: #14243d;
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: bold;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.2s;
        z-index: 999;
    }
    .floating-cart:hover { transform: scale(1.05); background: #2a436b; }
    .cart-badge { background: #ff7900; color: white; border-radius: 50%; padding: 4px 10px; font-size: 14px; }

    /* RECOMMENDATIONS STYLES */
    .rec-section { margin-top: 60px; }
    .rec-title { font-size: 28px; color: #14243d; margin-bottom: 20px; border-bottom: 2px solid #07559b; padding-bottom: 10px; display: inline-block; }
    .rec-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
    .rec-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; border-top: 4px solid #07559b; display: flex; flex-direction: column;}
    .rec-card h4 { margin: 0 0 10px 0; color: #14243d; font-size: 18px; }
    .rec-desc { color: #666; font-size: 13px; margin-bottom: 15px; flex-grow: 1;}
    .rec-price { font-weight: bold; color: #ff7900; font-size: 18px; margin-bottom: 15px;}
    .btn-rec-add { background: #14243d; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s;}
    .btn-rec-add:hover { background: #2a436b; }
</style>

<?php 
    $finalCartCount = 0;
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $q) { $finalCartCount += $q; }
    }
    if ($finalCartCount > 0): 
?>
    <a href="<?= base_url('orders') ?>" class="floating-cart">
        🛒 View Cart <span class="cart-badge"><?= $finalCartCount ?></span>
    </a>
<?php endif; ?>

<div class="restaurant-container">
    <div class="restaurant-header">
        <h1>LLV Bistro</h1>
        <p>Discover our food selection and enjoy your dining experience.</p>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div style="background:#d4edda; color:#155724; padding:15px; margin-bottom:20px; border-radius:5px; text-align:center;">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="filter-section">
        <input type="text" id="searchInput" class="filter-input" placeholder="Search food...">
        <select id="categoryFilter" class="filter-select">
            <option value="all">All Categories</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <?php $c = (array) $cat; ?>
                    <option value="<?= htmlspecialchars($c['category_name'] ?? '') ?>">
                        <?= htmlspecialchars($c['category_name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="menu-grid" id="menuGrid">
        <?php if (!empty($menu_items)): ?>
            <?php foreach ($menu_items as $item): ?>
                
                <?php 
                    $i = (array) $item;
                    $itemId = $i['id'] ?? 0;
                    $itemName = $i['item_name'] ?? 'Menu Item';
                    $itemDesc = $i['description'] ?? 'No description available.';
                    $itemPrice = $i['price'] ?? 0;
                    $itemCategory = $i['category_name'] ?? 'Uncategorized';
                ?>

                <div class="menu-card" data-name="<?= strtolower(htmlspecialchars($itemName)) ?>" data-category="<?= htmlspecialchars($itemCategory) ?>">
                    <span class="menu-category"><?= htmlspecialchars($itemCategory) ?></span>
                    <h3><?= htmlspecialchars($itemName) ?></h3>
                    <p class="menu-desc"><?= htmlspecialchars($itemDesc) ?></p>
                    
                    <div class="menu-meta">
                        <div class="menu-price">₱<?= number_format((float)$itemPrice, 2) ?></div>
                    </div>
                    
                    <form action="<?= base_url('restaurant/add_to_cart') ?>" method="POST" class="order-form">
                        <input type="hidden" name="item_id" value="<?= $itemId ?>">
                        <input type="number" name="quantity" value="1" min="1" required>
                        <button type="submit" class="btn-order">Add to Order</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">
                Our menu will be available here soon.
            </div>
        <?php endif; ?>
    </div>

    <!-- SMART RECOMMENDATIONS SECTION -->
    <?php if (!empty($recommendations)): ?>
        <div class="rec-section">
            <h2 class="rec-title">Recommended for You</h2>
            <div class="rec-grid">
                <?php foreach ($recommendations as $rec): ?>
                    <?php $r = (array) $rec; ?>
                    <div class="rec-card">
                        <h4><?= htmlspecialchars($r['item_name'] ?? 'Item') ?></h4>
                        <p class="rec-desc"><?= htmlspecialchars($r['description'] ?? '') ?></p>
                        <p class="rec-price">₱<?= number_format((float)($r['price'] ?? 0), 2) ?></p>
                        
                        <form action="<?= base_url('restaurant/add_to_cart') ?>" method="POST">
                            <input type="hidden" name="item_id" value="<?= $r['id'] ?? 0 ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn-rec-add">Quick Add</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const menuCards = document.querySelectorAll('.menu-card');

        function filterMenu() {
            const searchValue = searchInput.value.toLowerCase();
            const categoryValue = categoryFilter.value;

            menuCards.forEach(card => {
                const itemName = card.getAttribute('data-name');
                const itemCategory = card.getAttribute('data-category');

                const matchesSearch = itemName.includes(searchValue);
                const matchesCategory = (categoryValue === 'all' || itemCategory === categoryValue);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('keyup', filterMenu);
        categoryFilter.addEventListener('change', filterMenu);
    });
</script>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>