let counter = document.querySelectorAll('.quantity-product');
let minus = document.querySelectorAll('.minus');
let plus = document.querySelectorAll('.plus');

function update(min, plus, counter){
    min.disabled = parseInt(counter.value) <= 1;
    plus.disabled = parseInt(counter.value) >= parseInt(counter.attributes.max.value);

    if (parseInt(counter.value) >= parseInt(counter.attributes.max.value)) {
        plus.disabled = true;
        counter.value = parseInt(counter.attributes.max.value);
    }else if (parseInt(counter.value) < 1) {
        min.disabled = true;
        counter.value = 1;
    }
}

minus.forEach((min) => {
    min.addEventListener('click', function() {
        let counter = this.parentElement.querySelector('.quantity-product');
        counter.value = parseInt(counter.value) - 1;
        update(min, this.parentElement.querySelector('.plus'), counter);
    });
})

plus.forEach((plu) => {
    plu.addEventListener('click', function() {
        let counter = this.parentElement.querySelector('.quantity-product');
        counter.value = parseInt(counter.value) + 1;
        update(this.parentElement.querySelector('.minus'), plu, counter);
    });
})