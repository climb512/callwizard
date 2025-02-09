document.addEventListener("DOMContentLoaded", function () {
    var vapiInstance = null;
    const assistant = "4e81c450-26a7-41ed-9e15-5d59d657ae8b"; // Ella chatbot
    const apiKey = "75d60fc5-2a1f-4b08-89c3-347c1aa95ffc"; // public key
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