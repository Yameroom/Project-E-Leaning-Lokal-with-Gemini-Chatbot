const form = document.getElementById("chat-form");
const output = document.getElementById("output");

async function typeHTML(element, html, delay = 5) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    element.innerHTML = '';

    async function typeNode(node, parent) {
        if (node.nodeType === Node.TEXT_NODE) {
            for (let char of node.textContent) {
                parent.appendChild(document.createTextNode(char));
                await new Promise(resolve => setTimeout(resolve, delay));
                element.scrollTop = element.scrollHeight;
            }
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            const newElem = document.createElement(node.tagName);
            for (let attr of node.attributes) {
                newElem.setAttribute(attr.name, attr.value);
            }
            parent.appendChild(newElem);
            for (let child of node.childNodes) {
                await typeNode(child, newElem);
            }
        }
    }

    for (let child of temp.childNodes) {
        await typeNode(child, element);
    }
}

form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const prompt = document.getElementById("prompt-input").value;

    output.innerHTML = "<em>Generating...</em>";

    try {
        const response = await fetch("http://127.0.0.1:5000/chat", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ prompt })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || "Terjadi kesalahan jaringan.");
        }

        const data = await response.json();
        const md = window.markdownit();
        let cleaned = (data.response || "(Tidak ada jawaban)").trim().replace(/\n\s*\n+/g, '\n\n');
        const htmlOutput = md.render(cleaned);
        await typeHTML(output, htmlOutput, 3);
    } catch (error) {
        output.innerHTML = `<div class="error">Error: ${error.message}</div>`;
        console.error(error);
    }
});