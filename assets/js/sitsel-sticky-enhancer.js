(function ($) {
    'use strict';

    class SITSStickyEnhancer {
        constructor($element) {
            this.$element = $element;
            this.settings = this.$element.data('sits-sticky-settings') || {};
            this.isSticky = false;
            this.lastScroll = 0;
            this.scrollDirection = 'up';
            this.ticking = false;
            this.placeholder = null;
            this.currentDevice = this.getCurrentDevice();
            
            // Parse settings
            this.enabledDevices = this.settings.enabled_devices || ['desktop', 'tablet', 'mobile'];
            this.positionType = this.settings.position_type || 'stick_on_scroll';
            this.stickyRelation = this.settings.sticky_relation || 'parent';
            this.location = this.settings.location || 'top';
            this.stickOnScroll = this.settings.stick_on_scroll === 'yes';
            this.scrollOffset = parseInt(this.settings.scroll_offset || 100, 10);
            this.transitionDuration = parseInt(this.settings.transition_duration || 300, 10);
            this.hideOnScrollDown = this.settings.hide_on_scroll_down === 'yes';
            this.replaceOnScroll = this.settings.replace_on_scroll === 'yes';
            
            this.init();
        }

        init() {
            // Check if sticky should be enabled on current device
            if (!this.isEnabledOnCurrentDevice()) {
                return;
            }
            
            // Add base class
            this.$element.addClass('sits-sticky-enhanced');
            
            // Set transition duration
            if (this.transitionDuration) {
                this.$element.css('transition-duration', `${this.transitionDuration}ms`);
            }
            
            // Create placeholder
            this.createPlaceholder();
            
            // Bind events
            this.bindEvents();
            
            // Handle different position types
            this.handlePositionType();
            
            // Initial check
            this.checkScroll();
        }

        getCurrentDevice() {
            const width = $(window).width();
            if (width >= 1025) return 'desktop';
            if (width >= 768) return 'tablet';
            return 'mobile';
        }

        isEnabledOnCurrentDevice() {
            return this.enabledDevices.includes(this.currentDevice);
        }

        handlePositionType() {
            switch (this.positionType) {
                case 'stick_immediately':
                    this.makeSticky();
                    break;
                case 'stick_with_offset':
                    // Will be handled in checkScroll
                    break;
                case 'stick_on_scroll':
                default:
                    // Default behavior - will be handled in checkScroll
                    break;
            }
        }

        createPlaceholder() {
            this.placeholder = $('<div class="sits-sticky-placeholder"></div>');
            this.$element.after(this.placeholder);
        }

        bindEvents() {
            $(window).on('scroll.sitsStickyEnhancer', () => {
                this.requestTick();
            });
            
            $(window).on('resize.sitsStickyEnhancer', () => {
                this.handleResize();
            });
        }

        requestTick() {
            if (!this.ticking) {
                requestAnimationFrame(() => {
                    this.onScroll();
                    this.ticking = false;
                });
                this.ticking = true;
            }
        }

        onScroll() {
            if (!this.isEnabledOnCurrentDevice()) {
                return;
            }
            
            const currentScroll = $(window).scrollTop();
            
            // Determine scroll direction
            if (currentScroll > this.lastScroll && currentScroll > this.scrollOffset) {
                this.scrollDirection = 'down';
            } else if (currentScroll < this.lastScroll) {
                this.scrollDirection = 'up';
            }
            
            this.checkScroll();
            this.lastScroll = currentScroll;
        }

        checkScroll() {
            if (this.positionType === 'stick_immediately') {
                return; // Already sticky
            }
            
            const currentScroll = $(window).scrollTop();
            const shouldBeSticky = this.shouldBeSticky(currentScroll);
            
            if (shouldBeSticky) {
                this.makeSticky();
            } else {
                this.removeSticky();
            }
        }

        shouldBeSticky(currentScroll) {
            switch (this.positionType) {
                case 'stick_immediately':
                    return true;
                case 'stick_with_offset':
                    return currentScroll > this.scrollOffset;
                case 'stick_on_scroll':
                default:
                    return currentScroll > this.scrollOffset;
            }
        }

        makeSticky() {
            if (!this.isSticky) {
                // Get element dimensions before making sticky
                const elementHeight = this.$element.outerHeight();
                
                // Handle different sticky relations
                this.handleStickyRelation();
                
                // Add sticky class
                this.$element.addClass('is-sticky');
                
                // Set placeholder height to prevent content jump
                this.placeholder.height(elementHeight);
                
                // Handle location (top/bottom)
                this.handleLocation();
                
                this.isSticky = true;
                
                // Trigger custom event
                this.$element.trigger('sits:sticky:on');
            }
            
            // Handle stick on scroll behavior
            this.handleStickOnScroll();
        }

        handleStickyRelation() {
            switch (this.stickyRelation) {
                case 'viewport':
                    this.$element.addClass('sits-viewport-sticky');
                    break;
                case 'column':
                    this.$element.addClass('sits-column-sticky');
                    break;
                case 'parent':
                default:
                    this.$element.addClass('sits-parent-sticky');
                    break;
            }
        }

        handleLocation() {
            if (this.location === 'bottom') {
                this.$element.css({
                    'top': 'auto',
                    'bottom': '0'
                });
            } else {
                this.$element.css({
                    'top': '0',
                    'bottom': 'auto'
                });
            }
        }

        handleStickOnScroll() {
            if (this.stickOnScroll) {
                if (this.scrollDirection === 'down') {
                    this.$element.addClass('sits-hidden-down');
                } else if (this.scrollDirection === 'up') {
                    this.$element.removeClass('sits-hidden-down');
                }
            }
            
            // Legacy support for hideOnScrollDown
            if (this.hideOnScrollDown) {
                if (this.scrollDirection === 'down') {
                    this.$element.addClass('sits-hidden-down');
                } else if (this.scrollDirection === 'up') {
                    this.$element.removeClass('sits-hidden-down');
                }
            }
        }

        removeSticky() {
            if (this.isSticky) {
                this.$element.removeClass('is-sticky sits-hidden-down sits-viewport-sticky sits-column-sticky sits-parent-sticky');
                this.$element.css({
                    'top': '',
                    'bottom': ''
                });
                this.placeholder.height(0);
                this.isSticky = false;
                
                // Trigger custom event
                this.$element.trigger('sits:sticky:off');
            }
        }

        handleResize() {
            const newDevice = this.getCurrentDevice();
            
            // Check if device changed
            if (newDevice !== this.currentDevice) {
                this.currentDevice = newDevice;
                
                // Re-initialize or destroy based on new device
                if (this.isEnabledOnCurrentDevice()) {
                    this.checkScroll();
                } else {
                    this.removeSticky();
                }
            }
            
            if (this.isSticky) {
                // Temporarily remove sticky to get natural height
                this.$element.removeClass('is-sticky');
                const elementHeight = this.$element.outerHeight();
                this.$element.addClass('is-sticky');
                
                // Update placeholder height
                this.placeholder.height(elementHeight);
            }
        }

        destroy() {
            $(window).off('.sitsStickyEnhancer');
            this.$element.removeClass('sits-sticky-enhanced is-sticky sits-hidden-down sits-viewport-sticky sits-column-sticky sits-parent-sticky');
            this.$element.css({
                'top': '',
                'bottom': ''
            });
            if (this.placeholder) {
                this.placeholder.remove();
            }
        }
    }

    // Device detection utility
    function getCurrentDevice() {
        const width = $(window).width();
        if (width >= 1025) return 'desktop';
        if (width >= 768) return 'tablet';
        return 'mobile';
    }

    // Initialize on Elementor frontend
    function initStickyEnhancer() {
        $('.sits-sticky-enhanced').each(function() {
            const $element = $(this);
            if (!$element.data('sits-sticky-instance')) {
                const instance = new SITSStickyEnhancer($element);
                $element.data('sits-sticky-instance', instance);
            }
        });
    }

    // Re-initialize on device change
    function reinitializeOnResize() {
        let currentDevice = getCurrentDevice();
        
        $(window).on('resize', function() {
            const newDevice = getCurrentDevice();
            if (newDevice !== currentDevice) {
                currentDevice = newDevice;
                
                // Re-initialize all sticky elements
                $('.sits-sticky-enhanced').each(function() {
                    const $element = $(this);
                    const instance = $element.data('sits-sticky-instance');
                    if (instance) {
                        instance.currentDevice = newDevice;
                        instance.handleResize();
                    }
                });
            }
        });
    }

    // Elementor frontend initialization
    $(window).on('elementor/frontend/init', function () {
        // Initialize for containers
        elementorFrontend.hooks.addAction('frontend/element_ready/container', function ($scope) {
            if ($scope.hasClass('sits-sticky-enhanced')) {
                if (!$scope.data('sits-sticky-instance')) {
                    const instance = new SITSStickyEnhancer($scope);
                    $scope.data('sits-sticky-instance', instance);
                }
            }
        });
        
        // Initialize for sections
        elementorFrontend.hooks.addAction('frontend/element_ready/section', function ($scope) {
            if ($scope.hasClass('sits-sticky-enhanced')) {
                if (!$scope.data('sits-sticky-instance')) {
                    const instance = new SITSStickyEnhancer($scope);
                    $scope.data('sits-sticky-instance', instance);
                }
            }
        });
        
        // Initialize resize handler
        reinitializeOnResize();
    });

    // Fallback initialization for non-Elementor environments
    $(document).ready(function() {
        // Small delay to ensure all elements are ready
        setTimeout(function() {
            initStickyEnhancer();
            reinitializeOnResize();
        }, 100);
    });

    // Re-initialize after AJAX or dynamic content loading
    $(document).on('elementor/popup/show', function() {
        setTimeout(initStickyEnhancer, 100);
    });

    // Export for global access
    window.SITSStickyEnhancer = SITSStickyEnhancer;

})(jQuery);