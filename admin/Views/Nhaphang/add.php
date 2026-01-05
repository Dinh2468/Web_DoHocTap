<?php
// admin/Views/Nhaphang/add.php
include_once '../../includes/header.php';
$db = new Db();
$suppliers = $db->query("SELECT * FROM nhacungcap")->fetchAll();
$products = $db->query("SELECT * FROM sanpham")->fetchAll();
?>
<div class="main-content-inner">
    <header class="main-header">
        <h2>Tạo phiếu nhập kho</h2>
    </header>
    <div class="form-container">
        <form action="../../controller/AdminNhaphangController.php?action=create" method="POST">
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Nhà cung cấp</label>
                    <select name="maNCC" id="selectNCC" class="form-control" required onchange="updateNCCInfo()">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($suppliers as $ncc): ?>
                            <option value="<?php echo $ncc['MaNCC']; ?>"
                                data-address="<?php echo htmlspecialchars($ncc['DiaChi']); ?>"
                                data-phone="<?php echo $ncc['SDT']; ?>"
                                data-email="<?php echo $ncc['Email']; ?>">
                                <?php echo $ncc['TenNCC']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Ngày nhập</label>
                    <input type="date" name="ngayNhap" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            <div id="nccDetailBox" style="display: none; background: #f8fdf9; border: 1px dashed #2E7D32; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <p><strong>📍 Địa chỉ:</strong> <span id="displayAddress">N/A</span></p>
                    <p><strong>📞 SĐT:</strong> <span id="displayPhone">N/A</span></p>
                    <p><strong>✉️ Email:</strong> <span id="displayEmail">N/A</span></p>
                </div>
            </div>
            <h3 style="margin: 20px 0;">Danh sách sản phẩm nhập</h3>
            <table id="importTable" class="table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Sản phẩm</th>
                        <th style="width: 15%;">Số lượng</th>
                        <th style="width: 20%;">Giá nhập</th>
                        <th style="width: 20%;">Thành tiền</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody id="importBody">
                    <tr>
                        <td>
                            <select name="products[]" class="form-control" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                <?php foreach ($products as $sp): ?>
                                    <option value="<?php echo $sp['MaSP']; ?>"><?php echo $sp['TenSP']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="number" name="quantities[]" class="form-control qty" min="1" value="1" oninput="calculateTotal()"></td>
                        <td><input type="number" name="prices[]" class="form-control price" min="0" placeholder="0" oninput="calculateTotal()"></td>
                        <td class="subtotal" style="font-weight: bold; padding-top: 20px;">0đ</td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; padding-top: 20px;">TỔNG CỘNG:</td>
                        <td id="grandTotal" style="font-weight: bold; color: var(--primary-color); font-size: 18px; padding-top: 20px;">0đ</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div style="margin-top: 15px; display: flex; justify-content: space-between;">
                <button type="button" class="btn-clear" onclick="addRow()" style="background: #e3f2fd; color: #1976d2; border: 1px solid #bbdefb;">+ Thêm dòng sản phẩm</button>
                <button type="submit" class="btn-save">XÁC NHẬN NHẬP KHO</button>
            </div>
        </form>
    </div>
