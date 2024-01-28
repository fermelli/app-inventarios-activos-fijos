export const aplanarCategorias = (
    categorias,
    categoriasPadresConHijasAplanadas,
    nivel = 0,
) => {
    categorias.forEach((categoria) => {
        categoriasPadresConHijasAplanadas.push({
            id: categoria.id,
            nombre: categoria.nombre,
            nombre_mostrado: `${"…".repeat(nivel)} ${categoria.nombre}`,
            categoria_padre_id: categoria.categoria_padre_id,
            eliminado_en: categoria.eliminado_en,
            nivel,
        });

        if (categoria.categorias_hijas?.length > 0) {
            aplanarCategorias(
                categoria.categorias_hijas,
                categoriasPadresConHijasAplanadas,
                nivel + 1,
            );
        }
    });
};
