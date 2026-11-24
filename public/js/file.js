$(document).ready(function () {
    let currentStep = 1;
    const totalSteps = $(".step").length;

    function updateProgressCircle() {
        const percentage = ((currentStep - 1) / (totalSteps -2)) * 100;
        const offset = 339.292 - (339.292 * percentage) / 100; 
        $(".progress").css("stroke-dashoffset", offset);
        $(".progress-text").text(`${Math.round(percentage)}%`);
    }

    
    $(document).on("click", "#next-step", function () {
        
        if (currentStep < totalSteps) {
            currentStep++;
            updateSteps();
        } else {
            alert("Vous avez terminé !");
        }
    });

    $(document).on("click", "#prev-step", function () {
        if (currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

    updateSteps();
});



function replacePlaceholders() {
    document.querySelectorAll('#header').forEach(placeholder => {
        placeholder.outerHTML = header;
    });
}
const observer = new MutationObserver(() => replacePlaceholders());
observer.observe(document.body, { childList: true, subtree: true });
replacePlaceholders();


