@extends('layout')

@section('title')
ChatBot AI - Trợ lý thông minh
@endsection

@section('content')
<div class="container chatbot-container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="m-0"><i class="fas fa-robot me-2"></i> ChatBot AI - Trợ giúp thông minh</h5>
                    @if($chatHistory->count() > 0)
                    <form action="{{ route('chatbot.clear') }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa lịch sử chat?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-trash-alt"></i> Xóa lịch sử
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body chat-area" id="chat-area">
                    <div class="welcome-message text-center mb-4" id="welcome-message">
                        <div class="bot-avatar mb-3">
                            <i class="fas fa-robot fa-3x text-primary"></i>
                        </div>
                        <h4>Xin chào! Tôi là ChatBot AI</h4>
                        <p class="text-muted">Tôi có thể giúp bạn tìm phim, giới thiệu phim mới, hoặc trả lời các câu
                            hỏi về phim ảnh.</p>
                        <div class="suggested-questions mt-4">
                            <p>Một số câu hỏi gợi ý:</p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button class="btn btn-outline-primary btn-sm question-suggestion">Phim hành động hay
                                    nhất?</button>
                                <button class="btn btn-outline-primary btn-sm question-suggestion">Có phim mới nào
                                    không?</button>
                                <button class="btn btn-outline-primary btn-sm question-suggestion">Phim Marvel nào hay
                                    nhất?</button>
                            </div>
                        </div>
                    </div>

                    <div class="chat-messages" id="chat-messages">
                        @foreach($chatHistory as $chat)
                        <div class="message user-message">
                            <div class="message-content">
                                <p>{{ $chat->user_message }}</p>
                            </div>
                            <div class="avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="message bot-message">
                            <div class="avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="message-content">
                                <p>{!! nl2br(e($chat->bot_response)) !!}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer p-2">
                    <form id="chat-form" class="d-flex">
                        @csrf
                        <input type="text" class="form-control" id="user-message" placeholder="Hỏi điều gì đó..."
                            required>
                        <button type="submit" class="btn btn-primary ms-2" id="send-message">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS dành cho ChatBot -->
