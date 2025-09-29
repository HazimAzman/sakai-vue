<script setup lang="ts">
import AboutWidget from '@/components/landing/AboutWidget.vue';
import ClientsWidget from '@/components/landing/ClientsWidget.vue';
import FooterWidget from '@/components/landing/FooterWidget.vue';
import HeroWidget from '@/components/landing/HeroWidget.vue';
import InstituteWidget from '@/components/landing/InstituteWidget.vue';
import ProductWidget from '@/components/landing/ProductWidget.vue';
import ServiceWidget from '@/components/landing/ServiceWidget.vue';
import TopbarWidget from '@/components/landing/TopbarWidget.vue';
import VisionCarousel from '@/components/landing/VisionCarousel.vue';
</script>

<template>
    <div class="bg-surface-0 dark:bg-surface-900">
        <div id="home" class="landing-wrapper overflow-hidden">
            <div class="py-6 px-6 mx-0 md:mx-12 lg:mx-20 lg:px-20 flex items-center justify-between relative lg:static">
                <TopbarWidget />
            </div>
            <HeroWidget />
            <VisionCarousel
                :slides="[
                    { title: 'OUR VISION', description: 'To strive to be the most creative and reliable supplier in providing solutions & services and laboratory needs products & services to sustain quality of pathology service, education and research activity', image: '/images/slide/slider1.png', link: '#' },
                    { title: 'OUR MISSION', description: 'To deliver precise, accurate and effective solution to customers to fulfill requirements and optimize their objective and aims', image: '/images/slide/slider2.png', link: '#' },
                    { title: 'OUR VALUES', description: 'Providing Accurate & Precise support all the time', image: '/images/slide/slider3.png', link: '#' }
                ]"
                :autoplayMs="7000"
            />
            <ServiceWidget />
            <ProductWidget />
            <ClientsWidget />
            <InstituteWidget />
            <AboutWidget />
            <ContactWidget />
            <FooterWidget />
        </div>
    </div>
</template>
