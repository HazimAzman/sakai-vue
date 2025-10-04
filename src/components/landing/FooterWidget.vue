<template>
    <div class="bg-gray-800 dark:bg-gray-900 py-12 px-6 lg:px-20">
        <div class="grid grid-cols-12 gap-8">
            <!-- Left Column - Company Information -->
            <div class="col-span-12 md:col-span-4">
                <div class="mb-6">
                    <h3 class="text-white font-bold text-2xl mb-2">AZTEC SINAR SDN BHD</h3>
                    <p class="text-gray-300 text-lg">Bringing Your Lab Solutions & Needs</p>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center">
                        <span class="text-gray-300">Call: </span>
                        <span class="text-orange-400 font-semibold ml-1">+6012 - 433 9558</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-300">Email: </span>
                        <span class="text-orange-400 font-semibold ml-1">support@aztecsb.com</span>
                    </div>
                </div>
                
                <div class="text-gray-400 text-sm">
                    2025 © AztecSinar Sdn Bhd
                </div>
            </div>

            <!-- Middle Column - Resources -->
            <div class="col-span-12 md:col-span-4">
                <h4 class="text-white font-bold text-xl mb-4">RESOURCE</h4>
                <div class="space-y-2 mb-6">
                    <a href="/#product" class="block text-gray-300 hover:text-white transition-colors">Product</a>
                    <a href="/#clients" class="block text-gray-300 hover:text-white transition-colors">Client</a>
                    <a href="/#about" class="block text-gray-300 hover:text-white transition-colors">About</a>
                    <a href="/#institute" class="block text-gray-300 hover:text-white transition-colors">Institute</a>
                    <a href="/#download" class="block text-gray-300 hover:text-white transition-colors">Download</a>
                </div>
                <div class="text-gray-400 text-sm">
                    Registered MOF: 357-0203-5897 & Registered as BUMIPUTERA company.
                </div>
            </div>

            <!-- Right Column - Support -->
            <div class="col-span-12 md:col-span-4">
                <h4 class="text-white font-bold text-xl mb-4">SUPPORT</h4>
                <div class="space-y-2 mb-6">
                    <a href="#contact" class="flex items-center text-gray-300 hover:text-white transition-colors">
                        <i class="pi pi-phone mr-2"></i>
                        Contact
                    </a>
                    <router-link to="/privacy-policy" class="flex items-center text-gray-300 hover:text-white transition-colors">
                        <i class="pi pi-file mr-2"></i>
                        Privacy Policy
                    </router-link>
                </div>
                
                <!-- Social Media Icons -->
                <div class="flex space-x-3">
                    <a href="https://www.facebook.com/aztecsinar/" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors">
                        <i class="pi pi-facebook text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors">
                        <i class="pi pi-twitter text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-600 transition-colors">
                        <i class="pi pi-instagram text-white"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
