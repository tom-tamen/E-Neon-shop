let counter = document.querySelector('#quantity');
let minus = document.querySelector('.minus');
let plus = document.querySelector('.plus');

const price = parseFloat(document.querySelector('.price').innerHTML.replace('$',""))

function update(){
    minus.disabled = parseInt(counter.value) <= 1;
    plus.disabled = parseInt(counter.value) >= parseInt(counter.attributes.max.value);

    if (parseInt(counter.value) >= parseInt(counter.attributes.max.value)) {
        plus.disabled = true;
        counter.value = parseInt(counter.attributes.max.value);
    }else if (parseInt(counter.value) < 1) {
        minus.disabled = true;
        counter.value = 1;
    }

    document.querySelector('.total-display').innerHTML = "Total: $" + (price * parseInt(counter.value)).toFixed(2);
}
minus.addEventListener('click', function() {
    counter.value = parseInt(counter.value) - 1
    update();
});

plus.addEventListener('click', function() {
    counter.value = parseInt(counter.value) + 1;
    update();
});