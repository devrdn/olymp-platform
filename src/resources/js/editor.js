import { monaco } from "./monaco";

let editor;

let model;

/**
 * Creates a Monaco editor model with the specified value and language.
 *
 * @param {*} value
 * @param {*} language
 * @returns
 */
function createModel(value, language) {
    const uri = monaco.Uri.parse(`inmemory://model.${language}`);
    return monaco.editor.createModel(value, language, uri);
}

document.addEventListener("DOMContentLoaded", () => {
    const editorElement = document.getElementById("editor");
    const languageSelector = document.getElementById("language");
    const fullscreenBtn = document.getElementById("fullscreen-btn");
    const fullscreenEnter = document.getElementById("fullscreen-enter");
    const fullscreenExit = document.getElementById("fullscreen-exit");

    model = createModel(
        "// Write your solution here ...\n",
        languageSelector.value
    );

    // Init Monaco editor
    editor = monaco.editor.create(editorElement, {
        model: model,
        theme: "vs-dark",
        fontFamily: "JetBrains Mono, monospace",
        fontSize: 16,
        lineHeight: 24,
        automaticLayout: true,
    });

    //Change Programming Language
    languageSelector.addEventListener("change", () => {
        const newLanguage = languageSelector.value ?? "cpp";
        const oldValue = model.getValue();

        // Delete the old model
        model.dispose();

        // Create a new model with the new language
        model = createModel(oldValue, newLanguage);
        editor.setModel(model);
    });

    const enterFullscreen = () => {
        editorElement.classList.add("fullscreen-editor");
        document.body.classList.add("fullscreen-active");
        fullscreenEnter.classList.add("hidden");
        fullscreenExit.classList.remove("hidden");
        editor.layout();
    };

    function exitFullscreen() {
        editorElement.classList.remove("fullscreen-editor");
        editorElement.classList.add("h-[20vh]");
        document.body.classList.remove("fullscreen-active");
        fullscreenEnter.classList.remove("hidden");
        fullscreenExit.classList.add("hidden");
    }

    fullscreenBtn.addEventListener("click", () => {
        if (!editorElement.classList.contains("fullscreen-editor")) {
            enterFullscreen();
        } else {
            exitFullscreen();
        }
    });
    fullscreenExit.addEventListener("click", exitFullscreen);

    // Escape key exits fullscreen ONLY if сейчас fullscreen
    document.addEventListener("keydown", (e) => {
        if (
            e.key === "Escape" &&
            editorElement.classList.contains("fullscreen-editor")
        ) {
            exitFullscreen();
        }
    });
});