<style>
    .chatbot-container {
        min-height: 70vh;
    }

    .chat-area {
        height: 60vh;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 5px;
        scrollbar-width: thin;
    }

    .chat-messages {
        display: flex;
        flex-direction: column;
    }

    .message {
        display: flex;
        margin-bottom: 20px;
        max-width: 80%;
    }

    .user-message {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .bot-message {
        align-self: flex-start;
    }

    .message-content {
        padding: 10px 15px;
        border-radius: 15px;
    }

    .user-message .message-content {
        background-color: #007bff;
        color: white;
        border-top-right-radius: 5px;
        margin-right: 10px;
    }

    .bot-message .message-content {
        background-color: #e9ecef;
        color: #343a40;
        border-top-left-radius: 5px;
        margin-left: 10px;
    }

    .avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e6e6e6;
    }

    .user-message .avatar {
        background-color: #cfe2ff;
    }

    .bot-message .avatar {
        background-color: #d1e7dd;
    }

    .message p {
        margin: 0;
    }

    .typing {
        display: flex;
        align-items: center;
        margin-left: 45px;
        margin-top: -10px;
        margin-bottom: 15px;
    }

    .typing span {
        height: 8px;
        width: 8px;
        background: #777;
        display: block;
        border-radius: 50%;
        margin-right: 5px;
        animation: typing 1s infinite;
    }

    @keyframes typing {

        0%,
        100% {
            opacity: 0.3;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.1);
        }
    }

    .typing span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing span:nth-child(3) {
        animation-delay: 0.4s;
    }

    .bot-avatar {
        width: 70px;
        height: 70px;
        margin: 0 auto;
        border-radius: 50%;
        background-color: #e6f7ff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .message.bot-message.error .message-content {
        background-color: #fff0f0;
        border-left: 3px solid #dc3545;
    }

    .message.bot-message.error .avatar {
        background-color: #ffebee;
        color: #dc3545;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Cải thiện giao diện tin nhắn */
    .message-content p {
        margin: 0;
        word-break: break-word;
    }

    .message-content {
        line-height: 1.5;
    }
</style>

<!-- JavaScript dành cho ChatBot -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatForm = document.getElementById('chat-form');
        const userMessageInput = document.getElementById('user-message');
        const chatArea = document.getElementById('chat-area');
        const chatMessages = document.getElementById('chat-messages');
        const welcomeMessage = document.getElementById('welcome-message');
        
        // Cuộn xuống cuối khi tải trang
        if (chatMessages.childElementCount > 0) {
            welcomeMessage.style.display = 'none';
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        
        // Xử lý submit form
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const userMessage = userMessageInput.value.trim();
            if (!userMessage) return;
            
            // Ẩn welcome message nếu có
            welcomeMessage.style.display = 'none';
            
            // Hiển thị tin nhắn người dùng
            addUserMessage(userMessage);
            
            // Hiển thị hiệu ứng typing
            const typingElement = document.createElement('div');
            typingElement.className = 'typing';
            typingElement.id = 'typing-indicator';
            typingElement.innerHTML = '<span></span><span></span><span></span>';
            chatMessages.appendChild(typingElement);
            chatArea.scrollTop = chatArea.scrollHeight;
            
            // Clear input và disable
            userMessageInput.value = '';
            userMessageInput.disabled = true;
            
            // Disable nút gửi
            const sendButton = document.getElementById('send-message');
            sendButton.disabled = true;
            sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            // Thiết lập timeout trong trường hợp API không phản hồi
            const timeoutId = setTimeout(() => {
                removeTypingIndicator();
                addBotMessage('Phản hồi mất quá nhiều thời gian. API có thể đang bận, vui lòng thử lại sau.');
                enableForm();
            }, 30000); // 30 giây timeout
            
            // Gửi API request
            fetch('{{ route('chatbot.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: userMessage
                })
            })
            .then(response => {
                clearTimeout(timeoutId);
                
                if (!response.ok) {
                    if (response.status === 429) {
                        throw new Error('Quá nhiều yêu cầu. Vui lòng đợi một lát trước khi thử lại.');
                    } else if (response.status === 500) {
                        throw new Error('Lỗi máy chủ. Vui lòng thử lại sau.');
                    } else if (response.status === 401) {
                        throw new Error('Lỗi xác thực API. Vui lòng liên hệ quản trị viên.');
                    } else {
                        throw new Error('Đã xảy ra lỗi. Mã lỗi: ' + response.status);
                    }
                }
                
                return response.json();
            })
            .then(data => {
                // Xóa hiệu ứng typing
                removeTypingIndicator();
                
                // Hiển thị câu trả lời từ bot
                if (data.success) {
                    addBotMessage(data.response);
                } else {
                    addBotErrorMessage(data.response || 'Xin lỗi, có lỗi xảy ra.');
                }
            })
            .catch(error => {
                // Xóa hiệu ứng typing
                removeTypingIndicator();
                
                // Hiển thị lỗi
                addBotErrorMessage('Xin lỗi, có lỗi xảy ra: ' + error.message);
                console.error('Error:', error);
            })
            .finally(() => {
                // Bật lại form
                enableForm();
            });
        });
        
        function removeTypingIndicator() {
            const typingElement = document.getElementById('typing-indicator');
            if (typingElement) typingElement.remove();
        }
        
        function enableForm() {
            userMessageInput.disabled = false;
            userMessageInput.focus();
            
            const sendButton = document.getElementById('send-message');
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }
        
        // Xử lý các gợi ý câu hỏi
        document.querySelectorAll('.question-suggestion').forEach(button => {
            button.addEventListener('click', function() {
                userMessageInput.value = this.textContent;
                chatForm.dispatchEvent(new Event('submit'));
            });
        });
        
        // Hàm thêm tin nhắn người dùng vào chat
        function addUserMessage(message) {
            const userMessageElement = document.createElement('div');
            userMessageElement.className = 'message user-message';
            userMessageElement.innerHTML = `
                <div class="message-content">
                    <p>${escapeHtml(message)}</p>
                </div>
                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>
            `;
            chatMessages.appendChild(userMessageElement);
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        
        // Hàm thêm tin nhắn bot vào chat
        function addBotMessage(message) {
            const botMessageElement = document.createElement('div');
            botMessageElement.className = 'message bot-message';
            botMessageElement.innerHTML = `
                <div class="avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <p>${formatMessage(message)}</p>
                </div>
            `;
            chatMessages.appendChild(botMessageElement);
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        
        // Hàm thêm tin nhắn lỗi từ bot
        function addBotErrorMessage(message) {
            const botMessageElement = document.createElement('div');
            botMessageElement.className = 'message bot-message error';
            botMessageElement.innerHTML = `
                <div class="avatar error">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="message-content error">
                    <p>${formatMessage(message)}</p>
                </div>
            `;
            chatMessages.appendChild(botMessageElement);
            chatArea.scrollTop = chatArea.scrollHeight;
        }
        
        // Escape HTML để tránh XSS
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        // Format message (thêm xuống dòng)
        function formatMessage(message) {
            return escapeHtml(message).replace(/\n/g, '<br>');
        }
    });
</script>
@endsection