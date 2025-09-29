<template>
    <div id="contact" class="py-6 px-6 lg:px-20 my-2 md:my-6 bg-white relative overflow-hidden">
        <!-- Background wavy lines -->
        <div class="absolute top-0 right-0 w-96 h-96 opacity-10">
            <svg viewBox="0 0 400 400" class="w-full h-full">
                <path d="M0,100 Q100,50 200,100 T400,100 L400,0 L0,0 Z" fill="currentColor" class="text-gray-300"/>
                <path d="M0,200 Q100,150 200,200 T400,200 L400,100 L0,100 Z" fill="currentColor" class="text-gray-200"/>
                <path d="M0,300 Q100,250 200,300 T400,300 L400,200 L0,200 Z" fill="currentColor" class="text-gray-300"/>
            </svg>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="mb-12">
          
                <div class="text-center mb-8">
            
                    <div class="text-surface-900 dark:text-surface-0 font-bold text-3xl mb-4">Contact</div>
                    <div class="h-1 w-16 bg-primary-500 mx-auto"></div>
                </div>
            </div>

            <!-- Office Contact Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
                <!-- Northern Office -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">Northern Office</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    Plot 85B, Lintang Bayan Lepas 9, Bayan Lepas Industrial Park Phase 4, 11900 Bayan Lepas, Penang
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">04-6432 080</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Central Office -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">Central Office</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    Lot 12A, Jalan Gitar 33/3, Elite Industrial Park, 40350 Shah Alam, Selangor
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">03-5121 2673</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Southern Office -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">Southern Office</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    No 19, Jalan Cantik 3, Taman Pelangi Indah, 81800 Ulu Tiram, Johor
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">
                                    <div>07-8619511</div>
                                    <div>07-8612790</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sarawak Office -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">Sarawak Office</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    9th Floor, Bangunan Binamas, Jalan Padungan, 93100 Kuching, Sarawak
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">016-8079 616</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sabah Office -->
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                    <h3 class="text-surface-900 dark:text-surface-0 font-bold text-xl mb-4">Sabah Office</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="pi pi-map-marker text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">FIND US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm leading-relaxed">
                                    8-1, 8th Floor, Suria Sabah Shopping Mall, No.1, Jalan Tun Fuad Stephen, 88000 Kota Kinabalu, Sabah
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="pi pi-phone text-red-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <div class="text-red-500 font-semibold text-sm mb-1">CALL US</div>
                                <div class="text-surface-700 dark:text-surface-300 text-sm">
                                    <div>016-8398 627</div>
                                    <div>088-366 500</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
