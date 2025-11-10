document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('toggleDashboardSidebar');
  const sidebar80 = document.querySelector('.collapsed80');
  const sidebar100 = document.querySelector('.collapsed100');
  const mainContent = document.querySelector('.dashboard_main_content');
  const toggleOutBtns = document.querySelectorAll('.toggle-out');

  if (toggleBtn && mainContent) {
    mainContent.style.transition = 'padding-left 0.3s ease';

    toggleBtn.addEventListener('click', function () {
      let isCollapsed = false;

      if (sidebar80) {
        isCollapsed = sidebar80.classList.toggle('collapsed');
        mainContent.style.paddingLeft = isCollapsed
          ? 'calc(48px + 20px)'
          : 'calc(280px + 20px)';
      } else if (sidebar100) {
        isCollapsed = sidebar100.classList.toggle('collapsed');
        mainContent.style.paddingLeft = isCollapsed
          ? 'calc(0px + 20px)'
          : 'calc(400px + 20px)';

        toggleOutBtns.forEach(function (btn) {
          btn.style.left = isCollapsed ? '-4px' : '397px';
          btn.style.transition = 'left 0.3s ease';
        });
      }

      const links = document.querySelectorAll('.dashbord_nav_list li a');

      links.forEach(function (link) {
        link.childNodes.forEach(function (node) {
          if (node.nodeType === Node.TEXT_NODE) {
            if (!node._originalText) {
              node._originalText = node.textContent;
            }
      
            node.textContent = isCollapsed ? '' : node._originalText;
          }
        });
      });
      
    });
  }

  const offset = -17;
  window.addEventListener('scroll', function () {
    const scrollY = window.scrollY || window.pageYOffset;
    toggleOutBtns.forEach(function (btn) {
      btn.style.transform = `translateY(${offset + scrollY}px)`;
    });
  });
});
