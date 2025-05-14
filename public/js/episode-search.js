document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('movie-search');
    if (searchInput) {
        let timer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            
            timer = setTimeout(function() {
                const searchTerm = searchInput.value.trim();
                
                // Get current URL and update search param
                const url = new URL(window.location.href);
                
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                
                // Reset to page 1 when searching
                if (url.searchParams.has('page')) {
                    url.searchParams.set('page', 1);
                }
                
                window.location.href = url.toString();
            }, 500); // Debounce 500ms
        });
        
        // Set search input value from URL if exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
    }
}); 