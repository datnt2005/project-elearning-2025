php -S localhost:8000
# duAnBanKhoaHoc
- lenh cai dat
composer install
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require phpoffice/phpspreadsheet:^1.23
composer require vlucas/phpdotenv
composer require google/apiclient:^2.15


---everyone---
1: Trước khi bắt đầu "LUÔN LUÔN CHECK GIT XEM CÓ CODE MỚI KHÔNG. LUÔN LUÔN PULL CODE MỚI TRƯỚC KHI BẮT ĐẦU CODE".

2: Sau khi pull code mới về thì 
    - Bước 1: cd vào folder cần xử lý 
    - Bước 2: chạy php -S localhost:8000
    - Bước 3: copy env trên Group của Tiến Đạt 
    - Bước 4: Tiến hành code

3: Trước khi code thì đọc kỹ mục 1 và 2 và check requirement của leader

4: Xử lý Conflict trong team

5: Yêu cầu họp team
    - Không được tự ý xử lý conflict một mình
    - Việc Merge code:      
        + TRƯỚC KHI TẮT MÁY GIT COMMIT GIT PUSH lên local của mình
        + LUÔN MERGE VÀO NHÁNH VÀO CỦA MÌNH TRƯỚC, RỒI MỚI ĐƯỢC MERGE VÀO NHANH DEV
        + LUÔN LUÔN MERGE VÀO NHÁNH "dev"
        + NHÁNH MAIN CHỈ CÓ LEADER CHECK ổn rồi mới được merge
        + Nếu đọc requirement không hiểu thì liên hệ leader để đc hỗ trợ

6: Đọc thật kỹ và check requirment trước khi yêu cầu leader check

7: Mỗi ngày kết thúc việc code phải báo cáo trong daily stand up bao gồm task của mình progress, effort bao nhiêu giờ, issue(vấn đề cần hỗ trợ) leader sẽ check hằng ngày để đảm bảo tiến độ của dự án.

Chúc các bạn làm việc thật hiệu quả. Passion💕 .
// thư viện đọc file pdf
composer require smalot/pdfparser
