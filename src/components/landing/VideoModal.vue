<template>
    <Teleport to="body">
        <div v-if="visible" class="video-modal-overlay" @click="closeModal">
            <div class="video-modal-container" @click.stop>
                <button class="video-modal-close" @click="closeModal">
                    <i class="pi pi-times"></i>
                </button>
                
                <div class="video-wrapper">
                    <iframe
                        v-if="videoId"
                        :src="`https://www.youtube.com/embed/PvbOnetd2qA?autoplay=1&rel=0&modestbranding=1`"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="video-iframe"
                    ></iframe>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { defineEmits, defineProps } from 'vue';

const props = defineProps({
    visible: {
        type: Boolean,
        default: false
    },
    videoId: {
        type: String,
        default: 'PvbOnetd2qA' // Default YouTube video ID - replace with your actual video ID
    }
});

const emit = defineEmits(['close']);

const closeModal = () => {
    emit('close');
};
</script>

<style scoped>
.video-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 99999;
    backdrop-filter: blur(5px);
    isolation: isolate;
}

.video-modal-container {
    position: relative;
    width: 90%;
    max-width: 1200px;
    max-height: 90vh;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    border: 2px solid #333;
    z-index: 100000;
    isolation: isolate;
}

.video-modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.7);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10000;
    transition: all 0.3s ease;
    font-size: 18px;
}

.video-modal-close:hover {
    background: rgba(255, 0, 0, 0.8);
    transform: scale(1.1);
}

.video-wrapper {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    background: #000;
    z-index: 100001;
    isolation: isolate;
}

.video-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
    z-index: 100002;
    background: #000;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .video-modal-container {
        width: 95%;
        margin: 20px;
    }
    
    .video-modal-close {
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
}

/* Animation for modal appearance */
.video-modal-overlay {
    animation: fadeIn 0.3s ease-out;
}

.video-modal-container {
    animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: scale(0.8) translateY(-50px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* Ensure modal is above everything */
.video-modal-overlay * {
    box-sizing: border-box;
}

/* Prevent any interference from other elements */
.video-modal-overlay {
    pointer-events: auto;
}

.video-modal-container {
    pointer-events: auto;
}

/* Ensure iframe is clickable and interactive */
.video-iframe {
    pointer-events: auto;
}
</style>
