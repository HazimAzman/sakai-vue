<template>
    <div class="min-h-screen bg-surface-50 dark:bg-surface-900 py-10 px-6 lg:px-20">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="text-sm mb-6">
                <RouterLink to="/" class="text-primary-600 hover:underline">Home</RouterLink>
                <span class="mx-2 text-surface-500">/</span>
                <RouterLink to="/" class="text-primary-600 hover:underline">Products</RouterLink>
                <span class="mx-2 text-surface-500">/</span>
                <span class="text-surface-700 dark:text-surface-300">Scilab Wisebath</span>
            </nav>

            <!-- Header -->
            <div class="bg-white dark:bg-surface-800 rounded-xl shadow-md p-8 mb-8">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-surface-900 dark:text-surface-0">Scilab Wisebath</h1>
                        <p class="mt-2 text-surface-700 dark:text-surface-300">Scilab water bath accessory (Wisebath series)</p>
                    </div>
                    <div class="flex gap-3">
                        <a :href="brochureUrl" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-4 py-2 rounded-md">
                            <i class="pi pi-download"></i>
                            Download brochure
                        </a>
                        <a :href="brochureUrl" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border border-primary-600 text-primary-700 hover:bg-primary-50 font-medium px-4 py-2 rounded-md">
                            <i class="pi pi-external-link"></i>
                            Open in new tab
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Large PDF Embed -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-surface-800 rounded-xl shadow-md p-6">
                        <h2 class="text-xl font-semibold text-surface-900 dark:text-surface-0 mb-4">Product Brochure</h2>
                        <p class="text-surface-800 dark:text-surface-200 leading-relaxed mb-6">
                            Explore the Scilab Wisebath product brochure below. This page provides a quick preview and an instant download link. For full technical specifications, performance data, and configuration details, use the buttons above to download or open the brochure in a new tab.
                        </p>
                        <div class="mt-6 pdf-container bg-surface-100 dark:bg-surface-700 rounded-lg overflow-hidden">
                            <iframe :src="pdfEmbedUrl" class="w-full h-full" title="Scilab Wisebath Brochure"></iframe>
                        </div>
                        <p class="mt-3 text-sm text-surface-600 dark:text-surface-400">
                            If the preview does not load, click "Open in new tab" above to view the PDF in your browser.
                        </p>
                    </div>
                </div>
                
                <!-- Quick Specifications Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="bg-white dark:bg-surface-800 rounded-xl shadow-md p-6 sticky top-8">
                        <h3 class="text-lg font-semibold text-surface-900 dark:text-surface-0 mb-4">Quick Specifications</h3>
                        <div class="rounded-lg border border-surface-200 dark:border-surface-700 overflow-hidden">
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr class="odd:bg-white even:bg-surface-50 dark:odd:bg-surface-800 dark:even:bg-surface-700">
                                        <td class="px-3 py-2 text-surface-600 dark:text-surface-300 text-xs">Brand</td>
                                        <td class="px-3 py-2 text-xs">Scilab</td>
                                    </tr>
                                    <tr class="odd:bg-white even:bg-surface-50 dark:odd:bg-surface-800 dark:even:bg-surface-700">
                                        <td class="px-3 py-2 text-surface-600 dark:text-surface-300 text-xs">Product</td>
                                        <td class="px-3 py-2 text-xs">Wisebath</td>
                                    </tr>
                                    <tr class="odd:bg-white even:bg-surface-50 dark:odd:bg-surface-800 dark:even:bg-surface-700">
                                        <td class="px-3 py-2 text-surface-600 dark:text-surface-300 text-xs">Type</td>
                                        <td class="px-3 py-2 text-xs">Water Bath</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</template>

<script setup>
const brochureUrl = '/catalog/SCILAB/wisebath.pdf';
const pdfEmbedUrl = `${encodeURI(brochureUrl)}#toolbar=1&navpanes=0&scrollbar=1`;
</script>

<style scoped>
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
@media (min-width: 1024px) {
  .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
  .lg\:col-span-3 { grid-column: span 3 / span 3; }
  .lg\:col-span-1 { grid-column: span 1 / span 1; }
}

.pdf-container {
    position: relative;
    width: 100%;
    height: 90vh;
    min-height: 800px;
}

.pdf-container > iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}
</style>

