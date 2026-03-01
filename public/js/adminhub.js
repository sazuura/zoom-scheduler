const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    });
});


// --- Toggle Sidebar ---
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});


// --- Search (Mobile Responsive) ---
const searchForm = document.querySelector('#content nav form');
const searchButton = searchForm ? searchForm.querySelector('button') : null;
const searchButtonIcon = searchButton ? searchButton.querySelector('.bx') : null;

if (searchButton) {
    searchButton.addEventListener('click', function (e) {
        if (window.innerWidth < 576) {
            e.preventDefault();
            searchForm.classList.toggle('show');

            if (searchForm.classList.contains('show')) {
                searchButtonIcon.classList.replace('bx-search', 'bx-x');
            } else {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
            }
        }
    });
}


// --- Responsive Adjustment ---
if (window.innerWidth < 768) {
    sidebar.classList.add('hide');
} else if (window.innerWidth > 576) {
    if (searchButtonIcon) searchButtonIcon.classList.replace('bx-x', 'bx-search');
    if (searchForm) searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
    if (this.innerWidth > 576) {
        if (searchButtonIcon) searchButtonIcon.classList.replace('bx-x', 'bx-search');
        if (searchForm) searchForm.classList.remove('show');
    }
});


// --- Dark Mode Switch ---
const switchMode = document.getElementById('switch-mode');

if (switchMode) {
    switchMode.addEventListener('change', function () {
        if (this.checked) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
    });
}
