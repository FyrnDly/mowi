var context = ["Tepat", "Cepat", "Mudah"];
var index = charIndex = 0;
var typing = true;

function typeText() {
    var span = document.getElementById("run");
    if (span) {
        if (typing) {
            if (charIndex < context[index].length) {
                span.textContent += context[index][charIndex];
                charIndex++;
            } else {
                typing = false;
                setTimeout(function () {
                    index = (index + 1) % context.length;
                    charIndex = 0;
                    span.textContent = '';
                    typing = true;
                }, 1000);
            }
        }
    }
}

setInterval(typeText, 1500 / context[index].length);