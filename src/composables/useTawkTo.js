import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';

const isTawkToVisible = ref(true);

export function useTawkTo() {
    const route = useRoute();

    // Function to show Tawk.to widget
    const showTawkTo = () => {
        if (window.Tawk_API && window.Tawk_API.showWidget) {
            window.Tawk_API.showWidget();
        }
        
        // Remove admin-page class from body
        if (document && document.body) {
            document.body.classList.remove('admin-page');
        }
        
        // Show via CSS
        const tawkElements = document.querySelectorAll('[id*="tawk"], [class*="tawk"], iframe[src*="tawk"]');
        tawkElements.forEach(el => {
            el.style.display = '';
            el.style.visibility = '';
            el.style.opacity = '';
        });
        
        isTawkToVisible.value = true;
    };

    // Function to hide Tawk.to widget
    const hideTawkTo = () => {
        if (window.Tawk_API && window.Tawk_API.hideWidget) {
            window.Tawk_API.hideWidget();
        }
        
        // Aggressive hiding via CSS
        const tawkElements = document.querySelectorAll('[id*="tawk"], [class*="tawk"], iframe[src*="tawk"], div[style*="position: fixed"][style*="bottom"]');
        tawkElements.forEach(el => {
            el.style.display = 'none !important';
            el.style.visibility = 'hidden !important';
            el.style.opacity = '0 !important';
        });
        
        // Add admin-page class to body
        if (document && document.body) {
            document.body.classList.add('admin-page');
        }
        
        isTawkToVisible.value = false;
    };

    // Function to toggle Tawk.to widget
    const toggleTawkTo = () => {
        if (isTawkToVisible.value) {
            hideTawkTo();
        } else {
            showTawkTo();
        }
    };

    // Initialize Tawk.to management after component is mounted
    onMounted(() => {
        // Wait for Tawk.to to load
        const initTawkTo = () => {
            if (window.Tawk_API) {
                // Tawk.to is loaded, set up route watching
                watch(
                    () => route.path,
                    (newPath) => {
                        try {
                            console.log('Route changed to:', newPath); // Debug log
                            
                            // Only show Tawk.to on the landing page (root path)
                            if (newPath === '/') {
                                console.log('Showing Tawk.to on landing page'); // Debug log
                                showTawkTo();
                                // Remove admin-page class from body
                                if (document && document.body) {
                                    document.body.classList.remove('admin-page');
                                }
                            } else {
                                console.log('Hiding Tawk.to on page:', newPath); // Debug log
                                // Hide Tawk.to on all other pages (admin, login, etc.)
                                hideTawkTo();
                                // Add admin-page class to body for CSS-based hiding
                                if (document && document.body) {
                                    document.body.classList.add('admin-page');
                                }
                            }
                        } catch (error) {
                            console.warn('Tawk.to management error:', error);
                        }
                    },
                    { immediate: true }
                );
            } else {
                // Retry after a short delay
                setTimeout(initTawkTo, 500);
            }
        };
        
        // Also check immediately on mount
        setTimeout(() => {
            const currentPath = route.path;
            console.log('Initial route check:', currentPath); // Debug log
            
            if (currentPath === '/') {
                showTawkTo();
                if (document && document.body) {
                    document.body.classList.remove('admin-page');
                }
            } else {
                hideTawkTo();
                if (document && document.body) {
                    document.body.classList.add('admin-page');
                }
            }
        }, 1000);
        
        initTawkTo();
    });

    return {
        isTawkToVisible,
        showTawkTo,
        hideTawkTo,
        toggleTawkTo
    };
}
