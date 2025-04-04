<h1>Upload file câu hỏi và câu trả lời</h1>
<form method="POST" enctype="multipart/form-data" action="/admin/uploadQuizzByFile/create">
    <div class="mb-3">
        <label for="File" class="form-label">File</label>
        <input type="file" class="form-control" id="File" name="File" required>
    </div>
    <button type="submit" class="btn btn-success">Tải lên</button>
</form>
