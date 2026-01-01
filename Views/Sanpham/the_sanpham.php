<?php
// Views/Sanpham/the_sanpham.
?>
<div class="product-card">
    <a href="/Web_DoHocTap/Views/Sanpham/chitiet.php?id=<?php echo $sp['MaSP']; ?>" style="text-decoration: none; color: inherit;">
        <img src="assets/images/Sanpham/<?php echo $sp['HinhAnh']; ?>" class="product-img">
        <div class="product-name"><?php echo $sp['TenSP']; ?></div>

        <div style="color: #FFD700; font-size: 12px; margin-bottom: 5px;">
            <?php
            // Đảm bảo biến $dgModel đã được khởi tạo ở index.php
            $sao = $dgModel->tinh_sao_trung_binh($sp['MaSP']);
            echo str_repeat('★', floor($sao)) . str_repeat('☆', 5 - floor($sao));
            ?>
        </div>

        <div class="product-price">
            <span class="current-price"><?php echo number_format($sp['Gia'], 0, ',', '.'); ?>đ</span>
        </div>
    </a>
    <form action="/Web_DoHocTap/controller/GiohangController.php" method="POST" class="add-to-cart-quick">
        <input type="hidden" name="maSP" value="<?php echo $sp['MaSP']; ?>">
        <input type="hidden" name="sl" value="1">
        <input type="hidden" name="ajax" value="1">
        <button type="submit" class="btn-buy-now">MUA</button>
    </form>
</div>