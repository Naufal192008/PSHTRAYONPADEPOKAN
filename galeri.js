document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            galleryItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    const modal = document.querySelector('.gallery-modal');
    const modalImg = document.getElementById('modal-image');
    const modalCaption = document.querySelector('.modal-caption');
    const closeModal = document.querySelector('.close-modal');
    const prevBtn = document.querySelector('.modal-nav.prev');
    const nextBtn = document.querySelector('.modal-nav.next');
    
    let currentIndex = 0;
    let filteredItems = Array.from(galleryItems);
    
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            const caption = this.querySelector('h3').textContent + ' - ' + this.querySelector('p').textContent;
            
            modal.style.display = 'block';
            modalImg.src = imgSrc;
            modalCaption.textContent = caption;
            
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            
            if (activeFilter === 'all') {
                filteredItems = Array.from(galleryItems);
            } else {
                filteredItems = Array.from(galleryItems).filter(item => 
                    item.getAttribute('data-category') === activeFilter
                );
            }
            
            currentIndex = filteredItems.findIndex(filteredItem => 
                filteredItem.querySelector('img').src === imgSrc
            );
        });
    });
    
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    function showImage(index) {
        if (index >= 0 && index < filteredItems.length) {
            currentIndex = index;
            const item = filteredItems[currentIndex];
            const imgSrc = item.querySelector('img').src;
            const caption = item.querySelector('h3').textContent + ' - ' + item.querySelector('p').textContent;
            
            modalImg.src = imgSrc;
            modalCaption.textContent = caption;
        }
    }
    
    prevBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        showImage(currentIndex - 1);
    });
    
    nextBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        showImage(currentIndex + 1);
    });
    
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'block') {
            if (e.key === 'ArrowLeft') {
                showImage(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                showImage(currentIndex + 1);
            } else if (e.key === 'Escape') {
                modal.style.display = 'none';
            }
        }
    });
});