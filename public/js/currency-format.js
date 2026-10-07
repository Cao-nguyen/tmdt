/**
 * Currency Format Utilities for VND
 * Dùng để format và parse giá tiền VNĐ với dấu chấm phân cách hàng nghìn
 */

/**
 * Format số thành chuỗi VNĐ với dấu chấm phân cách hàng nghìn
 * @param {number|string} number - Số cần format
 * @returns {string} - Chuỗi đã format, ví dụ: "450.000"
 */
function formatVND(number) {
    // Ép kiểu về number và làm tròn bỏ phần thập phân
    const num = Math.round(parseFloat(number) || 0);
    return num.toLocaleString('vi-VN', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
}

/**
 * Parse chuỗi đã format về số gốc
 * @param {string} formattedString - Chuỗi đã format, ví dụ: "450.000"
 * @returns {number} - Số gốc, ví dụ: 450000
 */
function parseVND(formattedString) {
    // Xóa tất cả dấu chấm và khoảng trắng, ép về number
    const cleaned = formattedString.replace(/[.\s]/g, '');
    return parseInt(cleaned) || 0;
}

/**
 * Setup input để format real-time khi gõ giá tiền VNĐ
 * @param {HTMLInputElement} input - Ô input cần setup
 * @param {boolean} addSuffix - Có thêm "VNĐ" ở cuối khi blur không (mặc định: true)
 */
function setupCurrencyInput(input, addSuffix = true) {
    if (!input) return;

    // Chỉ cho phép nhập số
    input.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        
        if (value) {
            // Lưu vị trí con trỏ trước khi format
            const cursorPos = this.selectionStart;
            const originalLength = this.value.length;
            
            // Format với dấu chấm
            const num = parseInt(value);
            this.value = num.toLocaleString('vi-VN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            
            // Tính vị trí con trỏ mới (dựa trên sự thay đổi độ dài)
            const newLength = this.value.length;
            const diff = newLength - originalLength;
            this.setSelectionRange(cursorPos + diff, cursorPos + diff);
            
            // Lưu giá trị gốc để submit
            this.dataset.rawValue = num.toString();
        } else {
            this.value = '';
            this.dataset.rawValue = '';
        }
    });

    // Khi blur, thêm VNĐ vào cuối
    if (addSuffix) {
        input.addEventListener('blur', function() {
            if (this.dataset.rawValue) {
                const num = parseInt(this.dataset.rawValue);
                this.value = formatVND(num) + ' VNĐ';
            }
        });

        // Khi focus, bỏ VNĐ để chỉnh sửa
        input.addEventListener('focus', function() {
            if (this.dataset.rawValue) {
                this.value = formatVND(this.dataset.rawValue);
            }
        });
    }
}

/**
 * Load giá trị vào input (xử lý đúng giá trị có phần thập phân từ DB)
 * @param {HTMLInputElement} input - Ô input cần load giá trị
 * @param {number|string} rawValue - Giá trị gốc từ database
 * @param {boolean} addSuffix - Có thêm "VNĐ" ở cuối không (mặc định: true)
 */
function loadCurrencyInput(input, rawValue, addSuffix = true) {
    if (!input) return;
    
    if (rawValue) {
        // Ép kiểu về number, làm tròn, rồi format
        const num = Math.round(parseFloat(rawValue) || 0);
        input.dataset.rawValue = num.toString();
        input.value = addSuffix ? formatVND(num) + ' VNĐ' : formatVND(num);
    } else {
        input.dataset.rawValue = '';
        input.value = '';
    }
}

// Export để dùng trong module ES6 (nếu cần)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        formatVND,
        parseVND,
        setupCurrencyInput,
        loadCurrencyInput
    };
}
