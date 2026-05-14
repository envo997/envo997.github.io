
const styles: Record<string, string> = {
    "Styl Podstawowy": "style-1.css",
    "Styl Alternatywny": "style-2.css",
    "Styl pusty": "style-3.css",
};

function changeStyle(styleName: string): void {
    const head = document.head;
    const oldLink = document.getElementById("dynamic-style");

    if (oldLink) {
        head.removeChild(oldLink);
    }

    const newLink = document.createElement("link");
    newLink.id = "dynamic-style";
    newLink.rel = "stylesheet";
    newLink.href = styles[styleName];

    head.appendChild(newLink);
}

function createSwitcher(): void {
    const nav = document.createElement("div");
    nav.style.padding = "15px";
    nav.style.background = "#ddd";
    nav.style.marginBottom = "20px";

    const label = document.createElement("span");
    label.innerText = "Wybierz styl: ";
    label.style.fontWeight = "bold";
    label.style.marginRight = "10px";
    nav.appendChild(label);

    Object.keys(styles).forEach((name) => {
        const btn = document.createElement("button");
        btn.innerText = name;
        btn.style.marginRight = "10px";
        btn.onclick = () => changeStyle(name);
        nav.appendChild(btn);
    });

    document.body.prepend(nav);
}

createSwitcher();
changeStyle(Object.keys(styles)[0]);