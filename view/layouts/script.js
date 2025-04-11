const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessageButton = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = document.querySelector("#file-cancel");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");

// API setup
const API_KEY = "AIzaSyB1vzKdeNtRivvx9BI0uXQWemrdbNvE7A0"; // https://aistudio.google.com/apikey
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`;

const userData = {
    message: null,
    file: {
        data: null,
        mime_type: null
    }
};

// Khởi tạo chatHistory từ localStorage
let chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];

const initialInputHeight = messageInput.scrollHeight;

// Tạo phần tử tin nhắn với các lớp động
const createMessageElement = (content, ...classes) => {
    const div = document.createElement("div");
    div.classList.add("message", ...classes);
    div.innerHTML = content;
    return div;
};

// Khôi phục lịch sử trò chuyện khi tải trang
document.addEventListener("DOMContentLoaded", () => {
    if (chatHistory.length > 0) {
        chatHistory.forEach(chat => {
            const isUser = chat.role === "user";
            const messageContent = `<div class="message-text">${chat.parts[0].text}</div>`;
            const messageDiv = createMessageElement(messageContent, isUser ? "user-message" : "bot-message");
            chatBody.appendChild(messageDiv);
        });
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
    }
});

// Tạo phản hồi bot
const generateBotResponse = async (incomingMessageDiv) => {
    const messageElement = incomingMessageDiv.querySelector(".message-text");

    chatHistory.push({
        role: "user",
        parts: [{ text: userData.message }]
    });

    try {
        if (userData.message.toLowerCase().includes("tìm khoá học") || userData.message.toLowerCase().includes("course")) {
            const response = await fetch('http://localhost:8000/api/courses');
            const courses = await response.json();

            let responseText = `<div style="font-family: Arial, sans-serif;">
                <h3 style="color: #2c3e50;">📚 Các khóa học hiện có:</h3>`;

            for (const course of courses) {
                let sections = [];
                try {
                    const sectionRes = await fetch(`http://localhost:8000/api/sections?course_id=${course.id}`);
                    sections = await sectionRes.json();
                } catch (err) {
                    console.warn("Không thể lấy section cho course ID:", course.id);
                }

                // Lấy bài học trong từng section
                for (const section of sections) {
                    try {
                        const lessonRes = await fetch(`http://localhost:8000/api/lessons?section_id=${section.id}`);
                        const lessons = await lessonRes.json();
                        section.lessons = lessons;
                    } catch (err) {
                        section.lessons = [];
                        console.warn("Không thể lấy lessons cho section ID:", section.id);
                    }
                }

                responseText += `
                <div style="border: 1px solid #ddd; border-radius: 10px; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9;">
                    <h4 style="color: #2980b9; margin-bottom: 10px;">${course.title}</h4>
                    <p><strong>📂 Danh mục:</strong> ${course.category_name} | <strong>📁 Danh mục con:</strong> ${course.subcategory_name}</p>
                    <p><strong>👨‍🏫 Giảng viên:</strong> ${course.instructor_name}</p>
                    <p><strong>💰 Giá:</strong> ${course.discount_price ? course.discount_price : course.price} VNĐ</p>
                    <p><strong>⏱️ Thời lượng:</strong> ${course.duration} H</p>
                    ${course.image ? `<img src="${course.image}" alt="${course.title}" style="width: 120px; height: auto; border-radius: 8px; margin-top: 10px;">` : ''}

                    <div style="margin-top: 10px;">
                        <strong>📘 Nội dung khóa học:</strong>
                        ${sections.length > 0 ? `
                            <ul style="padding-left: 20px;">
                                ${sections.map(section => `
                                    <li>
                                        <strong>📁 ${section.title}</strong>
<ul>
                                            ${section.lessons && section.lessons.length > 0 
                                                ? section.lessons.map(lesson => `<li>📖 ${lesson.title}</li>`).join('')
                                                : '<li>Không có bài học.</li>'}
                                        </ul>
                                    </li>`).join('')}
                            </ul>` : `<p>❌ Chưa có nội dung.</p>`}
                    </div>

                    <a href="/courses/show/${course.id}" target="_blank" style="display: inline-block; margin-top: 10px; padding: 8px 12px; background-color:rgb(209, 32, 47); color: white; text-decoration: none; border-radius: 5px;">🔗 Xem khóa học</a>
                </div>`;
            }

            responseText += `</div>`;
            messageElement.innerHTML = responseText;

            chatHistory.push({
                role: "model",
                parts: [{ text: responseText }]
            });
        } else {
            // Các câu hỏi khác
            const requestOptions = {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ contents: chatHistory })
            };
            const response = await fetch(API_URL, requestOptions);
            const data = await response.json();
            if (!response.ok) throw new Error(data.error.message);

            const apiResponseText = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1").trim();
            messageElement.innerText = apiResponseText;

            chatHistory.push({
                role: "model",
                parts: [{ text: apiResponseText }]
            });
        }

        localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
    } catch (error) {
        messageElement.innerText = "Có lỗi xảy ra: " + error.message;
        messageElement.style.color = "#ff0000";
    } finally {
        userData.file = { data: null, mime_type: null };
        incomingMessageDiv.classList.remove("thinking");
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
    }
};

