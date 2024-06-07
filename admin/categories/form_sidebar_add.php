<form class="bsc__category_sidebar" v-if="category_add_active">
    <h2>
        Nueva Categoria en {{ category_add_active }}
    </h2>
    <hr style="width: 100%; display:block">
    <br>
    <fieldset bsc-field="slug">
        <label>slug</label>
        <input type="text" v-model="category_add[category_add_active].slug">
    </fieldset>
    <fieldset bsc-field="name">
        <label>Label</label>
        <input type="text" v-model="category_add[category_add_active].name">
    </fieldset>
    <fieldset bsc-field="bsc__extra__how_to" v-if="isRutina(category_add[category_add_active].slug)">
        <label>Como usar?</label>
        <input type="text" v-model="category_add[category_add_active].bsc__extra__how_to">
    </fieldset>
    <fieldset bsc-field="bsc__extra__rutine" v-if="isRutina(category_add[category_add_active].slug)">
        <label>Paso de la Rutina Coreana</label>
        <input type="text" v-model="category_add[category_add_active].bsc__extra__rutine">
    </fieldset>
    <fieldset bsc-field="bsc__extra__skin_type" v-if="isTipoPiel(category_add[category_add_active].slug)">
        <label>Para que Tipo de Piel</label>
        <input type="text" v-model="category_add[category_add_active].bsc__extra__skin_type">
    </fieldset>
    <fieldset bsc-field="description">
        <label>Description</label>
        <input type="text" v-model="category_add[category_add_active].description">
    </fieldset>
    <br>
    <br>
    <div class="bsc__buttons">
        <button @click="setCategorySelected(null)" class="button-primary woocommerce-save-button bsc__button button--cancel"> Cancelar </button>
        <button @click="saveCategorySelected()" class="button-primary woocommerce-save-button bsc__button button--save"> Guardar </button>
    </div>
</form>