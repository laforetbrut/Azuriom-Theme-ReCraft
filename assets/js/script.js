/**
 * ReCraft Theme - Azuriom
 * Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // Navbar scroll effect
    // ==========================================
    const navbar = document.getElementById('rcNavbar');
    if (navbar) {
        const onScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ==========================================
    // Mobile navbar toggle
    // ==========================================
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarCollapse = document.getElementById('navbarCollapse');
    if (navbarToggle && navbarCollapse) {
        navbarToggle.addEventListener('click', function () {
            navbarCollapse.classList.toggle('show');
            // Animate hamburger
            this.classList.toggle('active');
        });

        // Close menu on link click (mobile)
        navbarCollapse.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    navbarCollapse.classList.remove('show');
                    navbarToggle.classList.remove('active');
                }
            });
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (window.innerWidth < 992 && navbarCollapse.classList.contains('show')) {
                if (!navbarCollapse.contains(e.target) && !navbarToggle.contains(e.target)) {
                    navbarCollapse.classList.remove('show');
                    navbarToggle.classList.remove('active');
                }
            }
        });
    }

    // ==========================================
    // Scroll to top button
    // ==========================================
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    if (scrollTopBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        }, { passive: true });

        scrollTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ==========================================
    // Fade-in animations on scroll
    // ==========================================
    const fadeElements = document.querySelectorAll('.fade-in');
    if (fadeElements.length > 0) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        fadeElements.forEach(function (el) {
            observer.observe(el);
        });
    }

    // ==========================================
    // Toast auto-dismiss
    // ==========================================
    const toast = document.getElementById('rcToast');
    if (toast) {
        // Show
        setTimeout(function () {
            toast.classList.add('show');
        }, 100);
        // Hide after 4s
        setTimeout(function () {
            toast.classList.remove('show');
            setTimeout(function () {
                toast.remove();
            }, 400);
        }, 4000);
    }

    // ==========================================
    // Stat number counter animation
    // ==========================================
    const statNumbers = document.querySelectorAll('.rc-stat-number');
    if (statNumbers.length > 0) {
        const counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(function (el) {
            counterObserver.observe(el);
        });
    }

    function animateCounter(element) {
        const text = element.textContent.trim();
        const match = text.match(/^(\d+)/);
        if (!match) return;

        const target = parseInt(match[1]);
        const suffix = text.replace(match[1], '');
        const duration = 1500;
        const start = Date.now();

        function update() {
            const elapsed = Date.now() - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            element.textContent = current + suffix;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = target + suffix;
            }
        }

        requestAnimationFrame(update);
    }

    // ==========================================
    // Stat block counter animation (configurable stats)
    // ==========================================
    const statBlocks = document.querySelectorAll('.rc-stat-block-value');
    if (statBlocks.length > 0) {
        const blockObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateBlockCounter(entry.target);
                    blockObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        statBlocks.forEach(function (el) {
            blockObserver.observe(el);
        });
    }

    function animateBlockCounter(element) {
        const text = element.textContent.trim();
        const match = text.match(/^(\d+)/);
        if (!match) return;

        const target = parseInt(match[1]);
        const suffix = text.replace(match[1], '');
        const duration = 1500;
        const start = Date.now();

        function update() {
            const elapsed = Date.now() - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(eased * target);
            element.textContent = current + suffix;

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = target + suffix;
            }
        }

        requestAnimationFrame(update);
    }

    // ==========================================
    // Smooth scroll for anchor links
    // ==========================================
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

});

// ==========================================
// Copy IP to clipboard (global function for onclick)
// ==========================================
function copyIP() {
    const badge = document.querySelector('.rc-hero-badge');
    if (!badge) return;

    // Extract just the IP text (excluding icon)
    const ip = badge.textContent.trim().replace(/\s+/g, ' ').split(' ').find(function(part) {
        return part.includes('.') || part.includes(':');
    });

    if (!ip) return;

    navigator.clipboard.writeText(ip).then(function () {
        const icon = document.getElementById('copyIcon');
        if (icon) {
            icon.className = 'bi bi-check-lg';
            icon.style.opacity = '1';
            icon.style.color = '#4ade80';
            setTimeout(function () {
                icon.className = 'bi bi-clipboard';
                icon.style.opacity = '0.7';
                icon.style.color = '';
            }, 2000);
        }

        // Show toast
        showToast('IP copiee !', 'success');
    }).catch(function () {
        // Fallback
        const tmp = document.createElement('textarea');
        tmp.value = ip;
        document.body.appendChild(tmp);
        tmp.select();
        document.execCommand('copy');
        document.body.removeChild(tmp);
        showToast('IP copiee !', 'success');
    });
}

function showToast(message, type) {
    // Remove existing
    const existing = document.querySelector('.rc-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'rc-toast rc-toast-' + (type || 'success');
    toast.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    document.body.appendChild(toast);

    setTimeout(function () { toast.classList.add('show'); }, 50);
    setTimeout(function () {
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 400);
    }, 3000);
}
