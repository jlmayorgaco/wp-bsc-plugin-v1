

groups
    group-skin-care
        sk-rutina
            sk-rutina-s1-limpiadores-aceitosos
            sk-rutina-s2-limpiadores-acuosos
            sk-rutina-s3-exfoliantes
            sk-rutina-s4-tonicos
            sk-rutina-s5-mascarillas
            sk-rutina-s6-esencias
            sk-rutina-s7-serums
            sk-rutina-s8-contorno-de-ojos
            sk-rutina-s9-hidratantes
            sk-rutina-s10-protectores-solares

        sk-complementos
            sk-complementos-c1-aceites-faciales
            sk-complementos-c2-spot-y-patches
            sk-complementos-c3-mist-y-brumas
            sk-complementos-c4-sticks
            sk-complementos-c5-labios
            sk-complementos-c6-inner-beauty
            sk-complementos-c7-accesorios
            sk-complementos-c8-minis

        sk-tipo-piel
            sk-tipo-piel-seca
            sk-tipo-piel-grasa
            sk-tipo-piel-mixta
            sk-tipo-piel-normal

        sk-marcas
            sk-marca-around-me
            sk-marca-im-from
            sk-marca-macqueen
            sk-marca-make-p-rem
            sk-marca-mary-and-may
            sk-marca-masil
            sk-marca-mediheal
            sk-marca-medipeel
            sk-marca-missha
            sk-marca-nine-less
            sk-marca-pyunkang-yul

        sk-necesidades
            sk-necesidad-1
            sk-necesidad-2
            sk-necesidad-3
            sk-necesidad-4
            sk-necesidad-5

        sk-ingredientes
            sk-ingredient-acido-hialuronico
            sk-ingredient-aha-bha-pha
            sk-ingredient-arroz
            sk-ingredient-centella-asiatica
            sk-ingredient-ceramidas
            sk-ingredient-fermentos
            sk-ingredient-miel-propoleo
            sk-ingredient-mucina-de-caracol
            sk-ingredient-niacinamida
            sk-ingredient-peptidos
            sk-ingredient-retinol
            sk-ingredient-te-verde
            sk-ingredient-vitamina-c

    group-hair-care
        hc-rutina
            hc-rutina-s1-shampoo
            hc-rutina-s2-exfoliantes
            hc-rutina-s3-mascarillas
            hc-rutina-s4-acondicionadores
            hc-rutina-s5-tonicos
            hc-rutina-s6-serums
            hc-rutina-s7-esencias-leave-in
            hc-rutina-s8-sprays
            hc-rutina-s9-aceites
            hc-rutina-s10-protectores

        hc-complementos
            hc-complementos-c1-pestanas
            hc-complementos-c2-cepillos
            hc-complementos-c3-cushions
            hc-complementos-c4-minis
            hc-complementos-c5-accesorios

        hc-marca
            hc-marca-lunabelle
            hc-marca-glowberry
            hc-marca-dewdrop
            hc-marca-petalista
            hc-marca-bloomberry
            hc-marca-sunkissed

        hc-necesidades
            hc-necesidad-1
            hc-necesidad-2
            hc-necesidad-3
            hc-necesidad-4
            hc-necesidad-5


    group-make-up
        mk-productos
            mk-rutina-p1-bb-creams-y-bases
            mk-rutina-p2-cushions-y-refills
            mk-rutina-p3-sombras-y-paletas
            mk-rutina-p4-delineadores
            mk-rutina-p5-pestaninas
            mk-rutina-p6-rubores
            mk-rutina-p7-iluminadores
            mk-rutina-p8-correctores
            mk-rutina-p9-tintas
            mk-rutina-p10-labiales
            mk-rutina-p11-polvos

        mk-complementos
            mk-complementos-c1-cejas
            mk-complementos-c2-primers
            mk-complementos-c3-fijadores
            mk-complementos-c4-brochas
            mk-complementos-c5-pestanas

        mk-marcas
            mk-marca-glow-glamour
            mk-marca-luxe-lash
            mk-marca-dream-dazzle
            mk-marca-sparkle-siren
            mk-marca-flawless-finish
            mk-marca-velvet-vogue
            mk-marca-radiant-rose
            mk-marca-chic-cheeks


// -------- 
mod. agregar mejorado
[ SKIN CARE] [HAIR CAIRE ] [ MAKEUP ]
[]

mod. de marcas unidas
filterByName('loreal','sk-page-marcas') + filterByName('loreal','hc-page-marcas') + filterByName('loreal','mk-page-marcas')










* 1.) Terminar el Excel de Categorias
* 2.) Terminar el Excel de Productos ( Ordenar y llenar por BSC)
* 3.) BSC Plugin :: Boton de Plugin de Subir CSV de Categorias
** 4.) BSC Plugin :: Boton de Borrar todos los Productos
** 5.) BSC Plugin :: Boton de Cargar el Excel de todos los productos (hoja amarilla)
-- 1 Week --
* 6.) Decidir las categorias de los filtros (titulos)
* 7.) Estilos de la cuadricula (tiles)
*** 8.) Agregar el field de how-to-use al json, al modelo de woocommerce de categorias, para llenar en vista de producto segun el paso de la rutina tanto sk como hc.
-- 2 Week ---
*** 9.) Crear en add-new-product una cajita para seleccionar las categorias rapido.
-- 3 Week --
