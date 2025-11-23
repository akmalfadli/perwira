{{-- resources/views/partials/articles.blade.php --}}

<div class="mt-16" id="articles-section">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Artikel {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h2>
        <div class="text-sm">  
            @include('theme::commons.paging', ['paging_page' => $paging_page])
        </div>
    </div>

    @php
        $filteredArtikel = $artikel->reject(fn($post) => $post['kategori'] === 'agenda');
    @endphp

    @if ($filteredArtikel->count() > 0)
        <!-- Mobile Carousel Container (visible on mobile only) -->
        <div class="block sm:hidden">
            <div class="relative">
                <!-- Carousel Wrapper -->
                <div id="mobile-articles-carousel" 
                     class="flex gap-4 overflow-x-auto scrollbar-hide pb-4 snap-x snap-mandatory"
                     style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                    @foreach ($filteredArtikel->take(6) as $index => $post)
                        <div class="flex-shrink-0 w-80 snap-start">
                            <div class="mobile-article-wrapper">
                                @include('theme::partials.artikel.list', ['post' => $post])
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Arrows -->
                <button id="carousel-prev" 
                        class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-green-600 transition-colors z-10 opacity-90 hover:opacity-100"
                        aria-label="Previous article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <button id="carousel-next" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-green-600 transition-colors z-10 opacity-90 hover:opacity-100"
                        aria-label="Next article">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Dots Indicator -->
                <div class="flex justify-center mt-4 gap-2" id="carousel-indicators" role="tablist" aria-label="Article navigation">
                    @foreach ($filteredArtikel->take(6) as $index => $post)
                        <button class="w-2 h-2 rounded-full transition-all duration-200 carousel-dot" 
                                data-slide="{{ $index }}"
                                role="tab"
                                aria-label="Go to article {{ $index + 1 }}"
                                style="background-color: {{ $index === 0 ? '#16a34a' : '#d1d5db' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Desktop Grid (hidden on mobile, visible on sm and up) - 2 rows with 3 columns each (6 articles total) -->
        <div class="hidden sm:grid sm:grid-cols-3 gap-6">
            @foreach ($filteredArtikel->take(6) as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
</div>

<style>
/* Hide scrollbar for mobile carousel */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Scroll snapping for smooth navigation */
.snap-x {
    scroll-snap-type: x mandatory;
}

.snap-start {
    scroll-snap-align: start;
}

/* Mobile article wrapper adjustments */
.mobile-article-wrapper {
    height: 100%;
}

.mobile-article-wrapper > * {
    height: 100%;
    max-width: none;
    width: 100%;
}

/* Responsive adjustments for mobile carousel */
@media (max-width: 639px) {
    .mobile-article-wrapper .grid {
        display: block !important;
    }
    
    .mobile-article-wrapper .md\:grid-cols-2,
    .mobile-article-wrapper .lg\:grid-cols-3 {
        display: block !important;
    }
    
    /* Ensure article cards fit properly in carousel */
    .mobile-article-wrapper [class*="col-span"] {
        width: 100% !important;
    }
}

/* Touch scrolling improvements */
@supports (-webkit-overflow-scrolling: touch) {
    #mobile-articles-carousel {
        -webkit-overflow-scrolling: touch;
    }
}

/* Prevent text selection during drag */
#mobile-articles-carousel.dragging * {
    user-select: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('mobile-articles-carousel');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');
    
    if (!carousel || dots.length === 0) return;
    
    let currentSlide = 0;
    const slideWidth = 336; // 320px + 16px gap (w-80 + gap-4)
    const totalSlides = dots.length;
    
    // Throttle function for performance
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // Update carousel position and indicators
    function updateCarousel() {
        if (!carousel) return;
        
        carousel.scrollTo({
            left: currentSlide * slideWidth,
            behavior: 'smooth'
        });
        
        // Update dot indicators
        dots.forEach((dot, index) => {
            const isActive = index === currentSlide;
            dot.style.backgroundColor = isActive ? '#16a34a' : '#d1d5db';
            dot.style.transform = isActive ? 'scale(1.2)' : 'scale(1)';
            dot.setAttribute('aria-selected', isActive);
        });
        
        // Update button states and accessibility
        if (prevBtn) {
            const isDisabled = currentSlide === 0;
            prevBtn.style.opacity = isDisabled ? '0.5' : '0.9';
            prevBtn.disabled = isDisabled;
            prevBtn.setAttribute('aria-disabled', isDisabled);
        }
        if (nextBtn) {
            const isDisabled = currentSlide === totalSlides - 1;
            nextBtn.style.opacity = isDisabled ? '0.5' : '0.9';
            nextBtn.disabled = isDisabled;
            nextBtn.setAttribute('aria-disabled', isDisabled);
        }
    }
    
    // Navigation functions
    function goToPrevSlide() {
        if (currentSlide > 0) {
            currentSlide--;
            updateCarousel();
        }
    }
    
    function goToNextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
            updateCarousel();
        }
    }
    
    function goToSlide(index) {
        if (index >= 0 && index < totalSlides) {
            currentSlide = index;
            updateCarousel();
        }
    }
    
    // Event listeners
    if (prevBtn) {
        prevBtn.addEventListener('click', goToPrevSlide);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', goToNextSlide);
    }
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });
    
    // Touch and swipe support
    let touchState = {
        startX: 0,
        currentX: 0,
        isDragging: false,
        startScrollLeft: 0
    };
    
    function handleTouchStart(e) {
        touchState.startX = e.touches[0].clientX;
        touchState.startScrollLeft = carousel.scrollLeft;
        touchState.isDragging = true;
        carousel.classList.add('dragging');
    }
    
    function handleTouchMove(e) {
        if (!touchState.isDragging) return;
        
        touchState.currentX = e.touches[0].clientX;
        const diffX = touchState.startX - touchState.currentX;
        carousel.scrollLeft = touchState.startScrollLeft + diffX;
    }
    
    function handleTouchEnd() {
        if (!touchState.isDragging) return;
        
        touchState.isDragging = false;
        carousel.classList.remove('dragging');
        
        const diffX = touchState.startX - touchState.currentX;
        const threshold = slideWidth / 3;
        
        if (Math.abs(diffX) > threshold) {
            if (diffX > 0 && currentSlide < totalSlides - 1) {
                goToNextSlide();
            } else if (diffX < 0 && currentSlide > 0) {
                goToPrevSlide();
            }
        } else {
            updateCarousel(); // Snap back to current slide
        }
        
        // Reset touch state
        Object.assign(touchState, {
            startX: 0,
            currentX: 0,
            startScrollLeft: 0
        });
    }
    
    carousel.addEventListener('touchstart', handleTouchStart, { passive: true });
    carousel.addEventListener('touchmove', handleTouchMove, { passive: true });
    carousel.addEventListener('touchend', handleTouchEnd, { passive: true });
    
    // Mouse drag support (for desktop testing)
    let mouseState = {
        isDown: false,
        startX: 0,
        scrollLeft: 0
    };
    
    carousel.addEventListener('mousedown', function(e) {
        mouseState.isDown = true;
        mouseState.startX = e.pageX - carousel.offsetLeft;
        mouseState.scrollLeft = carousel.scrollLeft;
        carousel.style.cursor = 'grabbing';
        carousel.classList.add('dragging');
        e.preventDefault();
    });
    
    carousel.addEventListener('mouseleave', function() {
        mouseState.isDown = false;
        carousel.style.cursor = 'grab';
        carousel.classList.remove('dragging');
    });
    
    carousel.addEventListener('mouseup', function() {
        mouseState.isDown = false;
        carousel.style.cursor = 'grab';
        carousel.classList.remove('dragging');
    });
    
    carousel.addEventListener('mousemove', function(e) {
        if (!mouseState.isDown) return;
        e.preventDefault();
        const x = e.pageX - carousel.offsetLeft;
        const walk = (x - mouseState.startX) * 2;
        carousel.scrollLeft = mouseState.scrollLeft - walk;
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (window.innerWidth >= 640) return; // Only on mobile
        
        switch(e.key) {
            case 'ArrowLeft':
                if (currentSlide > 0) {
                    goToPrevSlide();
                    e.preventDefault();
                }
                break;
            case 'ArrowRight':
                if (currentSlide < totalSlides - 1) {
                    goToNextSlide();
                    e.preventDefault();
                }
                break;
        }
    });
    
    // Update current slide based on scroll position (throttled for performance)
    const handleScroll = throttle(function() {
        const newSlide = Math.round(carousel.scrollLeft / slideWidth);
        if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
            currentSlide = newSlide;
            dots.forEach((dot, index) => {
                const isActive = index === currentSlide;
                dot.style.backgroundColor = isActive ? '#16a34a' : '#d1d5db';
                dot.style.transform = isActive ? 'scale(1.2)' : 'scale(1)';
                dot.setAttribute('aria-selected', isActive);
            });
        }
    }, 100);
    
    carousel.addEventListener('scroll', handleScroll);
    
    // Auto-play functionality with proper cleanup
    let autoPlayState = {
        interval: null,
        isPlaying: false,
        inactivityTimer: null
    };
    
    function startAutoPlay() {
        if (autoPlayState.isPlaying) return;
        
        autoPlayState.isPlaying = true;
        autoPlayState.interval = setInterval(() => {
            if (currentSlide < totalSlides - 1) {
                goToNextSlide();
            } else {
                goToSlide(0);
            }
        }, 4000);
    }
    
    function stopAutoPlay() {
        if (autoPlayState.interval) {
            clearInterval(autoPlayState.interval);
            autoPlayState.interval = null;
            autoPlayState.isPlaying = false;
        }
    }
    
    function resetInactivityTimer() {
        clearTimeout(autoPlayState.inactivityTimer);
        stopAutoPlay();
        
        autoPlayState.inactivityTimer = setTimeout(() => {
            if (window.innerWidth < 640 && document.visibilityState === 'visible') {
                startAutoPlay();
            }
        }, 2000);
    }
    
    // Track user interactions for auto-play
    const interactionEvents = ['touchstart', 'mousedown', 'click', 'keydown'];
    interactionEvents.forEach(event => {
        carousel.addEventListener(event, resetInactivityTimer);
    });
    
    // Pause auto-play when page is not visible
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoPlay();
        } else {
            resetInactivityTimer();
        }
    });
    
    // Initialize carousel
    updateCarousel();
    resetInactivityTimer();
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopAutoPlay();
        clearTimeout(autoPlayState.inactivityTimer);
    });
    
    // Article section scroll functionality
    function scrollToArticles() {
        const articlesSection = document.getElementById('articles-section');
        if (articlesSection) {
            const offsetTop = articlesSection.offsetTop - 100;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    }
    
    // Handle scroll to articles from pagination
    if (sessionStorage.getItem('scrollToArticles') === 'true') {
        sessionStorage.removeItem('scrollToArticles');
        setTimeout(scrollToArticles, 300);
    }
    
    // Handle page parameter
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page');
    
    if (currentPage && currentPage !== '1') {
        setTimeout(scrollToArticles, 300);
    }
    
    // Handle direct anchor links
    if (window.location.hash === '#articles-section') {
        setTimeout(scrollToArticles, 300);
    }
});
</script>