</div>
<script>
    function calculateRow(input) {
        const row = input.closest('tr');
        const qty = row.querySelector('input[name="quantities[]"]').value;
        const price = row.querySelector('input[name="prices[]"]').value;
        const subtotal = row.querySelector('.subtotal');
        const total = qty * price;
        subtotal.innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }
    const productOptions = `<?php foreach ($products as $sp): ?>
    <option value="<?php echo $sp['MaSP']; ?>"><?php echo htmlspecialchars($sp['TenSP']); ?></option>
<?php endforeach; ?>`;

    function addRow() {
        const tbody = document.getElementById('importBody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
        <td>
            <select name="products[]" class="form-control" required>
                <option value="">-- Chọn sản phẩm --</option>
                ${productOptions}
            </select>
        </td>
        <td><input type="number" name="quantities[]" class="form-control qty" min="1" value="1" oninput="calculateTotal()"></td>
        <td><input type="number" name="prices[]" class="form-control price" min="0" placeholder="0" oninput="calculateTotal()"></td>
        <td class="subtotal" style="font-weight: bold; padding-top: 20px;">0đ</td>
        <td><button type="button" onclick="removeRow(this)" style="color: #d32f2f; border: none; background: none; cursor: pointer; font-size: 20px;">&times;</button></td>
    `;
        tbody.appendChild(newRow);
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        calculateTotal();
    }

    function calculateTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('#importBody tr');
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const subtotal = qty * price;
            row.querySelector('.subtotal').innerText = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
            grandTotal += subtotal;
        });
        document.getElementById('grandTotal').innerText = new Intl.NumberFormat('vi-VN').format(grandTotal) + 'đ';
    }

    function updateNCCInfo() {
        const select = document.getElementById('selectNCC');
        const selectedOption = select.options[select.selectedIndex];
        const infoBox = document.getElementById('nccDetailBox');
        if (select.value === "") {
            infoBox.style.display = 'none';
            return;
        }
        const address = selectedOption.getAttribute('data-address');
        const phone = selectedOption.getAttribute('data-phone');
        const email = selectedOption.getAttribute('data-email');
        document.getElementById('displayAddress').innerText = address || "Chưa cập nhật";
        document.getElementById('displayPhone').innerText = phone || "Chưa cập nhật";
        document.getElementById('displayEmail').innerText = email || "Chưa cập nhật";
        infoBox.style.display = 'block';
    }
    document.addEventListener('DOMContentLoaded', function() {
        const storageKey = 'import_form_draft';
        const form = document.querySelector('form');
        const savedData = JSON.parse(localStorage.getItem(storageKey));
        if (savedData) {
            document.getElementById('selectNCC').value = savedData.maNCC || '';
            document.querySelector('input[name="ngayNhap"]').value = savedData.ngayNhap || '<?php echo date("Y-m-d"); ?>';
            updateNCCInfo();
            if (savedData.items && savedData.items.length > 0) {
                const tbody = document.getElementById('importBody');
                tbody.innerHTML = '';
                savedData.items.forEach((item, index) => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                    <td>
                        <select name="products[]" class="form-control" required>
                            <option value="">-- Chọn sản phẩm --</option>
                            ${productOptions}
                        </select>
                    </td>
                    <td><input type="number" name="quantities[]" class="form-control qty" min="1" value="${item.qty}" oninput="calculateTotal()"></td>
                    <td><input type="number" name="prices[]" class="form-control price" min="0" value="${item.price}" oninput="calculateTotal()"></td>
                    <td class="subtotal" style="font-weight: bold; padding-top: 20px;">0đ</td>
                    <td>${index > 0 ? `<button type="button" onclick="removeRow(this)" style="color: #d32f2f; border: none; background: none; cursor: pointer; font-size: 20px;">&times;</button>` : ''}</td>
                `;
                    tbody.appendChild(newRow);
                    newRow.querySelector('select').value = item.id;
                });
                calculateTotal();
            }
        } -
        form.addEventListener('input', function() {
            const items = [];
            document.querySelectorAll('#importBody tr').forEach(row => {
                items.push({
                    id: row.querySelector('select').value,
                    qty: row.querySelector('.qty').value,
                    price: row.querySelector('.price').value
                });
            });
            const draft = {
                maNCC: document.getElementById('selectNCC').value,
                ngayNhap: document.querySelector('input[name="ngayNhap"]').value,
                items: items
            };
            localStorage.setItem(storageKey, JSON.stringify(draft));
        });
        form.addEventListener('submit', () => localStorage.removeItem(storageKey));
        const btnClear = document.querySelector('.btn-clear-all');
        if (btnClear) btnClear.addEventListener('click', () => {
            if (confirm("Xóa toàn bộ nội dung đang nhập?")) {
                localStorage.removeItem(storageKey);
                location.reload();
            }
        });
    });
</script>