document.addEventListener('DOMContentLoaded', function () {
    const itemsContainer = document.getElementById('itemsContainer');
    const addRowButton = document.getElementById('addRow');
    const subtotalInput = document.getElementById('subtotal');
    const ivaTotalInput = document.getElementById('iva_total');
    const totalPagarInput = document.getElementById('total_pagar');

    // Función para calcular y actualizar totales
    function calcularTotales() {
        let subtotalGeneral = 0;
        let ivaGeneral = 0;

        document.querySelectorAll('.item-row').forEach(function (row) {
            const cantidadInput = row.querySelector('input[name="cantidad[]"]');
            const precioInput = row.querySelector('input[name="precio[]"]');
            const ivaPorcentajeInput = row.querySelector('input[name="iva[]"]');

            const cantidad = parseFloat(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            const ivaPorcentaje = parseFloat(ivaPorcentajeInput.value) || 0;

            const totalFilaSinIva = cantidad * precio;
            const ivaFila = (totalFilaSinIva * ivaPorcentaje) / 100;

            subtotalGeneral += totalFilaSinIva;
            ivaGeneral += ivaFila;
        });

        const totalGeneral = subtotalGeneral + ivaGeneral;

        subtotalInput.value = subtotalGeneral.toFixed(2);
        ivaTotalInput.value = ivaGeneral.toFixed(2);
        totalPagarInput.value = totalGeneral.toFixed(2);
    }

    // Función para agregar una nueva fila
    function agregarFila() {
        const primeraFila = itemsContainer.querySelector('.item-row');
        const nuevaFila = primeraFila.cloneNode(true);

        // Limpiar los valores de los inputs en la nueva fila
        nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
        // Restablecer el valor de IVA al predeterminado si es necesario (ej. 19%)
        const ivaInput = nuevaFila.querySelector('input[name="iva[]"]');
        if (ivaInput) ivaInput.value = '19'; 

        // Mostrar el botón de eliminar en la nueva fila
        const botonEliminar = nuevaFila.querySelector('.remove-row');
        if (botonEliminar) {
            botonEliminar.style.display = 'inline-block';
            botonEliminar.addEventListener('click', function () {
                this.closest('.item-row').remove();
                calcularTotales(); // Recalcular al eliminar
            });
        }
        
        itemsContainer.appendChild(nuevaFila);
        asignarListenersInputs(nuevaFila); // Asignar listeners a los inputs de la nueva fila
        calcularTotales(); // Recalcular después de agregar
    }

    // Función para asignar listeners a los inputs de una fila específica
    function asignarListenersInputs(fila) {
        fila.querySelectorAll('input[name="cantidad[]"], input[name="precio[]"], input[name="iva[]"]').forEach(input => {
            input.addEventListener('input', calcularTotales);
        });
    }

    // Event listener para el botón de agregar fila
    if (addRowButton) {
        addRowButton.addEventListener('click', agregarFila);
    }

    // Asignar listeners a los inputs de la primera fila (plantilla)
    const primeraFilaExistente = itemsContainer.querySelector('.item-row');
    if (primeraFilaExistente) {
        asignarListenersInputs(primeraFilaExistente);
    }

    // Mostrar botón de eliminar en filas ya existentes (si hay más de una al cargar, aunque no debería ser el caso con la plantilla)
    // Y asignarles el evento de eliminación
    document.querySelectorAll('.item-row').forEach((row, index) => {
        if (index > 0) { // Solo mostrar en filas que no sean la primera plantilla
            const botonEliminar = row.querySelector('.remove-row');
            if (botonEliminar) {
                botonEliminar.style.display = 'inline-block';
                botonEliminar.addEventListener('click', function () {
                    this.closest('.item-row').remove();
                    calcularTotales();
                });
            }
        }
    });

    // Calcular totales al cargar la página por si hay datos precargados (aunque la plantilla empieza vacía)
    calcularTotales();
});