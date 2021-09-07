
// Обработка выбора слайдов.
const slides = document.querySelectorAll('.slide-list figure');
const slide = document.querySelector('.slide-view figure');

slides.forEach(item => {
    item.addEventListener('click', function(e) {
        slide.querySelector('img').src = e.target.src;
        slide.querySelector('figcaption').innerText = 'Слайд: ' + e.target.alt;
    });
});



