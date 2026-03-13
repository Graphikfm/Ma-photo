document.addEventListener("DOMContentLoaded", function() {

    const modal = document.getElementById('contact-modal');
    const body = document.body;
    const closeBtn = document.querySelector('.modal-close');

    if (!modal) return;

    // OUVERTURE MODALE
    document.querySelectorAll('.open-contact').forEach(btn => {

        btn.addEventListener('click', function() {

            modal.classList.add('active');
            body.style.overflow = "hidden";

            // RÉCUPÉRATION DE LA REF
            const ref = this.dataset.ref;

            if(ref){
                const input = document.querySelector('input[name="ref-photo"]');
                if(input){
                    input.value = ref;
                }
            }

        });

    });

    // FERMETURE bouton X
    if(closeBtn){
        closeBtn.addEventListener('click', function() {
            modal.classList.remove('active');
            body.style.overflow = "";
        });
    }

    // FERMETURE clic extérieur
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            body.style.overflow = "";
        }
    });

    // FERMETURE ESC
    document.addEventListener('keydown', function(e){
        if(e.key === "Escape"){
            modal.classList.remove('active');
            body.style.overflow = "";
        }
    });

});