<?php
// Views/Sanpham/the_sanpham.
?>
<div class="product-card">
    <a href="/Web_DoHocTap/Views/Sanpham/chitiet.php?id=<?php echo $sp['MaSP']; ?>" style="text-decoration: none; color: inherit;">
        <img src="/Web_DoHocTap/assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>" class="product-img">
        <div class="product-name"><?php echo $sp['TenSP']; ?></div>
        <div style="color: #FFD700; font-size: 12px; margin-bottom: 5px;">
            <?php
            $sao = $dgModel->tinh_sao_trung_binh($sp['MaSP']);
            echo str_repeat('★', floor($sao)) . str_repeat('☆', 5 - floor($sao));
            ?>
        </div>
        <div class="product-price">
            <?php if (isset($sp['GiaKM']) && $sp['GiaKM'] < $sp['Gia']): ?>
                <span style="text-decoration: line-through; color: #999; font-size: 13px; margin-right: 5px;">
                    <?php echo number_format($sp['Gia'], 0, ',', '.'); ?>đ
                </span>
                <span class="current-price" style="color: #d32f2f; font-weight: bold;">
                    <?php echo number_format($sp['GiaKM'], 0, ',', '.'); ?>đ
                </span>
            <?php else: ?>
                <span class="current-price"><?php echo number_format($sp['Gia'], 0, ',', '.'); ?>đ</span>
            <?php endif; ?>
        </div>
    </a>
    <form action="/Web_DoHocTap/controller/GiohangController.php" method="POST" class="add-to-cart-quick">
        <input type="hidden" name="maSP" value="<?php echo $sp['MaSP']; ?>">
        <input type="hidden" name="sl" value="1">
        <input type="hidden" name="ajax" value="1">
        <button type="submit" class="btn-buy-now">MUA</button>
    </form>
</div>