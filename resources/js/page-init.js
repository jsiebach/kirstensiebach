// Page modules only evaluate once, but Turbo swaps pages without reloading,
// so run the initializer now and again after every Turbo visit
export function onPageLoad(initialize) {
    initialize();
    document.addEventListener('turbo:load', initialize);
}
