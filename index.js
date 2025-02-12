document.querySelector(".btn").addEventListener("click", function(event) {
    event.preventDefault(); // Prevents form submission (for demo purposes)
    document.querySelector(".message").innerText = "Thanks for submitting your form. We are happy to see you joining us for the US trip.";
});
