const allLinks = document.querySelectorAll('.product-link');
const allInputs = document.querySelectorAll('.remove-input');
document.querySelectorAll('.btn-remove').forEach(btn => {
    btn.addEventListener('click', () => {
        productID = btn.getAttribute('data-btn-id');

        allInputs.forEach(input => {
            if (input.getAttribute('data-input-id') == productID) {

                allLinks.forEach(link => {
                    if (link.getAttribute('data-link-id') == productID) {
                        let url = link.getAttribute('href');
                        
                        if (input.value < 1) {
                            url += '/'+1;
                        }else{
                            url += '/'+input.value;
                        }

                        link.setAttribute('href', url);
                        link.click();
                    }
                })

            }
        })
    })
})