function repeat() {
    document.querySelectorAll('[data-repeat-add]').forEach(addButton => {
        const targetSelector = addButton.getAttribute('data-repeat-add');
        addButton.addEventListener('click', function() {
            const target = document.querySelector('.' + targetSelector);
            if (target) {
                const newElement = target.cloneNode(true);
                const inputElements = newElement.querySelectorAll('input, select');
                inputElements.forEach(input => {
                    input.value = '';
                    // Clear validation errors if any
                    input.classList.remove('is-invalid');
                    const errorDiv = input.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                        errorDiv.remove();
                    }
                });

                // Update names and ids to avoid duplicates
                const index = target.parentNode.querySelectorAll('.' + targetSelector).length;
                inputElements.forEach(input => {
                    const dataName = input.getAttribute('data-name');
                    if (dataName) {
                        input.name = `order_items[${index}][${dataName}]`;
                    }
                    //update id
                    if(input.id){
                        const oldId = input.id;
                        const newId = oldId.substring(0, oldId.lastIndexOf('_')) + "_" + index;
                        input.id = newId;
                    }
                });

                // Calculate and set values for pending_qty, production_qty, planning_qty, and short_qty
                const orderQtyInput = newElement.querySelector('[data-name="order_qty"]');
                const pendingQtyInput = newElement.querySelector('[data-name="pending_qty"]');
                const productionQtyInput = newElement.querySelector('[data-name="production_qty"]');
                const planningQtyInput = newElement.querySelector('[data-name="planning_qty"]');
                const shortQtyInput = newElement.querySelector('[data-name="short_qty"]');

                if (orderQtyInput && pendingQtyInput && productionQtyInput && planningQtyInput && shortQtyInput) {
                    orderQtyInput.addEventListener('input', function() {
                        const orderQty = parseInt(orderQtyInput.value) || 0;
                        pendingQtyInput.value = orderQty;
                        shortQtyInput.value = orderQty;
                        productionQtyInput.value = 0;
                        planningQtyInput.value = 0;
                    });
                }
                const removeButton = newElement.querySelector('[data-repeat-remove]');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        newElement.remove();
                    });
                }

                // Insert the new element before the add button's parent node
                addButton.parentNode.insertBefore(newElement, addButton);
            }
        });
    });

    document.querySelectorAll('[data-repeat-remove]').forEach(removeButton => {
        removeButton.addEventListener('click', function() {
            const targetSelector = removeButton.getAttribute('data-repeat-remove');
            const target = removeButton.closest('.' + targetSelector);
            if (target && target.parentNode.querySelectorAll('.' + targetSelector).length > 1) {
                target.remove();
            } else if (target && target.parentNode.querySelectorAll('.' + targetSelector).length === 1) {
                // Optionally clear fields of the last item instead of removing
                const inputElements = target.querySelectorAll('input, select');
                inputElements.forEach(input => {
                    input.value = '';
                });
            }
        });
    });

    // Add this part to handle the initial load
    document.querySelectorAll('[data-name="order_qty"]').forEach(orderQtyInput => {
        const container = orderQtyInput.closest('.repeatable'); // Find the parent .repeatable element
        const pendingQtyInput = container.querySelector('[data-name="pending_qty"]');
        const shortQtyInput = container.querySelector('[data-name="short_qty"]');
        const productionQtyInput = container.querySelector('[data-name="production_qty"]');
        const planningQtyInput = container.querySelector('[data-name="planning_qty"]');

        if (orderQtyInput && pendingQtyInput && productionQtyInput && planningQtyInput && shortQtyInput) {
            orderQtyInput.addEventListener('input', function() {
                const orderQty = parseInt(orderQtyInput.value) || 0;
                pendingQtyInput.value = orderQty;
                shortQtyInput.value = orderQty;
                productionQtyInput.value = 0;
                planningQtyInput.value = 0;
            });
            // Trigger input event on load to handle initial value
            orderQtyInput.dispatchEvent(new Event('input'));
        }
    });
}