// Xử lý tin nhắn gửi đi
const handleOutgoingMessage = (e) => {
    e.preventDefault();
    userData.message = messageInput.value.trim();
    if (!userData.message) return; // Không gửi nếu tin nhắn rỗng
    messageInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    messageInput.dispatchEvent(new Event("input"));

    const messageContent = `<div class="message-text">${userData.message}</div>
                            ${userData.file.data ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />` : ""}`;
    const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
    chatBody.appendChild(outgoingMessageDiv);
    chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });

    setTimeout(() => {
        const botMessageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
                    <path d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"></path>
                </svg>
                <div class="message-text">
                    <div class="thinking-indicator">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>`;
        const incomingMessageDiv = createMessageElement(botMessageContent, "bot-message", "thinking");
        chatBody.appendChild(incomingMessageDiv);
        chatBody.scrollTo({ behavior: "smooth", top: chatBody.scrollHeight });
        generateBotResponse(incomingMessageDiv);
    }, 600);
};

// Xử lý sự kiện nhấn Enter
messageInput.addEventListener("keydown", (e) => {
    const userMessage = e.target.value.trim();
    if (e.key === "Enter" && userMessage && !e.shiftKey && window.innerWidth > 768) {
        handleOutgoingMessage(e);
    }
});

// Điều chỉnh chiều cao input
messageInput.addEventListener("input", () => {
    messageInput.style.height = `${initialInputHeight}px`;
    messageInput.style.height = `${messageInput.scrollHeight}px`;
    document.querySelector(".chat-form").style.borderRadius = messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
});

// Xử lý tải file
fileInput.addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!validImageTypes.includes(file.type)) {
        await Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WEBP)',
            confirmButtonText: 'OK'
        });
        resetFileInput();
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => {
        fileUploadWrapper.querySelector("img").src = e.target.result;
        fileUploadWrapper.classList.add("file-uploaded");
        const base64String = e.target.result.split(",")[1];
        userData.file = {
            data: base64String,
            mime_type: file.type
        };
        fileInput.value = "";
    };
    reader.readAsDataURL(file);
});

// Hủy tải file
fileCancelButton.addEventListener("click", () => {
    resetFileInput();
});

// Reset file input
function resetFileInput() {
    fileInput.value = "";
    fileUploadWrapper.classList.remove("file-uploaded");
    fileUploadWrapper.querySelector("img").src = "#";
    userData.file = { data: null, mime_type: null };
    document.querySelector(".chat-form").reset();
}

// Thêm Emoji Picker
const picker = new EmojiMart.Picker({
    theme: "light",
    showSkinTones: "none",
    previewPosition: "none",
    onEmojiSelect: (emoji) => {
        const { selectionStart: start, selectionEnd: end } = messageInput;
        messageInput.setRangeText(emoji.native, start, end, "end");
        messageInput.focus();
    },
    onClickOutside: (e) => {
        if (e.target.id === "emoji-picker") {
            document.body.classList.toggle("show-emoji-picker");
        } else {
            document.body.classList.remove("show-emoji-picker");
        }
    },
});
document.querySelector(".chat-form").appendChild(picker);

// Gắn sự kiện
sendMessageButton.addEventListener("click", handleOutgoingMessage);
document.querySelector("#file-upload").addEventListener("click", () => fileInput.click());
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
closeChatbot.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
 
 
 