function initVideoTracking(video, userId, lessonId) {
    video.addEventListener('timeupdate', function() {
        const progress = (video.currentTime / video.duration) * 100;
        document.getElementById('progress').textContent = Math.round(progress) + '%';

        fetch('/update_progress.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: userId,
                lesson_id: lessonId,
                progress: progress,
                completed: progress >= 95
            })
        });
    });
}