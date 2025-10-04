<script setup>
function smoothScroll(id) {
    document.body.click();
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}
</script>

<template>
    <a class="flex items-center" href="/landing">
        <img src="/images/logo-black.png" alt="Sakai" class="h-20 mr-20" />
      
    </a>
    <Button
        class="lg:!hidden"
        text
        severity="secondary"
        rounded
        v-styleclass="{ selector: '@next', enterFromClass: 'hidden', enterActiveClass: 'animate-scalein', leaveToClass: 'hidden', leaveActiveClass: 'animate-fadeout', hideOnOutsideClick: true }"
    >
        <i class="pi pi-bars !text-2xl"></i>
    </Button>
    <div class="items-center bg-surface-0 dark:bg-surface-900 grow justify-between hidden lg:flex absolute lg:static w-full left-0 top-full px-12 lg:px-0 z-20 rounded-border">
        <ul class="list-none p-0 m-0 flex lg:items-center select-none flex-col lg:flex-row cursor-pointer gap-8">
            <li>
                <a @click="smoothScroll('hero')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a @click="smoothScroll('service')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Services</span>
                </a>
            </li>
            <li>
                <a @click="smoothScroll('product')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Product</span>
                </a>
            </li>
            <li>
                <a @click="smoothScroll('clients')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Client</span>
                </a>
            </li>
            <li>
                    <a @click="smoothScroll('about')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>About</span>
                </a>
            </li>
            <li>
                <a @click="smoothScroll('contact')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Contact</span>
                </a>
            </li>
            <li>
                <a @click="smoothScroll('download')" class="px-0 py-4 text-surface-900 dark:text-surface-0 font-medium text-xl">
                    <span>Download</span>
                </a>
            </li>
        </ul>
 
    </div>
</template>
