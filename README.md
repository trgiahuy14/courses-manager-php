<p align="center">
  <img src="./templates/assets/image/logo.png" alt="Courses Manager Logo" width="160">
</p>

<h1 align="center">Courses Manager</h1>

<p align="center">
  Dự án quản lý khóa học được xây dựng bằng PHP thuần.<br>
</p>

---

## 🧩 Giới thiệu

**Courses Manager** là một hệ thống quản lý khóa học được code bằng PHP thuần và MySQL (PDO).  
Dự án được phát triển với mục tiêu thực hành mô hình CRUD, cấu trúc module hoá.

---

## 🌟 Tính năng nổi bật

- 🔑 **Đăng nhập, đăng ký, quên mật khẩu, gửi mail xác nhận**
- 👤 **Quản lý người dùng:** xem danh sách tài khoản, thêm/sửa/xóa thông tin, tìm kiếm người dùng
- 📘 **Quản lý khóa học:** xem danh sách khóa học, thêm/sửa/xóa, tìm kiếm theo từ khóa hoặc lĩnh vực
- 🗂 **Quản lý danh mục:** xem danh sách lĩnh vực, thêm/sửa/xóa, tìm kiếm theo từ khóa
- 🎓 **Quản lý học viên:** xem danh sách học viên, tìm kiếm theo khóa học mà học viên tham gia
- 🖼 **Chỉnh sửa hồ sơ (profile):** thay đổi avatar, mật khẩu, tên, email, số điện thoại,...

---

## 🛠 Công nghệ sử dụng

| Thành phần        | Mô tả                         |
| ----------------- | ----------------------------- |
| **Ngôn ngữ**      | PHP 8.4.12                    |
| **Cơ sở dữ liệu** | MySQL (PDO)                   |
| **Giao diện**     | AdminLTE 4, Bootstrap 5       |
| **Icons**         | Font Awesome, Bootstrap Icons |
| **Xử lý session** | PHP session + token           |

---

## ⚙️ Cấu trúc thư mục

```bash
courses-manager-php/
│
├── includes/           # Các file helper, cấu hình PDO,...
├── modules/            # Chứa các module của website (users, course, category,...)
├── templates/          # Layouts (header, sidebar, footer) + assets
│   └── uploads/        # Thư mục chứa thumbnail khóa học, ảnh đại diện mà người dùng upload
├── index.php           # Router chính
├── config.php          # File cấu hình hệ thống
└── README.md
```
