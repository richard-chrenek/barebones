export const setCollapsible = (selector: string) => {
    const collapsible = document.querySelector(selector);

    collapsible?.querySelector('.heading input')?.addEventListener('input', () => {
        collapsible.querySelector('.collapsible-message')?.classList.toggle('hidden');
    });
}