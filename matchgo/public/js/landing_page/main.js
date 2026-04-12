/* ============================================
   MATCHGO - Landing Page JavaScript
   Vanilla JS (No framework dependencies)
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Theme Toggle (Light / Dark) ---------- */
  var themeToggle = document.getElementById('themeToggle');
  var htmlEl = document.documentElement;

  var savedTheme = localStorage.getItem('matchgo-theme');
  if (savedTheme) {
    htmlEl.setAttribute('data-theme', savedTheme);
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', function () {
      var current = htmlEl.getAttribute('data-theme') || 'dark';
      var next = current === 'dark' ? 'light' : 'dark';
      htmlEl.setAttribute('data-theme', next);
      localStorage.setItem('matchgo-theme', next);
    });
  }

  /* ---------- Navbar scroll effect ---------- */
  var navbar = document.querySelector('.navbar-matchgo');
  window.addEventListener('scroll', function () {
    if (window.scrollY > 60) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  /* ---------- Close mobile menu on link click ---------- */
  var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
  var navCollapse = document.getElementById('navbarNav');

  navLinks.forEach(function (link) {
    link.addEventListener('click', function () {
      if (navCollapse.classList.contains('show')) {
        var bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
        if (bsCollapse) bsCollapse.hide();
      }
    });
  });

  /* ---------- Scroll Reveal ---------- */
  var revealElements = document.querySelectorAll('.reveal');

  function checkReveal() {
    var windowHeight = window.innerHeight;
    revealElements.forEach(function (el) {
      var elementTop = el.getBoundingClientRect().top;
      if (elementTop < windowHeight - 80) {
        el.classList.add('revealed');
      }
    });
  }

  window.addEventListener('scroll', checkReveal);
  checkReveal();

  /* ---------- Counter Animation ---------- */
  var counters = document.querySelectorAll('[data-counter]');
  var countersAnimated = false;

  function animateCounters() {
    if (countersAnimated) return;

    var statsSection = document.getElementById('stats');
    if (!statsSection) return;

    var rect = statsSection.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      countersAnimated = true;

      counters.forEach(function (counter) {
        var target = parseInt(counter.getAttribute('data-counter'), 10);
        var suffix = counter.getAttribute('data-suffix') || '';
        var duration = 2000;
        var startTime = performance.now();

        function updateCounter(currentTime) {
          var elapsed = currentTime - startTime;
          var progress = Math.min(elapsed / duration, 1);
          var easeOut = 1 - Math.pow(1 - progress, 3);
          var current = Math.floor(target * easeOut);
          counter.textContent = current.toLocaleString('id-ID') + suffix;

          if (progress < 1) {
            requestAnimationFrame(updateCounter);
          }
        }

        requestAnimationFrame(updateCounter);
      });
    }
  }

  window.addEventListener('scroll', animateCounters);
  animateCounters();

  /* ---------- Active nav link on scroll ---------- */
  var sections = document.querySelectorAll('section[id]');

  function highlightNav() {
    var scrollY = window.scrollY + 120;

    sections.forEach(function (section) {
      var sectionTop = section.offsetTop;
      var sectionHeight = section.offsetHeight;
      var sectionId = section.getAttribute('id');

      if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
        navLinks.forEach(function (link) {
          link.classList.remove('active');
          if (link.getAttribute('href') === '#' + sectionId) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  window.addEventListener('scroll', highlightNav);
  highlightNav();

  /* ---------- Winrate bar animation ---------- */
  var winrateBars = document.querySelectorAll('.winrate-fill');
  var winrateAnimated = false;

  function animateWinrate() {
    if (winrateAnimated) return;
    winrateBars.forEach(function (bar) {
      var rect = bar.getBoundingClientRect();
      if (rect.top < window.innerHeight - 50) {
        winrateAnimated = true;
        var targetWidth = bar.getAttribute('data-width') || '78%';
        bar.style.width = targetWidth;
      }
    });
  }

  window.addEventListener('scroll', animateWinrate);
  animateWinrate();

  /* ---------- Smooth scroll for all anchor links ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId === '#') return;
      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

});