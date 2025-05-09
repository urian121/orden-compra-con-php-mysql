$(document).ready(function () {
    const itemsContainer = $("#itemsContainer");
    const subtotal = $("#subtotal");
    const ivaTotal = $("#iva_total");
    const totalPagar = $("#total_pagar");
    

  function calcularTotales() {
    let subtotal = 0,
      iva = 0;

    $(".item-row").each(function () {
      const $row = $(this);
      const cantidad =
        parseFloat($row.find('input[name="cantidad[]"]').val()) || 0;
      const precio = parseFloat($row.find('input[name="precio[]"]').val()) || 0;
      const ivaPorc = parseFloat($row.find('input[name="iva[]"]').val()) || 0;

      const totalFila = cantidad * precio;
      subtotal += totalFila;
      iva += totalFila * (ivaPorc / 100);
    });

    subtotal.val(subtotal.toFixed(2));
    ivaTotal.val(iva.toFixed(2));
    totalPagar.val((subtotal + iva).toFixed(2));
  }

  function asignarEventosFila($fila) {
    $fila
      .find(
        'input[name="cantidad[]"], input[name="precio[]"], input[name="iva[]"]'
      )
      .on("input", calcularTotales);

    $fila
      .find(".remove-row")
      .show()
      .on("click", function () {
        $fila.remove();
        calcularTotales();
      });
  }

  function agregarFila() {
    const $nueva = $(".item-row").first().clone();

    $nueva.find("input").val(""); // limpiar todos los campos
    $nueva.find('input[name="iva[]"]').val("19"); // valor predeterminado
    asignarEventosFila($nueva);

    itemsContainer.append($nueva);
    calcularTotales();
  }

  $("#addRow").on("click", agregarFila);

  // Inicializar la fila base
  const $primeraFila = $(".item-row").first();
  if ($primeraFila.length) {
    asignarEventosFila($primeraFila);
  }

  calcularTotales(); 
});
