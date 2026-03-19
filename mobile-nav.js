    console.log('MOBILE NAV JS CHARGÉ');
    document.addEventListener('DOMContentLoaded', function() {
        const BurgerNav = document.querySelector('#burger-btn');
        const littleNav = document.querySelector('.little-nav');

            BurgerNav.addEventListener('click', function() {
                littleNav.classList.toggle('open');
                console.log('test');
            });

    });