<template>
    <div id="activities" class="py-6 px-6 lg:px-20 mx-0 my-12 lg:mx-20">
        <div class="text-center mb-16">
            <div class="text-surface-900 dark:text-surface-0 font-bold mb-4 text-5xl">Recent Activities</div>
            <div class="h-1 w-16 bg-primary-500 mx-auto mb-4"></div>
            <span class="text-surface-600 dark:text-surface-300 text-2xl">Discover our monthly activities</span>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
            <div class="text-red-500 text-lg">Failed to load activities. Please try again later.</div>
        </div>

        <!-- Activities Cards with Flip Effect -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="(activity, index) in activities"
                :key="`activity-${index}`"
                class="activity-card"
                @mouseenter="hoveredCard = index"
                @mouseleave="hoveredCard = null"
            >
                <div class="card-inner" :class="{ 'flipped': hoveredCard === index }">
                    <!-- Front of Card -->
                    <div class="card-front">
                        <div class="card-image">
                            <img
                                :src="activity.image_url"
                                :alt="activity.title"
                                class="w-full h-64 object-cover"
                                @error="handleImageError"
                            />
                            <div class="card-overlay">
                                <div class="card-title">{{ activity.title }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Back of Card -->
                    <div class="card-back">
                        <div class="card-content">
                            <h3 class="card-back-title">{{ activity.title }}</h3>
                            <p class="card-description">{{ activity.description }}</p>
                            <div class="card-icon">
                                <i class="pi pi-arrow-right text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ApiService } from '@/service/ApiService.js';
import { onMounted, ref } from 'vue';

const activities = ref([]);
const loading = ref(true);
const error = ref(false);
const hoveredCard = ref(null);

const loadActivities = async () => {
    try {
        loading.value = true;
        error.value = false;

        const response = await ApiService.getActivities();
        activities.value = response.value || response.data || response;

        // If no activities, show empty state
        if (!activities.value || activities.value.length === 0) {
            activities.value = [];
        }
    } catch (err) {
        console.error('Failed to load activities:', err);
        error.value = true;
        activities.value = [];
    } finally {
        loading.value = false;
    }
};

const handleImageError = (event) => {
    // Set a placeholder image if the original fails to load
    event.target.src = '/images/placeholder-activity.png';
};

onMounted(() => {
    loadActivities();
});
</script>

<style scoped>
.activity-card {
    perspective: 1000px;
    height: 300px;
}

.card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.6s;
    transform-style: preserve-3d;
}

.card-inner.flipped {
    transform: rotateY(180deg);
}

.card-front,
.card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-front {
    background: white;
}

.card-back {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: rotateY(180deg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-image {
    position: relative;
    width: 100%;
    height: 100%;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    padding: 2rem 1.5rem 1.5rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    line-height: 1.2;
}

.card-content {
    padding: 2rem;
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-back-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.card-description {
    font-size: 1rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1.5rem;
    flex-grow: 1;
    display: flex;
    align-items: center;
}

.card-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.card-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Hover effects */
.activity-card:hover .card-front {
    transform: translateY(-5px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .activity-card {
        height: 250px;
    }
    
    .card-back-title {
        font-size: 1.25rem;
    }
    
    .card-description {
        font-size: 0.9rem;
    }
    
    .card-content {
        padding: 1.5rem;
    }
}

/* Dark mode support */
.dark .card-front {
    background: #1f2937;
}

.dark .card-overlay {
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
}
</style>
