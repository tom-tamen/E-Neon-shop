
const intput = document.querySelector("#quantity");
const max = parseInt(intput.attributes.max.value);

document.querySelector("#atc-btn").addEventListener("click", () => {
    let link = document.querySelector('#add-to-cart');
    let url = link.getAttribute('href');
    if (intput.value> max) {
        url += '/'+max;
    }else if (intput.value < 1) {
        url += '/'+1;
    }else{
        url += '/'+intput.value;
    }
    link.setAttribute('href', url);
    link.click();
})