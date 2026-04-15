# Hướng dẫn sử dụng Plugin: Part Pages

Chào mừng bạn đến với **Part Pages**! Đây là công cụ giúp bạn tự tay tạo ra một trang đích (Landing Page) tuyệt đẹp cho các sự kiện, ra mắt sản phẩm hay chiến dịch quảng cáo... mà **không cần biết lập trình**.

Plugin này chia trang web của bạn thành các "khối" (được gọi là Session). Bạn chỉ việc bật/tắt, sắp xếp thứ tự và điền chữ/hình ảnh vào là xong!

---

## 🛠 1. Chuẩn bị trước khi dùng

1. **Cài đặt Plugin:** Bạn nén thư mục `part-pages` thành file ZIP, sau đó vào `Plugins > Add New > Upload Plugin` trong WordPress để tải lên và kích hoạt. Hoặc sao chép thư mục này thẳng vào thư mục `wp-content/plugins/` trên host.
2. **Cài đặt Contact Form 7:** Hãy chắc chắn bạn đã cài đặt và kích hoạt plugin **Contact Form 7** (miễn phí ở kho ứng dụng WordPress). Đây là công cụ giúp bạn tạo form thu thập thông tin khách hàng, Part Pages sẽ dùng nó để hiển thị form tự động.

---

## 🚀 2. Tạo Trang Landing Page Đầu Tiên

Dưới đây là 3 bước đơn giản nhất để tạo trang:

### Bước 1: Tạo một trang mới
- Trong bảng điều khiển WordPress (Dashboard), bạn nhấp vào **Pages (Trang)** > **Add New (Thêm trang mới)**.
- Đặt tiêu đề cho trang (Ví dụ: *Sự Kiện Ra Mắt 2026*).

### Bước 2: Chọn giao diện (Template) Part Pages
- Nhìn sang thanh công cụ bên tay phải màn hình, bạn tìm hộp có tên là **Page Attributes (Thuộc tính trang)**.
- Ở mục **Template (Giao diện)**, bạn bấm vào và chọn giao diện có tên: `Part Page – Event / Launch`.

### Bước 3: Điền nội dung theo dạng "lắp ghép"
- Ngay sau khi chọn template ở bước 2, bạn kéo chuột xuống phía dưới khu vực gõ văn bản thông thường. Bạn sẽ thấy một bảng điều khiển lớn tên là **Part Page Sessions**.
- Bảng này chứa danh sách 7 "khối" nội dung sẵn có. Điểm đặc biệt là:
  - 👆 **Nhấp vào tiêu đề:** Một khung điền thông tin sẽ mở ra để bạn tải ảnh lên, gõ chữ, chọn màu...
  - 🔄 **Kéo thả để sắp xếp:** Nắm chuột vào biểu tượng "⠿" ở đầu mỗi dòng, bạn có thể kéo khối đó lên trên hoặc xuống dưới. Khối nào nằm trên cùng sẽ hiển thị đầu trang!
  - ✅ **Bật / Tắt:** Bạn không thích khối nào? Chỉ việc nhấn vào nút dấu tick (✔) để tắt khối đó lặn mất tiêu mà không sợ mất dữ liệu.

---

## 🎨 3. Các Khối Nội Dung (Sessions) Khám Phá

Part Pages cung cấp 7 khối nội dung được thiết kế sẵn, thay vì phải tự thiết kế phức tạp, bạn chỉ cần điền text vào:

1. 🖼 **Hero (Banner Khởi Đầu):** Khối nằm trên cùng, thường chứa logo nhỏ và một câu khẩu hiệu thật to, bắt mắt trên nền màu tùy chọn tĩnh.
2. 🎬 **Video Full-width:** Một trình phát video hiển thị tràn màn hình (Hỗ trợ Youtube, Vimeo, file mp4). Nó có thể tự động chạy khi người xem lướt tới.
3. 📅 **Event Card (Thẻ Sự Kiện):** Một khối được chia làm hai cột rất đẹp: 1 bên để ảnh sự kiện, 1 bên chứa nút Đăng ký, thông tin ngày giờ, địa điểm... và phần diễn giải "Về chúng tôi" (About). Nút ở đây có thể bấm là tự cuộn tuột xuống Form đăng ký!
4. 🗂 **Tabbed Content (Nội dung dạng Tab):** Tạo ra các thẻ bấm qua lại. Trông rất chuyên nghiệp nếu bạn muốn liệt kê ưu điểm công nghệ, nội dung chương trình theo ngày... Có nút "Thêm Tab" thoả thích.
5. 📝 **Text Section (Đoạn Văn Bản):** Khối dùng để trình bày các đoạn văn bản dài, tâm thư, lời giới thiệu. Có tuỳ chọn căn lề, loại font và độ rộng màn hình.
6. 📢 **CTA Banner (Banner Kêu gọi):** Khối chèn một ảnh nền và dán 1 dòng chữ kêu gọi mua hàng / đăng ký đè lên trên, thu hút ánh nhìn bằng nút bấm nổi.
7. 📋 **Contact Form 7 (Biểu mẫu đăng ký):** Khối chèn Form nhập liệu ở cuối trang (chứa trường Họ, Tên, Số Điện thoại...) để lưu lại thông tin khách.

---

## 💡 4. Mẹo Vặt Vô Cùng Hữu Ích

**Làm sao để có Form thu thập thông tin chuẩn, lười không muốn tự tạo?**
1. Nhìn sang cột Menu màu đen ngoài cùng bên tay trái của WordPress. Bấm vào chữ **Part Pages**.
2. Ở đây có mục **🔌 Contact Form 7**, bạn chỉ cần bấm nút **✨ Tạo Form Mẫu (Device Contact Form)** màu xanh.
3. Hệ thống sẽ tự động tạo sẵn cho bạn một biểu mẫu đăng ký chuyên nghiệp 100% chuẩn layout.
4. Trở lại trang đang sửa dở, ở khối **📋 Contact Form 7** phần dưới cùng, mục "CF7 Form" bạn chọn tên form vừa được tạo ra là giao diện sẽ hiện form đẹp mắt!

---

*Trường hợp thắc mắc hoặc cần chèn thêm "khối" riêng biệt cho công ty, vui lòng cung cấp tài liệu này cho bạn lập trình viên Web của bạn để họ hỗ trợ dùng tính năng (Filters `pp_registered_sessions`)*.