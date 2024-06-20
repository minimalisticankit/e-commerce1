document.addEventListener('DOMContentLoaded', function () {
    function setupFilterSearch(type) {
        var searchInput = document.getElementById(type + '-search');
        searchInput.addEventListener('input', function () {
            var searchValue = this.value.toLowerCase();
            var filters = document.querySelectorAll('.' + type + '-filter');
            filters.forEach(function (filter) {
                var label = filter.querySelector('label').textContent.toLowerCase();
                filter.style.display = label.includes(searchValue) ? 'flex' : 'none';
            });
        });
    }

    function toggleFilters(type) {
        var buttons = document.querySelectorAll('.' + type + '-filters .show-more');
        buttons.forEach(function (button) {
            var extraFilters = button.parentElement.querySelectorAll('.' + type + '-filter.extra-filter');
            button.addEventListener('click', function () {
                var isExpanded = button.textContent === 'Show Less';
                extraFilters.forEach(function (filter) {
                    filter.style.display = isExpanded ? 'none' : 'flex';
                });
                button.textContent = isExpanded ? 'Show More' : 'Show Less';
            });
        });
    }

    setupFilterSearch('brand');
    setupFilterSearch('processor');
    setupFilterSearch('ram');
    setupFilterSearch('storage');
    setupFilterSearch('display');
    setupFilterSearch('graphic');

    toggleFilters('brand');
    toggleFilters('processor');
    toggleFilters('ram');
    toggleFilters('storage');
    toggleFilters('display');
    toggleFilters('graphic');
});
