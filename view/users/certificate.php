
<main class="ml-24 pt-20 px-4">
    <div class="certificate">
        <div class="header">
            <h1>P6</h1>
            <h2>GIẤY CHỨNG NHẬN HOÀN THÀNH</h2>
        </div>
        <div class="course-info">
            <h3><?php echo $certificate['title']; ?></h3>
            <p>Giảng viên: <?php echo $certificate['instructor_name']; ?> </p>
        </div>
        <div class="recipient">
            <p>Người nhận chứng chỉ: </p>
            <h2><?php echo $certificate['user_name']; ?></h2>
        </div>
        <div class="details">
            <p>Ngày: <?= $certificate['issued_at']; ?></p>
        </div>
        <p class="description">
        Chứng chỉ trên xác nhận rằng <?php echo $certificate['user_name']; ?> đã hoàn thành thành công khóa học
<?php echo $certificate['title']; ?>  vào ngày <?php echo $certificate['issued_at']; ?> do
<?php echo $certificate['instructor_name']; ?> giảng dạy trên F6. Chứng chỉ cho biết toàn bộ khóa học đã
hoàn thành theo xác nhận của học viên..
        </p>
        <div class="footer">
            <a href="#" class="btn">Download</a>
            <a href="#" class="btn">Share</a>
        </div>
    </div>
</main>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.certificate {
    background-color: white;
    border-radius: 10px;
    width: 800px;
    padding: 20px;
    margin: 50px auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h1 {
    font-size: 40px;
    color:rgb(212, 56, 28); /* Color of Udemy logo */
    font-weight: bold;
}

.header h2 {
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.course-info {
    text-align: center;
    margin-bottom: 30px;
}

.course-info h3 {
    font-size: 28px;
    font-weight: bold;
}

.recipient {
    text-align: center;
    margin-bottom: 20px;
}

.recipient p {
    font-size: 18px;
}

.recipient h2 {
    font-size: 32px;
    font-weight: bold;
}

.details {
    text-align: center;
    margin-bottom: 20px;
}

.details p {
    font-size: 16px;
}

.description {
    font-size: 14px;
    text-align: center;
    margin-bottom: 30px;
}

.footer {
    display: flex;
    justify-content: center;
}

.btn {
    background-color:rgb(218, 110, 22); /* Color of Udemy button */
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    margin: 0 10px;
    font-weight: bold;
}

.btn:hover {
    background-color:rgb(248, 177, 110);
}
</style>