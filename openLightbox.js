document.addEventListener('DOMContentLoaded', function () {
let currentIndex = 0;

document.addEventListener('click', function(e) {

  if (e.target.classList.contains('open-lightbox')) {

    e.preventDefault();

    const allVignettes = document.querySelectorAll('.vignette');
    const clickedVignette = e.target.closest('.vignette');

    currentIndex = Array.from(allVignettes).indexOf(clickedVignette);

    openLightbox();
  }

});

function openLightbox() {

  const allVignettes = document.querySelectorAll('.vignette');
  const v = allVignettes[currentIndex];

  document.getElementById('lightbox-img').src = v.dataset.full;

  document.querySelector('.lightbox-meta').innerHTML =
    `<span>${v.dataset.ref}</span><span>${v.dataset.cat}</span>`;

  document.getElementById('lightbox').classList.remove('hidden');
}

document.querySelector('.lightbox-close').onclick = closeLightbox;
document.querySelector('.lightbox-overlay').onclick = closeLightbox;

function closeLightbox() {
  document.getElementById('lightbox').classList.add('hidden');
}

document.querySelector('.lightbox-prev').addEventListener('click', () => {

  const allVignettes = document.querySelectorAll('.vignette');

  currentIndex = (currentIndex - 1 + allVignettes.length) % allVignettes.length;

  openLightbox();
  });
  document.querySelector('.lightbox-next').addEventListener('click', () => {

  const allVignettes = document.querySelectorAll('.vignette');

  currentIndex = (currentIndex + 1) % allVignettes.length;

  openLightbox();
});

});