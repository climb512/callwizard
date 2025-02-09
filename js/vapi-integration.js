document.addEventListener("DOMContentLoaded", function () {
    var vapiInstance = null;
    const assistant = "RETRIEVE-FROM-VAPI"; // Ella chatbot
    const apiKey = "RETRIEVE-FROM-VAPI"; // public key
    const buttonConfig = {}; // Modify this as required

    (function (d, t) {
        var g = document.createElement(t),
            s = d.getElementsByTagName(t)[0];
        g.src =
            "https://cdn.jsdelivr.net/gh/VapiAI/html-script-tag@latest/dist/assets/index.js";
        g.defer = true;
        g.async = true;
        s.parentNode.insertBefore(g, s);

        g.onload = function () {
            vapiInstance = window.vapiSDK.run({
                apiKey: apiKey, // mandatory
                assistant: assistant, // mandatory
                config: buttonConfig, // optional
            });
        };
    })(document, "script");
});