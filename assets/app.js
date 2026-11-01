// JavaScript solo filtra lo visible. Los precios y el carrito se resuelven en PHP.
const buscar = document.querySelector('#buscar');
const categoria = document.querySelector('#categoria');
if (buscar && categoria) {
    document.querySelector('#filtros').hidden = false;
    const productos = document.querySelectorAll('.producto');
    function normalizar(texto) {
        return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }
    function filtrar() {
        let visibles = 0;
        for (const producto of productos) {
            const coincideNombre = normalizar(producto.dataset.nombre).includes(normalizar(buscar.value.trim()));
            const coincideCategoria = !categoria.value || producto.dataset.categoria === categoria.value;
            producto.hidden = !(coincideNombre && coincideCategoria);
            if (!producto.hidden) visibles++;
        }
        document.querySelector('#sin-resultados').hidden = visibles > 0 || productos.length === 0;
    }
    buscar.addEventListener('input', filtrar);
    categoria.addEventListener('change', filtrar);
}
