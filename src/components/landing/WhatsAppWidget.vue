<template>
    <div class="whatsapp-widget">
        <!-- Chat Window -->
        <div v-if="isOpen" class="chat-window">
            <!-- Header -->
            <div class="chat-header">
                <div class="profile-info">
                    <div class="profile-pic">
                        <img src="/images/hazim.jpeg" alt="Hazim Aztec Sinar" />
                        <div class="online-indicator"></div>
                    </div>
                    <div class="profile-details">
                        <div class="name">Hazim Aztec Sinar</div>
                        <div class="status">Online</div>
                    </div>
                </div>
                <button @click="toggleChat" class="close-btn">
                    <i class="pi pi-times"></i>
                </button>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages">
                <div class="timestamp">12:24</div>
                <div class="message received">
                    <div class="message-bubble">
                        Hi there 👋<br>
                        How can I help you?
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="chat-input">
                <input 
                    v-model="userMessage" 
                    type="text" 
                    placeholder="Enter Your Message..." 
                    @keyup.enter="sendMessage"
                    class="message-field"
                />
                <button @click="sendMessage" class="send-btn">
                    <i class="pi pi-send"></i>
                </button>
            </div>
        </div>

        <!-- Floating WhatsApp Button -->
        <button 
            @click="toggleChat" 
            class="whatsapp-button"
            :class="{ 'animate-pulse': !isOpen }"
        >
            <i class="pi pi-whatsapp"></i>
            <div v-if="!isOpen" class="notification-dot"></div>
        </button>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';

// WhatsApp configuration
const phoneNumber = '+60134980680'; // Replace with your actual WhatsApp number
const defaultMessage = 'Hello! I would like to know more about your services.';

// Chat state
const isOpen = ref(false);
const userMessage = ref('');

// Toggle chat window
const toggleChat = () => {
    isOpen.value = !isOpen.value;
};

// Send message to WhatsApp
const sendMessage = () => {
    if (!userMessage.value.trim()) return;
    
    const message = userMessage.value.trim();
    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}?text=${encodedMessage}`;
    
    // Open WhatsApp in new tab
    window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
    
    // Clear input and close chat
    userMessage.value = '';
    isOpen.value = false;
};

// Generate WhatsApp URL for default message
const whatsappUrl = computed(() => {
    const encodedMessage = encodeURIComponent(defaultMessage);
    return `https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}?text=${encodedMessage}`;
});
</script>

<style scoped>
.whatsapp-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Chat Window */
.chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Chat Header */
.chat-header {
    background: linear-gradient(135deg, #25D366, #128C7E);
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-pic {
    position: relative;
    width: 40px;
    height: 40px;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
}

.online-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #4CAF50;
    border: 2px solid white;
    border-radius: 50%;
}

.profile-details .name {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 2px;
}

.profile-details .status {
    font-size: 12px;
    opacity: 0.9;
}

.close-btn {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Chat Messages */
.chat-messages {
    flex: 1;
    padding: 16px;
    background: #f8f9fa;
    background-image: 
        radial-gradient(circle at 20% 20%, rgba(0, 0, 0, 0.02) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(0, 0, 0, 0.02) 0%, transparent 50%);
    overflow-y: auto;
    position: relative;
}

.timestamp {
    text-align: center;
    color: #999;
    font-size: 12px;
    margin-bottom: 16px;
}

.message {
    margin-bottom: 12px;
}

.message-bubble {
    background: white;
    padding: 12px 16px;
    border-radius: 18px;
    max-width: 80%;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    line-height: 1.4;
    font-size: 14px;
}

.message.received .message-bubble {
    border-bottom-left-radius: 4px;
}

/* Chat Input */
.chat-input {
    padding: 16px;
    background: white;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 8px;
    align-items: center;
}

.message-field {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 20px;
    padding: 12px 16px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.message-field:focus {
    border-color: #25D366;
}

.send-btn {
    width: 40px;
    height: 40px;
    background: #25D366;
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}

.send-btn:hover {
    background: #128C7E;
}

.send-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* Floating WhatsApp Button */
.whatsapp-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #25D366, #128C7E);
    border: none;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    color: white;
    font-size: 28px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.whatsapp-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
}

.whatsapp-button:active {
    transform: scale(0.95);
}

.notification-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 12px;
    height: 12px;
    background: #ff4444;
    border-radius: 50%;
    border: 2px solid white;
}

/* Animation for the button */
@keyframes whatsapp-pulse {
    0% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
    50% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 0 0 10px rgba(37, 211, 102, 0.1);
    }
    100% {
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    }
}

.whatsapp-button.animate-pulse {
    animation: whatsapp-pulse 2s infinite;
}

/* Responsive design */
@media (max-width: 768px) {
    .whatsapp-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .chat-window {
        width: 300px;
        height: 450px;
        bottom: 70px;
    }
    
    .whatsapp-button {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .chat-window {
        width: calc(100vw - 30px);
        right: -15px;
        bottom: 70px;
    }
}
</style>
