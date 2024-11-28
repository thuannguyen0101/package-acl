# Workable Translate Package

## Giới thiệu

Translate Package

- Lệnh Artisan hỗ trợ dịch các tệp ngôn ngữ sang nhiều ngôn ngữ khác nhau, lưu vào cơ sở dữ liệu, và cung cấp tùy chọn
  kiểm tra hoặc cập nhật bản dịch.
- Cung cấp hàm __trans để lấy bản dịch trong mã nguồn của bạn một cách nhanh chóng.

## Cách cài đặt

```bash
  # 1. install
  composer require workable/translate
  # 2. run migrate
  php artisan migrate
  3
```

## Cách sử dụng

Cú pháp lệnh:

```bash
  php artisan language:translate {--path=} {--languages=} {--update}
```

### Tùy chọn:

- --path=
    - Mô tả: Đường dẫn đến tệp nguồn cần dịch. Nếu không cung cấp, sẽ sử dụng giá trị từ cấu hình
      translate.languages.translation_files.
    - Ví dụ: --path=resources/lang/en/messages.php.
- --languages=
    - Mô tả: Danh sách ngôn ngữ đích, phân cách bằng dấu phẩy. Nếu không cung cấp, sẽ sử dụng các ngôn ngữ mặc định từ
      cấu hình translate.languages.target_languages.
    - Ví dụ: --languages=vi,es.
- --update
    - Mô tả: Nếu tùy chọn này được chỉ định, lệnh sẽ sử dụng phương thức updateOrCreate. Nếu không có tùy chọn này, kiểm
      tra trước khi thêm và báo lỗi nếu key và
      language_code đã tồn tại.

### Cấu hình

Các giá trị mặc định được lấy từ tệp cấu hình. Bạn có thể tùy chỉnh các giá trị này trong tệp config/translate.php:

- languages.translation_files: Danh sách tệp nguồn mặc định để dịch.
- languages.target_languages: Các ngôn ngữ đích mặc định.
- languages.source_language: Ngôn ngữ nguồn để dịch.

```bash
  <?php
  return [
      'source_language'  => 'en',
      'target_languages' => ['vi', 'es'],
      'translation_files' => [
          __DIR__ . '/alert.php',
          __DIR__ . '/permissions.php',
          __DIR__ . '/private.php',
      ],
  ];
```

#
